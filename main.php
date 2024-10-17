<?php  
	include('server/connection.php');
	if(!isset($_SESSION['username'])){
		header('location: index.php');
	}
	$added = isset($_GET['added']);
	$error = isset($_GET['error']);
	$undelete = isset($_GET['undelete']);
	$updated = '';
	$deleted = '';
	$failure = isset($_GET['failure']);
	
	$query 	= "SELECT * FROM `customer`";
	$show	= mysqli_query($db,$query);
	if(isset($_SESSION['username'])){
		$user = $_SESSION['username'];
		$sql = "SELECT position FROM users WHERE username='$user'";
		$result	= mysqli_query($db, $sql);
	}   
	if (mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_assoc($result)){
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Dashboard</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
	<?php include('templates/head.php'); ?>
	<style>
		/* Reset some default styles */
		body, html {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-family: Arial, sans-serif;
		}

		/* Navbar Styles */
		#navbar {
			display: flex;
			justify-content: space-between;
			align-items: center;
			background-color: #2d3748; /* Dark Gray */
			color: white;
			padding: 15px 20px;
			position: relative;
		}

		#navbar img {
			width: 120px;
		}

		/* Navigation Links */
		.nav-links {
			display: flex;
			gap: 20px;
		}

		.nav-links button {
			background: none;
			border: none;
			color: white;
			font-size: 16px;
			cursor: pointer;
			display: flex;
			align-items: center;
			gap: 5px;
		}

		.nav-links button:hover {
			color: #f7fafc; /* Light Gray */
		}

		/* Hamburger Menu */
		.hamburger {
			display: none;
			font-size: 24px;
			cursor: pointer;
		}

		/* Sidebar Styles */
		.sidebar {
			height: 100%;
			width: 0;
			position: fixed;
			top: 0;
			right: 0;
			background-color: rgba(0, 0, 0, 0.95);
			overflow-x: hidden;
			transition: 0.3s;
			padding-top: 60px;
			z-index: 1000;
		}

		.sidebar-content {
			display: flex;
			flex-direction: column;
			gap: 20px;
			padding: 20px;
		}

		.sidebar a {
			padding: 10px 20px;
			text-decoration: none;
			font-size: 18px;
			color: #f1f1f1;
			display: flex;
			align-items: center;
			gap: 10px;
		}

		.sidebar a:hover {
			background-color: #575757;
			color: white;
		}

		.close-btn {
			position: absolute;
			top: 20px;
			right: 25px;
			font-size: 30px;
			cursor: pointer;
			color: white;
		}

		/* User Info */
		.user-info {
			display: none;
			flex-direction: column;
			gap: 5px;
		}

		/* Media Queries */
		@media (max-width: 600px) {
			.nav-links {
				display: none;
			}

			#smalltxt {
				font-size: 12px;
			}

			

			.hamburger {
				display: block;
			}

			.sidebar {
				width: 50%;
			}

			.user-info {
				display: flex;
				margin-top: 20px;
			}
		}

		@media (min-width: 601px) {
			.nav-links {
				display: flex;
			}

			.hamburger {
				display: none;
			}

			.user-info {
				display: flex;
				flex-direction: column;
				gap: 5px;
				margin-left: 20px;
			}
		}

		/* Additional Styles for Main Content */
		.bg-gray-100 {
			background-color: #f7fafc;
		}

		.text-gray-800 {
			color: #2d3748;
		}

		/* Adjust other styles as needed */
	</style>
</head>

