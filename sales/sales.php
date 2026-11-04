<?php
include("../server/connection.php");
include('../set.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('../templates/head1.php'); ?>
    <title>Sales Records</title>
</head>

<body>
    <div class="container-fluid">
        <?php include('../sales/base.php'); ?>

        <div class="pr-1">
            <!-- Page Header -->
            <div class="mt-3">
                <h1 class="ml-4 pt-2" align="left">
                    <i class="fas fa-shopping-cart"></i> Sales Records
                </h1>
            </div>

            <!-- Date Filters -->
            <form method="GET" action="" class="form-inline ml-4">
                <label for="from_date" class="mr-2">From:</label>
                <input type="date" name="from_date" id="from_date" class="form-control mr-3" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : ''; ?>" required>

                <label for="to_date" class="mr-2">To:</label>
                <input type="date" name="to_date" id="to_date" class="form-control mr-3" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : ''; ?>" required>

                <button type="submit" class="btn btn-primary">Filter</button>
            </form>

            <!-- Table -->
            <div class="table-responsive pl-lg-5 pr-5 mt-4">
                <table class="table table-bordered table-striped" id="sales_table" style="margin-top: 22px;">
                    <thead>
                        <tr class="p-10">
                            <th scope="col">Product ID</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Buying Price</th>
                            <th scope="col">Sell Price</th>
                            <th scope="col">Minimum Price</th>
                            <th scope="col">Quantity Sold</th>
                            <th scope="col">Total Amount</th>
                            <th scope="col">Profit</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch user inputs
                        $from_date = isset($_GET['from_date']) ? $_GET['from_date'] : null;
                        $to_date = isset($_GET['to_date']) ? $_GET['to_date'] : null;

                        // Default query without filtering
                        $sql = "
                            SELECT 
                                sp.product_id, 
                                p.product_name, 
                                p.buying_price,
                                p.sell_price, 
                                p.minimum_price, 
                                COUNT(sp.product_id) AS quantity,
                                (COUNT(sp.product_id) * p.sell_price) AS total_amount,
                                (COUNT(sp.product_id) * (p.sell_price - p.buying_price)) AS profit,
                                s.date AS sale_date
                            FROM sales_product sp
                            INNER JOIN products p ON sp.product_id = p.product_no
                            INNER JOIN sales s ON sp.reciept_no = s.reciept_no
                        ";

                        // Add date filtering if both dates are provided
                        if ($from_date && $to_date) {
                            $from_date = $from_date . " 00:00:00"; // Start of the day
                            $to_date = $to_date . " 23:59:59"; // End of the day
                            $sql .= " WHERE s.date BETWEEN '$from_date' AND '$to_date'";
                        }

                        $sql .= " GROUP BY sp.product_id, p.product_name, p.buying_price, p.sell_price, p.minimum_price, s.date";

                        $result = mysqli_query($db, $sql);

                        // Check if rows exist
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>{$row['product_id']}</td>";
                                echo "<td>{$row['product_name']}</td>";
                                echo "<td>{$row['buying_price']}</td>";
                                echo "<td>{$row['sell_price']}</td>";
                                echo "<td>{$row['minimum_price']}</td>";
                                echo "<td>{$row['quantity']}</td>";
                                echo "<td class='total-amount'>{$row['total_amount']}</td>";
                                echo "<td>{$row['profit']}</td>";
                                echo "<td>{$row['sale_date']}</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center'>No Records Found</td></tr>";
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-right">Grand Total:</th>
                            <th id="total_amount"></th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../bootstrap4/jquery/jquery.min.js"></script>
    <script src="../bootstrap4/jquery/accounting.min.js"></script>
    <script src="../bootstrap4/jquery/datepicker.js"></script>
    <script src="../bootstrap4/js/jquery.dataTables.js"></script>
    <script src="../bootstrap4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../bootstrap4/js/bootstrap.bundle.min.js"></script>
    <script>
        // Calculate Grand Total
        document.addEventListener("DOMContentLoaded", function () {
            let total = 0;

            // Get all total amounts in the table
            document.querySelectorAll(".total-amount").forEach(function (element) {
                let amount = parseFloat(element.textContent || 0);
                total += amount;
            });

            // Display the grand total as a label
            let formattedTotal = total.toLocaleString("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById("total_amount").textContent = formattedTotal;
        });
    </script>
</body>

</html>
