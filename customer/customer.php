<?php 
	include("../server/connection.php");
	include '../set.php';
	$sql = "SELECT * FROM customer ORDER BY customer_id ASC ";
	$result	= mysqli_query($db, $sql);
	$deleted = isset($_GET['deleted']);
	$added  = isset($_GET['added']);
	$updated = isset($_GET['updated']);
	$undelete = isset($_GET['undelete']);
	$failure = "";
	$error = "";
?>
<!DOCTYPE html>
<html>
<head>
	<?php include('../templates/head1.php');
	?>

	<style>
		#sidebar_button{
			
			font-size: 20px;
			cursor: pointer;
			color: whitesmoke;
			padding-top: 5px;
		}
		.current{
			border-bottom: 2px solid whitesmoke;
			
			padding-bottom: 10px;
		}
	

		.hamburg-menu{
			font-size: 50px;
			padding-left: 15px;
			cursor: pointer;
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
	.base > img{
		display: none;
		
	}
	.btn-flex{
		flex-direction: column;
	}
	tr{
		font-size: 9px;
	}
	.sideebar{
		
		flex-direction: column;
		gap: 50px;
		padding-bottom: 30px;
		width: 100%;
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
	.image{
		text-align: center;
	}
	
}


		</style>
</head>
<body>

	
		<span class="image"> <div class="header bg-dark"></span>

	</div>
	
	
		<?php 
			
			include('../alert.php');
		?>
		<div>

		
				<div style="" class="flex-section">
					
						<div id="sidebar" class="sideebar">
							<h3 class="dashboard"><i class="fas fa-tachometer-alt"></i> Customer Dashboard</h3></button>
							<span class="pt-sm-10"><span id="sidebar_button" onclick="window.location.href='../customer/customer.php'"><i class="fas fa-list-ul"></i> Customer List</span></span>
							<span id="sidebar_button" onclick="window.location.href='../customer/add_customer.php'"><i class="fas fa-user-plus"></i> Add Customer</span>
							<span id="sidebar_button" type="button" data-toggle="popover" title="Customer Management" data-content="Here you can create, update, delete and view customer profiles." data-placement="bottom"><i class="fas fa-question"></i> Help</span>
							<span id="sidebar_button" onclick="window.location.href='../main.php'"><i class="fas fa-arrow-alt-circle-left"></i> Back</span>	
						</div>
							<div class="heading">
								<h1 class="ml-4  pl-60"><i class="fas fa-user-friends"></i> Customer Management</h1>
							</div>
				</div>
				
				<hr>
				<span class="tabo"> <div class="table-responsive mt-4 pl-lg-5  pr-5">
				<table class="table table-striped table-bordered" id="customer_table" style="margin-top: -22px;">
					<thead> 
						<tr>
							<th scope="col" class="column-text">Customer ID</th>
							<th scope="col" class="column-text">Customer Name</th>
							<th scope="col" class="column-text">Address</th>
							<th scope="col" class="column-text">Contact Number</th>
							<th scope="col" class="column-text">Actions</th>
						</tr>
					</thead>
					<tbody class="table-hover">
						<?php 
							while($row = mysqli_fetch_assoc($result)){
						?>
						<tr class="table-active">
							<td><a href="customer_sales.php?customer_id=<?php echo $row['customer_id'];?>"><?php echo $row['customer_id'];?></a></td>
							<td><?php echo $row['firstname'].'&nbsp'.$row['lastname'];?></td>
							<td><?php echo $row['address'];?></td>
							<td><?php echo $row['contact_number'];?></td>
							<td style="display: flex; gap: 10px" class="btn-flex">
								<a name="edit" title="Edit" style='font-size:10px; border-radius:5px;padding:4px;' href="update_customer.php?id=<?php echo $row['customer_id'];?>" class="btn btn-info btn-xs"><i class="fas fa-user-edit"></i></a>
								<button type="button" name="view" style='font-size:10px; border-radius:5px;padding:4px;' id="<?php echo $row['customer_id'];?>" class="btn btn-success btn-xs view_data"><i class="fas fa-eye"></i></button>
								<button type="button" name="delete" title="Delete" style='font-size:10px; border-radius:5px;padding:4px;' data-id="<?php echo $row['customer_id'];?>"  class="delete btn btn-danger btn-xs" data-toggle="#deleteModal" title="Delete"><i class="fas fa-trash"></i></button>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>

				</div>
				</span>
			</div>
	</div>
	<script src="../bootstrap4/jquery/jquery.min.js"></script>
	<script src="../bootstrap4/js/jquery.dataTables.js"></script>
	<script src="../bootstrap4/js/dataTables.bootstrap4.min.js"></script>
	<script src="../bootstrap4/js/bootstrap.bundle.min.js"></script>
	<?php include('../customer/delete_customer.php');?>
	<script src="../customer/javascript.js"></script>	
</body>
</html>
<div id="dataModal" class="modal fade bd-example-modal-md" data-backdrop="static" data-keyboard="false">  
	<div class="modal-dialog modal-md"  role="document">  
		<div class="modal-content">   
		<div class="modal-body d-inline" id="Contact_Details"></div> 
			<div class="modal-footer"> 
				<input type="button" class="btn btn-default btn-success" data-dismiss="modal" value="Okay">   
			</div>  
	   </div>  
	</div>  
</div>
