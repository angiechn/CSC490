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
	
	<!-- Start All Pages -->
	<div class="all-page-title page-breadcrumb">
		<div class="container text-center">
			<div class="row">
				<div class="col-lg-12">
					<h1>Account</h1>
				</div>
			</div>
		</div>
	</div>
	<!-- End All Pages -->

	<!-- Start blog details -->
	<div class="blog-box">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="heading-title text-center">
                    <?php if ($UserBookmarkResult && $UserBookmarkStmt ->rowCount() > 0) { ?>
                        <h2><?php echo escape($userName)?>'s Profile</h2>
                        <?php } ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xl-8 col-lg-8 col-12">
					<div class="blog-inner-details-page">
						<div class="blog-inner-box">
							<div class="inner-blog-detail details-page">
								<h3>Bookmarked Recipes.</h3>
                                <div class="row">
                                    <?php foreach ($UserBookmarkResult as $row) { ?>
                                        <div class="col-xl-8 col-lg-8 col-12">
                                        <img src="../images/placeholder.png" class="result-image" alt="Image">
                                        <h1><a href="not-demo-recipe.php?recipeID=<?php echo escape($row["recipeID"]);?>">
                                            <?php echo escape($row["recipeName"]); ?></a></h1>
                                    <?php } ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-4 col-lg-4 col-md-6 col-sm-8 col-12 blog-sidebar">
					<div class="right-side-blog">
						<h3>My Pantry</h3>
						<div class="search-form">
							<input name="search" placeholder="Search ingredient" type="text">
						</div>
						<div class="blog-tag-box">
							<ul class="list-inline tag-list">
								<li class="list-inline-item"><a href="#">Lions</a></li>
								<li class="list-inline-item"><a href="#">Tigers</a></li>
								<li class="list-inline-item"><a href="#">Bears</a></li>
								<li class="list-inline-item"><a href="#">Oh my</a></li>
							</ul>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	<!-- End details -->

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
	<script src="js/isotope.min.js"></script>
	<script src="js/custom.js"></script>
</body>

</html>

<?php
/**
 * Displays information from bookmarked 
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
