<?php
require("connection.php");
try {
    //Import recipe table 
    $connection->query("DELETE FROM whatsdinner.recipe;");
    $connection->query("DELETE FROM whatsdinner.raw;");
    $connection->query("DELETE FROM whatsdinner.ingredient;");

    $csvFilePath = "../../recipeTablePractice.csv";
    $file = fopen($csvFilePath, "r");
    
    while (($row = fgetcsv($file)) !== FALSE) {
        $connection->query('INSERT INTO whatsdinner.recipe 
        VALUES ("'.$row[0].'", "'.$row[1].'", "'.$row[2].'", "'.$row[3].'", "'.$row[4].'")');
    
        if(feof($file) == TRUE) { 
        echo "Entries for recipe table inserted" . "<br>";
        }
    }

    fclose($file);

    //Import raw table 
    $csvFilePath = "../../rawTablePractice.csv";
    $file = fopen($csvFilePath, "r");

    while (($row = fgetcsv($file)) !== FALSE) {
        $connection->query('INSERT INTO whatsdinner.raw 
        VALUES ("'.$row[0].'", "'.$row[1].'")');
    
        if(feof($file) == TRUE) { 
        echo "Entries for raw table inserted" . "<br>";
        }
    }

    fclose($file);

    //Import ingredient table
    $csvFilePath = "../../ingredientTablePractice.csv";
    $file = fopen($csvFilePath, "r");

    while (($row = fgetcsv($file)) !== FALSE) {
        $connection->query('INSERT INTO whatsdinner.ingredient
        VALUES ("'.$row[0].'", "'.$row[1].'", "'.$row[2].'", "'.$row[3].'", "'.$row[4].'")');
    
        if(feof($file) == TRUE) { 
        echo "Entries for ingredient table inserted" . "<br>";
        }
    }

    fclose($file);

} catch (PDOException $error) { 
    echo "Something's wrong..." . $error->getMessage() . "<BR>";
    die;
}

?>