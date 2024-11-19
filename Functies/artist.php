<?php

require_once "Database.php";

class Artist{
  
    private $db;
    public function __construct(){
        $this->db = new Database();
    }

    public function insertArtist($name,$nickname,$year,$country){
        try{
           $query =  "INSERT INTO artist(name,nickname,year,country) VALUES (:name,:nickname,:year,:country)";
           $inProcess = $this->db->pdo->prepare($query);
           $inProcess->bindParam(":name",$name);
           $inProcess->bindParam(":nickname",$nickname);
           $inProcess->bindParam(":year",$year);
           $inProcess->bindParam(":country",$country);
           $inProcess->execute();
           echo "Artist added";
        }
        catch(PDOException $e){
            if ($e->getCode() == 23000) { // SQLSTATE code for integrity constraint violation
               echo "Duplicate song entry is not allowed!";
          } else {
              echo "Song not added: " . $e->getMessage();
        }
        }
    }

    

}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist</title>
    <link rel="stylesheet" href="../functionstyles/artist.css">
</head>
<body>
    
    
      <div class="navbar-upper">

        <div class="first-navbar">
            <h2>my music</h2>
        </div>

        <div class="navbar">
            <a href="../Functies/songs.php">Songs</a>
            <a href="../Functies/artist.php">Artist</a>
        </div>
        
        <div class="logout">
            <a href="../music.html">Logout</a>
        </div>
    </div>

    
    
</body>
</html>