<body class="bg-gray-100 text-gray-800">
	<div id="navbar">
		<img src="images/logo1.jpg" alt="Logo">
		<div class="nav-links">
			<button onclick="window.location.href='user/user.php'"><i class="fas fa-users"></i> User</button>
			<button onclick="window.location.href='products/products.php'"><i class="fas fa-box-open"></i> Product</button>
			<button onclick="window.location.href='supplier/supplier.php'"><i class="fas fa-user-tie"></i> Supplier</button>
			<button onclick="window.location.href='customer/customer.php'"><i class="fas fa-user-friends"></i> Customer</button>
			<button onclick="window.location.href='logs/logs.php'"><i class="fas fa-globe"></i> Logs</button>
			<button onclick="window.location.href='cashflow/cashflow.php'"><i class="fas fa-money-bill-wave"></i> Cash-Flow</button>
			<button onclick="window.location.href='sales/sales.php'"><i class="fas fa-shopping-cart"></i> Sales</button>
			<button onclick="window.location.href='sales/closeofday.php'"><i class="fas fa-box-open"></i> Close of Day Summary</button>
			<button onclick="window.location.href='delivery/delivery.php'"><i class="fas fa-truck"></i> Deliveries</button>
		</div>
		
		<div class="user-info">
			<h6 class="text-sm"><i class="fas fa-user-tie"></i> Username: <span><?php echo htmlspecialchars($row['position']); ?></span></h6>
			<h6 class="text-sm"><i class="fa-solid fa-calendar-days"></i> Date: <span id="Date"></span></h6>
		</div>
		<div class="hamburger" onclick="openSidebar()">
			<i class="fas fa-bars"></i>
		</div>
	</div>

	<!-- Sidebar for Mobile Navigation -->
	<div id="sidebar" class="sidebar">
		<span class="close-btn" onclick="closeSidebar()">&times;</span>
		<div class="sidebar-content">
			<a href="user/user.php"><i class="fas fa-users"></i> User</a>
			<a href="products/products.php"><i class="fas fa-box-open"></i> Product</a>
			<a href="supplier/supplier.php"><i class="fas fa-user-tie"></i> Supplier</a>
			<a href="customer/customer.php"><i class="fas fa-user-friends"></i> Customer</a>
			<a href="logs/logs.php"><i class="fas fa-globe"></i> Logs</a>
			<a href="cashflow/cashflow.php"><i class="fas fa-money-bill-wave"></i> Cash-Flow</a>
			<a href="sales/sales.php"><i class="fas fa-shopping-cart"></i> Sales</a>
			<a href="sales/closeofday.php"><i class="fas fa-box-open"></i> Close of Day Summary</a>
			<a href="delivery/delivery.php"><i class="fas fa-truck"></i> Deliveries</a>
		</div>
	</div>

	<script>
		function openSidebar() {
			document.getElementById('sidebar').style.width = '50%';
		}

		function closeSidebar() {
			document.getElementById('sidebar').style.width = '0';
		}

		function updateDateTime() {
			var datetimeElement = document.getElementById('Date');
			var currentTime = new Date();
			var formattedDateTime = currentTime.getFullYear() + '-' + 
				('0' + (currentTime.getMonth() + 1)).slice(-2) + '-' + 
				('0' + currentTime.getDate()).slice(-2) + ' ' + 
				('0' + currentTime.getHours()).slice(-2) + ':' + 
				('0' + currentTime.getMinutes()).slice(-2) + ':' + 
				('0' + currentTime.getSeconds()).slice(-2);
			datetimeElement.innerHTML = formattedDateTime;
		}
		setInterval(updateDateTime, 1000);
		updateDateTime();
	</script>

	<!-- Main Section -->
	<div class="flex flex-wrap justify-around p-4 bg-gray-50">
		<!-- Grand Total Card -->
		<div class="bg-black text-green-500 p-6 rounded-lg border-2 border-white text-center w-64">
			<h3 class="text-xl font-bold">Grand Total</h3>
			<p id="totalValue" class="text-2xl mt-4">Ksh 0.00</p>
		</div>
		
		<!-- Search Bar -->
		<div class="w-full mt-6">
			<div class="flex justify-center">
				<input type="text" class="p-2 border rounded-lg w-1/2" placeholder="Product Search" id="search" name="search" onkeyup="loadproducts();">
			</div>
		</div>
		
		<!-- Product Table -->
		<div class="mt-6 w-full overflow-x-auto">

		<table class="w-100 table-striped font-weight-bold" style="cursor: pointer;" id="table1">
        <thead>
            <tr class='text-center'>
                <th>Barcode</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Unit</th>
                <th>Stocks</th>
            </tr>
        </thead>
        <tbody id="tableData">
            <!-- Product rows will be populated here by AJAX -->
        </tbody>
    </table>

		</div>
	</div>

	<!-- Cart Section -->
	<div class="p-4">
		<form method="POST" action="">
		<div class="mt-6 w-full overflow-x-auto">
			<table class="min-w-full bg-white shadow-lg rounded-lg">
				<thead class="bg-gray-200">
					<tr>
						<th id="smalltxt" class="p-3 text-left">Barcode</th>
						<th id="smalltxt" class="p-3 text-left">Description</th>
						<th id="smalltxt" class="p-3 text-left">Price</th>
						<th id="smalltxt" class="p-3 text-left">Unit</th>
						<th id="smalltxt" class="p-3 text-left">Qty</th>
						<th id="smalltxt" class="p-3 text-left">Sub.Total</th>
						<th id="smalltxt" class="p-3 text-left">Action</th>
					</tr>
				</thead>
				<tbody id="products">
					<!-- Dynamic rows here -->
				</tbody>
			</table>
		</div>
		</form>
	</div>
	<div style="text-align:center" >
		<small>
			<ul class="text-black ">
				<li class="d-flex mb-0">Total (Ksh): <p id="totalValue1" class="mb-0 ml-5 pl-3">0.00</p></li>
				<li class="mb-0 mt-0">Discount (Ksh): <input style="width: 100px" class="text-right form-control-sm" type="number" name="discount" value="0" min="0" placeholder="Enter Discount" id="discount" ></li>
			</ul>
		</small>
		<div class="finish">
			<button id="buttons" type="button" name='enter' class="Enter btn btn-secondary border ml-2 w-30"><i class="fas fa-handshake"></i> Pay Now</button>
		</div>
	</div>

	<!-- Footer Buttons -->

	<!-- Logout -->
	<div class="flex justify-center mt-8">
		<button class="bg-red-600 text-white py-2 px-6 rounded-lg"><i class="fas fa-sign-out-alt"></i><a href="logout.php">Logout</a></button>
	</div>

	<?php include('add.php'); ?>
	<?php include('templates/js_popper.php'); ?>
	<script type="text/javascript" src="script.js"></script>
</body>
</html>
<?php } } ?>
