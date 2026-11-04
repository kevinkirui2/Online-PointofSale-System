<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();

?>
<?php 

$month = date('m');
$day = date('d');
$year = date('Y');
$w=0.0;
$c=0.0;
$x=0.0;
$y=0.0;
$z=0.0;
 

$today = $year . '-' . $month . '-' . $day;
$fromdate=$today;
  $todate=$today;
if(isset($_GET['search'])) { 
           if (isset($_GET['fromdate'])) {
     $fromdate=($_GET['fromdate']); 
}  

if (isset($_GET['todate'])) {
     $todate=($_GET['todate']); 
}  
        } 
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>TopTen</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">Top Ten</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" />
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#">Settings</a>
                        <a class="dropdown-item" href="#">Activity Log</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="index.html">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                          
                            <a class="nav-link" href="main.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Home
                            </a>
                           
                            <div class="sb-sidenav-menu-heading">Reports</div>
                            <a class="nav-link" href="charts.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Charts
                            </a>
                        
                            
                             <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Tables
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                    <a class="nav-link collapsed" href="salessummary.php">
                                        Sales Summary
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                  
                                    <a class="nav-link collapsed" href="dailysummary.php" >
                                        Daily Report
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <a class="nav-link collapsed" href="restocking.php" >
                                        Restocking Summary
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <a class="nav-link collapsed" href="pricechange.php" >
                                        Price Change Summary
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                   <a class="nav-link collapsed" href="stock.php" >
                                        Stock Summary
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                </nav>
                            </div>
                            
                            
                            
                            
                            
                            
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as: <?=$_SESSION['name']?></div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Charts</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">Charts</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-body">
                                <form  method="GET">
                                 <label padding="50">
                                    From:
                                    </label>
                                    <input type="date" value="<?php echo $fromdate; ?>" class="" id="fromdate" name="fromdate"> 
                                    <p>
                                    <label padding="50">
                                    To:
                                    </label>
                                    
                                    <input type="date" value="<?php echo $todate; ?>" class="" id="todate" name="todate" padding="50dp">
                                
                               
                                    <input type="submit" name="search" class="button" value="Search"></p> 
                                    
                                     
                                     <?php 
                                     /* Database connection settings */
                                    	$host = 'localhost';
                                    	$user = 'flashtec_flashtec';
                                    	$pass = 'Kenya254.';
                                    	$db = 'flashtec_double';
                                    	$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);
                                    
                                    	$date = '';
                                    	$amount = '';
                                    	$margin = '';
                                        $month = '';
                                    	$total = '';
                                    	$item = '';
                                    	$qty = '';
                                    	//query to get data from the table
                                    	//$sql = "SELECT DATE_FORMAT(date,'%W %D') as date,sum(Qty*Unit_price) as amount,sum(Qty*Unit_price)-(sum(Qty*Buying_price)) as margin FROM sales WHERE DATE_FORMAT(time,'%y-%m-%d') >= (DATE(NOW()) - INTERVAL 7 DAY) group by DATE_FORMAT(Time,'%d') ORDER BY DATE_FORMAT(time,'%y-%m-%d') ASC";
                                    	$sql = "SELECT DATE_FORMAT(s.date, '%W %D') AS date, 
                                    	COUNT(sp.product_id) AS quantity, 
                                    	SUM(sp.product_id * p.sell_price) AS total_amount, 
                                    	SUM(sp.product_id * p.sell_price) - SUM(sp.product_id * p.buying_price) AS profit
                                    	FROM sales_product sp
                                    	INNER JOIN products p ON sp.product_id = p.product_no
                                    	INNER JOIN sales s ON sp.reciept_no = s.reciept_no
                                    	WHERE DATE_FORMAT(s.date, '%Y-%m-%d') >= (DATE(NOW()) - INTERVAL 7 DAY)
                                    	GROUP BY DATE_FORMAT(s.date, '%d')
                                    	ORDER BY DATE_FORMAT(s.date, '%Y-%m-%d') ASC;";
                                        $result = mysqli_query($mysqli, $sql);
                                    
                                    	//loop through the returned data
                                    	while ($row = mysqli_fetch_array($result)) {
                                    
                                    		$date = $date . '"'. $row['date'].'",';
                                    		$amount = $amount . '"'. $row['total_amount'] .'",';
                                    		$margin = $margin . '"'. $row['profit'] .'",';
                                    	}
                                    
                                    	$date = trim($date,",");
                                    	$amount = trim($amount,",");
                                    	$margin = trim($margin,",");
                                    	//=========end for line
                                    	
                                    	//=====================start for bar chart
                                    	
                                    		//query to get data from the table
                                    	//$sql = "SELECT DATE_FORMAT(time,'%M %Y') as date,sum(Qty*Unit_price) as amount,sum(Qty*Unit_price)-(sum(Qty*Buying_price)) as margin FROM sales WHERE DATE_FORMAT(time,'%y-%m-%d') >= (DATE(NOW()) - INTERVAL 12 MONTH) group by DATE_FORMAT(Time,'%M') ORDER BY DATE_FORMAT(time,'%y-%m-%d') ASC";
                                    	
                                    	$sql = "SELECT 
    DATE_FORMAT(s.date, '%M %Y') AS date, 
    COUNT(sp.product_id) AS quantity, 
    SUM(p.sell_price) AS total_amount, 
    SUM(p.sell_price - p.buying_price) AS profit
