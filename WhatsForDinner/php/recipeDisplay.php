<?php
/** RECIPE DISPLAY
 * takes recipeID and displays recipe information
 * USER FUNCTIONS
 * bookmark
 */
session_start();

require "connection.php";
require "common.php";
?>

<?php
// fetch all recipe information from recipeID
if (isset($_GET['recipeID'])) {
  try {
    $recipeID = $_GET['recipeID']; 

    $RecDisplaySQL= "SELECT * 
    FROM whatsdinner.recipe 
    WHERE recipeID = :recipeID";

    $IngDisplaySQL = "SELECT * 
    FROM whatsdinner.ingredientRaw 
    LEFT JOIN whatsdinner.ingredient 
    ON whatsdinner.ingredient.recipeID = whatsdinner.ingredientRaw.recID 
    AND whatsdinner.ingredient.ingredientID = whatsdinner.ingredientRaw.ingID 
    LEFT JOIN whatsdinner.raw ON whatsdinner.raw.rawID = whatsdinner.ingredientraw.rawID 
    WHERE recID = :recipeID";
    
    $RecDisplayStmt = $connection->prepare($RecDisplaySQL); 
    $RecDisplayStmt->bindParam(':recipeID', $recipeID, PDO::PARAM_STR);
    $RecDisplayStmt->execute();

    $RecResult = $RecDisplayStmt->fetchAll();
    
    $IngDisplayStmt = $connection->prepare($IngDisplaySQL); 
    $IngDisplayStmt->bindParam(':recipeID', $recipeID, PDO::PARAM_STR);
    $IngDisplayStmt->execute();

    $IngResult = $IngDisplayStmt->fetchAll();
  } catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
}

// insert into bookmark 
if (isset($_POST['BookmarkSubmit'])) {
  try {
      $userID = $_SESSION['userID'];

      $AddBookmarkSQL = "INSERT INTO whatsdinner.bookmarked (userID, recipeID) 
      VALUES (:userID, :recipeID)";
      
      $AddBookmarkStmt = $connection->prepare($AddBookmarkSQL); 
      $AddBookmarkStmt->bindParam(':recipeID', $recipeID, PDO::PARAM_STR);
      $AddBookmarkStmt->bindParam(':userID', $userID, PDO::PARAM_STR);
      $AddBookmarkStmt->execute();

      echo "Bookmark Added";
  } catch (PDOException $error) {
    echo $AddBookmarkSQL . "<br>" . $error->getMessage();
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
      <?php foreach ($RecResult as $row): ?>
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
      <?php foreach ($IngResult as $row): ?>
        <tr>
          <td><?php echo escape($row["preparation"]); ?></td>
          <td><?php echo escape($row["rawName"]); ?></td>
          <td><?php echo escape($row["measurement"]); ?></td>
          <td><?php echo escape($row["unit"]); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

<?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == TRUE) { ?>
        <form method = "post">
        <input type="submit" name="BookmarkSubmit" value="Bookmark">
        </form>
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