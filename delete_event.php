<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query = "DELETE FROM events WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        header("Location: events.php");
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>