<?php
include "db.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$email = $_POST['email'];
	$pwd = $_POST['pwd'];

	$hashed_pwd = password_hash($pwd, PASSWORD_DEFAULT);

	$sql = "INSERT INTO users (email, password) VALUES ('$email', '$hashed_pwd')";

	if (mysqli_query($link, $sql)) {
		echo "<script>alert('Records inserted successfully');</script>";
        echo "<script type= 'text/javascript'> document.location= '../html/login.html'; </script>";
	} else {
		echo "<script>alert('ERROR: Please try again');</script>";
		echo "<script type= 'text/javascript'> document.location= '../html/register.html'; </script>";
	}
}

mysqli_close($link);
?>