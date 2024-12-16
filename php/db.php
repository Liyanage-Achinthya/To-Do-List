<?php 
$link = mysqli_connect("localhost", "root", "", "to_do_db");

if (!$link) {
	die("Connection failed: " . mysqli_connect_error());
}
?>