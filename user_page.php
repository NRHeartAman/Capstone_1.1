<?php
include 'config.php'; // Database connection
include 'session_check.php'; // Login check

// Dapat ganito rin ang query sa sidebar badge mo:
$alert_count_query = mysqli_query($conn, "SELECT COUNT(*) as alerts FROM inventory WHERE stock_qty <= 20");$alert_res = mysqli_fetch_assoc($alert_count_query);
$alert_count = $alert_res['alerts'] ?? 0;

// --- 1. LIVE DATABASE FETCHING ---
$check_data = mysqli_query($conn, "SELECT id FROM sales_data LIMIT 1");
$has_data = mysqli_num_rows($check_data) > 0;

// Initialize para walang error
$daily_sold = 0;
$new_cust = 0;
$weekly_revenue = 0;
$daily_orders = 0;

if ($has_data) {
    // Real-time Daily Sold
    $q1 = mysqli_query($conn, "SELECT SUM(quantity) as total FROM sales_data WHERE DATE(sale_date) = CURDATE()");
    $daily_sold = mysqli_fetch_assoc($q1)['total'] ?? 0;

    // Real-time Daily Orders
    $q2 = mysqli_query($conn, "SELECT COUNT(*) as total FROM sales_data WHERE DATE(sale_date) = CURDATE()");
    $daily_orders = mysqli_fetch_assoc($q2)['total'] ?? 0;

    // Real-time Weekly Revenue
    $q3 = mysqli_query($conn, "SELECT SUM(quantity * price) as total FROM sales_data WHERE sale_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $weekly_revenue = mysqli_fetch_assoc($q3)['total'] ?? 0;

    // Real-time New Customers
    $q4 = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE created_at = CURDATE()");
    $new_cust = mysqli_fetch_assoc($q4)['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="Dstyle.css"> 
    <link rel="stylesheet" href="Fstyle.css"> 
    <title>CraveCast - Dashboard</title>
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
                    <span style="background: #e74c3c; color: white; border-radius: 50%; padding: 2px 7px; font-size: 11px; margin-left: 8px; font-weight: bold;">
                        <?php echo $alert_count; ?>
                    </span>
                <?php endif; ?>
            </a>
        </li>

        <?php if ($_SESSION['role'] == 'Admin' || $_SESSION['role'] == 'Owner'): ?>
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
        <?php else: ?>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'pos_entry.php') ? 'active' : ''; ?>">
                <a href="pos_entry.php"><i class='bx bx-cart-add'></i>Take Order (POS)</a>
            </li>
        <?php endif; ?>
        
        <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'settings.php') ? 'active' : ''; ?>">
            <a href="settings.php"><i class='bx bx-cog'></i>Settings</a>
        </li>
    </ul>
    <ul class="side-menu">
        <li><a href="logout.php" class="logout"><i class='bx bx-log-out-circle'></i>Logout</a></li>
    </ul>
</div>
    </div>

    <div class="content">
        <nav>
            <i class='bx bx-menu'></i>
            <form action="#" style="flex-grow: 1;"></form>
            <a href="#" class="profile"><img src="logo.png"></a>
        </nav>

        <main>
            <div class="header">
                <div class="left">
                    <h1>Dashboard</h1>
                    <ul class="breadcrumb">
                        <li><a href="#">Analytics</a></li>
                        <li>/</li>
                        <li><a href="#" class="active">Real-time</a></li>
                    </ul>
                </div>
                <div class="user-info-display" style="text-align: right;">
                    <small style="color: #888;">Admin • <?php echo date('l, F j, Y'); ?></small>
                </div>
            </div>

            <?php if (!$has_data): ?>
                <div class="bottom-data">
                    <div class="orders" style="text-align: center; padding: 60px;">
                        <i class="bx bx-cloud-upload" style="font-size: 80px; color: #eee;"></i>
                        <h2>No Available Data</h2>
                        <p>Upload Data</p>
                    </div>
                </div>
            <?php else: ?>
                <ul class="insights">
                    <li>
                        <i class='bx bx-calendar-check'></i>
                        <span class="info">
                            <h3><?php echo $daily_sold; ?></h3>
                            <p>Daily Product Sold</p>
                        </span>
                    </li>
                    <li>
                        <i class='bx bx-cart-alt'></i>
                        <span class="info">
                            <h3><?php echo $daily_orders; ?></h3>
                            <p>Daily Total Order</p>
                        </span>
                    </li>
                    <li>
                        <i class='bx bx-money-circle'></i>
                        <span class="info">
                            <h3>₱<?php echo number_format($weekly_revenue, 2); ?></h3>
                            <p>Weekly Total Sales</p>
                        </span>
            </li>
                </ul>

             <div class="orders">
    <div class="header">
        <i class='bx bx-analyse' style="color: #3C91E6;"></i>
        <h3>Smart Forecast Analysis</h3>
    </div>
    
    <div class="forecast-container" style="padding: 20px;">
       <div class="forecast-card" style="display: flex; align-items: center; justify-content: space-between; background: white; padding: 25px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f1f1; margin: 15px 0;">
    
    <div style="flex: 1;">
        <h4 id="user-city-name" style="margin: 0; color: #888; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px;">Current Weather</h4>
        <div id="user-weather-status" style="display: flex; align-items: center; font-size: 1.2rem; font-weight: 700; margin-top: 5px; color: #333;">
            <i class='bx bx-loader-alt bx-spin'></i>
        </div>
    </div>

    <div style="flex: 1; text-align: center; border-left: 2px solid #f8fafd; border-right: 2px solid #f8fafd;">
        <span style="display: block; font-size: 0.7rem; color: #888; font-weight: 600;">TEMPERATURE</span>
        <h2 id="user-temp-display" style="margin: 0; font-size: 2.2rem; color: #333;">--°C</h2>
    </div>

    <div class="ai-prediction" style="flex: 1; text-align: right;">
        <span style="display: block; font-size: 0.7rem; color: #888; font-weight: 800; letter-spacing: 0.5px;">CRAVECAST PREDICTION</span>
        <div style="display: flex; align-items: baseline; justify-content: flex-end; gap: 4px;">
            <h1 id="predictionValue" style="margin: 0; font-size: 2.8rem; transition: all 0.3s ease;">--</h1>
            <span style="font-weight: 600; color: #333;">cups</span>
        </div>
        <small id="aiStatus" style="font-weight: 700; font-size: 0.75rem; transition: all 0.3s ease;">● Live XGBoost</small>
    </div>
</div>
                <div class="reminders">
    <div class="header">
        <i class='bx bx-bell-plus' style="color: #e74c3c;"></i>
        <h3>Stock Alerts & Reminders</h3>
    </div>
    <div class="stock-table-container" style="max-height: 300px; overflow-y: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid #f8fafd; color: #888;">
                    <th style="padding: 10px;">Item Name</th>
                    <th style="padding: 10px;">Stock</th>
                    <th style="padding: 10px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
// LINE 186: In-update natin mula 'quantity' papuntang 'stock_qty'
$sql = "SELECT item_name, stock_qty FROM inventory WHERE stock_qty <= 20 ORDER BY stock_qty ASC";
$stock_query = mysqli_query($conn, $sql);

if($stock_query && mysqli_num_rows($stock_query) > 0) {
    while($stock = mysqli_fetch_assoc($stock_query)) {
        // Siguraduhin na 'stock_qty' din ang gagamitin dito sa array key
        $qty = $stock['stock_qty']; 
        
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
                ?>
            </tbody>
        </table>
    </div>
    <div class="footer" style="padding-top: 15px; border-top: 1px solid #eee; margin-top: 10px;">
        <a href="inventory.php" style="text-decoration: none; font-size: 0.8rem; color: #3C91E6; font-weight: bold;">
            Manage Inventory <i class='bx bx-right-arrow-alt'></i>
        </a>
    </div>
</div>
            <?php endif; ?>
        </main>
    </div>
    <script src="Dindex.js"></script> <script src="Fscript.js"></script> 
            </body>
</html>
</body>
</html>