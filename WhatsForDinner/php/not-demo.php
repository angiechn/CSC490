<!DOCTYPE html>
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
?>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<!-- Mobile Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Site Metas -->
	<title>What's For Dinner?</title>
	<meta name="keywords" content="">
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- Site Icons -->
	<link rel="shortcut icon" href="../images/placeholder.png" type="image/x-icon">
	<link rel="apple-touch-icon" href="../images/apple-touch-icon.png">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<!-- Site CSS -->
	<link rel="stylesheet" href="../css/style.css">
	<!-- Responsive CSS -->
	<link rel="stylesheet" href="../css/responsive.css">
	<!-- Custom CSS -->
	<link rel="stylesheet" href="../css/custom.css">

	<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<?php
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

<!--take user input from submit bar-->
<?php
if (isset($_POST['submitMulti'])) {
  try {
    // query to fetch recipe name from raw name
    $sql = "SELECT *
    FROM whatsdinner.recipe
    WHERE whatsdinner.recipe.recipeID IN 
      (SELECT DISTINCT whatsdinner.recipe.recipeID
      FROM whatsdinner.recipe 
      LEFT JOIN whatsdinner.ingredient ON whatsdinner.recipe.recipeID = whatsdinner.ingredient.recipeID
      LEFT JOIN whatsdinner.ingredientraw ON whatsdinner.ingredientraw.recID = whatsdinner.ingredient.recipeID
      LEFT JOIN whatsdinner.raw ON whatsdinner.raw.rawID = whatsdinner.ingredientraw.rawID
      WHERE rawName = :rawName)";

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


<body>
	<!-- Start header -->
	<header class="top-navbar">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="container">
				<a class="navbar-brand" href="not-demo.php">
					<img src="../images/logo.png" width=150px alt="What's for Dinner?" />
				</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbars-rs-food"
					aria-controls="navbars-rs-food" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbars-rs-food">
					<ul class="navbar-nav ml-auto">
						<div class="search">
              <form method="post">
                  <input type="text" required name="recName" id="recName">
                  <input type="submit" name="submit" value="Search">
              </form>
						</div>
						<li class="nav-item"><a class="nav-link" href="not-demo.php">Home</a></li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="dropdown-a"
								data-toggle="dropdown">Catagories</a>
							<div class="dropdown-menu" aria-labelledby="dropdown-a">
								<a class="dropdown-item" href="">Entrees</a>
								<a class="dropdown-item" href="">Sides</a>
								<a class="dropdown-item" href="">Desserts</a>
							</div>
						</li>
						<li class="nav-item"><a class="nav-link" href="">Account</a></li>
					</ul>
				</div>
			</div>
		</nav>
	</header>
	<!-- End header -->
	

	<!-- Start header -->
	<div class="all-page-title page-breadcrumb">
		<div class="container text-center">
			<div class="row">
				<div class="col-lg-12">
					<h1>What's For Dinner?</h1>
					<h2>Reverse Recipe Search</h2>
				</div>
			</div>
		</div>
	</div>
	<!-- End header -->


  	<!-- Start Search -->
	<div class="section-box">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="search-form">
              <form method="post">
                <select name = "rawName" id = "rawName"> 
                    <option style = "display:none">Choose an ingredient.</option>
                      <?php foreach($result2 as $option):?>
                        <option value= "<?php echo $option['rawName'];?>" required><?php echo $option['rawName'];?>
                      <?php endforeach; ?>
                </select>
                <a class="btn btn-lg btn-circle btn-outline-new-white">
                  <input type="submit" name="submit" value="Search">
                </a>
              </form>
						<div class="text-sm-center">
							<p>selected ingredients to be shown as tags, click to remove</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Search -->


	<div class="result-container">
		<div class="container">
			<?php
				// output results 
				if (isset($_POST['submitMulti'])) {
				if ($result && $statement->rowCount() > 0) { ?>
						<?php foreach ($result as $row) { ?>
						<div class="row">
							<div class="col-lg-11">
								<h1><a href="not-demo-recipe.php?recipeID=<?php echo escape($row["recipeID"]);?>">
								<?php echo escape($row["recipeName"]); ?></a></h1>
							<?php } ?>
								<ul>
									<p class="em">matched ingredients</p> 
									<p>other ingredients</p>
								</ul>
							</div>
						</div>
				<?php } else { ?>
					<p> No results found.</p>
				<?php }
			} ?>
		</div>
	</div>


	<!-- Start Footer -->
	<footer class="footer-area bg-f">
			<div class="container">
				<div class="row">
				</div>
			</div>
	</footer>
	<!-- End Footer -->

	<a href="#" id="back-to-top" title="Back to top" style="display: none;">&uarr;</a>

	<!-- ALL JS FILES -->
	<script src="js/jquery-3.2.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<!-- ALL PLUGINS -->
	<script src="js/images-loded.min.js"></script>
	<script src="js/custom.js"></script>
</body>

</html>