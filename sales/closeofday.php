<?php
session_start();

$database = 'pointofsale';
$username_db = 'root';
$host = 'localhost';
$password_db = '';

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$db = mysqli_connect($host, $username_db, $password_db, $database);

if (!$db) {
    die("Connection Failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

$current_user = $_SESSION['username'];
$today = date("Y-m-d");

/* ==============================
   GET TODAY SALES TOTALS
============================== */

$queryCash = "SELECT IFNULL(SUM(total),0) AS total_cash 
              FROM sales 
              WHERE DATE(date) = '$today' 
              AND payment_method = 'Cash'";
$resultCash = mysqli_query($db, $queryCash);
$totalCashToday = mysqli_fetch_assoc($resultCash)['total_cash'];

$queryMpesa = "SELECT IFNULL(SUM(total),0) AS total_mpesa 
               FROM sales 
               WHERE DATE(date) = '$today' 
               AND payment_method = 'MPESA'";
$resultMpesa = mysqli_query($db, $queryMpesa);
$totalMpesaToday = mysqli_fetch_assoc($resultMpesa)['total_mpesa'];

/* ==============================
   GET YESTERDAY CLOSING BALANCES
============================== */

$yesterday = date('Y-m-d', strtotime('-1 day'));

$yesterdayQuery = "
SELECT cash_end, mpesa_end 
FROM closeofdaysummary 
WHERE date = '$yesterday'
LIMIT 1";

$yesterdayResult = mysqli_query($db, $yesterdayQuery);

$cash_start = 0;
$mpesa_start = 0;

if (mysqli_num_rows($yesterdayResult) > 0) {
    $row = mysqli_fetch_assoc($yesterdayResult);
    $cash_start = $row['cash_end'];
    $mpesa_start = $row['mpesa_end'];
}

/* ==============================
   HANDLE FORM SUBMISSION
============================== */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$checkQuery = "SELECT id FROM closeofdaysummary 
               WHERE username = '$current_user' 
               AND date = '$today' 
               LIMIT 1";

$checkResult = mysqli_query($db, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
    die("<script>alert('Close of Day already done today!'); window.location.href='../employee_page.php';</script>");
}

    $deduction_method = $_POST['deduction_method'];
    $deduction_amount = floatval($_POST['deduction_amount']);
    $notes = mysqli_real_escape_string($db, $_POST['notes']);

    // Calculate final balances
    $final_cash = $cash_start + $totalCashToday;
    $final_mpesa = $mpesa_start + $totalMpesaToday;

    if ($deduction_method == "Cash") {
        $final_cash -= $deduction_amount;
    }

    if ($deduction_method == "MPESA") {
        $final_mpesa -= $deduction_amount;
    }

    // TOTAL MADE TODAY
    $total_made_today = ($totalCashToday + $totalMpesaToday) - $deduction_amount;

    // Prevent double closing
    $check = mysqli_query($db, "SELECT id FROM closeofdaysummary WHERE date='$today' LIMIT 1");

    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Close of Day already done today!');</script>";
    } else {

        $insert = "INSERT INTO closeofdaysummary
        (username, cash_start, cash_end, mpesa_start, mpesa_end,
         deduction_method, deduction_amount, total_made, notes, date)
        VALUES
        ('$current_user', '$cash_start', '$final_cash',
         '$mpesa_start', '$final_mpesa',
         '$deduction_method', '$deduction_amount',
         '$total_made_today',
         '$notes', '$today')";

        if (mysqli_query($db, $insert)) {

            /* ==============================
               SEND EMAIL
            ============================== */

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
            $mail->Username = 'riversidehostels@gmail.com'; // Replace with your Gmail address
            $mail->Password = 'vgkizurrywyuyhwm';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

            $mail->setFrom('riversidehostels@gmail.com', 'POS SYSTEM'); // Sender email and name
            $mail->addAddress('smoothdime@gmail.com'); // Recipient email

                $mail->isHTML(true);
                $mail->Subject = "Close Of Day Summary - $today";

                $mail->Body = "
                <h2>Close Of Day Summary - $today</h2>

                <p><strong>User:</strong> $current_user</p>

                <h3>Cash At Drawer</h3>
                <ul>
                    <li>Start: $cash_start</li>
                    <li>Today Sales: $totalCashToday</li>
                    <li>Final Closing: $final_cash</li>
                </ul>

                <h3>Mpesa At Phone</h3>
                <ul>
                    <li>Start: $mpesa_start</li>
                    <li>Today Sales: $totalMpesaToday</li>
                    <li>Final Closing: $final_mpesa</li>
                </ul>

                <h3>Deduction</h3>
                <ul>
                    <li>Method: $deduction_method</li>
                    <li>Amount: $deduction_amount</li>
                </ul>

                <h3>Total Made Today</h3>
                <p><strong>$total_made_today</strong></p>

                <h3>Notes</h3>
                <p>$notes</p>

                <br>Regards,<br>$current_user
                ";

                $mail->send();

            } catch (Exception $e) {
                echo "Email Error: {$mail->ErrorInfo}";
            }

            echo "<script>alert('Close Of Day Submitted Successfully!'); window.location.href='closeofday.php';</script>";
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Close Of Day Summary</title>

    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #f4f6f9, #e9f5ec);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 750px;
            margin: 50px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        h2 {
            margin-bottom: 5px;
            color: #1b5e20;
        }

        h3 {
            margin-top: 30px;
            margin-bottom: 10px;
            color: #2e7d32;
            border-left: 5px solid #2e7d32;
            padding-left: 10px;
        }

        .info {
            display: flex;
            justify-content: space-between;
            background: #f8f9fa;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        hr {
            border: none;
            height: 1px;
            background: #e0e0e0;
            margin: 25px 0;
        }

        label {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            font-size: 14px;
            transition: 0.3s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #2e7d32;
            outline: none;
            box-shadow: 0 0 5px rgba(46,125,50,0.2);
        }

        .readonly {
            background: #f1f8f4;
            font-weight: bold;
        }

        .highlight-total {
            background: #e8ffe8;
            font-weight: bold;
            font-size: 16px;
            color: #1b5e20;
            border: 2px solid #2e7d32;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #2e7d32;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #1b5e20;
        }

        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<?php include('../sales/base.php'); ?>

<body>

<div class="container">

    <h2>Close Of Day Summary</h2>

    <div class="info">
        <span><strong>Date:</strong> <?php echo $today; ?></span>
        <span><strong>User:</strong> <?php echo $current_user; ?></span>
    </div>

    <hr>

    <div class="grid-2">

        <div>
            <h3>Cash At Drawer</h3>

            <label>Start (Yesterday Closing)</label>
            <input type="text" value="<?php echo $cash_start; ?>" readonly class="readonly">

            <label>End (Today Sales Cash)</label>
            <input type="text" value="<?php echo $totalCashToday; ?>" readonly class="readonly">
        </div>

        <div>
            <h3>Mpesa At Phone</h3>

            <label>Start (Yesterday Closing)</label>
            <input type="text" value="<?php echo $mpesa_start; ?>" readonly class="readonly">

            <label>End (Today Sales MPESA)</label>
            <input type="text" value="<?php echo $totalMpesaToday; ?>" readonly class="readonly">
        </div>

    </div>

    <form method="POST">

        <h3>Deduction Section</h3>

        <label>Select Payment Method</label>
        <select name="deduction_method" required>
            <option value="">-- Select Payment Method --</option>
            <option value="Cash">Cash</option>
            <option value="MPESA">MPESA</option>
        </select>

        <label>Deduction Amount</label>
        <input type="number" step="0.01" name="deduction_amount" id="deduction_amount" required>

        <h3>Total Made Today</h3>
        <input type="text" id="total_made_display" readonly class="highlight-total">

        <h3>Notes</h3>
        <textarea name="notes" rows="4" placeholder="Enter any notes for closing..."></textarea>

        <button type="submit">
            SUBMIT CLOSE
        </button>

    </form>

</div>

<script>
function updateTotalMade() {

    const cashToday = parseFloat(<?php echo $totalCashToday; ?>) || 0;
    const mpesaToday = parseFloat(<?php echo $totalMpesaToday; ?>) || 0;
    const deduction = parseFloat(document.getElementById("deduction_amount").value) || 0;

    const total = (cashToday + mpesaToday) - deduction;

    document.getElementById("total_made_display").value = total.toFixed(2);
}

document.getElementById("deduction_amount")
    .addEventListener("input", updateTotalMade);

updateTotalMade();
</script>

</body>
</html>