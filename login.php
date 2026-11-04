<?php
include('server/connection.php');
$error = array();

if (isset($_POST['login'])) {
	$password = md5($_POST['password']);
	$position = $_POST['position'];
	$username = $_POST['username'];

	if ($username != '' AND $password != '' AND $position != '') {

		$stmt = mysqli_prepare($db, "SELECT * FROM users WHERE username = ? AND position = ? AND password = ?");
		mysqli_stmt_bind_param($stmt, 'sss', $username, $position, $password);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		if (mysqli_num_rows($result) == 1) {
			$row = mysqli_fetch_assoc($result);

			$_SESSION['username'] = $row['username'];
			$user = $_SESSION['username'];
			$purpose = "User $user login";

			$log = mysqli_prepare($db, "INSERT INTO logs (username, purpose) VALUES (?, ?)");
			mysqli_stmt_bind_param($log, 'ss', $user, $purpose);
			mysqli_stmt_execute($log);

			header('location: employee_page.php');
			exit;
		} else {
			array_push($error, "Wrong username/password!");
		}

	} else {
		array_push($error, "All fields are required.");
	}
}