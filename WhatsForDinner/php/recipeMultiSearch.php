<?php
/** MULTI SEARCH
 * Function to take user input of ingredients
 * Display names of resulting recipes
 * Display matching and unmatching ingredients for each result
 */
require "connection.php";
require "common.php";

session_start();
?>

<style>
a:link, a:visited {
  color: #000000;
}  
a:hover, a:active {
  color: #ff7e66;
}
</style>

<?php // fetch rawnames for dynamic search
$RawSQL = "SELECT DISTINCT rawName FROM whatsdinner.raw ORDER BY rawName";
$RawStmt = $connection->prepare($RawSQL); 
$RawStmt->execute();
$RawResult = $RawStmt->fetchAll();
?>

<?php // fetch pantry if user logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == TRUE) { 
  try {
    $UserPantrySQL = "SELECT raw.rawName
    FROM whatsdinner.raw
    LEFT JOIN whatsdinner.inpantry ON whatsdinner.raw.rawID = whatsdinner.inpantry.rawID
    WHERE whatsdinner.inpantry.userID = :userID";

    $UserPantryStmt = $connection->prepare($UserPantrySQL); 
    $UserPantryStmt->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_STR);
    $UserPantryStmt->execute();

    $UserPantryResult = $UserPantryStmt->fetchAll();
  } catch (PDOException $error) {
      echo $UserPantrySQL . "<br>" . $error->getMessage();
  }
} ?>

<?php // take user input from submit bar
if (isset($_POST['submitMulti'])) {
  try {
    $raws = $_POST['rawName'];
    // query to fetch recipe name from raw names
    $MultiSearchSQL = sprintf(
      "SELECT DISTINCT recipe.recipeID, recipe.recipeName, COUNT(rawName)
      FROM whatsdinner.recipe
      LEFT JOIN whatsdinner.ingredientRaw
      ON whatsdinner.ingredientRaw.recID = whatsdinner.recipe.recipeID
      LEFT JOIN whatsdinner.ingredient 
      ON whatsdinner.ingredient.recipeID = whatsdinner.ingredientRaw.recID 
      AND whatsdinner.ingredient.ingredientID = whatsdinner.ingredientRaw.ingID 
      LEFT JOIN whatsdinner.raw ON whatsdinner.raw.rawID = whatsdinner.ingredientraw.rawID 
      WHERE rawName IN (%s)
      GROUP BY ingredientRaw.recID
      ORDER BY COUNT(rawName) DESC",
      "'" . implode("', '", $raws) . "'"
    );
    $MultiSearchStmt = $connection->prepare($MultiSearchSQL); 
    $MultiSearchStmt->execute();
    $MultiSearchResult = $MultiSearchStmt->fetchAll();
  } catch (PDOException $error) {
    echo $MultiSearchSQL . "<br>" . $error->getMessage();
  }
} ?>

