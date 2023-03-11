<?php
require_once("connection.php");

//Import recipe table 
$csvFilePath = "../../recipeTablePractice.csv";
$file = fopen($csvFilePath, "r");

while (($row = fgetcsv($file)) !== FALSE) {
    $connection->query('INSERT INTO whatsdinner.recipe VALUES ("'.$row[0].'","'.$row[1].'",
    "'.$row[2].'", "'.$row[3].'", "'.$row[4].'")');
}

fclose($file);

//Import raw table 
//Import ingredient table 
//Import ingredientraw table

?>