<?php

include('server/connection.php');
$error = array();

// Make sure the session is started (skip if connection.php already does it)
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

$max_attempts = 5;
$lock_seconds = 120; // 2 minutes

if (isset($_POST['login'])) {

	// Is this visitor currently locked out?
	$locked_until = $_SESSION['locked_until'] ?? 0;

	if (time() < $locked_until) {
		$wait = $locked_until - time();
		array_push($error, "Too many failed attempts. Please wait $wait seconds and try again.");
	} else {

		// Lock has expired, so start fresh
		if ($locked_until) {
			unset($_SESSION['locked_until']);
			$_SESSION['attempts'] = 0;
		}

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
				unset($_SESSION['attempts'], $_SESSION['locked_until']); // reset on success
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
				// Wrong login: count the failed attempt
				$_SESSION['attempts'] = ($_SESSION['attempts'] ?? 0) + 1;

				if ($_SESSION['attempts'] >= $max_attempts) {
					$_SESSION['locked_until'] = time() + $lock_seconds;
					array_push($error, "Too many failed attempts. Please wait 2 minutes and try again.");
				} else {
					$left = $max_attempts - $_SESSION['attempts'];
					array_push($error, "Wrong username/password! $left attempt(s) left.");
				}
			}

		} else {
			array_push($error, "All fields are required.");
		}
	}
}
?>