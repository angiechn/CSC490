<?php
/**
 * Function to query all recipe information based on recipeID
 * recipeID is sent from recipe.php
 */

// required 
require "connection.php";
require "common.php";

// take recipeID
if (isset($_GET['recipeID'])) {
  // queries to fetch recipe and ingredient information from recipeID 
  try {
    $recipeID = $_GET['recipeID']; 

    $sql = "SELECT * 
    FROM whatsdinner.recipe 
    WHERE recipeID = :recipeID";

    $sql2 = "SELECT * 
    FROM whatsdinner.ingredientRaw 
    LEFT JOIN whatsdinner.ingredient 
    ON whatsdinner.ingredient.recipeID = whatsdinner.ingredientRaw.recID 
    AND whatsdinner.ingredient.ingredientID = whatsdinner.ingredientRaw.ingID 
    LEFT JOIN whatsdinner.raw ON whatsdinner.raw.rawID = whatsdinner.ingredientraw.rawID 
    WHERE recID = :recipeID";
    
    $statement = $connection->prepare($sql); 
    $statement->bindParam(':recipeID', $recipeID, PDO::PARAM_STR);
    $statement->execute();

    $result = $statement->fetchAll();
    
    $statement2 = $connection->prepare($sql2); 
    $statement2->bindParam(':recipeID', $recipeID, PDO::PARAM_STR);
    $statement2->execute();

    $result2 = $statement2->fetchAll();
  } catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
}
?>

<h2>Recipe Information</h2>
  <table>
    <thead>
      <tr>
        <th>Recipe Name</th>
        <th>Instructions</th>
        <th>Notes</th>
        <th>Author</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($result as $row): ?>
        <tr>
          <td><?php echo escape($row["recipeName"]); ?></td>
          <td><?php echo escape($row["instructions"]); ?></td>
          <td><?php echo escape($row["notes"]); ?></td>
          <td><?php echo escape($row["author"]); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<table>
    <thead>
      <tr>
        <th>Ingredients</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($result2 as $row): ?>
        <tr>
          <td><?php echo escape($row["preparation"]); ?></td>
          <td><?php echo escape($row["rawName"]); ?></td>
          <td><?php echo escape($row["measurement"]); ?></td>
          <td><?php echo escape($row["unit"]); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

<?php
// old section for searching by recipeID directly

/*
<h2>Search Recipe</h2>

<form method="post">
  <label for="recipeID">by RecipeID</label>
  <input type="text" id="recipeID" name="recipeID">
  <input type="submit" name="submit" value="Search">
</form>
*/
?>