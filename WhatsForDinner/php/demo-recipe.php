<!DOCTYPE html>
<html lang="en"><!-- Basic -->

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

<body>
	<!-- Start header -->
	<header class="top-navbar">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="container">
				<a class="navbar-brand" href="demo-home.php">
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
								<input type="submit" name="submitMatchCase" value="Search">
							</form>
						</div>
						<li class="nav-item"><a class="nav-link" href="demo-home.php">Home</a></li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="dropdown-a"
								data-toggle="dropdown">Categories</a>
							<div class="dropdown-menu" aria-labelledby="dropdown-a">
								<a class="dropdown-item" href="demo-entrees.php">Entrees</a>
								<a class="dropdown-item" href="demo-sides.php">Sides</a>
								<a class="dropdown-item" href="demo-desserts.php">Desserts</a>
							</div>
						</li>
						<li class="nav-item"><a class="nav-link" href="demo-account.php">Account</a></li>
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
					<h1><?php foreach ($result as $row): ?>
						<?php echo escape($row["recipeName"]); ?>
					<?php endforeach; ?></h1>
				</div>
			</div>
		</div>
	</div>
	<!-- End header -->

	<!-- Start Recipe -->
	<div class="about-section-box">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 col-md-6">
					<img src="../images/Placeholder.png" alt="" class="img-fluid">
				</div>
				<div class="col-lg-6 col-md-6 text-center">
					<div class="inner-column">
						<h1>Ingredients</h1>
						<?php foreach ($result2 as $row): ?>
							<ul>
								<?php echo escape($row["measurement"]); ?>
								<?php echo escape($row["unit"]); ?>
								<?php echo escape($row["preparation"]); ?>
								<?php echo escape($row["rawName"]); ?>
							</ul>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="col-md-12">
					<div class="instructions">
						<h1>Directions</h1>
						<?php foreach ($result as $row): ?>
							<p><?php echo escape($row["instructions"]); ?></p>
							<p><?php echo escape($row["notes"]); ?></p>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Recipe -->

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
	<script src="../js/jquery-3.2.1.min.js"></script>
	<script src="../js/popper.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>
    <!-- ALL PLUGINS -->
	<script src="../js/images-loded.min.js"></script>
    <script src="../js/custom.js"></script>
</body>
</html>
