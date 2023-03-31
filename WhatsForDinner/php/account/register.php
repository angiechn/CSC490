
<!DOCTYPE html>
<html>
	<head>
		<title>Register</title>
	</head>
	<body>
    <style>
    	h1 {text-align: center;}
        form {text-align: center;}
    </style>
		<div class="register">
			<h1>Register</h1>
			<form action="register.php" method="post" autocomplete="off">
				<input type="text" name="username" placeholder="Username" id="username" required>
				<input type="password" name="password" placeholder="Password" id="password" required>
				<input type="email" name="email" placeholder="Email" id="email" required>
				<input type="submit" value="Register">
			</form>
		</div>
	</body>
</html>

<?php
require "../connection.php";

//Check if data exists
if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
	//Could not retrieve data
	exit('Please complete the registration form!');
}
//Make sure values are not empty
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
	//Some values are empty
	exit('Please complete the registration form');
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
		//Insert new account
        try {
            $userID = uniqid();
            $username = $_POST['username'];
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            
            $stmt = $connection->prepare('INSERT INTO whatsdinner.user (userID, username, email, password) 
            VALUES (:userID, :username, :email, :password)');

            $stmt->bindParam(':userID', $userID);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            echo 'You have successfully registered!';
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

