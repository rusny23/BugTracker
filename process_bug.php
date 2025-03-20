<? php
session_start();
$servername = 'localhost';
$username = "root";
$password = "";
$dbname = "bug_tracker";

//start up the connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error){
    die("Connection Failed: " . $conn->connection_error)
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
    $attachmentpath = $targetDir . basename($_FILES["attachment"]["name"]);
    move_uploaded_file($_FILES["attachment"]["tmp_name"], $attachmentpath);
}

//Put the bug into the database
$sql = "INSERT INTO bugs (title, description, steps, severity, email, attachment) 
        VALUES ('$title', '$description', '$steps', '$severity', '$email', '$attachmentPath')";

if($conn->query($sql) == TRUE){
    echo("Bug submitted successfully!")

} else {
    echo "Error" . $sql . "<br>" . $conn->error;
}

$conn->close();
?>