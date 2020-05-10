<?php
// Connect to AWS MySQL server
$db = new PDO('mysql:host=172.31.22.43;dbname=Nicholas_A1117292', 'Nicholas_A1117292', 'grnUGrRUw4');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
