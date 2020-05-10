<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Saving...</title>
</head>
<body>

<?php

// Store form inputs for the username and password in variables
$username = $_POST['username'];
$password = $_POST['password'];
$confirm = $_POST['confirm'];
$ok = true;

// Validate inputs from username and passwords to ensure that the forms are filled, and that passwords match.
if (empty($username)) {
    echo 'Username is required<br />';
    $ok = false;
}

if (empty($password)) {
    echo 'Password is required<br />';
    $ok = false;
}

if ($password != $confirm) {
    echo 'Passwords must match<br />';
    $ok = false;
}

if ($ok) {
    // Hash the password
    $password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Connect
        require_once 'db.php';

        // Duplicate check before insert
        $sql = "SELECT * FROM users WHERE username = :username";
        $cmd = $db->prepare($sql);
        $cmd->bindParam(':username', $username, PDO::PARAM_STR, 50);
        $cmd->execute();
        $user = $cmd->fetch();

        if (!empty($user)) {
            echo 'Username already exists<br />';
        } else {
            // Set up & run insert into database
            $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
            $cmd = $db->prepare($sql);
            $cmd->bindParam(':username', $username, PDO::PARAM_STR, 50);
            $cmd->bindParam(':password', $password, PDO::PARAM_STR, 255);
            $cmd->execute();
        }

        // Disconnect from database
        $db = null;

        // Redirect to login page
        header('location:login.php');
    } // If anything goes wrong with a connection, it will throw an error
    catch (Exception $e) {
        header('location:error.php');
        exit();
    }
}
?>

</body>
</html>
