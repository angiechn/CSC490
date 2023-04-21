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
                        <h1 class="text-center">Reset Password</h1>
                        <form action="demo-reset.php" method="post" autocomplete="off" class="blog-search-form">
                            <input type="email" name="email" placeholder="Email" id="email" required>
                            <p></p>
                            <input type="password" name="new_password" placeholder="New Password" id="new_password" required>
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
                            <input class="btn btn-lg btn-circle btn-outline-new-white" type="submit" value="Reset">
                        </form>
                        <div class="text-center">
                            <p></p>
                        <div class="login-link">
                            Remembered your password? <a href="demo-login.php">Login here</a>
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

//Make sure form is not empty 
if (empty($_POST['email']) || empty($_POST['security_answer']) || empty($_POST['new_password'])) {
    exit();
}

//Clean it up
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$security_answer = filter_var($_POST['security_answer'], FILTER_SANITIZE_STRING);
$new_password = filter_var($_POST['new_password'], FILTER_SANITIZE_STRING);

//Validate email
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    exit('Invalid Email!');
}

//Compare input values with database values
$stmt = $connection->prepare('SELECT userID, password, security_question, security_answer FROM whatsdinner.user WHERE email = :email');
$stmt->bindParam(':email', $_POST['email']);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    exit('Email address not found!');
}

if ($_POST['security_answer'] != $user['security_answer']) {
    exit('Security answer is incorrect!');
}

//Change password
$password = password_hash($new_password, PASSWORD_DEFAULT);
$stmt = $connection->prepare('UPDATE whatsdinner.user SET password = :password WHERE userID = :userID');
$stmt->bindParam(':password', $password);
$stmt->bindParam(':userID', $user['userID']);
$stmt->execute();

echo "<div class='success-message'><strong>Password reset successful. Try logging in!</div>";


$connection = null;
?>
