<?php
$host='localhost'; $db='digitalnovels'; $user='root'; $pass='';
$conn = new mysqli($host, $user, $pass, $db);
if($conn->connect_error) die('DB Error: '.$conn->connect_error);
?>