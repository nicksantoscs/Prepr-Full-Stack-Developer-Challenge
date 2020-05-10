<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>

<?php
$username = $_POST['username'];
$password = $_POST['password'];

try {
    require_once 'db.php';
    $sql = "SELECT userId, password FROM users WHERE username = :username";

    $cmd = $db->prepare($sql);
    $cmd->bindParam(':username', $username, PDO::PARAM_STR, 50);
    $cmd->execute();
    $user = $cmd->fetch();

    if (!password_verify($password, $user['password'])) {
        header('location:login.php?invalid=true');
        //echo 'Invalid Login';
        exit();
    } else {
        // Access the existing session - we need to do this in order to read or write values to / from the session object
        session_start();

        // Create a session variable called "userId" and fill it from the id in our login query above
        $_SESSION['userId'] = $user['userId'];

        // Also store username in a 2nd session variable to display in navbar
        $_SESSION['username'] = $username;

        // Redirect to labs-list page
        header('location:labs-list.php');
    }

    $db = null;
} catch (Exception $e) {
    header('location:error.php');
    exit();
}
?>

</body>
</html>
