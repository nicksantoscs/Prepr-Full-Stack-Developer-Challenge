<?php
// Exit page and prompt login if user is not already logged in
if (empty($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}
?>