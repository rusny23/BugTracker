<? php
session_start();
//start up new connection
$conn = new mysqli("localhost", "root", "", "bug_tracker");

if ($conn->connect_error){
    die("Connection Failed: " . $conn->connection_error)
}

$sql = "SELECT * FROM bugs ORDERED BY created_at DESC";

$result = $conn->query($sql)

echo "<h2>Bug List</h2><table border='1'><tr><th>Title</th><th>Severity</th><th>Status</th><th>Reported By</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr><td>{$row['title']}</td><td>{$row['severity']}</td><td>{$row['status']}</td><td>{$row['email']}</td></tr>";
}

echo "</table>";
$conn->close();
?>