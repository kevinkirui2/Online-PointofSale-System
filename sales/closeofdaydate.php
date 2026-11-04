<?php
$database = 'pointofsale';
$username = 'root';
$host = 'localhost';
$password = '';
$msg = '';
$conn = new mysqli($host, $username, $password, $database);

ini_set('display_errors', 1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | E_DEPRECATED);

$db = mysqli_connect($host, $username, $password, $database);

if ($db == false) {
    die("Connection Failed: " . mysqli_connect_error());
}

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_POST['logout'])) {
    $user = $_SESSION['username'];
    $insert = "INSERT INTO logs (username, purpose) VALUES('$user', 'User $user logout')";
    $logs = mysqli_query($db, $insert);
    session_destroy();
    unset($_SESSION['username']);
    header('location: ../index.php');
}

// Initialize variables to prevent undefined errors
$machine_reading = '';
$machine_sale = '';
$cash_difference = '';
$mpesa_difference = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['date'])) {
    $Date = $_POST['date'];

    // Fetch data from the database for the selected date
    $sql = "SELECT * FROM `closeofdaysummary` WHERE date = '$Date'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $machine_reading = $row["current_machine_reading"];
        $machine_sale = $row["machine_sale"];
        $cash_difference = $row["cash_difference"];
        $mpesa_difference = $row["mpesa_difference"];
    } else {
        echo "<script>alert('No data found for the selected date.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Close of Day Summary</title>
    <?php include('../templates/head1.php'); ?>
</head>

<body>
    <?php include('../sales/base.php'); ?>

    <br><br>

    <div style="text-align:left; padding:20px; max-width: 600px; margin: auto; border: 1px solid #ccc; border-radius: 8px; background-color: #f9f9f9;">
        <div style="padding:10px; padding-bottom:10px; border-bottom: 2px solid #ddd;">
            <h1 style="font-family: Arial, sans-serif; color: #333;">
                <i class="fas fa-shopping-cart"></i> Close of Day Summary Analytics
            </h1>
        </div>
        <br>

        <form method="post" enctype="multipart/form-data">
            <label style="display: block; margin-bottom: 5px;">Enter Date</label>
            <input name="date" type="date" id="currentMachineReading" value=""
                   style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px;">
            <div class="finish" style="margin-top: 20px;">
                <button type="submit" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    <b>SUBMIT</b>
                </button>
            </div>
        </form>

        <br><br><br>
        <div style="text-align:left; margin: 15px 0;">
            <p style="font-weight: bold; font-size: 18px;">Machine Reading Prompts</p>

            <label style="display: block; margin-bottom: 5px;">Current Machine Reading</label>
            <input name="current_reading" type="text" id="currentMachineReading" 
                   value="<?php echo htmlspecialchars($machine_reading); ?>" 
                   placeholder="Current Machine Reading" readonly 
                   style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px;">

            <label style="display: block; margin-bottom: 5px;">Machine Sales (Litres)</label>
            <input name="machine_sales" type="text" id="machineSales" 
                   value="<?php echo htmlspecialchars($machine_sale); ?>" 
                   placeholder="Machine Sales" readonly 
                   style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <hr style="margin: 20px 0; border: 1px solid #ddd;">

        <div style="text-align:left; margin: 15px 0;">
            <p style="font-weight: bold; font-size: 18px;">Money Prompt</p>

            <div style="display: flex; justify-content: space-between;">
                <div style="flex: 1; margin-right: 10px;">
                    <label style="display: block; margin-bottom: 5px;">Cash Difference</label>
                    <input name="cash_difference" id="cash_difference" type="text" 
                           value="<?php echo htmlspecialchars($cash_difference); ?>" 
                           placeholder="Cash Difference" readonly 
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px;">Mpesa Difference</label>
                    <input name="mpesa_difference" id="mpesa_difference" type="text" 
                           value="<?php echo htmlspecialchars($mpesa_difference); ?>" 
                           placeholder="Mpesa Difference" readonly 
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>
        </div>

        <hr style="margin: 20px 0; border: 1px solid #ddd;">
    </div>
</body>

</html>
