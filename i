<?php
include 'config.php'; // Database connection
include 'session_check.php'; // Login check

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
                        <h2>Walang Data na Makita</h2>
                        <p>Mag-upload muna ng CSV sa Upload Data page para makita ang analytics.</p>
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
                        <i class='bx bx-dollar-circle'></i>
                        <span class="info">
                            <h3>₱<?php echo number_format($weekly_revenue, 2); ?></h3>
                            <p>Weekly Total Sales</p>
                        </span>
                    </li>
                    <li>
                        <i class='bx bx-user-plus'></i>
                        <span class="info">
                            <h3><?php echo $new_cust; ?></h3>
                            <p>New Customers</p>
                        </span>
                    </li>
                </ul>

                <div class="bottom-data">
                    <div class="orders">
                        <div class="header">
                            <i class='bx bx-cloud-lightning'></i>
                            <h3>Weather Forecast (Binangonan)</h3>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Condition</th>
                                    <th>Temp</th>
                                    <th>Sales Impact</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class='bx bx-sun' style='color: orange;'></i> Sunny</td>
                                    <td>32°C</td>
                                    <td><span class="status completed">High Demand</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="reminders">
                        <div class="header">
                            <i class='bx bx-receipt'></i>
                            <h3>Recent Orders</h3>
                        </div>
                        <ul class="task-list">
                            <?php
                            $recent = mysqli_query($conn, "SELECT * FROM sales_data ORDER BY sale_date DESC LIMIT 5");
                            while($row = mysqli_fetch_assoc($recent)) {
                                echo "<li>
                                        <div class='task-title'>
                                            <p>".$row['product_name']."</p>
                                        </div>
                                        <i class='bx bx-check-circle' style='color: var(--success);'></i>
                                      </li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
    <script src="Dindex.js"></script>
</body>
</html>