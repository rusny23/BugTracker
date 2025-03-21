<?php
session_start();

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION['account_loggedin'])) {
    header('Location: index.php');
    exit;
}

// Start up new connection
$servername = 'localhost';
$username = "root";
$password = "";
$dbname = "bug_tracker";

// Start up the connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connection_error);
}

if ($stmt = $conn->prepare('SELECT * FROM bugs ORDER BY created_at DESC')) {
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $title, $description, $steps, $severity, $email, $attachment, $status, $created);

        echo "<h1 class='table-title' style='font-size: 36px; text-align: center; margin-top: 25px;'>Bug List</h1>";
        echo "<table id='dataTable' class='styled-table' border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Title</th>";
        echo "<th>Description</th>";
        echo "<th>Steps</th>";
        echo "<th>Severity</th>";
        echo "<th>Email</th>";
        echo "<th>Attachment</th>";
        echo "<th>Status</th>";
        echo "<th>Created</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        while ($stmt->fetch()) {
            echo "<tr class='data-row' data-id='$id'>";
            echo "<td>" . htmlspecialchars($id) . "</td>";
            echo "<td>" . htmlspecialchars($title) . "</td>";
            echo "<td>" . htmlspecialchars($description) . "</td>";
            echo "<td>" . htmlspecialchars($steps) . "</td>";
            echo "<td>" . htmlspecialchars($severity) . "</td>";
            echo "<td>" . htmlspecialchars($email) . "</td>";
            if ($attachment) {
                echo "<td><a href='$attachment' target='_blank'>View Attachment</a></td>"; // Display hyperlink to file
            } else {
                echo "<td>No Attachment</td>"; // If no attachment, show "No Attachment"
            } 
            ?>
            <td>
                <?php echo htmlspecialchars($status); ?>
                <form action="update_status.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="bug_id" value="<?php echo htmlspecialchars($id); ?>">
                    <select name="status" onchange="this.form.submit()">
                        <option style="display:none;" selected>Change Status</option>
                        <option value="Open" <?php if($status == 'Open') echo 'selected'; ?>>Open</option>
                        <option value="In Progress" <?php if($status == 'In Progress') echo 'selected'; ?>>In Progress</option>
                        <option value="Resolved" <?php if($status == 'Resolved') echo 'selected'; ?>>Resolved</option>
                    </select>
                </form>
            </td>
            <?php
            echo "<td>" . htmlspecialchars($created) . "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    }
} else {
    echo 'Could not prepare statement';
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

                <h1>Bug Tracker</h1>
                
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