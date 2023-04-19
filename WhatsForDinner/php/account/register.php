
<!DOCTYPE html>
<html>
	<head>
		<title>Register</title>
	</head>
	<body>
    <style>
    	h1 {text-align: center;}
        form {text-align: center;}
		footer {padding-top: 15px;}
    </style>
		<div class="register">
			<h1>Register</h1>
			<form action="register.php" method="post" autocomplete="off">
				<input type="text" name="username" placeholder="Username" id="username" required><br><br>
				<input type="password" name="password" placeholder="Password" id="password" required><br><br>
				<input type="email" name="email" placeholder="Email" id="email" required><br><br>
				<label for="security_question">Security Question:</label>
				<select name="security_question" id="security_question" required>
				<option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
				<option value="What was the name of your first pet?">What was the name of your first pet?</option>
				<option value="What is the name of the city where you were born?">What is the name of the city where you were born?</option>
				<option value="What was the make and model of your first car?">What was the make and model of your first car?</option>
				</select>
				<input type="text" name="security_answer" placeholder="Security Answer" id="security_answer" required>
				<input type="submit" value="Register">
				<footer>Already have an account? <a href="login.php">Login here</a></footer>
			</form>
		</div>
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

