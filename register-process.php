<?php
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
$errors = [];

if ($conn->connect_error){
    die("Connection Failed: " . $conn->connection_error);
}

// Check if form was submitted properly
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	//exit('Please complete the registration form!');
    $errors['general'] = "Please complete the registration form!";
}

// Make sure the submitted registration values are not empty
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	$errors['general'] = "Please complete the registration form!";
}

// Validate email address
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Email is not valid!";
    exit();
}

if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
    $errors['username'] = "Username is not valid!";
	exit();
}

// Validate password (between 5 and 20 characters long)
if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
    $errors['password'] = "Password must be between 5 and 20 characters long!";
	exit();
}

if($stmt = $conn->prepare('SELECT id, password FROM accounts WHERE username = ?')){
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();

    $stmt->store_result();
    
    if($stmt->num_rows > 0){
        //echo 'Username already exists! Please choose another!';
        $errors['username'] = "Username already exists! Please choose another!";
    } else {
        $registered = date('Y-m-d H:i:s');
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        //insert new account into accounts table
        if ($stmt = $conn->prepare('INSERT INTO accounts (username, password, email, registered) VALUES (?, ?, ?, ?)')) {
            
            $stmt->bind_param('ssss', $_POST['username'], $password, $_POST['email'], $registered);
            $stmt->execute();
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
?>
