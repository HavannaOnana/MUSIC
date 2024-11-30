<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us </title>
    <link rel="stylesheet" href="./contact.css">
</head>
<body>

            <!--navbar-->
     <div class="navbar">
                <a href="../MUSIC/music.html">Home</a>
                <a href="../MUSIC/info.html">Info</a>
                <a href="../MUSIC/contact.php">ContactUs</a>
                <a href="../MUSIC/Functies/login.php">LogIn</a>
     </div>

    <div class="contact">
         <img src="./images/undraw_reminder_re_fe15.svg" alt="">

         <div class="contactustext">
             <h1>Contact Us</h1>
             <form action="thanks.php" method="post">
                 <label for="fullname">Name:</label><br>
                 <input type="text" for="fullname"><br><br>

                 <label for="Email">Email:</label><br>
                 <input type="text" for="Email"><br><br>

                 <label for="message" style="height: 3vh;">Message:</label><br>
                 <input type="text" class="message"><br><br>

                 <button type="submit" id="submit">Send a Message</button>
             </form>
         </div>
    </div>
    
</body>
</html>