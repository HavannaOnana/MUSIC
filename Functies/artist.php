<?php

require_once "Database.php";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $name = htmlspecialchars($_POST["name"]);
    $nickname = htmlspecialchars($_POST['nickname']);
    $year = htmlspecialchars($_POST['year']);
    $country = htmlspecialchars($_POST['country']);

    if(!empty($name) && !empty($nickname) && !empty($year) && !empty($country)){
        $artistInsert = new Artist();
        $artistInsert->insertArtist($name,$nickname,$year,$country);
    }
}


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
          }else{
            echo "Song not added: " . $e->getMessage();
          }
        }
        }


    public function showAllArtist(){
        try{
           $sqlQuery = "SELECT * FROM artist";
           $inProcess = $this->db->pdo->query($sqlQuery);

           echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%; text-align: left;'>";
           echo "<tr>
                <th>ArtistId</th>
                <th>Name</th>
                <th>Nickname</th>
                <th>Year</th>
                <th>Country</th>
              </tr>";

                // Fetch rows and populate the table
            while($row = $inProcess->fetch(PDO::FETCH_ASSOC)){
                echo "<tr>
                        <td>{$row['ArtistID']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['nickname']}</td>
                        <td>{$row['year']}</td>
                        <td>{$row['country']}</td>
                    </tr>";
            }

            // End the table
            echo "</table>";

        }
        catch(PDOException $e){
            echo "Error fetching songs: " . $e->getMessage();
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

    <div class="addartist">
          <h1>Add an Artist</h1>

          <form action="artist.php" method="post">
               <label for="name" name="name">Name of the Artist :</label><br>
               <input type="text" name="name"><br><br>
               <label for="nickname" name="nickname">Nickname:</label><br>
               <input type="text" name="nickname"><br><br>
               <label for="year" name="year">Year:</label><br>
               <input type="text" name="year"><br><br>
               <label for="country" name="country">Country:</label><br>
               <input type="text" name="country"><br><br>
               <button type="submit">Add Song</button>
          </form>
     </div>

     <div class="showallartist">

     <h1>Show All the Artist</h1>
         <?php
            $artist = new Artist();
            $artist->showAllArtist();
         ?>
     </div>

    
    
</body>
</html>