<?php
include 'config.php';

if(isset($_POST['add_stock'])){
$name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $qty = (int)$_POST['stock_qty'];

    $query = "INSERT INTO inventory_data (item_name, stock_qty) VALUES ('$name', '$qty')";
    
    if(mysqli_query($conn, $query)){
        header("Location: sales_trends.php"); // Redirect back to your page
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>