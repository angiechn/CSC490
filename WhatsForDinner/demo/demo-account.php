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
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../demo/account/demo-login.php');
}
?>

<?php // fetch raw names for pantry saving
$RawSQL = "SELECT DISTINCT rawName, rawID FROM whatsdinner.raw ORDER BY rawName";
$RawStmt = $connection->prepare($RawSQL);
$RawStmt->execute();
$RawResult = $RawStmt->fetchAll();
?>

<?php // fetch recipe names from user bookmarks
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
	$UserBookmarkStmt->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_STR);
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
	$UserPantryStmt->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_STR);
	$UserPantryStmt->execute();

	$UserPantryResult = $UserPantryStmt->fetchAll();
} catch (PDOException $error) {
	echo $UserPantrySQL . "<br>" . $error->getMessage();
}
?>

<?php // delete bookmark if get recipeID is sent
if (isset($_GET['recipeID'])) {
	try {
		$recipeID = $_GET['recipeID'];

		$DelBookmarkSQL = "DELETE FROM whatsdinner.bookmarked WHERE recipeID = :recipeID AND userID = :userID";

		$DelBookmarkStmt = $connection->prepare($DelBookmarkSQL);
		$DelBookmarkStmt->bindParam(':recipeID', $recipeID, PDO::PARAM_STR);
		$DelBookmarkStmt->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_STR);
		$DelBookmarkStmt->execute();

		// refresh page after deleted
		header('Location: demo-account.php');
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
		$DelPantryStmt->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_STR);
		$DelPantryStmt->execute();

		// refresh page after deleted
		header('Location: demo-account.php');
	} catch (PDOException $error) {
		echo $DelPantrySQL . "<br>" . $error->getMessage();
	}
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
		$AddPantryStmt->bindParam(':userID', $_SESSION['userID'], PDO::PARAM_STR);
		$AddPantryStmt->bindParam(':rawID', $rawID, PDO::PARAM_STR);
		$AddPantryStmt->execute();

		// refresh page after inserted
		header('Location: demo-account.php');
	} catch (PDOException $error) {
		echo $AddPantrySQL . "<br>" . $error->getMessage();
	}
}
?>

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

	<!-- Start All Pages -->
	<div class="all-page-title page-breadcrumb">
		<div class="container text-center">
			<div class="row">
				<div class="col-lg-12">
					<?php if (!isset($_POST['submitMatchCase'])) { ?>
						<h1><?php echo escape($_SESSION['username']) ?>'s Profile</h1>
					<?php } else if (isset($_POST['submitMatchCase'])) { ?>
						<h1>Results</h1>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<!-- End All Pages -->

	<!-- Start blog details -->
	<?php if (!isset($_POST['submitMatchCase'])) { ?>
		<div class="blog-box">
			<div class="container">
				<div class="row">
					<div class="col-xl-8 col-lg-8 col-12">
						<div class="blog-inner-details-page">
							<div class="blog-inner-box">
								<div class="inner-blog-detail details-page">
									<h2>Bookmarked Recipes</h2>
									<div class="row">
										<?php foreach ($UserBookmarkResult as $row) { ?>
											<div class="col-xl-8 col-lg-8 col-12">
												<img src="../images/<?php echo escape($row["recipeID"]); ?>.jpg" class="result-image" alt="Image">
												<p></p>
												<h3><a href="demo-recipe.php?recipeID=<?php echo escape($row["recipeID"]); ?>">
														<?php echo escape($row["recipeName"]); ?></a></h3>
												<a href="demo-account.php?recipeID=<?php echo escape($row["recipeID"]); ?>" class="btn btn-lg btn-circle btn-outline-new-white">Delete</a>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-xl-4 col-lg-4 col-md-6 col-sm-8 col-12 blog-sidebar">
						<div class="right-side-blog">
							<p></p>
							<h2>My Pantry</h2>
							<div class="blog-search-form">
								<form method="post">
									<select name="rawID" id="rawID">
										<optgroup label="Choose an ingredient to add.">
											<?php foreach ($RawResult as $option) : ?>
												<option value="<?php echo $option['rawID']; ?>" required><?php echo $option['rawName']; ?>
												<?php endforeach; ?>
										</optgroup>
									</select>
									<p></p>
							</div>
							<input class="btn btn-lg btn-circle btn-outline-new-white" type="submit" name="submitAddToPantry" value="Add">
							</form>
							<div class="blog-tag-box">
								<ul class="list-inline tag-list">
									<p></p>
									<?php if ($UserPantryResult && $UserPantryStmt->rowCount() > 0) { ?>
										<?php echo "Click on an ingredient to delete." ?>
										<p></p>
										<?php foreach ($UserPantryResult as $row) { ?>
											<li class="list-inline-item"><a href="demo-account.php?rawID=<?php echo escape($row["rawID"]); ?>"><?php echo escape($row["rawName"]); ?></a></li>
										<?php } ?>
									<?php } else {
										echo "You have no ingredients in your pantry.";
									} ?>
								</ul>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		<!-- End details -->
		<!-- Start Results -->
		<?php } else if (isset($_POST['submitMatchCase'])) {
		if ($matchCaseResult && $matchCaseStmt->rowCount() > 0) { ?>
			<div class="result-container">
				<div class="container">
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
			<!-- End Results -->
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