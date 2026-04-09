<?php
include 'config.php'; // Gagamitin ang config.php para sa connection

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=sales_trend_export.csv');

$output = fopen('php://output', 'w');
fputcsv($output, array('ID', 'Product Name', 'Quantity', 'Price', 'Sale Date'));

$query = "SELECT * FROM sales_data ORDER BY sale_date DESC";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}
fclose($output);
exit();
?>