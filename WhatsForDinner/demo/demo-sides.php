<?php
require "connection.php";
require "common.php";
?>

<?php // query to fetch sides
$sideSQL = "SELECT whatsdinner.recipe.recipeID, whatsdinner.recipe.recipeName 
        FROM whatsdinner.recipe
        LEFT JOIN whatsdinner.type 
        ON whatsdinner.recipe.recipeID = whatsdinner.type.recipeID
        WHERE whatsdinner.type.type = 'Side'";

$sideStmt= $connection->prepare($sideSQL); 
$sideStmt->execute();
$sideResult = $sideStmt->fetchAll();
?>

<?php if (isset($_POST['submitMatchCase'])) {
	try { // query to fetch recipe name from rec name
		$matchCaseSQL = "SELECT *
        FROM whatsdinner.recipe
        WHERE whatsdinner.recipe.recipeName LIKE :recName";
		$matchCaseStmt= $connection->prepare($matchCaseSQL);
		$recName = '%' . $_POST['recName'] . '%';
		$matchCaseStmt->bindParam(':recName', $recName, PDO::PARAM_STR);
		$matchCaseStmt->execute();

		$matchCaseResult = $matchCaseStmt->fetchAll();
	} catch (PDOException $error) {
		echo $matchCaseSQL . "<br>" . $error->getMessage();
	}
}?>

<!DOCTYPE html>
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
					<?php if (!isset($_POST['submitMatchCase'])) { ?>
						<h1>Sides</h1>
					<?php } else if (isset($_POST['submitMatchCase'])) { ?>
						<h1>Results</h1>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<!-- End header -->

	<!--Start Results-->
	<div class="result-container">
		<div class="container">
				<?php // output results for match case
					if ($sideResult && $sideStmt->rowCount() > 0 && !isset($_POST['submitMatchCase'])) { ?>
						<div class="row">
								<?php foreach ($sideResult as $row) { 
									try { // fetch unmatching ingredients for recipe
										$recipeID = $row["recipeID"];
						
										$IngDisplaySQL = "SELECT *
											FROM whatsdinner.ingredientRaw 
											LEFT JOIN whatsdinner.ingredient 
											ON whatsdinner.ingredient.recipeID = whatsdinner.ingredientRaw.recID 
											AND whatsdinner.ingredient.ingredientID = whatsdinner.ingredientRaw.ingID 
											LEFT JOIN whatsdinner.raw ON whatsdinner.raw.rawID = whatsdinner.ingredientraw.rawID 
											WHERE recID = :recipeID";
						
										$IngDisplayStmt = $connection->prepare($IngDisplaySQL); 
										$IngDisplayStmt->bindParam(':recipeID', $recipeID, PDO::PARAM_STR);
										$IngDisplayStmt->execute();
						
										$IngResult = $IngDisplayStmt->fetchAll();
										} catch (PDOException $error) {
										echo $IngDisplaySQL . "<br>" . $error->getMessage();
										} ?>
									<div class="col-lg-11">
										<img src="../images/<?php echo escape($row["recipeID"]); ?>.jpg" class="result-image" alt="Image">
										<h1><a href="demo-recipe.php?recipeID=<?php echo escape($row["recipeID"]);?>">
										<?php echo escape($row["recipeName"]); ?></a></h1>
										<p> <?php foreach ($IngResult as $tuple) { echo escape($tuple["rawName"]) . ", "; } ?> </p>
									</div>
								<?php } ?>
						</div>
			  	<?php } else if (isset($_POST['submitMatchCase'])) {
							if ($matchCaseResult && $matchCaseStmt->rowCount() > 0) { ?>
								<div class="row">
									<?php foreach ($matchCaseResult as $row) { ?>
										<div class="col-lg-11">
											<img src="../images/<?php echo escape($row["recipeID"]); ?>.jpg" class="result-image" alt="Image">
											<h1><a href="demo-recipe.php?recipeID=<?php echo escape($row["recipeID"]); ?>"> <?php echo escape($row["recipeName"]); ?></a></h1>
										</div>
									<?php } ?>
								</div>
							<?php } else { ?> <p> No results found.</p> <?php }
					} else { ?>
						<p> No results found. </p>
				<?php } ?>
		</div>
	</div>
	<!--End Results-->

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