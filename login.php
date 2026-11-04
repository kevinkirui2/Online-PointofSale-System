<?php

include('server/connection.php');
$error = array();

if (isset($_POST['login'])) {
	$username     = trim($_POST['username'] ?? '');
	$raw_password = $_POST['password'] ?? '';
	$position     = trim($_POST['position'] ?? '');

	if ($username !== '' && $raw_password !== '' && $position !== '') {

		$password = md5($raw_password);

		$stmt = mysqli_prepare($db, "SELECT * FROM users WHERE username = ? AND position = ? AND password = ?");
		mysqli_stmt_bind_param($stmt, 'sss', $username, $position, $password);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);

		if (mysqli_num_rows($result) == 1) {
			$row = mysqli_fetch_assoc($result);

			session_regenerate_id(true);
			$_SESSION['username'] = $row['username'];
			$_SESSION['position'] = $row['position'];

			$user    = $row['username'];
			$purpose = "User $user login";

			$log = mysqli_prepare($db, "INSERT INTO logs (username, purpose) VALUES (?, ?)");
			mysqli_stmt_bind_param($log, 'ss', $user, $purpose);
			mysqli_stmt_execute($log);

			// Redirect based on the position stored in the database
			if (strtolower($row['position']) === 'admin') {
				header('Location: main.php');
			} elseif (strtolower($row['position']) === 'employee') {
				header('Location: employee_page.php');
			} else {
				array_push($error, "Unknown position assigned to this account.");
				session_destroy();
			}

			if (empty($error)) {
				exit;
			}
		} else {
			array_push($error, "Wrong username/password!");
		}

	} else {
		array_push($error, "All fields are required.");
	}
}
?>