FROM 
    sales_product sp
INNER JOIN 
    products p ON sp.product_id = p.product_no
INNER JOIN 
    sales s ON sp.reciept_no = s.reciept_no
WHERE 
    s.date >= (DATE(NOW()) - INTERVAL 12 MONTH)
GROUP BY 
    DATE_FORMAT(s.date, '%M %Y')
ORDER BY 
    s.date ASC
LIMIT 25;
";
                                    	
                                        $result = mysqli_query($mysqli, $sql);
                                    
                                    	//loop through the returned data
                                    	while ($row = mysqli_fetch_array($result)) {
                                    
                                    		$month = $month . '"'. $row['date'].'",';
                                    		$total = $total . '"'. $row['total_amount'] .'",';
                                    		
                                    	}
                                    
                                    	$month = trim($month,",");
                                    	$total = trim($total,",");
                                    	
                                    	//========================end for pie chart
                                    	
                                    		//=====================start for bar chart
                                    	
                                    		//query to get data from the table
                                    	$sql = "SELECT sp.product_id AS product_id, 
                                    	p.product_name AS product_name, 
                                    	COUNT(sp.product_id) AS quantity, 
                                    	SUM(quantity * p.sell_price) AS total_amount, 
                                    	SUM(quantity * p.sell_price) - SUM(quantity * p.buying_price) AS profit
                                    	FROM sales_product sp
                                    	INNER JOIN products p ON sp.product_id = p.product_no
                                    	INNER JOIN sales s ON sp.reciept_no = s.reciept_no
                                    	WHERE DATE_FORMAT(s.date, '%Y-%m-%d') >= (DATE(NOW()) - INTERVAL 12 MONTH)
                                    	GROUP BY sp.product_id, p.product_name
                                    	ORDER BY quantity DESC
                                    	LIMIT 5;";
                                        $result = mysqli_query($mysqli, $sql);
                                    
                                    	//loop through the returned data
                                    	while ($row = mysqli_fetch_array($result)) {
                                    
                                    		$item = $item . '"'. $row['product_name'].'",';
                                    		$qty = $qty . '"'. $row['quantity'] .'",';
                                    		
                                    	}
                                    
                                    	$item = trim($item,",");
                                    	$qty = trim($qty,",");
                                    	
                                    	//========================end for pie chart
                                    	?>
                                
                            </div>
                           
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-chart-area mr-1"></i>
                                Sales Graph
                            </div>
                            <div class="card-body"><canvas id="myAreaChart" width="100%" height="30"></canvas></div>
                            <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar mr-1"></i>
                                        Last 5 Days Sales and Profit Graph
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart" width="100%" height="50"></canvas></div>
                                    <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-pie mr-1"></i>
                                        Top 5 selling Items
                                    </div>
                                    <div class="card-body"><canvas id="myPieChart" width="100%" height="50"></canvas></div>
                                    <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    .</form>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Flash Technologies 0723527560 2020</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="assets/demo/chart-pie-demo.js"></script>
        <script>
    // Bar Chart Example
    var ctxBar = document.getElementById("myBarChart").getContext('2d');
    var myBarChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: [<?php echo $date; ?>], // Dates from PHP
            datasets: [{
                label: "Total Amount",
                backgroundColor: "rgba(2,117,216,1)",
                borderColor: "rgba(2,117,216,1)",
                data: [<?php echo $amount; ?>], // Total amounts from PHP
            },
            {
                label: "Profit",
                backgroundColor: "rgba(40,167,69,1)",
                borderColor: "rgba(40,167,69,1)",
                data: [<?php echo $margin; ?>], // Profit from PHP
            }]
        },
        options: {
            scales: {
                xAxes: [{
                    time: {
                        unit: 'day'
                    },
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                }],
                yAxes: [{
                    ticks: {
                        min: 0,
                        maxTicksLimit: 5
                    },
                    gridLines: {
                        display: true
                    }
                }]
            },
            legend: {
                display: true
            }
        }
    });

    // Pie Chart Example
    var ctxPie = document.getElementById("myPieChart").getContext('2d');
    var myPieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: [<?php echo $item; ?>], // Product names from PHP
            datasets: [{
                data: [<?php echo $qty; ?>], // Quantities from PHP
                backgroundColor: ['#007bff', '#dc3545', '#ffc107', '#28a745', '#6c757d'], // Colors
            }],
        },
        options: {
            responsive: true,
            legend: {
                position: 'top',
            },
        }
    });
</script>

    </body>
</html>
