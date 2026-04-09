<?php
require 'config.php'; 
include 'session_check.php'; 

// Database defined in config.php
$query = "SELECT * FROM events";
$result = mysqli_query($conn, $query);

// Kunin ang count ng items na mababa ang stock para sa sidebar badge
$alert_count_query = mysqli_query($conn, "SELECT COUNT(*) as alerts FROM inventory WHERE quantity <= 20");
$alert_res = mysqli_fetch_assoc($alert_count_query);
$alert_count = $alert_res['alerts'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="Dstyle.css">
    <title>CraveCast - Events</title>
    <style>
        /* Blue Button Style */
        .btn-blue { background: #1976D2 !important; color: #fff !important; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 8px; font-weight: 500; }
        
        /* Table Styling */
        .clickable-row { cursor: pointer; transition: 0.2s; }
        .clickable-row:hover { background: #f0f4ff !important; }
        .delete-btn { color: var(--danger); font-size: 1.3rem; cursor: pointer; transition: 0.3s; padding: 5px; }
        .delete-btn:hover { color: #b71c1c; transform: scale(1.1); }

        /* Modal Specific Styles */
        .modal-content { width: 500px !important; padding: 30px !important; }
        .input-group { margin-bottom: 20px; }
        .input-group label { display: block; font-size: 14px; margin-bottom: 8px; color: #333; font-weight: 500; }
        .input-group input, .input-group select, .input-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 12px;
            font-size: 14px;
            outline: none;
            transition: 0.3s;
        }
        .input-group input:focus { border-color: var(--primary); }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="user_page.php" class="logo">
            <div class="logo-name"><span>Crave</span>Cast</div>
        </a>
        <ul class="side-menu">
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'user_page.php') ? 'active' : ''; ?>">
                <a href="user_page.php"><i class='bx bxs-dashboard'></i>Dashboard</a>
            </li>

            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'inventory.php') ? 'active' : ''; ?>">
                <a href="inventory.php">
                    <i class='bx bx-package'></i>
                    <span>Inventory</span>
                    <?php if($alert_count > 0): ?>
                        <span style="background: #e74c3c; color: white; border-radius: 50%; padding: 2px 7px; font-size: 11px; margin-left: 8px; font-weight: bold; box-shadow: 0 2px 5px rgba(231, 76, 60, 0.4);">
                            <?php echo $alert_count; ?>
                        </span>
                    <?php endif; ?>
                </a>
            </li>

            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'sales_trends.php') ? 'active' : ''; ?>">
                <a href="sales_trends.php"><i class='bx bx-store-alt'></i>Sales Trends</a>
            </li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'forecast.php') ? 'active' : ''; ?>">
                <a href="forecast.php"><i class='bx bx-analyse'></i>Forecast</a>
            </li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'events.php') ? 'active' : ''; ?>">
                <a href="events.php"><i class='bx bx-message-square-dots'></i>Events</a>
            </li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'upload_data.php') ? 'active' : ''; ?>">
                <a href="upload_data.php"><i class='bx bx-cloud-upload'></i>Upload Data</a>
            </li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'admin_login.php') ? 'active' : ''; ?>">
                <a href="admin_login.php"><i class='bx bx-user-circle'></i>Admin & Login</a>
            </li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'settings.php') ? 'active' : ''; ?>">
                <a href="settings.php"><i class='bx bx-cog'></i>Settings</a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="logout.php" class="logout"><i class='bx bx-log-out-circle'></i>Logout</a>
            </li>
        </ul>
    </div>

    <div class="content">
        <nav>
            <i class='bx bx-menu'></i>
            <form action="#" style="flex-grow: 1;"></form> 
            <a href="#" class="notif" id="notif-btn"><i class='bx bx-bell'></i><span class="count"><?php echo $alert_count; ?></span></a>
            <a href="#" class="profile"><img src="logo.png"></a>
        </nav>

        <main>
            <div class="header">
                <div class="left">
                    <h1>Event Management</h1>
                    <p style="color: var(--dark-grey);">Manage local events. Auto-sorted by date.</p>
                </div>
                <button class="btn-blue" id="openModalBtn"><i class='bx bx-plus'></i> Add Event</button>
            </div>

            <div class="bottom-data">
                <div class="orders">
                    <div class="header"><i class='bx bx-calendar-event'></i><h3>Active Events</h3></div>
                    <table>
                        <thead>
                            <tr>
                                <th>Event Name</th>
                                <th>Date</th>
                                <th>Impact</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                  <tbody>
    <?php
    // In-update ang query para gamitin ang 'stock_qty'
    $sql = "SELECT item_name, stock_qty FROM inventory WHERE stock_qty <= 20 ORDER BY stock_qty ASC";
    $stock_query = mysqli_query($conn, $sql);

    if($stock_query && mysqli_num_rows($stock_query) > 0) {
        while($stock = mysqli_fetch_assoc($stock_query)) {
            $qty = $stock['stock_qty']; // Siguraduhin na 'stock_qty' din ang array key dito
            
            $status_class = ($qty <= 5) ? 'process' : 'pending'; 
            $status_text = ($qty <= 5) ? 'Critical' : 'Low Stock';

            echo "<tr>
                    <td style='padding: 12px; font-weight: 500;'>".$stock['item_name']."</td>
                    <td style='padding: 12px;'>".$qty." pcs</td>
                    <td style='padding: 12px;'><span class='status ".$status_class."' style='font-size: 0.7rem;'>".$status_text."</span></td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='3' style='text-align:center; padding: 20px; color: #888;'>All stocks are sufficient. <i class='bx bx-check-double'></i></td></tr>";
    }
    ?>
</tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div id="eventModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Event</h3>
                <span class="close-modal" style="cursor:pointer; font-size: 24px;">&times;</span>
            </div>
            <form action="save_event.php" method="POST">
                <div class="input-group">
                    <label>Event Name *</label>
                    <input type="text" name="event_name" placeholder="e.g., Town Fiesta, Payday Weekend" required>
                </div>
                <div class="input-group">
                    <label>Date *</label>
                    <input type="date" name="event_date" required>
                </div>
                <div class="input-group">
                    <label>Event Type *</label>
                    <select name="event_type" required>
                        <option value="Festival">Festival</option>
                        <option value="Holiday">Holiday</option>
                        <option value="Payday">Payday</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
                <div class="input-group">
                    <label>Business Impact *</label>
                    <select name="impact" required>
                        <option value="Low">Low</option>
                        <option value="Medium" selected>Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
                <div class="input-group">
                    <label>Expected Sales Increase *</label>
                    <input type="text" name="sales_increase" placeholder="e.g., +60%, +P15,000" required>
                </div>
                <div class="input-group">
                    <label>Description (Optional)</label>
                    <textarea name="description" rows="3" placeholder="Additional details about this event..."></textarea>
                </div>
                <div class="modal-footer" style="display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" class="btn-cancel" id="cancelBtn" style="padding:10px 20px; border-radius:8px; border:none; cursor:pointer;">Cancel</button>
                    <button type="submit" name="add_event" class="btn-blue" style="padding:10px 25px;">Add Event</button>
                </div>
            </form>
        </div>
    </div>

    <div id="viewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header"><h3 id="v-name"></h3><span class="close-view" style="cursor:pointer; font-size:24px;">&times;</span></div>
            <div style="margin-top:20px; line-height: 1.8;">
                <p><strong>Date:</strong> <span id="v-date"></span></p>
                <p><strong>Type:</strong> <span id="v-type"></span></p>
                <p><strong>Impact:</strong> <span id="v-impact"></span></p>
                <p><strong>Expected Increase:</strong> <span id="v-increase"></span></p>
                <p><strong>Description:</strong></p>
                <p id="v-desc" style="background:#f5f5f5; padding:15px; border-radius:10px; border: 1px solid #eee;"></p>
            </div>
            <div class="modal-footer"><button type="button" class="btn-cancel" onclick="document.getElementById('viewModal').style.display='none'">Close</button></div>
        </div>
    </div>

    <script src="Dindex.js"></script>
    <script>
        const modal = document.getElementById("eventModal");
        const viewModal = document.getElementById("viewModal");

        document.getElementById("openModalBtn").onclick = () => modal.style.display = "block";
        document.querySelector(".close-modal").onclick = () => modal.style.display = "none";
        document.getElementById("cancelBtn").onclick = () => modal.style.display = "none";

        function viewEvent(data) {
            document.getElementById("v-name").innerText = data.event_name;
            document.getElementById("v-date").innerText = data.event_date;
            document.getElementById("v-type").innerText = data.event_type;
            document.getElementById("v-impact").innerText = data.impact;
            document.getElementById("v-increase").innerText = data.sales_increase;
            document.getElementById("v-desc").innerText = data.description || "No description provided.";
            viewModal.style.display = "block";
        }

        document.querySelector(".close-view").onclick = () => viewModal.style.display = "none";
        window.onclick = (e) => {
            if (e.target == modal) modal.style.display = "none";
            if (e.target == viewModal) viewModal.style.display = "none";
        }
    </script>
</body>
</html>