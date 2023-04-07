<?php
try { 
    //Configuration for database connection
    $host = "localhost";
    $dbname = "whatsdinner";
    $dsn = "mysql:host = $host; mysql:dbname = $dbname";
    $username = "root";
    $options = null;

    require_once("secrets.php");
    
    //Open a connection via PDO
    $connection = new PDO($dsn, $username, $password, $options);
    
} catch(PDOException $error) { 
    echo "Database connection error: " . $error->getMessage() . "<BR>";
    die;
}
?>