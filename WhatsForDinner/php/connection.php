<?php
try { 
    //Configuration for database connection
    $host = "localhost";
    $dbname = "whatsdinner";
    $dsn = "mysql:host = $host; mysql:dbname = $dbname";
    $username = "root";
    $password = "";
    $options = null;
    
    //Open a connection via PDO
    $connection = new PDO($dsn, $username, $password, $options);
    
} catch(PDOException $error) { 
    echo "Database connection error: " . $error->getMessage() . "<BR>";
    die;
}
?>