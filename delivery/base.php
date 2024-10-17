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
		padding-bottom: 20px;
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
	.main{
		flex-direction: column;
		justify-content: center;
		padding-left: 10px;
		
	}
	.iimage{
		padding-left: 60px;
	}
	
	
}
	</style>


<span class="image"> <div class="header bg-dark"></span>
		</div>

			<div style="" class="flex-section">
								
				<div id="sidebar" class="sideebar">
				<h3 class="dashboard"><i class="fas fa-tachometer-alt"></i> Delivery Dashboard</h3></button>
				<span id="sidebar_button" onclick="window.location.href='../delivery/delivery.php'"><i class="fas fa-hands-helping"></i> Transaction Details</span>
				<span id="sidebar_button" onclick="window.location.href='../delivery/add_delivery.php'"><i class="fas fa-truck"></i> Add Deliveries</span>
				<span id="sidebar_button" onclick="window.location.href='../delivery/import_csv.php'"><i class="fas fa-file-upload"></i> Import CSV</span>			
				<span id="sidebar_button" data-toggle="popover" title="Product Deliveries" data-content="After the textbox is filled, Please click the Buy Price Textbox and Tax Rate(%) Textbox to get the Sell Price Value. " data-placement="bottom"><i class="fas fa-question"></i> Help</span>
				<span id="sidebar_button" onclick="window.location.href='../main.php'"><i class="fas fa-arrow-alt-circle-left"></i> Back</span>	
			</div>



						
			</div>






<!-- <div class="header bg-dark">
	<img class="img-fluid w-100 mt-2 ml-1" src="../images/logo.png" >
</div>
<div class="sidebar">
	<button><h3><i class="fas fa-tachometer-alt"></i> Dashboard</h3></button>
	<button id="sidebar_button" onclick="window.location.href='../delivery/delivery.php'"><i class="fas fa-hands-helping"></i> Transaction Details</button>
	<button id="sidebar_button" onclick="window.location.href='../delivery/add_delivery.php'"><i class="fas fa-truck"></i> Add Deliveries</button>
	<button id="sidebar_button" onclick="window.location.href='../delivery/import_csv.php'"><i class="fas fa-file-upload"></i> Import CSV</button>
	<button id="sidebar_button" data-toggle="popover" title="Product Deliveries" data-content="After the textbox is filled, Please click the Buy Price Textbox and Tax Rate(%) Textbox to get the Sell Price Value. " data-placement="bottom"><i class="fas fa-question"></i> Help</button>
	<div class="fixed-bottom">
		<button class="btn m-2 p-2" id="sidebar_button" onclick="window.location.href='../main.php'"><i class="fas fa-arrow-alt-circle-left"></i> Back</button>
	</div>
</div> -->