<?php include('server/connection.php'); ?>
<?php include('login.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <?php include('templates/head.php'); ?>
    <script src="bootstrap4/jquery/sweetalert.min.js"></script>
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-card {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .login-logo img {
            width: 150px;
            margin-bottom: 20px;
        }
        .login-buttons .btn {
            width: 180px;
            margin: 10px;
            padding: 15px;
            font-size: 18px;
            border-radius: 50px;
        }
        .login-buttons .btn:hover {
            background-color: #007bff;
            color: #fff;
        }
		.role{
			opacity: 50%;
			font-size: 20px;
			font-family: Arial;
		}
		.role2{
			opacity: 90%;
			font-size: 25px;
			font-family: Arial;
		}
    </style>
</head>
<body class="bg-dark">
    <div class="login-container">
        <div class="login-card text-center">
            <!-- Logo -->
            <div class="login-logo">
                <img class="img-fluid" src="images/log3.png" alt="Logo" />
            </div>
			<h1 class="role2">Welcome,</h1>
			<h1 class="role">Please State your Role!</h1>

            <!-- Error Messages -->
            <?php include('error.php'); ?>

            <!-- Buttons -->
            <div class="login-buttons">
                <button type="button" id="admin" class="btn btn-secondary" data-toggle="modal" data-target=".bd-example-modal-sm">
                    <i class="fas fa-user-tie"></i> Administrator
                </button>
                <button type="button" id="user" class="btn btn-secondary" data-toggle="modal" data-target="#modal-user">
                    <i class="fas fa-user"></i> User
                </button>
            </div>
        </div>
    </div>

    <script src="bootstrap4/jquery/jquery.min.js"></script>
    <script src="bootstrap4/js/bootstrap.bundle.min.js"></script>
    <?php include('modals/admin_login_modal.php'); ?>
    <?php include('modals/employee_login_modal.php'); ?>
</body>
</html>
