<?php
/**
 * Functions to bookmark recipes
 */
session_start();
if (!isset ($_SESSION['loggedin'])) {
    header('Location: login.php');
}

require "connection.php";
require "common.php";

$userName = $_SESSION['username'];
$userID = $_SESSION['userID'];

if ($_SESSION['loggedin'] = TRUE) {
    try {
        // query to fetch recipe names from user bookmarks
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
}
?>

<?php
// output results 
if ($UserBookmarkResult && $UserBookmarkStmt ->rowCount() > 0) { ?>
    <h2>Results</h2>
    <table>
      <thead>q
        <tr>
          <th>Bookmarked Recipes</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($UserBookmarkResult as $row) { ?>
          <tr>
            <td><?php echo escape($row["recipeName"]); ?></td>
            <td><a href="recipeDisplay.php?recipeID=<?php echo escape($row["recipeID"]);?>"><strong>View</strong></a></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
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