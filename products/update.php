<?php
include("../server/connection.php");
$msg = '';

if (isset($_POST['update'])) {
    $target = "../images/" . basename($_FILES['images']['name']);
    $image = $_FILES['images']['name'];
    $id = $_POST['id'];
    $pro_name = mysqli_real_escape_string($db, $_POST['product_name']);
    $price = mysqli_real_escape_string($db, $_POST['price']);
    $buying_price = mysqli_real_escape_string($db, $_POST['buying_price']);
    $minimum_price = mysqli_real_escape_string($db, $_POST['minimum_price']);
    $qty = mysqli_real_escape_string($db, $_POST['qty']);
    $unit = mysqli_real_escape_string($db, $_POST['unit']);
    $min_stocks = mysqli_real_escape_string($db, $_POST['min_stocks']);
    $remarks = mysqli_real_escape_string($db, $_POST['remarks']);
    $location = mysqli_real_escape_string($db, $_POST['location']);
    $expiry_date = mysqli_real_escape_string($db, $_POST['expiry_date']);
    $username = $_SESSION['username'];

    // Validate inputs
    if ($minimum_price > $price) {
        echo "<script>alert('Minimum Price Cannot be Higher Than Selling Price');</script>";
    } else {
        // Prepare the SQL query
        $sql = "UPDATE products 
                SET product_name = '$pro_name',
                    sell_price = '$price',
                    buying_price = '$buying_price',
                    minimum_price = '$minimum_price',
                    quantity = '$qty',
                    unit = '$unit',
                    min_stocks = '$min_stocks',
                    remarks = '$remarks',
                    Expiry_Date = '$expiry_date',
                    location = '$location'";
        
        // Add image if provided
        if (!empty($image)) {
            $sql .= ", images = '$image'";
        }

        $sql .= " WHERE product_no = '$id'";

        // Execute query
        if (mysqli_query($db, $sql)) {
            // Log the update
            $log_sql = "INSERT INTO logs (username, purpose) VALUES ('$username', 'Product $pro_name updated')";
            mysqli_query($db, $log_sql);

            // Handle image upload if necessary
            if (!empty($image) && move_uploaded_file($_FILES['images']['tmp_name'], $target)) {
                echo "<script>window.location.href = '../products/products.php?updated';</script>";
            } else {
                echo "<script>window.location.href = '../products/products.php?updated';</script>";
            }
        } else {
            // Log error
            echo "Error updating product: " . mysqli_error($db);
        }
    }
}
