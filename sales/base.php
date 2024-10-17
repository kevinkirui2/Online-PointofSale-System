<style>
	@import url('https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&display=swap');

	label{
		font-family: "EB Garamond", serif;
  font-optical-sizing: auto;
  font-weight: <400>;
  font-style: bold;
  font-size: 18px;

	}
	.tittle{
		background-color: grey;
		
		
	}

	form>button{
		border-radius: 5px;
		width: 10%;
		color: black;
		background-color: skyblue;
	}

	input{
		font-family: "EB Garamond", serif;
		border: none;
		outline: none;
		font-size: 17px;
	}
	.main-form {
	background-color: #f2f2f2;
	width: 55%;
	padding: 20px;
	margin: 0 auto;
	flex-wrap: wrap;
	border-radius: 10px;
	box-shadow: 5px 5px 10px #ccc;
	}
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

			.main-form{
				width: 100%;
			}
			form > button{
				width: 30%;
				border: none;
			}


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
				<h3 class="dashboard"><i class="fas fa-tachometer-alt"></i> Sales Dashboard</h3></button>
				<span  id="sidebar_button" onclick="window.location.href='../sales/sales.php'"><i class="fas fa-cart-arrow-down"></i> Sales</span>
				<span  id="sidebar_button" onclick="window.location.href='../sales/closeofday.php'"><i class="fas fa-cart-arrow-down"></i> Close of Day Sales</span>
				
				
				<span   id="sidebar_button" type="button" data-toggle="popover" title="Sales Record" data-content="Here you can view sales record of the store." data-placement="bottom"><i class="fas fa-question"></i> Help</span>
				<span id="sidebar_button" onclick="window.location.href='../main.php'"><i class="fas fa-arrow-alt-circle-left"></i> Back</span>	
			</div>



						
			</div>



<!-- <div class="header bg-dark">
	<img class="img-fluid w-100 mt-2 ml-1" src="../images/logo.png" >
</div>
<div class="sidebar">
	<button><h3><i class="fas fa-tachometer-alt"></i> Dashboard</h3></button>
	<button id="sidebar_button" onclick="window.location.href='../sales/sales.php'"><i class="fas fa-cart-arrow-down"></i> Sales</button>
	<button id="sidebar_button" type="button" data-toggle="popover" title="Sales Record" data-content="Here you can view sales record of the store." data-placement="bottom"><i class="fas fa-question"></i> Help</button>
	<div class="fixed-bottom">
		<button class="btn m-2 p-2" id="sidebar_button" onclick="window.location.href='../main.php'"><i class="fas fa-arrow-alt-circle-left"></i> Back</button>
	</div>
</div> -->