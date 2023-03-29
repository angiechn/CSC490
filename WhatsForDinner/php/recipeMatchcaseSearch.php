<?php
/**
 * Function to query recipes based on user input
 * User inputs recipeName
 */

// required
require "connection.php";
require "common.php";

// take user input from submit bar
if (isset($_POST['submit'])) {
  try {
    // query to fetch recipe name from rec name
    $sql = "SELECT *
    FROM whatsdinner.recipe
    WHERE whatsdinner.recipe.recipeName LIKE :recName";
    $statement = $connection->prepare($sql); 
    $recName = '%' . $_POST['recName'] . '%';
    $statement->bindParam(':recName', $recName, PDO::PARAM_STR);
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
    > No results found for <?php echo escape($_POST['recName']); ?>.
<?php }
} ?>

<?php // user input ?>
<h2>Search Recipe</h2>
<form method="post">
    <input type="text" required name="recName" id="recName">
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