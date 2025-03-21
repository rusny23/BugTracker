<?php
session_start();
$servername = 'localhost';
$username = "root";
$password = "";
$dbname = "bug_tracker";

//start up the connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error){
    die("Connection Failed: " . $conn->connection_error);
}

//now get the form data
$title = $_POST['title'];
$description = $_POST['description'];
$steps = $_POST['steps'];
$severity = $_POST['severity'];
$email = $_POST['email'];

//Handle any file uploads
$attachmentpath = "";
if($_FILES["attachment"]["error"] == 0){
    $targetDir = "uploads/";
    $target_file = $targetDir . basename($_FILES["attachment"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check file size
    if ($_FILES["attachment"]["size"] > 500000) {
        echo "Sorry, your file is too large.\r\n";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "pdf" ) {
        echo "Sorry, only JPG, JPEG, PNG & PDF files are allowed.\r\n";
        $uploadOk = 0;
    }

     // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded. Please try again.\r\n";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars( basename( $_FILES["attachment"]["name"])). " has been uploaded.";
        } else {
        echo "Sorry, there was an error uploading your file. Please try again\r\n";
        }
    }
}

//Put the bug into the database
$sql = "INSERT INTO bugs (title, description, steps, severity, email, attachment) 
        VALUES ('$title', '$description', '$steps', '$severity', '$email', '$target_file')";

if($conn->query($sql) == TRUE){
    echo("Bug submitted successfully! You can find it in the Bug List.\r\n");

} else {
    echo "Error" . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,minimum-scale=1">
        <title>Bug List</title>
        <link href="style.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <header class="header">

            <div class="wrapper">

                <h1>Bug List</h1>
                
                <nav class="menu">
                    <a href="home.php">Home</a>
                    <a href="profile.php">Profile</a>
                    <a href="submit_bug.php">Submit Bug</a>
                    <a href="bugs_list.php">Bug List</a>
                    <a href="logout.php">
                        <svg width="12" height="12" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/></svg>
                        Logout
                    </a>
                </nav>

            </div>
        </header>
    </body>
</html>