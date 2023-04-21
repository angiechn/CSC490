<?php //Start a session
session_start();
require "../connection.php";?>

<!DOCTYPE html>
<html lang="en"><!-- Basic -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Site Metas -->
    <title>What's For Dinner Reverse Recipe Search</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Site Icons -->
    <link rel="shortcut icon" href="../../images/placeholder.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="../../images/apple-touch-icon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <!-- Site CSS -->
    <link rel="stylesheet" href="../../css/style.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="../../css/responsive.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../css/custom.css">

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
                <a class="navbar-brand" href="../demo-home.php">
                    <img src="../../images/logo.png" width=150px alt="What's for Dinner?"/>
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
                        <li class="nav-item"><a class="nav-link" href="../demo-home.php">Home</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdown-a"
                                data-toggle="dropdown">Categories</a>
                            <div class="dropdown-menu" aria-labelledby="dropdown-a">
                                <a class="dropdown-item" href="../demo-entrees.php">Entrees</a>
                                <a class="dropdown-item" href="../demo-sides.php">Sides</a>
                                <a class="dropdown-item" href="../demo-desserts.php">Desserts</a>
                            </div>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="../demo-account.php">Account</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!-- End header -->

    <!-- Start header -->
    <div class="all-page-title page-breadcrumb">
        <div class="container text-center">
        </div>
    </div>
    <!-- End header -->

    <!--Start Registration-->

    <div class="blog-box">
        <div class="container" position="center">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="blog-box-inner">
                        <h1 class="text-center">Register</h1>
                        <form action="demo-register.php" method="post" autocomplete="off" class="blog-search-form">
                            <input type="text" name="username" placeholder="Username" id="username" required>
                            <p></p>
                            <input type="password" name="password" placeholder="Password" id="password" required>
                            <p></p>
                            <input type="email" name="email" placeholder="Email" id="email" required>
                            <p></p>
                            <label for="security_question">Security Question:</label>
                            <select name="security_question" id="security_question" required>
                                <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
                                <option value="What was the name of your first pet?">What was the name of your first pet?</option>
                                <option value="What is the name of the city where you were born?">What is the name of the city where you were born?</option>
                                <option value="What was the make and model of your first car?">What was the make and model of your first car?</option>
                            </select>
                            <p></p>
                            <input type="text" name="security_answer" placeholder="Security Answer" id="security_answer" required>
                            <p></p>
                            <input class="btn btn-lg btn-circle btn-outline-new-white" type="submit" value="Register">
                        </form>
                        <div class="text-center">
                            <p></p>
                        <div class="register-link">
                            Already have an account? <a href="demo-login.php">Login here</a>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End Registration-->

    <!-- Start Footer -->
    <footer class="footer-area bg-f">
        <div class="container">
            <div class="row">
            </div>
        </div>
    </footer>
    <!-- End Footer -->

    <!-- ALL JS FILES -->
    <script src="../../js/jquery-3.2.1.min.js"></script>
    <script src="../../js/popper.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <!-- ALL PLUGINS -->
    <script src="../../js/images-loded.min.js"></script>
    <script src="../../js/custom.js"></script>

</body>
</html>


<?php
require "../connection.php";

//Check form for empty values
if (!isset($_POST['username'], $_POST['password'], $_POST['email'], $_POST['security_answer'])) {
    exit('');
}

//Email, username, and password validation
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	exit('Email is not valid!');
}

if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
	echo 'Registration failed!';
    exit('Username can only contain alphabetical and numerical characters!');
}

if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
	exit('Password must be between 5 and 20 characters long!');
}

//Check if username exists in database
if ($stmt = $connection->prepare('SELECT userID, password FROM whatsdinner.user WHERE username = :username')) {
	
	$stmt->bindParam(':username', $_POST['username']);
    $stmt->execute();
	
	if ($stmt->rowCount() > 0) {
		echo 'Username exists, please choose another!';
	} else {
	
	//Generate unique userID
	$unique = false;
	while (!$unique) {
		$userID = mt_rand(1, 2147483647);
		
		// Check if userID exists in database
		$stmt = $connection->prepare('SELECT COUNT(*) FROM whatsdinner.user WHERE userID = :userID');
		$stmt->bindParam(':userID', $userID);
		$stmt->execute();
		
		$count = $stmt->fetchColumn();
		if ($count == 0) {
				$unique = true;
		}
	}
		//Insert new account
        try {
            $username = $_POST['username'];
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
			$security_question = $_POST['security_question'];
    		$security_answer = $_POST['security_answer'];	
            
            $stmt = $connection->prepare('INSERT INTO whatsdinner.user (userID, username, email, password, security_question, security_answer) 
            VALUES (:userID, :username, :email, :password, :security_question, :security_answer)');

            $stmt->bindParam(':userID', $userID);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
			$stmt->bindParam(':security_question', $security_question);
			$stmt->bindParam(':security_answer', $security_answer);
            $stmt->execute();
            echo "<div class='success-message'><strong>You have successfully registered!</div>";
        } catch (Exception $e) { 
            echo $e;
            echo 'Could not execute statement.';
        }
	}
} else {
	echo 'SQL statement is wrong.';
}
$connection = null;
?>

