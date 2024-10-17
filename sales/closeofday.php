<?php
$database = 'pointofsale';
$username = 'root';
$host = 'localhost';
$password = '';
$msg = '';

ini_set('display_errors', 1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | E_DEPRECATED);

$db = mysqli_connect($host, $username, $password, $database);

if ($db == false) {
    die("Connection Failed: " . mysql_connect_error());
}

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_POST['logout'])) {
    $user = $_SESSION['username'];
    $insert = "INSERT INTO logs (username,purpose) VALUES('$user','User $user logout')";
    $logs = mysqli_query($db, $insert);
    session_destroy();
    unset($_SESSION['username']);
    header('location: ../index.php');
}

$total_price = 0;

// Correct SQL query to fetch the sum of the 'price' from 'sales_product'
$sql = "SELECT SUM(price) AS total_price FROM sales_product";

// Execute the query
$result = mysqli_query($db, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result); // Fetch the result row
    $total_price = $row['total_price']; // Assign the SUM to a variable
} else {
    echo "Error: " . mysqli_error($db); // Display any errors
}

$today = date('Y-m-d');

// Query for Cash total
$queryCash = "SELECT IFNULL(SUM(total), 0) AS total_cash FROM sales WHERE DATE(date) = '$today' AND payment_method = 'Cash'";
$resultCash = mysqli_query($db, $queryCash);
$rowCash = mysqli_fetch_assoc($resultCash);
$totalCash = $rowCash['total_cash'];





// Query for Mpesa total
$queryMpesa = "SELECT IFNULL(SUM(total), 0) AS total_mpesa FROM sales WHERE DATE(date) = '$today' AND payment_method = 'Mpesa'";
$resultMpesa = mysqli_query($db, $queryMpesa);
$rowMpesa = mysqli_fetch_assoc($resultMpesa);
$totalMpesa = $rowMpesa['total_mpesa']; // Set to 0 if no value



// Query to get the previous machine reading
$query = "SELECT current_machine_reading FROM closeofdaysummary WHERE date = CURDATE() - INTERVAL 1 DAY LIMIT 1";
$result = $db->query($query);

// Initialize the previous reading
$previous_reading = "No data found";

if ($result->num_rows > 0) {
    // Fetch the result
    $row = $result->fetch_assoc();
    $previous_reading = $row['current_machine_reading'];
} else {
    $previous_reading = "No data for yesterday"; // Debug message if no data
}




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newDate = date("Y-m-d");
    // Retrieve form data
    
    $current_reading = $_POST['current_reading'];
    $machine_sales = $_POST['machine_sales'];
    $cash_difference = $_POST['cash_difference'];
    $mpesa_difference = $_POST['mpesa_difference'];

    // Prepare the SQL statement
    $sql = "INSERT INTO closeofdaysummary (
current_machine_reading, machine_sale, cash_difference, mpesa_difference, date) VALUES ( '$current_reading', '$machine_sales',  '$cash_difference', '$mpesa_difference', '$newDate')";

    // Execute the query
    if (mysqli_query($db, $sql)) {
        echo "<script> alert('Data inserted successfully!')</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($db);
    }
}


$db->close();

?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include('../templates/head1.php'); ?>
</head>

<body>
    <?php
    include('../sales/base.php');
    ?>

    <br><br>
    <div style="text-align:center; padding:20px; max-width: 600px; margin: auto; border: 1px solid #ccc; border-radius: 8px; background-color: #f9f9f9;">
    <div style="padding:10px; padding-bottom:10px; border-bottom: 2px solid #ddd;">
        <h1 style="font-family: Arial, sans-serif; color: #333;">
            <i class="fas fa-shopping-cart"></i> Close of Day Summary
        </h1>
    </div>
    <form method="post" enctype="multipart/form-data" id="myForm" style="margin-top: 20px;">
        <div style="text-align:left; margin: 15px 0;">
            <p style="font-weight: bold; font-size: 18px;">Machine Reading Prompts</p>
            
            <label style="display: block; margin-bottom: 5px;">Previous Machine Reading</label>
            <input type="text" value="<?php echo htmlspecialchars($previous_reading); ?>" 
                   placeholder="Previous Machine Reading" 
                   style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px;">

            <label style="display: block; margin-bottom: 5px;">Current Machine Reading</label>
            <input  name="current_reading" type="text" id="currentMachineReading" value="" placeholder="Current Machine Reading"
                   oninput="updateMachineSales()" 
                   style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px;">
            
            <label style="display: block; margin-bottom: 5px;">Machine Sales (Litres)</label>
            <input name="machine_sales" type="text" id="machineSales" value="Autofill" placeholder=""
                   style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px;" readonly>
        </div>
        
        <script>
            function updateMachineSales() {
                const currentReading = document.getElementById("currentMachineReading").value;
                const machineSales = currentReading ? (parseFloat(currentReading) / 10).toFixed(2) : '';
                document.getElementById("machineSales").value = machineSales;
            }
        </script>

        <hr style="margin: 20px 0; border: 1px solid #ddd;">

        <div style="text-align:left; margin: 15px 0;">
            <p style="font-weight: bold; font-size: 18px;">Money Prompt</p>

            <label style="display: block; margin-bottom: 5px;">Actual Cash</label>
            <input type="text" id="actualCash" value="" placeholder="Actual Cash" 
                   oninput="updateDifferences()" 
                   style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px;">

            <label style="display: block; margin-bottom: 5px;">Actual MPESA</label>
            <input type="text" id="actualMpesa" value="" placeholder="Actual Mpesa" 
                   oninput="updateDifferences()" 
                   style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px;">
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                <div style="flex: 1; margin-right: 10px;">
                    <label style="display: block; margin-bottom: 5px;">Expected Cash</label>
                    <input type="text" id="cash_total" name="cash_total" 
                           value="<?php echo htmlspecialchars($totalCash); ?>" readonly 
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px;">Expected MPESA</label>
                    <input type="text" id="mpesa_total" name="mpesa_total" 
                           value="<?php echo htmlspecialchars($totalMpesa); ?>" readonly 
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>

            <div style="display: flex; justify-content: space-between;">
                <div style="flex: 1; margin-right: 10px;">
                    <label style="display: block; margin-bottom: 5px;">Cash Difference</label>
                    <input name="cash_difference" id="cash_difference" type="text" value="" placeholder="Difference" readonly 
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
                <div style="flex: 1;">
                    <label style="display: block; margin-bottom: 5px;">Mpesa Difference</label>
                    <input name="mpesa_difference" id="mpesa_difference" type="text" value="" placeholder="Difference" readonly 
                           style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                </div>
            </div>
        </div>

        <script>
            function updateDifferences() {
                const expectedCash = parseFloat(document.getElementById("cash_total").value) || 0;
                const expectedMpesa = parseFloat(document.getElementById("mpesa_total").value) || 0;
                const actualCash = parseFloat(document.getElementById("actualCash").value) || 0;
                const actualMpesa = parseFloat(document.getElementById("actualMpesa").value) || 0;

                const cashDifference = expectedCash - actualCash;
                const mpesaDifference = expectedMpesa - actualMpesa;

                document.getElementById("cash_difference").value = cashDifference.toFixed(2);
                document.getElementById("mpesa_difference").value = mpesaDifference.toFixed(2);
            }
        </script>

        <hr style="margin: 20px 0; border: 1px solid #ddd;">

        <div class="finish" style="margin-top: 20px;">
            <button type="submit" style="padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                <b>SUBMIT</b>
            </button>
        </div>
    </form>
</div>


</body>

</html>