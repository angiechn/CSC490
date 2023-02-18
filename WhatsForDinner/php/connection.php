<?php
try { 
    //Configuration for database connection
    $host = "localhost";
    $dbname = "whatsdinner";
    $username = "root";
    $password = "";
    $dsn = "mysql:host=$host;dbname=$dbname";
    $options = null;
    
    //configure($host, $username, $password, $options, $dbname, $dsn);

    //Open a connection via PDO to create a new database
    $connection = new PDO("mysql:host=$host", $username, $password, $options);
    $sql = file_get_contents("../../whatsDinnerTonight.sql");
    $connection->exec($sql);

    echo "Database and table created sucessfully.";

} catch(PDOException $error) { 
    echo "Database connection error: " . $error->getMessage() . "<BR>";
    die;
}
?>