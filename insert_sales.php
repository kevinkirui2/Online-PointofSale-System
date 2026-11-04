<?php
include 'server/connection.php';

// Logging function to log messages into a file
function logMessage($message) {
    $logFile = 'sales_log.txt'; // Specify your log file name
    $currentDate = date('Y-m-d H:i:s'); // Get current date and time
    $formattedMessage = "[$currentDate] $message" . PHP_EOL; // Format the message
    file_put_contents($logFile, $formattedMessage, FILE_APPEND); // Append to the log file
}

if (isset($_POST['product'])) {
    $user = $_SESSION['username'];
    $discount = isset($_POST['discount']) ? mysqli_real_escape_string($db, $_POST['discount']) : 0;
    $total = isset($_POST['totalvalue']) ? mysqli_real_escape_string($db, $_POST['totalvalue']) : 0;
    $price = isset($_POST['price']) ? $_POST['price'] : [];

    $product = isset($_POST['product']) ? $_POST['product'] : [];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : [];
    $payment_method = isset($_POST['payment_method']) ? mysqli_real_escape_string($db, $_POST['payment_method']) : '';

    // Log all POST values for debugging
    logMessage("Received POST data: " . json_encode($_POST));

    // Check for missing values and log them
    if (empty($product) || empty($quantity) || empty($price)) {
        logMessage("Missing product, quantity or price data.");
        echo "failure"; // Return failure message if required fields are missing
        exit;
    }
	else if (empty($product) ){
        logMessage("Missing product, quantity or price data.");
        echo "failure"; // Return failure message if required fields are missing
        exit;
    }

    // Log the start of the sales process
    logMessage("Processing sale for user: $user with total value: $total");

    // Assume customer_id is hardcoded for now
    $customer_id = '16';

    // Insert sale into sales table
    $sql = "INSERT INTO sales(customer_id, username, discount, payment_method, total)
            VALUES('$customer_id', '$user', '$discount', '$payment_method', '$total')";
    
    if (mysqli_query($db, $sql)) {
        // Log successful sale insertion
        logMessage("Sale recorded successfully for user: $user");

        // Get the last receipt number
        $select = "SELECT reciept_no FROM sales ORDER BY reciept_no DESC LIMIT 1";
        $res = mysqli_query($db, $select);
        $id = mysqli_fetch_array($res);
        
        // Prepare to insert products sold
        for ($i = 0; $i < count($product); $i++) {
            $reciept[] = $id[0];
        }

        for ($num = 0; $num < count($product); $num++) {
            $product_id = mysqli_real_escape_string($db, $product[$num]);
            $qtyold = mysqli_real_escape_string($db, $quantity[$num]);

            // Check current quantity of product
            $sql1 = "SELECT quantity FROM products WHERE product_no='$product_id'";
            if ($result1 = mysqli_query($db, $sql1)) {
                if ($qty = mysqli_fetch_array($result1)) {
                    // Calculate new quantity
                    $newqty = (int)$qty['quantity'] - (int)$qtyold;

                    // Update product quantity in database
                    if (mysqli_query($db, "UPDATE products SET quantity=$newqty WHERE product_no='$product_id'")) {
                        logMessage("Updated quantity for product ID: $product_id");
                    } else {
                        logMessage("Failed to update quantity for product ID: $product_id");
                    }
                } else {
                    logMessage("Product not found: ID - $product_id");
                }
            } else {
                logMessage("Failed to fetch quantity for product ID: $product_id");
            }
        }

        // Insert logs for sale action
        mysqli_query($db, "INSERT INTO logs (username,purpose) VALUES('$user','Product sold')");

        // Prepare query for inserting sold products into sales_product table
        foreach ($product as $count => $prod) {
            if (!empty($prod) && !empty($quantity[$count]) && !empty($price[$count]) && !empty($reciept[$count])) {
                // Sanitize inputs before inserting into database
                $price_clean = mysqli_real_escape_string($db, str_replace('Ksh', '', str_replace(',', '', trim($price[$count]))));
                $reciept_clean = mysqli_real_escape_string($db, trim($reciept[$count]));
                $product_clean = mysqli_real_escape_string($db, trim($prod));
                $quantity_clean = mysqli_real_escape_string($db, trim($quantity[$count]));

                // Insert into sales_product table
                if (!empty($reciept_clean) && !empty($product_clean) && !empty($price_clean) && !empty($quantity_clean)) {
                    mysqli_query($db, "INSERT INTO sales_product(reciept_no, product_id, price, qty) 
                                       VALUES('$reciept_clean', '$product_clean', '$price_clean', '$quantity_clean')");
                }
            }
        }
        
        echo "success"; // Return success message

    } else {
        logMessage("Failed to record sale for user: $user");
        echo "failure"; // Return failure message
    }
}
?>