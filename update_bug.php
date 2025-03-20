<? php
session_start();
//start up new connection
$conn = new mysqli("localhost", "root", "", "bug_tracker");

if ($conn->connect_error){
    die("Connection Failed: " . $conn->connection_error)
}
