<?php
/** USER PROFILE
 * Display User's username
 * Display Bookmarked Recipes
 * Display Saved Pantry Ingredients
 * Form to Add ingredient to Pantry
 */
require "connection.php";
require "common.php";

session_start();

// redirect to login if not logged in
if (!isset ($_SESSION['loggedin'])) {
    header('Location: login.php');
}

// save session keys
$userName = $_SESSION['username'];
$userID = $_SESSION['userID'];
?>

<h1><?php echo escape($userName)?>'s Profile</h1>

<?php
// fetch raw names for pantry saving
$RawSQL = "SELECT DISTINCT rawName, rawID FROM whatsdinner.raw";
$RawStmt = $connection->prepare($RawSQL); 
$RawStmt->execute();
$RawResult = $RawStmt->fetchAll();

// fetch recipe names from user bookmarks
try {
    $UserBookmarkSQL = "SELECT *
    FROM whatsdinner.recipe
    WHERE whatsdinner.recipe.recipeID IN 
    (SELECT DISTINCT whatsdinner.recipe.recipeID
    FROM whatsdinner.recipe 
    LEFT JOIN whatsdinner.bookmarked ON whatsdinner.bookmarked.recipeID = whatsdinner.recipe.recipeID
    LEFT JOIN whatsdinner.user ON whatsdinner.bookmarked.userID = whatsdinner.user.userID
    WHERE user.userID = :userID)";

    $UserBookmarkStmt = $connection->prepare($UserBookmarkSQL); 
    $UserBookmarkStmt->bindParam(':userID', $userID, PDO::PARAM_STR);
    $UserBookmarkStmt->execute();

    $UserBookmarkResult = $UserBookmarkStmt->fetchAll();
} catch (PDOException $error) {
    echo $UserBookmarkSQL . "<br>" . $error->getMessage();
}

// fetch ingredient names from user pantry
try {
    $UserPantrySQL = "SELECT *
    FROM whatsdinner.raw
    LEFT JOIN whatsdinner.inpantry ON whatsdinner.raw.rawID = whatsdinner.inpantry.rawID
    WHERE whatsdinner.inpantry.userID = :userID";

    $UserPantryStmt = $connection->prepare($UserPantrySQL); 
    $UserPantryStmt->bindParam(':userID', $userID, PDO::PARAM_STR);
    $UserPantryStmt->execute();

    $UserPantryResult = $UserPantryStmt->fetchAll();
} catch (PDOException $error) {
    echo $UserPantrySQL . "<br>" . $error->getMessage();
}
?>

<?php
// delete bookmark if get recipeID is sent
if (isset($_GET['recipeID'])) {
  try {
      $recipeID = $_GET['recipeID'];

      $DelBookmarkSQL = "DELETE FROM whatsdinner.bookmarked WHERE recipeID = :recipeID AND userID = :userID";

      $DelBookmarkStmt = $connection->prepare($DelBookmarkSQL);
      $DelBookmarkStmt->bindParam(':recipeID', $recipeID, PDO::PARAM_STR);
      $DelBookmarkStmt->bindParam(':userID', $userID, PDO::PARAM_STR);
      $DelBookmarkStmt->execute();

      // refresh page after deleted
      header('Location: userProfile.php');
  } catch (PDOException $error) {
      echo $DelBookmarkSQL . "<br>" . $error->getMessage();
  }
}

// delete pantry ingredient if get rawID is sent
if (isset($_GET['rawID'])) {
  try {
      $rawID = $_GET['rawID'];

      $DelPantrySQL = "DELETE FROM whatsdinner.inpantry WHERE rawID = :rawID AND userID = :userID";

      $DelPantryStmt = $connection->prepare($DelPantrySQL);
      $DelPantryStmt->bindParam(':rawID', $rawID, PDO::PARAM_STR);
      $DelPantryStmt->bindParam(':userID', $userID, PDO::PARAM_STR);
      $DelPantryStmt->execute();

      // refresh page after deleted
      header('Location: userProfile.php');
  } catch (PDOException $error) {
      echo $DelPantrySQL . "<br>" . $error->getMessage();
  }
}
?>

<h3>Bookmarked Recipes</h3>

<?php
// display bookmarked results 
if ($UserBookmarkResult && $UserBookmarkStmt ->rowCount() > 0) { ?>
    <table>
      <tbody>
        <?php foreach ($UserBookmarkResult as $row) { ?>
          <tr>
            <td><?php echo escape($row["recipeName"]); ?></td>
            <td><a href="recipeDisplay.php?recipeID=<?php echo escape($row["recipeID"]);?>"><strong>View</strong></a></td>
            <td><a href="userProfile.php?recipeID=<?php echo escape($row["recipeID"]);?>"><strong>Delete</strong></a></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
<?php } else { 
  echo "You have no bookmarked recipes.";
} 
?>

<h3> In Pantry </h3>
<?php
// display ingredients in pantry
if ($UserPantryResult && $UserPantryStmt ->rowCount() > 0) { ?>
    <table>
      <tbody>
        <?php foreach ($UserPantryResult as $row) { ?>
          <tr>
            <td><?php echo escape($row["rawName"]);?></td>
            <td><a href="userProfile.php?rawID=<?php echo escape($row["rawID"]);?>"><strong>Delete</strong></a></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
<?php } else { 
  echo "You have no ingredients in your pantry.";
} 
?>

<?php 
// insert ingredients into pantry
if (isset($_POST['submitAddToPantry'])) {
  try {
    $rawID = $_POST['rawID'];

    $AddPantrySQL = "INSERT INTO whatsdinner.inpantry (userID, rawID) 
    VALUES (:userID, :rawID)";

    $AddPantryStmt = $connection->prepare($AddPantrySQL); 
    $AddPantryStmt->bindParam(':userID', $userID, PDO::PARAM_STR);
    $AddPantryStmt->bindParam(':rawID', $rawID, PDO::PARAM_STR);
    $AddPantryStmt->execute();

    // refresh page after inserted
    header('Location: userProfile.php');
  } catch (PDOException $error) {
    echo $AddPantrySQL . "<br>" . $error->getMessage();
  }
}
?>

<!-- user input for addToPantry -->
<h3>Add Ingredient to Pantry</h3>
<form method = "post">
  <select name = "rawID" id = "rawID"> 
      <option style = "display:none">Choose an ingredient to add.</option>
        <?php foreach($RawResult as $option):?>
          <option value = "<?php echo $option['rawID'];?>" required><?php echo $option['rawName'];?>
        <?php endforeach; ?>
  </select>
  <input type = "submit" name = "submitAddToPantry" value = "Add">
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