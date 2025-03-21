<?php
    session_start();

    // If the user is not logged in, redirect to the login page
    if (!isset($_SESSION['account_loggedin'])) {
        header('Location: index.php');
        exit;
    }

    //start up new connection
    $conn = new mysqli("localhost", "root", "", "bug_tracker");

    if ($conn->connect_error){
        die("Connection Failed: " . $conn->connection_error);
    }

 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $bug_id = $_POST['bug_id'] ?? null;
        $new_status = $_POST['status'] ?? null;
    
        // Validate input
        $allowed_statuses = ["Open", "In Progress", "Resolved"];
        if (!is_numeric($bug_id) || !in_array($new_status, $allowed_statuses)) {
            die("Invalid input not valid.");
        }
    
        // Prepare and execute update query
        $stmt = $conn->prepare("UPDATE bugs SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $bug_id);
    
        if ($stmt->execute()) {
            // Redirect back to bug list or details page
            header("Location: bugs_list.php"); 
            exit();
        } else {
            echo "Error updating status: " . $conn->error;
        }
    
        
        $stmt->close();
        $conn->close();
    } else {
        echo "Invalid request.";
    }
    ?>
    
