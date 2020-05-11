<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>

<?php
// Check if user is logged in
session_start();

// Ensure the page is private by asking the user to login
require_once 'authenticate.php';

// Parse the lab_id from the url parameter
$lab_id = $_GET['lab_id'];

try {
    // Connect to MySQL database
    require_once 'db.php';

    // Create the SQL DELETE command
    $sql = "DELETE FROM labs WHERE lab_id = :;lab_id";

    // Pass the lab_id parameter to the command
    $cmd = $db->prepare($sql);
    $cmd->bindParam(':lab_id', $lab_id, PDO::PARAM_INT);

    // Delete
    $cmd->execute();

    // Disconnect from database
    $db = null;

    // Redirect back to updated labs-list page
    header('location:labs-list.php');
} catch (Exception $e) {
    // If there is any kind of connection error, etc, throw an error
    header('location:error.php');
    exit();
}

?>

</body>
</html>
