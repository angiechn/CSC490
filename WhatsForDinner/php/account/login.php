<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Login</title>
	</head>
	<body>
    <style>
        h1 {text-align: center;}
        form {text-align: center;}
    </style>
		<div class="login">
			<h1>Login</h1>
			<form action="login.php" method="post">
				<input type="text" name="username" placeholder="Username" id="username" required>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<input type="submit" value="Login">
			</form>
		</div>
	</body>
</html>

<?php
//Start a session
session_start();

require "../connection.php";

//Check login data in the form
if ( !isset($_POST['username'], $_POST['password']) ) {
	exit('Please fill both the username and password fields!');
}

if ($stmt = $connection->prepare('SELECT userID, password FROM whatsdinner.user WHERE username = :username')) {
    
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
            header('Location: ../../account.html');
        } else {
            echo 'Incorrect username and/or password!';
        }
    } else {
        echo 'Incorrect username and/or password!';
    }
    unset($stmt);
}
?>
