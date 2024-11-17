<?php

require_once "Database.php";




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogIn Page</title>
    <link rel="stylesheet" href="../functionstyles/login.css">
</head>
<body>

     <div class="main-body">
        
       <h1>Login</h1>
       <form action="" method="post">
          <label for="username" name="username">Username:</label><br>
          <input type="text" name="username"><br>
          <label for="password" name="password">Password:</label><br>
          <input type="text" name="password"><br><br>
          <button type="submit">Submit</button>
       </form>

     </div>

     
    
</body>
</html>