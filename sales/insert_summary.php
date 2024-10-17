<?php
// Database connection
include 'server/connection.php';


$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$prev_reading = $_POST['prev_reading'];
$cur_reading = $_POST['cur_reading'];
$m_sales = $_POST['m_sales'];
$actual_cash = $_POST['actual_cash'];
$actual_mpesa = $_POST['actual_mpesa'];
$expt_cash = $_POST['expt_cash'];
$expt_mpesa = $_POST['expt_mpesa'];

// Insert data into MySQL table

$sql = "insert into day_summary (prev_reading,cur_reading,rate,expected_cash,expected_mpesa,actual_cash,actual_mpesa,difference) values ('$cur_reading','$m_sales','$actual_cash','$actual_mpesa','$expt_cash','$expt_mpesa')";

if ($conn->query($sql) === TRUE) {
    echo "Data inserted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

