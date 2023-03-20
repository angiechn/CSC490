<?php

/**
 * Function to query information based on
 * a parameter: in this case, location.
 *
 */

if (isset($_POST['submit'])) {
  try {
    require "connection.php";
    require "common.php";

    $sql = "SELECT DISTINCT recipeName
    FROM whatsdinner.recipe 
    LEFT JOIN whatsdinner.ingredient ON whatsdinner.recipe.recipeID = whatsdinner.ingredient.recipeID
    LEFT JOIN whatsdinner.ingredientraw ON whatsdinner.ingredientraw.recID = whatsdinner.ingredient.recipeID
    LEFT JOIN whatsdinner.raw ON whatsdinner.raw.rawID = whatsdinner.ingredientraw.rawID
    WHERE rawName = :rawName";

    $rawName = $_POST['rawName'];

    $statement = $connection->prepare($sql); 
    $statement->bindParam(':rawName', $rawName, PDO::PARAM_STR);
    $statement->execute();

    $result = $statement->fetchAll();
  } catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
}
?>

<?php
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
          </tr>
        <?php } ?>
      </tbody>
    </table>
  <?php } else { ?>
    > No results found for <?php echo escape($_POST['rawName']); ?>.
<?php }
} ?>

<h2>Search Recipe</h2>

<form method="post">
  <label for="rawName">by RecipeName</label>
  <input type="text" id="rawName" name="rawName">
  <input type="submit" name="submit" value="Search">
</form>