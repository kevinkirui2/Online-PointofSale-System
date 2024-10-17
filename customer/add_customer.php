<?php 
	include('../customer/add.php');
	include '../set.php';
	?>
<!DOCTYPE html>
<html>
<head>
	<?php include('../templates/head1.php');?>

	<style>
		
		#sidebar_button{
			
			font-size: 20px;
			cursor: pointer;
			color: whitesmoke;
			padding-top: 5px;
			
		}

		.sideebar{
			background-color: #001a33;
			padding: 20px;
			display:flex;
			gap: 150px;
		}
		.dashboard{
			color: green
		}
		.current{
			border-bottom: 2px solid whitesmoke;
			
			padding-bottom: 10px;
		}
		.heading{
			text-align: center;
			padding-top: 50px;
		}
		.tabo{
			padding-left: 100px;
		}
		img{
			width: 380px;
		}
		@media screen and (max-width: 769px) {
	
	.btn-flex{
		flex-direction: column;
	}
	
	.sideebar{
		text-align: center;
		flex-direction: column;
		color: aliceblue;
		padding-bottom: 30px;
		width: 100%;
		gap: 50px;
		overflow: hidden;
		
	}
	#sidebar_button{
		font-size: 20px;
		padding-bottom: 40px;
		padding-left: 100px;
		padding-top: 20px;
		padding-right: 100px;
	
		
		
		
	}
	.dashboard{
		text-align: center;
	}
	.heading{
		padding-bottom: 30px;
	}

	/* Table below */
	.main{
		flex-direction: column;
		padding-left: 10px;
		
	}
	
	
}
		
</style>
</head>
<body>
	<div class="">
		<?php 
			// include('../customer/base.php');
			include('../customer/alert.php');
		?>

		<!-- START OF COPY SECTION -->
<span class="image"> <div class="header bg-dark"></span>
			<img  class="" src="../images/logo.png" >
		</div>

			<div style="" class="flex-section">
								
				<div id="sidebar" class="sideebar">
				<h3 class="dashboard"><i class="fas fa-tachometer-alt"></i>Customer Dashboard</h3></button>
				<span id="sidebar_button" onclick="window.location.href='../customer/customer.php'"><i class="fas fa-list-ul"></i> Customer List</span>
				<span class=""><span id="sidebar_button" onclick="window.location.href='../customer/add_customer.php'"><i class="fas fa-user-plus"></i> Add Customer</span></span>
				<span id="sidebar_button" type="button" data-toggle="popover" title="Customer Management" data-content="Here you can create, update, delete and view customer profiles." data-placement="bottom"><i class="fas fa-question"></i> Help</span>
				<span id="sidebar_button" onclick="window.location.href='../main.php'"><i class="fas fa-arrow-alt-circle-left"></i> Back</span>	
			</div>



						<div class="heading">
							<h1 class="ml-4  pl-60"><i class="fas fa-user-friends"></i> Customer Management</h1>
						</div>
			</div>


			<!-- END OF COPY SECTION -->


		<div style="display: flex; justify-content: center;" class="main">
			
			<div class="first_side ml-5 pl-sm-5 mt-5 mr-3">
				<div style="border:1px dashed black; width: 250px;height: 250px;">
					<img class="img-fluid  p-2 h-100 w-100" src="../images/customer.png">
				</div>
			<form method="post" enctype="multipart/form-data">
				<input type="hidden" name="size" class="form-control-sm" value="1000000">
				<input class="form-control-sm" type="file" name="image" required>
				<p class="bg-danger mt-3">
			</div>
			<div class="second_side">
					<table class="table-responsive mt-5">
						<tbody>
							<tr>
								<td  valign="baseline">First Name:</td>
								<td class="pl-5 pb-2"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="fas fa-pen-alt"></i></span></div><input type="text" name="fname" class="form-control-sm form-control" pattern="[A-Za-z]+" title="Name must not contain numbers or spaces e.g John12" placeholder="Enter Firstname" required></div></td>
							</tr>
							<tr>
								<td  valign="baseline">Last Name:</td>
								<td class="pl-5 pb-2"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="fas fa-pen-alt"></i></span></div><input type="text" name="lname" class="form-control-sm form-control" pattern="[A-Za-z]+" title="Name must not contain numbers or spaces e.g John12" placeholder="Enter Lastname" required></div></td>
							</tr>
							<tr>
								<td  valign="baseline">Address:</td>
								<td class="pl-5 pb-2"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="fas fa-map-marker-alt"></i></span></div><textarea name="address" required class="form-control-sm form-control" pattern="[A-Za-z0-9]+" placeholder="Enter Adderss"  cols="23"></textarea></div></td>
							</tr>
							<tr>
								<td  valign="baseline">Contact Number:</td>
								<td class="pl-5 pb-2"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text" id="basic-addon1"><i class="fas fa-phone"></i></span></div><input type="text" name="number" class="form-control-sm form-control" pattern='[\+]\d{2}[\(]\d{2}[\)]\d{4}[\-]\d{4}' title='Phone Number (Format: +99(99)9999-9999)' placeholder="Enter Contact number" required></div></td>
							</tr>
						</tbody>
					</table>
					<div class="text-center pt-3">
						<button type="submit" name="add_customer" class="btn btn-secondary"><i class="fas fa-thumbs-up"></i> Submit</button>
						<button class="btn btn-danger" onclick="window.location.href='../customer/customer.php'" ><i class="fas fa-ban"></i> Cancel</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script src="../bootstrap4/jquery/jquery.min.js"></script>
	<script src="../bootstrap4/js/bootstrap.bundle.min.js"></script>
	<script>
		$(function () {
  			$('[data-toggle="popover"]').popover()
	})
	</script>
</body>
</html>