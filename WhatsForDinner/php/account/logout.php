<?php
//Logout and destroy session
session_start();
session_destroy();
//Redirect to home page
header('Location: ../home.php');
exit;
?>
