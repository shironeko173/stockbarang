<?php
require 'loginfunction.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Lupa Password</title>
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
       <h1 style="font-size: 60px; color:white; text-align:center;">Lupa Password</h1>
  </div>
    <form method="post" style="text-align:center;">
        <div class="container">
            <label for="uname" style="color:white;"><b>Email</b></label><br>
            <input type="text" placeholder="Enter Email" name="uname" required><br>

            <label class="mt-4"for="psw" style="color:white;"><b>New Password</b></label><br>
            <input type="password" placeholder="Enter Password" name="pswbaru" required><br>
                
            <button class="mt-4" type="submit" name="gantipass">Update</button>
        </div>
    </form>
</div>



</body>
</html>

