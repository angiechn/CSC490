<?php
require "connection.php";
require "common.php";

//Query to fetch all recipe names with type "Entree"
try { 
    $sql = "SELECT whatsdinner.recipe.recipeID, whatsdinner.recipe.recipeName 
        FROM whatsdinner.recipe
        LEFT JOIN whatsdinner.type 
        ON whatsdinner.recipe.recipeID = whatsdinner.type.recipeID
        WHERE whatsdinner.type.type = 'Side'";

    $statement = $connection->prepare($sql); 
    $statement->execute();
    $result = $statement->fetchAll();


} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
?>

<?php
//Results 
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
            <td><a href="recipe-2.php?recipeID=<?php echo escape($row["recipeID"]);?>"><strong>View</strong></a></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  <?php } else { ?>
    > No results found.
<?php } ?>


<style>
a:link, a:visited {
  color: #000000;
}  
a:hover, a:active {
  color: #ff7e66;
}
</style>
<a href="home.php"><strong>Back to Home</strong></a>