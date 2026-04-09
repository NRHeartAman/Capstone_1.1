<?php
// get_prediction.php
if (isset($_GET['temp'])) {
    $temp = escapeshellarg($_GET['temp']);
    
    // Sa XAMPP/Windows, kadalasan 'python' o 'py' ang command. 
    // Subukan mong i-type sa terminal/cmd ang 'python --version' para malaman ang tama.
    $output = shell_exec("python forecast.py $temp 2>&1"); 
    
    if ($output === null) {
        echo "Error executing Python";
    } else {
        echo trim($output);
    }
} else {
    echo "No Temp Provided";
}
?>