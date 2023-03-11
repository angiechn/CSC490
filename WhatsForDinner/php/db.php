<?php
//Create database
require_once("connection.php");

$sql = file_get_contents("../../whatsDinnerTonight.sql");
$connection->exec($sql);

echo "<br>";
echo "\n Database created successfully";
?>