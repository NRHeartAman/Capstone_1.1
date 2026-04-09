<?php
include 'config.php';

// 1. Bilangin kung ilang users ang nasa database
$count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$row = mysqli_fetch_assoc($count_query);

// Ito ang variable na hinahanap sa line 199
$user_count = $row['total'] ?? 0; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="Dstyle.css"> 
    <title>CraveCast - Admin & Login</title>
    <style>
        /* Figma Design Colors */
        :root {
            --figma-maroon: #631919;
            --figma-bg: #f8f9fa;
            --role-pink-bg: #fdf2f2;
            --role-pink-text: #991b1b;
        }

        .main-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
        }

        /* Tab Switcher Style */
        .tab-container {
            display: flex;
            background: #ededed;
            padding: 4px;
            border-radius: 10px;
        }

        .tab-link {
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            color: #555;
            background: transparent;
            transition: 0.3s;
        }

        .tab-link.active {
            background: white;
            color: #000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Table Card Styling */
        .user-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .card-top {
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
        }

        .btn-add {
            background: var(--figma-maroon);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* User Table Design */
        .user-table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-table th {
            text-align: left;
            background: #fafafa;
            padding: 12px 20px;
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
        }

        .user-table td {
            padding: 15px 20px;
            border-bottom: 1px solid #f1f1f1;
            font-size: 14px;
        }

        .avatar-circle {
            width: 32px;
            height: 32px;
            background: #ffe4e6;
            color: #e11d48;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .role-tag {
            background: var(--role-pink-bg);
            color: var(--role-pink-text);
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-dot {
            color: #16a34a;
            font-weight: 600;
        }

        /* Hidden tabs logic */
        .tab-content { display: none; }
        .tab-content.active { display: block; }

        .audit-list { padding: 20px; }
        .audit-row { padding: 12px 0; border-bottom: 1px solid #eee; }
    </style>
</head>
<div id="addUserModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 style="font-size: 20px;">Add New Account</h2>
            <span class="close-modal" onclick="closeModal()" style="cursor:pointer; font-size:24px;">&times;</span>
        </div>
        
        <form action="process_add_user.php" method="POST">
            <div style="margin-bottom: 15px;">
                <label>Username</label>
                <input type="text" name="username" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Password</label>
                <input type="password" name="password" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label>Role</label>
                <select name="role" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">
                    <option value="Staff">Staff (Binangonan Branch)</option>
                    <option value="Owner">Owner</option>
                </select>
            </div>
            <button type="submit" name="add_user" style="width:100%; background:#3C91E6; color:#fff; border:none; padding:12px; border-radius:8px; cursor:pointer; font-weight:600;">
                Save User Account
            </button>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('addUserModal').style.display = "block";
}
function closeModal() {
    document.getElementById('addUserModal').style.display = "none";
}
window.onclick = function(e) {
    if (e.target == document.getElementById('addUserModal')) closeModal();
}
</script>
<body>
    <div class="sidebar">
        <a href="user_page.php" class="logo">
            <div class="logo-name"><span>Crave</span>Cast</div>
        </a>
        <ul class="side-menu">
            <li><a href="user_page.php"><i class='bx bxs-home'></i>Home</a></li>
            <li><a href="forecast.php"><i class='bx bx-analyse'></i>Forecast</a></li>
            <li><a href="sales_trends.php"><i class='bx bx-trending-up'></i>Sales Trends</a></li>
            <li><a href="events.php"><i class='bx bx-calendar-event'></i>Events</a></li>
            <li><a href="upload_data.php"><i class='bx bx-cloud-upload'></i>Upload Data</a></li>
            <li class="active"><a href="admin_login.php"><i class='bx bx-user-circle'></i>Admin & Login</a></li>
        </ul>
        <ul class="side-menu">
            <li><a href="logout.php" class="logout"><i class='bx bx-log-out'></i>Logout</a></li>
        </ul>
    </div>

    <div class="content">
        <nav>
            <i class='bx bx-menu'></i>
            <form action="#" style="flex-grow: 1;"></form> 
            <div class="user-info-display" style="text-align: right; margin-right: 20px;">
                <small style="color: #888;">Admin • <?php echo date('l, F j, Y'); ?></small>
            </div>
            <a href="#" class="profile"><img src="logo.png"></a>
        </nav>

        <main>
            <div class="main-header">
                <div>
                    <h1 style="font-size: 24px; font-weight: 700;">Admin & Login Management</h1>
                    <p style="color: #666; font-size: 14px;">Manage user accounts, roles, and track system activities</p>
                </div>

                <div class="tab-container">
                    <button class="tab-link active" onclick="switchTab(event, 'users-tab')">
                        <i class='bx bx-user'></i> Users
                    </button>
                    <button class="tab-link" onclick="switchTab(event, 'roles-tab')">
                        <i class='bx bx-shield'></i> Roles
                    </button>
                    <button class="tab-link" onclick="switchTab(event, 'audit-tab')">
                        <i class='bx bx-list-check'></i> Audit Trail
                    </button>
                </div>
            </div>

            <div id="users-tab" class="tab-content active">
                <div class="user-card">
                    <div class="card-top">
                      <h3>User Accounts (<?php echo $user_count; ?>)</h3>
                        <a href="#" class="btn-add"><i class='bx bx-plus'></i> Add User</a>
                    </div>
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Last Login</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
             <tbody>
    <?php
    $result = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");

    if ($result) {
        while($row = mysqli_fetch_assoc($result)) {
            // DITO ANG MAGIC: 
            // Kahit 'user_id' ang nasa DB, i-assign natin sa variable na $id para hindi malito ang code mo sa baba.
            $id = $row['user_id']; 
            
            $username = htmlspecialchars($row['username'] ?? 'No Name');
            $role = htmlspecialchars($row['role'] ?? 'Staff');
            $status = htmlspecialchars($row['status'] ?? 'active');
            $created = $row['created_at'] ?? 'N/A';
            // Para mawala yung last_login warning, gumamit ng null coalescing
            $last_login = $row['last_login'] ?? 'Never'; 

            echo "<tr>
                    <td>" . $username . "</td>
                    <td>" . $role . "</td>
                    <td><span class='status completed'>● " . $status . "</span></td>
                    <td>" . $created . "</td>
                    <td>" . $last_login . "</td>
                    <td>
                        <div style='display:flex; gap:10px;'>
                            <a href='edit_user.php?id=$id' style='color:#3C91E6;'><i class='bx bx-edit'></i></a>
                            <a href='delete_user.php?id=$id' style='color:#db504a;' onclick='return confirm(\"Sigurado ka?\")'><i class='bx bx-trash'></i></a>
                        </div>
                    </td>
                  </tr>";
        }
    }
    ?>
</tbody>
                    </table>
                </div>
            </div>

            <div id="audit-tab" class="tab-content">
                <div class="user-card audit-list">
                    <h3 style="margin-bottom: 15px;">Activity Log</h3>
                    <div class="audit-row">
                        <strong>Admin</strong> logged in <br>
                        <small style="color:#aaa;">3/21/2026, 11:28:55 PM</small>
                    </div>
                    <div class="audit-row">
                        <strong>Admin</strong> logged out <br>
                        <small style="color:#aaa;">3/21/2026, 11:28:24 PM</small>
                    </div>
                </div>
            </div>

            <div id="roles-tab" class="tab-content">
                <div class="user-card" style="padding: 20px;">
                    <h3>Role Permissions</h3>
                    <p style="color:#666;">Define what Owners and Staff can see.</p>
                </div>
            </div>
        </main>
    </div>

    <script>
        function switchTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
                tabcontent[i].classList.remove("active");
            }
            tablinks = document.getElementsByClassName("tab-link");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active");
            }
            document.getElementById(tabName).style.display = "block";
            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }
    </script>
</body>
</html>