<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>

<?php
session_start(); // access current user session
session_unset(); // remove any session variables
session_destroy(); // end the current session
header('location:login.php');
?>

</body>
</html>
