<?php require "connection.php";
require "common.php";
session_start(); ?>

<?php if (isset($_POST['submitMatchCase'])) {
	try { // query to fetch recipe name from rec name
		$matchCaseSQL = "SELECT *
        FROM whatsdinner.recipe
        WHERE whatsdinner.recipe.recipeName LIKE :recName";
		$matchCaseStmt = $connection->prepare($matchCaseSQL);
		$recName = '%' . $_POST['recName'] . '%';
		$matchCaseStmt->bindParam(':recName', $recName, PDO::PARAM_STR);
		$matchCaseStmt->execute();

		$matchCaseResult = $matchCaseStmt->fetchAll();
	} catch (PDOException $error) {
		echo $matchCaseSQL . "<br>" . $error->getMessage();
	}
} ?>

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

// take recipeID
if (isset($_GET['recipeID'])) {
	// queries to fetch recipe and ingredient information from recipeID 
	try {
		$recipeID = $_GET['recipeID'];

		$rec1SQL = "SELECT * 
    FROM whatsdinner.recipe 
    WHERE recipeID = :recipeID";

		$rec2SQL = "SELECT * 
    FROM whatsdinner.ingredientRaw 
    LEFT JOIN whatsdinner.ingredient 
    ON whatsdinner.ingredient.recipeID = whatsdinner.ingredientRaw.recID 
    AND whatsdinner.ingredient.ingredientID = whatsdinner.ingredientRaw.ingID 
    LEFT JOIN whatsdinner.raw ON whatsdinner.raw.rawID = whatsdinner.ingredientraw.rawID 
    WHERE recID = :recipeID";

		$rec1Stmt = $connection->prepare($rec1SQL);
		$rec1Stmt->bindParam(':recipeID', $recipeID, PDO::PARAM_STR);
		$rec1Stmt->execute();

		$rec1Result = $rec1Stmt->fetchAll();

		$rec2Stmt = $connection->prepare($rec2SQL);
		$rec2Stmt->bindParam(':recipeID', $recipeID, PDO::PARAM_STR);
		$rec2Stmt->execute();

		$rec2Result = $rec2Stmt->fetchAll();
	} catch (PDOException $error) {
		echo $rec1SQL . "<br>" . $error->getMessage();
		echo $rec2SQL . "<br>" . $error->getMessage();
	}
}
?>

<?php // check if bookmarked 
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == TRUE) {
	$CheckBookmarkSQL = "SELECT 1 FROM whatsdinner.bookmarked 
  WHERE whatsdinner.bookmarked.userID = :userID AND whatsdinner.bookmarked.recipeID = :recipeID";

	$CheckBookmarkStmt = $connection->prepare($CheckBookmarkSQL);
	$CheckBookmarkStmt->bindParam(':recipeID', $recipeID, PDO::PARAM_STR);
	$CheckBookmarkStmt->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_STR);
	$CheckBookmarkStmt->execute();

	$CheckBookmarkResult = count($rows = $CheckBookmarkStmt->fetchAll());
}
?>

