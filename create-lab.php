<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Saving Lab...</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
</head>
<body>

<h1>Attempting to Save Lab</h1>

<?php
session_start();
require_once 'authenticate.php';

// Save the form inputs to variables
$labName = htmlspecialchars($_POST['labName']);
$dateAdded = $_POST['date_added'];
$location = $_POST['location'];

// Validate inputs
$ok = true;

if (empty($labName)) {
    echo 'Name is required<br />';
    $ok = false;
}

if ($ok) {
    try {
        // Connect to db
        require_once 'db.php';

        // Adding or editing depending if we already have an lab_id or not
        if (empty($labId)) {
            // Set up the SQL INSERT command
            $sql = "INSERT INTO labs (lab_name, date_added, location) VALUES (:lab_name, :date_added, :location)";
        } else {
            $sql = "UPDATE labs SET lab_name = :lab_name, dateAdded = :dateAdded, ;location = :location WHERE lab_id = :lab_id";
        }

        // Create a PDO command object and fill the parameters 1 at a time for type & safety checking
        $cmd = $db->prepare($sql);
        $cmd->bindParam(':labName', $labName, PDO::PARAM_STR, 50);
        $cmd->bindParam(':dateAdded', $dateAdded, PDO::PARAM_INT);
        $cmd->bindParam(':location', $location, PDO::PARAM_STR, 100);

        // If we have a labId, we need to bind the 4th parameter (but only if we have an ID already)
        if (!empty($labId)) {
            $cmd->bindParam(':labId', $labId, PDO::PARAM_INT);
        }

        // Try to send/save the data
        $cmd->execute();

        // Disconnect from database
        $db = null;

        // Show message to user
        echo '<h2 class="alert alert-success">Lab Saved</h2>';
        header('location:labs-list.php');
    } catch (Exception $e) {
        //Throw error if lab creation goes wrong in any way
        header('location:error.php');
        exit();
    }
}

?>

</body>
</html>
