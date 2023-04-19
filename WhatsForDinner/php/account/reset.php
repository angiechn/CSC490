<html>
	<head>
		<title>Reset Password</title>
	</head>
	<body>
		<style>
			h1 {text-align: center;}
			form {text-align: center;}
			footer {padding-top: 15px;}
		</style>
		<div class="reset">
			<h1>Reset Password</h1>
			<form action="reset.php" method="post" autocomplete="off">
				<input type="email" name="email" placeholder="Email" id="email" required><br><br>
				<input type="password" name="new_password" placeholder="New Password" id="new_password" required><br><br>
				<label for="security_question">Security Question:</label>
				<select name="security_question" id="security_question" required>
					<option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
					<option value="What was the name of your first pet?">What was the name of your first pet?</option>
					<option value="What is the name of the city where you were born?">What is the name of the city where you were born?</option>
					<option value="What was the make and model of your first car?">What was the make and model of your first car?</option>
				</select>
				<input type="text" name="security_answer" placeholder="Security Answer" id="security_answer" required><br><br>
				<input type="submit" value="Reset Password">
				<footer>Remembered your password? <a href="login.php">Login here</a></footer>
			</form>
		</div>
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

echo 'Password reset successful. Try logging in!';


$connection = null;
?>
