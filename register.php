<link href="style.css" rel="stylesheet" type="text/css">

<?php
// We need to use sessions, so you should always initialize sessions using the below function
session_start();
// If the user is logged in, redirect to the home page
if (isset($_SESSION['account_loggedin'])) {
	header('Location: home.php');
	exit;
}

$servername = 'localhost';
$username = "root";
$password = "";
$dbname = "bug_tracker";

//start up the connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error){
    die("Connection Failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$errors = [];

	// Check if form was submitted properly
	if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
		$errors['general'] = "Please complete the registration form!";
	}

	// Make sure the submitted registration values are not empty
	if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
		$errors['general'] = "Please complete the registration form!";
	}

	// Validate email address
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$errors['email'] = "Email is not valid!";
	}

	if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
		$errors['username'] = "Username is not valid!";
	}

	// Validate password (between 5 and 20 characters long)
	if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
		$errors['password'] = "Password must be between 5 and 20 characters long!";
	}

	if (empty($errors)) {
		if($stmt = $conn->prepare('SELECT id, password FROM accounts WHERE username = ?')){
			$stmt->bind_param('s', $_POST['username']);
			$stmt->execute();
		
			$stmt->store_result();
			
			if($stmt->num_rows > 0){
				echo 'Username already exists! Please choose another!';
				$stmt->close();
				//$errors['username'] = "Username already exists! Please choose another!";
			} else {
				$registered = date('Y-m-d H:i:s');
				$pswd = password_hash($_POST['password'], PASSWORD_DEFAULT);
				
				//insert new account into accounts table
				if ($stmt = $conn->prepare('INSERT INTO accounts (username, password, email, registered) VALUES (?, ?, ?, ?)')) {
					
					$stmt->bind_param('ssss', $_POST['username'], $pswd, $_POST['email'], $registered);
					$stmt->execute();
					$stmt->close();
					// Output success message
					echo 'You have successfully registered! You can now login!';
				} else {
					// Something is wrong with the SQL statement
					echo 'Could not prepare statement!';
				}
			}   
		} else {
			// Something is wrong with the SQL statement
			echo 'Could not prepare statement!';
		}
		// Close the connection
		$conn->close();
	}
}


?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title>Register</title>
	</head>
	<body>
		<div class="login">

			<h1>Member Register</h1>

			<form action="" method="post" class="form login-form">

				<label class="form-label" for="username">Username</label>
				<div class="form-group">
					<svg class="form-icon-left" width="14" height="14" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg>
					<input class="form-input" type="text" name="username" placeholder="Username" id="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
				</div>
				<?php if(isset($errors['username'])): ?>
					<p style="color: red;"><?php echo $errors['username']; ?></p>
				<?php endif; ?>

				<label class="form-label" for="email">Email</label>
				<div class="form-group">
					<svg class="form-icon-left" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/></svg>
					<input class="form-input" type="email" name="email" placeholder="Email" id="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"required>
				</div>
				<?php if(isset($errors['email'])): ?>
					<p style="color: red;"><?php echo $errors['email']; ?></p>
				<?php endif; ?>

				<label class="form-label" for="password">Password</label>
				<div class="form-group mar-bot-5">
					<svg class="form-icon-left" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"/></svg>
					<input class="form-input" type="password" name="password" placeholder="Password" id="password" autocomplete="new-password" required>
				</div>
				<?php if(isset($errors['password'])): ?>
					<p style="color: red;"><?php echo $errors['password']; ?></p>
				<?php endif; ?>

				<button class="btn blue" type="submit">Register</button>

				<p class="register-link">Already have an account? <a href="index.php" class="form-link">Login</a></p>

			</form>

		</div>
	</body>
</html>

