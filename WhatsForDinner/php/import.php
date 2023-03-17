<?php
require("connection.php");
try {
    $connection->query("DELETE FROM whatsdinner.type;");
    $connection->query("DELETE FROM whatsdinner.recipe;");
    $connection->query("DELETE FROM whatsdinner.raw;");
    $connection->query("DELETE FROM whatsdinner.ingredient;");
    $connection->query("DELETE FROM whatsdinner.ingredientraw;");

    //Import recipe table 
    $csvFilePath = "../../recipeEntries.csv";
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
    $csvFilePath = "../../rawEntries.csv";
    $file = fopen($csvFilePath, "r");

    while (($row = fgetcsv($file)) !== FALSE) {
        $connection->query('INSERT INTO whatsdinner.raw 
        VALUES ("'.$row[0].'", "'.$row[1].'")');
    
        if(feof($file) == TRUE) { 
        echo "Entries for raw table inserted" . "<br>";
        }
    }

    fclose($file);
    
    //Import ingredient
    $csvFilePath = "../../ingredientEntries.csv";
    $file = fopen($csvFilePath, "r");

    while (($row = fgetcsv($file)) !== FALSE) {
        $connection->query('INSERT INTO whatsdinner.ingredient
        VALUES ("'.$row[0].'", "'.$row[1].'", "'.$row[2].'", "'.$row[3].'", "'.$row[4].'")'); 

        if(feof($file) == TRUE) { 
        echo "Entries for ingredient table inserted" . "<br>";
        }
    }

    fclose($file);
    
    //Import type
    $csvFilePath = "../../typeEntries.csv";
    $file = fopen($csvFilePath, "r");

    while (($row = fgetcsv($file)) !== FALSE) {
        $connection->query('INSERT INTO whatsdinner.type
        VALUES ("'.$row[0].'", "'.$row[1].'")'); 

        if(feof($file) == TRUE) { 
        echo "Entries for type table inserted" . "<br>";
        }
    }

    //Import ingredientraw
    $csvFilePath = "../../ingredientRawEntries.csv";
    $file = fopen($csvFilePath, "r");

    while (($row = fgetcsv($file)) !== FALSE) {
        $connection->query('INSERT INTO whatsdinner.ingredientraw
        VALUES ("'.$row[0].'", "'.$row[1].'", "'.$row[2].'")'); 

        if(feof($file) == TRUE) { 
        echo "Entries for ingredientraw table inserted" . "<br>";
        }
    }

} catch (PDOException $error) { 
    echo "Something's wrong..." . $error->getMessage() . "<BR>";
    die;
}

?>