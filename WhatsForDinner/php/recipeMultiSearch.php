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
$RawSQL = "SELECT DISTINCT rawName, rawID FROM whatsdinner.raw ORDER BY rawName";
$RawStmt = $connection->prepare($RawSQL); 
$RawStmt->execute();
$RawResult = $RawStmt->fetchAll();
?>

<?php // take user input from submit bar
if (isset($_POST['submitMulti'])) {
  try {
    $raws = $_POST['rawName'];
    // query to fetch recipe name from raw names
    $MultiSearchSQL = sprintf(
      "SELECT *
      FROM whatsdinner.recipe
      WHERE whatsdinner.recipe.recipeID IN 
      (SELECT DISTINCT whatsdinner.recipe.recipeID
      FROM whatsdinner.recipe 
      LEFT JOIN whatsdinner.ingredient ON whatsdinner.recipe.recipeID = whatsdinner.ingredient.recipeID
      LEFT JOIN whatsdinner.ingredientraw ON whatsdinner.ingredientraw.recID = whatsdinner.ingredient.recipeID
      LEFT JOIN whatsdinner.raw ON whatsdinner.raw.rawID = whatsdinner.ingredientraw.rawID
      WHERE rawName IN (%s)
      GROUP BY whatsdinner.recipe.recipeID
      HAVING count(DISTINCT whatsdinner.raw.rawID) = %s)",
      "'" . implode("', '", $raws) . "'",
      count($raws)
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

                  $IngDisplaySQL = sprintf("SELECT *
                    FROM whatsdinner.ingredientRaw 
                    LEFT JOIN whatsdinner.ingredient 
                    ON whatsdinner.ingredient.recipeID = whatsdinner.ingredientRaw.recID 
                    AND whatsdinner.ingredient.ingredientID = whatsdinner.ingredientRaw.ingID 
                    LEFT JOIN whatsdinner.raw ON whatsdinner.raw.rawID = whatsdinner.ingredientraw.rawID 
                    WHERE recID = :recipeID AND rawName NOT IN (%s)",
                    "'" . implode("', '", $raws) . "'");

                  $IngDisplayStmt = $connection->prepare($IngDisplaySQL); 
                  $IngDisplayStmt->bindParam(':recipeID', $recipeID, PDO::PARAM_STR);
                  $IngDisplayStmt->execute();

                  $IngResult = $IngDisplayStmt->fetchAll();
                } catch (PDOException $error) {
                  echo $IngDisplaySQL . "<br>" . $error->getMessage();
                } ?>
              <tr><td><a href="recipeDisplay.php?recipeID=<?php echo escape($row["recipeID"]);?>"><strong><?php echo escape($row["recipeName"]); ?></strong></td></tr>
              <tr><td> Matched: <em> <?php $rawsString = implode(", ", $raws); echo($rawsString);?></em></tr></td>
              <tr><td> Other: <em><?php foreach ($IngResult as $tuple) { echo escape($tuple["rawName"]) . ", "; } ?></em></td></tr>
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
if (isset($_POST['yesPantry']) && $_SESSION['usePantry'] == "FALSE") {
  $_SESSION['usePantry'] = "TRUE";
  header('Location: recipeMultiSearch.php');
} else if (isset($_POST['noPantry']) && $_SESSION['usePantry'] == "TRUE") {
  $_SESSION['usePantry'] = "FALSE";
  header('Location: recipeMultiSearch.php');
}
?>

<h2>Search Recipe</h2>
<!-- pantry toggle -->
<form method = "post">
  <?php if ($_SESSION['usePantry'] == "FALSE") { ?>
    <input type = "submit" name = "yesPantry" value = "Use Pantry"> 
    <?php echo $_SESSION['usePantry']; ?>
  <?php } else if ($_SESSION['usePantry'] == "TRUE") { ?>
    <input type = "submit" name = "noPantry" value = "Don't Use Pantry">
    <?php echo $_SESSION['usePantry']; ?>
  <?php } ?>
</form>


<!-- user input for multisearch -->
<form method ="post">
  <select name = "rawName[]" multiple id = "rawName[]" size = 8 required> 
      <option style = "display:none">Choose an ingredient.</option>
        <?php foreach($RawResult as $option):?>
          <option value= "<?php echo $option['rawName'];?>" required><?php echo $option['rawName'];?>
        <?php endforeach; ?>
  </select>
  <input type="submit" name = "submitMulti" value= "Search">
</form>

<a href="home.php"><strong>Back to Home</strong></a>