<?php // insert into bookmark 
if (isset($_POST['BookmarkSubmit'])) {
	try {
		$AddBookmarkSQL = "INSERT INTO whatsdinner.bookmarked (userID, recipeID) 
      VALUES (:userID, :recipeID)";

		$AddBookmarkStmt = $connection->prepare($AddBookmarkSQL);
		$AddBookmarkStmt->bindParam(':recipeID', $recipeID, PDO::PARAM_STR);
		$AddBookmarkStmt->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_STR);
		$AddBookmarkStmt->execute();

		echo "Bookmark Added";
	} catch (PDOException $error) {
		echo $AddBookmarkSQL . "<br>" . $error->getMessage();
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
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbars-rs-food" aria-controls="navbars-rs-food" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbars-rs-food">
					<ul class="navbar-nav ml-auto">
						<div class="search">
							<form method="post">
								<input type="text" required name="recName" id="recName">
								<input type="submit" class="btn btn-circle btn-outline-new-white" name="submitMatchCase" value="Search">
							</form>
						</div>
						<li class="nav-item"><a class="nav-link" href="demo-home.php">Home</a></li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="dropdown-a" data-toggle="dropdown">Categories</a>
							<div class="dropdown-menu" aria-labelledby="dropdown-a">
								<a class="dropdown-item" href="demo-entrees.php">Entrees</a>
								<a class="dropdown-item" href="demo-sides.php">Sides</a>
								<a class="dropdown-item" href="demo-desserts.php">Desserts</a>
							</div>
						</li>
						<?php if (isset($_SESSION['loggedin'])) { ?>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="dropdown-b" data-toggle="dropdown">Account</a>
								<div class="dropdown-menu" aria-labelledby="dropdown-b">
									<a class="dropdown-item" href="demo-account.php">My Account</a>
									<a class="dropdown-item" href="account/demo-logout.php">Logout</a>
								</div>
							</li>
						<?php } else { ?>
							<li class="nav-item"><a class="nav-link" href="account/demo-login.php">Login</a></li>
						<?php } ?>
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
						<h1><?php foreach ($rec1Result as $row) : ?>
								<?php echo escape($row["recipeName"]); ?>
							<?php endforeach; ?></h1>
					<?php } else if (isset($_POST['submitMatchCase'])) { ?>
						<h1>Results</h1>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<!-- End header -->

	<!-- Start Recipe -->
	<div class="about-section-box">
		<div class="container">
			<?php if (!isset($_POST['submitMatchCase'])) { ?>
				<div class="row">
					<div class="col-lg-6 col-md-6">
						<img src="../images/<?php echo escape($row["recipeID"]); ?>.jpg" alt="" class="img-fluid">
					</div>
					<div class="col-lg-6 col-md-6 text-center">
						<div class="inner-column">
							<h1>Ingredients</h1>
							<?php foreach ($rec2Result as $row) : ?>
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
							<?php foreach ($rec1Result as $row) : ?>
								<p><?php echo escape($row["instructions"]); ?></p>
								<p><?php echo escape($row["notes"]); ?></p>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<?php } else if (isset($_POST['submitMatchCase'])) {
				if ($matchCaseResult && $matchCaseStmt->rowCount() > 0) { ?>
					<div class="row">
						<?php foreach ($matchCaseResult as $row) {
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
							}
						?>
							<div class="col-lg-11">
								<img src="../images/<?php echo escape($row["recipeID"]); ?>.jpg" class="result-image" alt="Image">
								<h1><a href="demo-recipe.php?recipeID=<?php echo escape($row["recipeID"]); ?>"> <?php echo escape($row["recipeName"]); ?></a></h1>
								<p> <?php foreach ($IngResult as $tuple) {
										echo escape($tuple["rawName"]) . ", ";
									} ?> </p>
							</div>
						<?php } ?>
					</div>
				<?php } else { ?> <p> No results found.</p> <?php }
													} else { ?>
				<p> No results found. </p>
			<?php } ?>
		</div>
	</div>
	<!-- End Recipe -->

	<!-- Start Bookmark -->
	<div class="container text-center">
		<?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == TRUE && $CheckBookmarkResult == 0 && !isset($_POST['submitMatchCase'])) { ?>
			<p>
			<form method="post">
				<input class="btn btn-lg btn-circle btn-outline-new-white" type="submit" name="BookmarkSubmit" value="Bookmark">
			</form>
			</p>
		<?php } else if (!isset($_SESSION['loggedin'])) { ?>
			<a href="./account/demo-login.php"><button class="btn btn-lg btn-circle btn-outline-new-white">Log In to Bookmark</button></a>
		<?php } ?>
	</div>

	<!-- End Bookmark -->


	<!-- Start Footer -->
	<p></p>
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