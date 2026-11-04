<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log incoming request data
file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Received POST data:\n" . print_r($_POST, true) . "\n\n", FILE_APPEND);

include('../server/connection.php');

if(isset($_POST['barcode'])){
    try {
        $barcode     = $_POST['barcode'];
        $product     = $_POST['product_name'];
        $qty         = $_POST['quantity'];
        $buy_price   = $_POST['buy_price'];
        $unit        = $_POST['unit'];
        $tax        = $_POST['tax_rate'];
        $expiry_date = $_POST['expiry_date'];
        $min_qty     = $_POST['min_qty'];
        $sell_price = $_POST['sell_price'];
        $minimum_price = $_POST['minimum_price'];
        $remarks     = $_POST['remarks'];
        $location     = $_POST['location'];
        $supplier     = mysqli_real_escape_string($db,$_POST['supplier']);
        $transaction_no = mysqli_real_escape_string($db,$_POST['transaction_no']);
        $user         = mysqli_real_escape_string($db,$_SESSION['username']);
        $transaction = array();
        $insert = '';

        // Log processed variables
        file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Processed variables:\n" . 
            "Supplier: $supplier\nTransaction: $transaction_no\nUser: $user\n\n", FILE_APPEND);

        $search = "SELECT supplier_id FROM supplier WHERE company_name = '$supplier'";
        $show = mysqli_query($db,$search);
        
        if(!$show) {
            throw new Exception("Supplier query failed: " . mysqli_error($db));
        }

        if(mysqli_num_rows($show) == 0){
            throw new Exception("Supplier not found: $supplier");
        }

        $row = mysqli_fetch_array($show);
        $supplier_1 = $row['supplier_id'];
        
        // Insert delivery record
        $sql = "INSERT INTO delivery(transaction_no,supplier_id,username) VALUES('$transaction_no',$supplier_1,'$user')";
        $result = mysqli_query($db, $sql);
        
        if(!$result) {
            throw new Exception("Failed to insert delivery record: " . mysqli_error($db));
        }

        // Insert log entry
        $insert1 = "INSERT INTO logs (username,purpose) VALUES('$user','Delivery Added')";
        $res = mysqli_query($db, $insert1);
        
        if(!$res) {
            throw new Exception("Failed to insert log entry: " . mysqli_error($db));
        }

        // Create transaction array
        for($count = 0; $count<count($_POST['barcode']); $count++){
            $transaction[] = $transaction_no;
        }

        // Process each product
        for($num= 0; $num < count($_POST['barcode']); $num++){
            $transaction1     = mysqli_real_escape_string($db, $transaction[$num]);
            $barcode_1       = mysqli_real_escape_string($db, $barcode[$num]);
            $product_1       = mysqli_real_escape_string($db, $product[$num]);
            $qty_1          = mysqli_real_escape_string($db, $qty[$num]);
            $buy_price_1    = mysqli_real_escape_string($db, $buy_price[$num]);
            $unit_1         = mysqli_real_escape_string($db, $unit[$num]);
            $tax_1          = mysqli_real_escape_string($db, $tax[$num]);
            $expiry_date   = mysqli_real_escape_string($db, $expiry_date[$num]);
            $min_qty_1      = mysqli_real_escape_string($db, $min_qty[$num]);
            $sell_price_1   = mysqli_real_escape_string($db, $sell_price[$num]);
            $minimum_price1 = mysqli_real_escape_string($db, $minimum_price[$num]);
            $remarks_1      = mysqli_real_escape_string($db, $remarks[$num]);
            $location_1     = mysqli_real_escape_string($db, $location[$num]);

            // Check if product exists
            $query = "SELECT product_no, quantity FROM products WHERE product_no='$barcode_1'";
            $result1 = mysqli_query($db, $query);
            
            if(!$result1) {
                throw new Exception("Failed to query product: " . mysqli_error($db));
            }

            if(mysqli_num_rows($result1) > 0){
                // Update existing product
                while($row = mysqli_fetch_array($result1)){
                    $newqty = $row['quantity'] + $qty_1;
                    $query1 = "UPDATE products SET 
                        product_name='$product_1',
                        sell_price = $sell_price_1,
                        quantity = $newqty,
                        unit = '$unit_1',
                        min_stocks=$min_qty_1, 
                        remarks='$remarks_1', 
                        location='$location_1' 
                        WHERE product_no = '$barcode_1'";
                    
                    if(!mysqli_query($db, $query1)) {
                        throw new Exception("Failed to update product: " . mysqli_error($db));
                    }
                }

                $insert .= "
                INSERT INTO product_delivered(transaction_no,product_id,total_qty,buy_price,tax_rate)
                VALUES('$transaction1','$barcode_1',$qty_1,$buy_price_1,$tax_1);
                ";
            } else {
                // Insert new product
                $insert .= "
                INSERT INTO products(product_no,product_name,sell_price,minimum_price,buying_price,quantity,unit,min_stocks,remarks,location,Expiry_Date) 
                VALUES('$barcode_1','$product_1','$sell_price_1','$minimum_price1','$buy_price_1','$qty_1','$unit_1',$min_qty_1,'$remarks_1','$location_1','$expiry_date');
                ";

                $insert .= "
                INSERT INTO product_delivered(transaction_no,product_id,total_qty,buy_price,tax_rate)
                VALUES('$transaction1','$barcode_1',$qty_1,$buy_price_1,$tax_1);
                ";
            }
        }

        // Log final SQL
        file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Final SQL:\n$insert\n\n", FILE_APPEND);

        if($insert != ''){
            if (mysqli_multi_query($db, $insert)) {
                do {
                    if ($result = mysqli_store_result($db)) {
                        mysqli_free_result($result);
                    }
                    
                    if (!mysqli_more_results($db)) {
                        break;
                    }
                    
                    if (!mysqli_next_result($db)) {
                        throw new Exception("Failed during multi-query execution: " . mysqli_error($db));
                    }
                } while (true);
                
                echo "success";
            } else {
                throw new Exception("Failed to execute final queries: " . mysqli_error($db));
            }
        } else {
            throw new Exception("No insert queries were generated");
        }
        
    } catch (Exception $e) {
        file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Error: " . $e->getMessage() . "\n\n", FILE_APPEND);
        echo "failure: " . $e->getMessage();
    }
} else {
    echo "failure: no barcode data received";
}
?>