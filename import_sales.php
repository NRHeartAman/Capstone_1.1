<?php
include 'config.php';

if (isset($_POST['import'])) {
    $fileName = $_FILES["csv_file"]["tmp_name"];

    if ($_FILES["csv_file"]["size"] > 0) {
        $file = fopen($fileName, "r");
        
        // Skip the first line (the headers: product_name, quantity, etc.)
        fgetcsv($file);

        while (($column = fgetcsv($file, 1000, ",")) !== FALSE) {
            $product_name = mysqli_real_escape_string($conn, $column[0]);
            $quantity = (int)$column[1];
            $price = (float)$column[2];
            $sale_date = mysqli_real_escape_string($conn, $column[3]);

            $sqlInsert = "INSERT INTO sales_data (product_name, quantity, price, sale_date) 
                          VALUES ('$product_name', '$quantity', '$price', '$sale_date')";
            
            mysqli_query($conn, $sqlInsert);
        }
        
        fclose($file);
        header("Location: user_page.php?upload=success");
    } else {
        header("Location: upload_data.php?error=empty");
    }
}
?>