<?php
include "db.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];

    $sql = "SELECT id, email, password FROM users WHERE email = '$email'";
    $result = mysqli_query($link, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $stored_hashed_pwd = $row['password'];

        if (password_verify($pwd, $stored_hashed_pwd)) {
            $_SESSION['user_id'] = $row['id']; // Store user ID
            $_SESSION['email'] = $row['email']; // Store email for display

            echo "<script>alert('Login sucessfull!');</script>";
            echo "<script type= 'text/javascript'> document.location= '../php/list.php'; </script>";
        } else {
            echo "<script>alert('Invalid password');</script>";
            echo "<script type= 'text/javascript'> document.location= '../html/login.html'; </script>";
        }
    } else {
        echo "<script>alert('Invalid username');</script>";
        echo "<script type= 'text/javascript'> document.location= '../html/login.html'; </script>";
    }
}

mysqli_close($link);
?>