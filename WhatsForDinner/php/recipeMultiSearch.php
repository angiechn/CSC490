<?php
/**
 * Function to query recipes based on user input
 * User inputs rawname
 */

// required
require "connection.php";
require "common.php";

// query to fetch rawnames for dropdown or dynamic search
$sql2 = "SELECT DISTINCT rawName FROM whatsdinner.raw";
$statement2 = $connection->prepare($sql2); 
$statement2->execute();
$result2 = $statement2->fetchAll();

// take user input from submit bar
if (isset($_POST['submit'])) {
  try {
    // query to fetch recipe name from raw names
    $sql = "SELECT *
    FROM whatsdinner.recipe
    WHERE whatsdinner.recipe.recipeID IN 
      (SELECT DISTINCT whatsdinner.recipe.recipeID
      FROM whatsdinner.recipe 
      LEFT JOIN whatsdinner.ingredient ON whatsdinner.recipe.recipeID = whatsdinner.ingredient.recipeID
      LEFT JOIN whatsdinner.ingredientraw ON whatsdinner.ingredientraw.recID = whatsdinner.ingredient.recipeID
      LEFT JOIN whatsdinner.raw ON whatsdinner.raw.rawID = whatsdinner.ingredientraw.rawID
      WHERE rawName IN (:rawsString)
      GROUP BY whatsdinner.recipe.recipeID
      HAVING count(DISTINCT whatsdinner.raw.rawID) = :rawCount)";
    $statement = $connection->prepare($sql); 

    $raws = $_POST['rawName']; // retrieve user input array
    $rawCount = count($raws); // retrieve number of array elements
    $rawsString = implode("', '", $raws); // separate with commas and single quotes
    $rawsString = "'" . $rawsString . "'"; // add single quotes to front and end
    
    $statement->bindParam(':rawCount', $rawCount, PDO::PARAM_STR);
    $statement->bindParam(':rawsString', $rawsString, PDO::PARAM_STR);
    $statement->execute();

    $result = $statement->fetchAll();
  } catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
}
?>

<?php
// output results 
if (isset($_POST['submit'])) {
  if ($result && $statement->rowCount() > 0) { ?>
    <h2>Results</h2>
    <table>
      <thead>
        <tr>
          <th>Recipe Name</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($result as $row) { ?>
          <tr>
            <td><?php echo escape($row["recipeName"]); ?></td>
            <td><a href="recipeDisplay.php?recipeID=<?php echo escape($row["recipeID"]);?>"><strong>View</strong></a></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  <?php } else { ?>
    > No results found for 
      <?php 
      $raws = $_POST['rawName']; // retrieve user input array
      $rawsString = implode(", ", $raws);
      echo escape($rawsString); 
      ?>
      .
<?php }
} ?>

<?php // user input ?>
<h2>Search Recipe</h2>
<form method="post">
  <select name = "rawName[]" multiple id = "rawName[]" size = 8> 
      <option style = "display:none">Choose an ingredient.</option>
        <?php foreach($result2 as $option):?>
          <option value= "<?php echo $option['rawName'];?>" required><?php echo $option['rawName'];?>
        <?php endforeach; ?>
  </select>
  <input type="submit" name="submit" value="Search">
</form>
<style>
a:link, a:visited {
  color: #000000;
}  
a:hover, a:active {
  color: #ff7e66;
}
</style>
<a href="home.php"><strong>Back to Home</strong></a>