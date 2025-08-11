<?php

require 'loginfunction.php';

if (isset($_SESSION['log']) && $_SESSION['log'] == 'Logged' && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header('Location: admin/home.php');
    } else {
        header('Location: user/home.php');
    }
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="login.css" />
<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Acme&display=swap');
</style>
</head>
<body>
<div class="center">
  <div class="garis">
       <h1 style="font-size: 60px; color:white; text-align:center;">LOGIN</h1>
  </div>

    <form method="post" style="text-align:center;">
    <div class="container">
        <label for="uname" style="color:white;"><b>Email</b></label><br>
        <input type="text" placeholder="Enter Email" name="uname" required><br>

        <label class="mt-4"for="psw" style="color:white;"><b>Password</b></label><br>
        <input type="password" placeholder="Enter Password" name="psw" required><br>
            
        <button class="mt-4" type="submit" name="login">Login</button>
        <div class="mt-4"><a href="lupapassword.php" target="_blank" style="text-decoration:none; color:white" 
        onmouseover="style='text-decoration:underline'"
        onmouseout="style='text-decoration:none; color:white'">
          Lupa Password
        </a>

        </div>
      </div>
    </form>
</div>
</body>
</html>

