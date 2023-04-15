<h1>What's Dinner Tonight?</h1>
<?php
session_start();
// required 
require "connection.php";
require "common.php"; ?>

<style>
a:link, a:visited {
  color: #000000;
  text-decoration: none
}  
a:hover, a:active {
  color: #ff7e66;
  text-decoration: none
}
</style>


<?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == TRUE) { ?>     
  <li><a href="userProfile.php"><strong>Profile</strong></a></li>
  <li><a href="recipeMultiSearch.php"><strong>Search by Ingredients</strong></a></li>
  <li><a href="recipeMatchcaseSearch.php"><strong>Search by Recipe Name</strong></a></li> 
  <li><strong>Search by Tags </strong><a href="entrees.php"><strong>[ Entree ]</strong></a> <a href="sides.php"><strong>[ Side ]</strong></a> <a href="desserts.php"><strong>[ Dessert ]</strong></a></li> 
  <form method="POST" action="account/logout.php">
    <input type="submit" value="Log out">
  </form>
<?php } else { ?>
  <li><a href="recipeMultiSearch.php"><strong>Search by Ingredients</strong></a></li>
  <li><a href="recipeMatchcaseSearch.php"><strong>Search by Recipe Name</strong></a></li> 
  <li><strong>Search by Tags </strong><a href="entrees.php"><strong>[ Entree ]</strong></a> <a href="sides.php"><strong>[ Side ]</strong></a> <a href="desserts.php"><strong>[ Dessert ]</strong></a></li> 
  <form method="POST" action="account/login.php">
    <input type="submit" value="Log in">
  </form>
  <?php } ?>
