<?php
/**
 * Function to query all recipe information based on User Input
 * User inputs recipeID
 */
if (isset($_POST['submit'])) {
  try {
    require "connection.php";
    require "common.php";

    $sql = "SELECT * 
    FROM whatsdinner.recipe 
    WHERE recipeID = :recipeID";

    $recipeID = $_POST['recipeID'];

    $statement = $connection->prepare($sql); 
    $statement->bindParam(':recipeID', $recipeID, PDO::PARAM_STR);
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
          <th>Instructions</th>
          <th>Notes</th>
          <th>Author</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($result as $row) { ?>
          <tr>
            <td><?php echo escape($row["recipeName"]); ?></td>
            <td><?php echo escape($row["instructions"]); ?></td>
            <td><?php echo escape($row["notes"]); ?></td>
            <td><?php echo escape($row["author"]); ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  <?php } else { ?>
    > No results found for <?php echo escape($_POST['recipeID']); ?>.
<?php }
} ?>

<h2>Search Recipe</h2>

<form method="post">
  <label for="recipeID">by RecipeID</label>
  <input type="text" id="recipeID" name="recipeID">
  <input type="submit" name="submit" value="Search">
</form>