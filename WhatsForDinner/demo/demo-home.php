<?php require "connection.php"; 
require "common.php"; 
session_start();?>

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
	<link rel="apple-touch-icon" href="../images/placeholder.png">

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
</html>

<?php // fetch rawnames for dynamic search
$RawSQL = "SELECT DISTINCT rawName FROM whatsdinner.raw ORDER BY rawName";
$RawStmt = $connection->prepare($RawSQL); 
$RawStmt->execute();
$RawResult = $RawStmt->fetchAll(); ?>

<?php // fetch pantry if user logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == TRUE) { 
  try {
    $UserPantrySQL = "SELECT raw.rawName
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
} ?>

<?php // matchcase or multisearch submits
if (isset($_POST['submitMatchCase'])) {
	try { // query to fetch recipe name from rec name
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
} else if (isset($_POST['submitMulti'])) {
  try {
    $raws = $_POST['rawName'];
    // query to fetch recipe name from raw names
    $MultiSearchSQL = sprintf(
      "SELECT DISTINCT recipe.recipeID, recipe.recipeName, COUNT(rawName)
      FROM whatsdinner.recipe
      LEFT JOIN whatsdinner.ingredientRaw
      ON whatsdinner.ingredientRaw.recID = whatsdinner.recipe.recipeID
      LEFT JOIN whatsdinner.ingredient 
      ON whatsdinner.ingredient.recipeID = whatsdinner.ingredientRaw.recID 
      AND whatsdinner.ingredient.ingredientID = whatsdinner.ingredientRaw.ingID 
      LEFT JOIN whatsdinner.raw ON whatsdinner.raw.rawID = whatsdinner.ingredientraw.rawID 
      WHERE rawName IN (%s)
      GROUP BY ingredientRaw.recID
      ORDER BY COUNT(rawName) DESC",
      "'" . implode("', '", $raws) . "'"
    );
    $MultiSearchStmt = $connection->prepare($MultiSearchSQL); 
    $MultiSearchStmt->execute();
    $MultiSearchResult = $MultiSearchStmt->fetchAll();
  } catch (PDOException $error) {
    echo $MultiSearchSQL . "<br>" . $error->getMessage();
  }
} ?>

<?php // toggle pantry
if (isset($_SESSION['loggedin']) && isset($_POST['yesPantry']) && $_SESSION['usePantry'] == "FALSE") {
  $_SESSION['usePantry'] = "TRUE";
  header('Location: demo-home.php');
} else if (isset($_SESSION['loggedin']) && isset($_POST['noPantry']) && $_SESSION['usePantry'] == "TRUE") {
  $_SESSION['usePantry'] = "FALSE";
  header('Location: demo-home.php');
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
						<div class="blog-search-form">
							<form method="post">
								<input type="text" required name="recName" id="recName">
								<btn class="search-btn">
								<i input type="submit" class="fa fa-search" name="submitMatchCase"></i>
								</btn>
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
						<?php if (isset($_SESSION['loggedin'])) { ?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="dropdown-b"
								data-toggle="dropdown">Account</a>
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
					<h1>What's For Dinner?</h1>
					<h2>Reverse Recipe Search</h2>
				</div>
			</div>
		</div>
	</div>
	<!-- End header -->


	<!-- Start Multi Search -->
	<div class="section-box">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="text-sm-left"> <p>To select multiple, hold CTRL while clicking.</p> </div>
						<!-- Submit Multi Search -->
						<div class="dropdown">
							<form method ="post" >
								<select class="form-select" name = "rawName[]" id = "rawName[]" multiple required> 
									<?php foreach($RawResult as $option):
										if((isset($_SESSION['loggedin']) && in_array($option, $UserPantryResult)) == TRUE && $_SESSION['usePantry'] == "TRUE") { ?>
											<option value = "<?php echo $option['rawName'];?>" required selected><?php echo $option['rawName'];?></option>
										<?php } else { ?>
											<option value = "<?php echo $option['rawName'];?>" required><?php echo $option['rawName'];?></option>
										<?php } 
									endforeach; ?>
								</select>
								<div class="col-lg-12">
									<div class="text-sm-center">
								<p></p>
								<p></p>
								<p></p>
										<input class="btn btn-circle btn-outline-new-white" name="submitMulti" type="submit" value="Search">
										<p></p>
										<!-- Toggle Pantry -->
										<form method = "post"> 
											<?php if (isset($_SESSION['loggedin']) && $_SESSION['usePantry'] == "FALSE") { ?>
												<input class = "btn btn-lg btn-circle btn-outline-new-white" type = "submit" name = "yesPantry" value = "Use Pantry"> 
											<?php } else if (isset($_SESSION['loggedin']) && $_SESSION['usePantry'] == "TRUE") { ?>
												<input class = "btn btn-lg btn-circle btn-outline-new-white" type = "submit" name = "noPantry" value = "Don't Use Pantry">
											<?php } ?>
										</form>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div> 
		
	<!-- End Multi Search -->

	<!--Start Results-->
	<div class="result-container">
		<div class="container">
			<?php // output results for match case
			if (isset($_POST['submitMatchCase'])) {
				if ($result && $statement->rowCount() > 0) { ?>
					<div class="row">
						<?php foreach ($result as $row) { ?>
							<div class="col-lg-11">
								<img src="../images/<?php echo escape($row["recipeID"]); ?>.jpg" class="result-image" alt="Image">
								<h1><a href="demo-recipe.php?recipeID=<?php echo escape($row["recipeID"]); ?>"> <?php echo escape($row["recipeName"]); ?></a></h1>
							</div>
						<?php } ?>
					</div>
				<?php } else { ?> <p> No results found.</p> <?php }
			} else if (isset($_POST['submitMulti'])) {
			  	if ($MultiSearchResult && $MultiSearchStmt->rowCount() > 0) { ?>
					<div class = "row">
						<?php foreach ($MultiSearchResult as $row) { 
								try { // fetch unmatching ingredients for recipe
								$recipeID = $row["recipeID"];
				
								$OtherIngDisplaySQL = sprintf("SELECT *
									FROM whatsdinner.ingredientRaw 
									LEFT JOIN whatsdinner.ingredient 
									ON whatsdinner.ingredient.recipeID = whatsdinner.ingredientRaw.recID 
									AND whatsdinner.ingredient.ingredientID = whatsdinner.ingredientRaw.ingID 
									LEFT JOIN whatsdinner.raw ON whatsdinner.raw.rawID = whatsdinner.ingredientraw.rawID 
									WHERE recID = :recipeID AND rawName NOT IN (%s)",
									"'" . implode("', '", $raws) . "'");
				
								$OtherIngDisplayStmt = $connection->prepare($OtherIngDisplaySQL); 
								$OtherIngDisplayStmt->bindParam(':recipeID', $recipeID, PDO::PARAM_STR);
								$OtherIngDisplayStmt->execute();
				
								$OtherIngResult = $OtherIngDisplayStmt->fetchAll();
								} catch (PDOException $error) {
								echo $OtherIngDisplaySQL . "<br>" . $error->getMessage();
								} 
				
								try { 
								$MatchIngDisplaySQL = sprintf("SELECT *
									FROM whatsdinner.ingredientRaw 
									LEFT JOIN whatsdinner.ingredient 
									ON whatsdinner.ingredient.recipeID = whatsdinner.ingredientRaw.recID 
									AND whatsdinner.ingredient.ingredientID = whatsdinner.ingredientRaw.ingID 
									LEFT JOIN whatsdinner.raw ON whatsdinner.raw.rawID = whatsdinner.ingredientraw.rawID 
									WHERE recID = :recipeID AND rawName IN (%s)",
									"'" . implode("', '", $raws) . "'");
				
								$MatchIngDisplayStmt = $connection->prepare($MatchIngDisplaySQL); 
								$MatchIngDisplayStmt->bindParam(':recipeID', $recipeID, PDO::PARAM_STR);
								$MatchIngDisplayStmt->execute();
				
								$MatchIngResult = $MatchIngDisplayStmt->fetchAll();
								} catch (PDOException $error) {
								echo $MatchIngDisplaySQL . "<br>" . $error->getMessage();
								} ?>

								<div class="col-lg-11">
									<img src="../images/<?php echo escape($row["recipeID"]); ?>.jpg" class="result-image" alt="Image">
									<h1><a href="demo-recipe.php?recipeID=<?php echo escape($row["recipeID"]); ?>"> <?php echo escape($row["recipeName"]); ?></a></h1>
									<p class="em"> <?php foreach ($MatchIngResult as $tuple) { echo escape($tuple["rawName"]) . ", "; } ?> </p>
									<p> <?php foreach ($OtherIngResult as $tuple) { echo escape($tuple["rawName"]) . ", "; } ?> </p>
								</div>
						<?php } ?>
			  	<?php } else { ?>
					> No results found for 
					<?php 
					$rawsString = implode("', '", $raws);
					echo($rawsString); 
				}
			} ?>
		</div>
	</div>
	<!--End Results-->

	<!-- Start Footer -->
	<footer class="footer-area bg-f">
		<div class="container" border-inline="black">
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