<?php // output results 
if (isset($_POST['submitMulti'])) {
  if ($MultiSearchResult && $MultiSearchStmt->rowCount() > 0) { ?>
    <h2>Results</h2>
    <table>
      <tbody>
        <?php foreach ($MultiSearchResult as $row) { 
                try { 
                  // fetch unmatching ingredients for recipe
                  $recipeID = $row["recipeID"];

                  $OtherIngDisplaySQL = sprintf("SELECT *
                    FROM whatsdinner.ingredientRaw 
                    LEFT JOIN whatsdinner.ingredient 
                    ON whatsdinner.ingredient.recipeID = whatsdinner.ingredientRaw.recID 
                    AND whatsdinner.ingredient.ingredientID = whatsdinner.ingredientRaw.ingID 
                    LEFT JOIN whatsdinner.raw ON whatsdinner.raw.rawID = whatsdinner.ingredientraw.rawID 
                    WHERE recID = :recipeID AND rawName NOT IN (%s)",
                    "'" . implode("', '", $raws) . "'");

                  $OtherIngDisplayStmt = $connection->prepare($OtherIngDisplaySQL); 
                  $OtherIngDisplayStmt->bindParam(':recipeID', $recipeID, PDO::PARAM_STR);
                  $OtherIngDisplayStmt->execute();

                  $OtherIngResult = $OtherIngDisplayStmt->fetchAll();
                } catch (PDOException $error) {
                  echo $OtherIngDisplaySQL . "<br>" . $error->getMessage();
                } 

                try { 
                  $MatchIngDisplaySQL = sprintf("SELECT *
                    FROM whatsdinner.ingredientRaw 
                    LEFT JOIN whatsdinner.ingredient 
                    ON whatsdinner.ingredient.recipeID = whatsdinner.ingredientRaw.recID 
                    AND whatsdinner.ingredient.ingredientID = whatsdinner.ingredientRaw.ingID 
                    LEFT JOIN whatsdinner.raw ON whatsdinner.raw.rawID = whatsdinner.ingredientraw.rawID 
                    WHERE recID = :recipeID AND rawName IN (%s)",
                    "'" . implode("', '", $raws) . "'");

                  $MatchIngDisplayStmt = $connection->prepare($MatchIngDisplaySQL); 
                  $MatchIngDisplayStmt->bindParam(':recipeID', $recipeID, PDO::PARAM_STR);
                  $MatchIngDisplayStmt->execute();

                  $MatchIngResult = $MatchIngDisplayStmt->fetchAll();
                } catch (PDOException $error) {
                  echo $MatchIngDisplaySQL . "<br>" . $error->getMessage();
                } 
                ?>
              <tr><td><a href="recipeDisplay.php?recipeID=<?php echo escape($row["recipeID"]);?>"><strong><?php echo escape($row["recipeName"]);?></strong></td></tr>
              <tr><td> Matched: <?php foreach ($MatchIngResult as $tuple) { echo escape($tuple["rawName"]) . ", "; } ?></td></tr>
              <tr><td> Other: <?php foreach ($OtherIngResult as $tuple) { echo escape($tuple["rawName"]) . ", "; } ?></td></tr>
        <?php } ?>
      </tbody>
    </table>
  <?php } else { ?>
    > No results found for 
      <?php 
      $rawsString = implode("', '", $raws);
      echo($rawsString); 
  }
} ?>

<?php // toggle pantry
if (isset($_SESSION['loggedin']) && isset($_POST['yesPantry']) && $_SESSION['usePantry'] == "FALSE") {
  $_SESSION['usePantry'] = "TRUE";
  header('Location: recipeMultiSearch.php');
} else if (isset($_SESSION['loggedin']) && isset($_POST['noPantry']) && $_SESSION['usePantry'] == "TRUE") {
  $_SESSION['usePantry'] = "FALSE";
  header('Location: recipeMultiSearch.php');
}
?>

<h2>Search Recipe</h2>
<!-- pantry toggle -->
<form method = "post">
  <?php if (isset($_SESSION['loggedin']) && $_SESSION['usePantry'] == "FALSE") { ?>
    <input type = "submit" name = "yesPantry" value = "Use Pantry"> 
  <?php } else if (isset($_SESSION['loggedin']) && $_SESSION['usePantry'] == "TRUE") { ?>
    <input type = "submit" name = "noPantry" value = "Don't Use Pantry">
  <?php } ?>
</form>

<!-- user input for multisearch -->
<form method ="post">
  <select name = "rawName[]" id = "rawName[]" size = 8 multiple required> 
      <option style = "display:none">Choose an ingredient.</option>
        <?php foreach($RawResult as $option):
                if((isset($_SESSION['loggedin']) && in_array($option, $UserPantryResult)) == TRUE && $_SESSION['usePantry'] == "TRUE") { ?>
                  <option value = "<?php echo $option['rawName'];?>" required selected><?php echo $option['rawName'];?></option>
                <?php } else { ?>
                  <option value = "<?php echo $option['rawName'];?>" required><?php echo $option['rawName'];?></option>
                <?php } 
        endforeach; ?>
  </select>
  <input type="submit" name = "submitMulti" value= "Search">
</form>

<a href="home.php"><strong>Back to Home</strong></a>