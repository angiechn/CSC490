<?php //Start a session
session_start();
require "../connection.php";?>

<?php if ($stmt = $connection->prepare('SELECT userID, password FROM whatsdinner.user WHERE username = :username')) {
    
    $stmt->bindParam(':username', $_POST['username']);
    $stmt->execute();
    
    //Check if account exists
    if ($stmt->rowCount() > 0) {
        
        $stmt->bindColumn('userID', $userID);
        $stmt->bindColumn('password', $password);
        $stmt->fetch();

        //If account exists, verify password
        if (password_verify($_POST['password'], $password) == TRUE) {

            //Verification success
            session_regenerate_id();

            $_SESSION['loggedin'] = TRUE;
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['userID'] = $userID;
            $_SESSION['usePantry'] = "FALSE";
            header('Location: ../demo-account.php');
        } else {
            echo 'Incorrect username and/or password!';
        }
    } else {
        echo 'Incorrect username and/or password!';
    }
    unset($stmt);
}
?>

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
        </div>
    </div>
    <!-- End header -->

    <!--Start Login-->
    <div class="blog-box">
        <div class="container" position="center">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="blog-box-inner">
                            <h1 class="text-center">Login</h1>
                            <form action = "demo-login.php" method = "post" class = "blog-search-form">
                                <input name="username" placeholder="Username" id="username" type="text" required>
                                <p></p>
                                <input name="password" placeholder="Password" id="password" type="password" required>
                                </div>
                                <div class="text-center">
                                <p></p>
                                <div class="register-link">
                                Not registered yet? <a href="demo-register.php">Register here</a>
                                </div>
                                <div class="reset-pw">
                                Forgot password? <a href="demo-reset.php">Reset password</a>
                                </div>
                                <p></p>
                                <input class="btn btn-lg btn-circle btn-outline-new-white" type="submit" value="Login">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End Login-->

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

<?//Check login data in the form
if ( !isset($_POST['username'], $_POST['password']) ) {
	exit('');
}?>