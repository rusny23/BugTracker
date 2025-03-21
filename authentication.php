<?php
echo "hello";
session_start();
$servername = 'localhost';
$username = "root";
$password = "Chapter7Bankruptcy!23";
$dbname = "bug_tracker";

//start up the connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error){
    die("Connection Failed: " . $conn->connect_error);
}

// Check if data from the login was actually submitted via isset()
if (!isset($_POST['username'], $_POST['password'])) {
    // Could not get the data that should have been sent
    exit('Please fill both the username and password fields!');
}

// This is where we enter the data into the database via prepared statement. NEED TO: sanatize for sql injection
if ($stmt = $conn->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
    // Bind parameters:
    // s = string, 
    // i = int, 
    // b = blob
    $stmt->bind_param('s', $_POST['username']);

    //run the statement
    $stmt->execute();
    // Must be called when you use a SELECT statment to store the result in memory
    $stmt->store_result();
    
    // Check if account exists with the input username
    if ($stmt->num_rows > 0) {
        // Account exists, so bind the results to variables
        $stmt->bind_result($id, $pswd);
        $stmt->fetch();

        if (password_verify($_POST['password'], $pswd)) {
            // Password is correct, user has logged in

            // Regenerate the session ID to prevent session fixation attacks
            session_regenerate_id();

            // Declare session variables (act like cookies but the data is remembered on the server)
            $_SESSION['account_loggedin'] = TRUE;
            $_SESSION['account_name'] = $_POST['username'];
            $_SESSION['account_id'] = $id;

            header('Location: home.php');
            exit;
        } else {
            //Incorrect password
            echo 'Incorrect username and/or password!';
        }
    } else {
        // Incorrect username
        echo 'Incorrect username and/or password!';
    }

    // Close the prepared statement
    $stmt->close();
}
?>