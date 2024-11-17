<?php

require_once "Database.php";

class FetchingUserDetails {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function validateUser($username, $password) {
        try {
            $sqlQuery = "SELECT * FROM users WHERE username = :username AND password = :password";
            $stmt = $this->db->pdo->prepare($sqlQuery);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            echo "Error bij " . $e->getMessage();
            return false;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $fetchingUserDetails = new FetchingUserDetails();

    if ($fetchingUserDetails->validateUser($username, $password)) {
        session_start();
        $_SESSION['username'] = $username;
        header('Location: /MUSIC/artist.html');
        exit;
    } else {
        echo "Invalid username or password";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogIn Page</title>
    <link rel="stylesheet" href="../functionstyles/loging.css">
</head>
<body>
    <div class="navbar">
                <a href="../MUSIC/music.html">Home</a>
                <a href="../MUSIC/info.html">Info</a>
                <a href="#">ContactUs</a>
                <a href="../MUSIC/Functies/login.php">LogIn</a>
    </div>


    <div class="main-body">
        <h1>Login</h1>
        <form action="login.php" method="post">
            <label for="username" name="username">Username:</label><br>
            <input type="text" name="username" placeholder="Email" required><br>
            <label for="password" name="password">Password:</label><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <a href="#">Forgot Password??</a><br><br>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
