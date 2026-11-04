    <?php
include('server/connection.php');

// Check if the request has been made
if (isset($_POST['products'])) {
    // Escape the input for safety
    $name = mysqli_real_escape_string($db, $_POST['products']);

    // If the search term is empty, select all products
    if (empty($name)) {
        $show = "SELECT * FROM products WHERE quantity > 0  LIMIT 5"; // Get all products with quantity > 0
    } else {
        // Otherwise, filter based on the search term
        $show = "SELECT * FROM products WHERE (product_name LIKE '$name%' OR product_no LIKE '$name%') AND quantity > 0";
    }

    $query = mysqli_query($db, $show);

    // Check for SQL query error
    if (!$query) {
        echo "<tr><td colspan='5'>Error: " . mysqli_error($db) . "</td></tr>"; // Output error message if any
        exit;
    }

    // Check if any products are found
    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_array($query)) {
            echo "<tr style='text-align: center' class='js-add' data-barcode='" . $row['product_no'] . "' data-product='" . $row['product_name'] . "' data-price='" . $row['sell_price'] . "' data-minimum_price='" . $row['minimum_price'] . "' data-unt='" . $row['unit'] . "'>";
            echo "<td>" . $row['product_no'] . "</td><td>" . $row['product_name'] . "</td>";
            echo "<td>" . $row['sell_price'] . "</td><td>" . $row['minimum_price'] . "</td><td>" . $row['unit'] . "</td><td>" . $row['quantity'] . "</td></tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No Products found!</td></tr>"; // Message when no products found
    }
}
?>
