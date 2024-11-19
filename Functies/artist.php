<?php

require_once "Database.php";



if($_SERVER['REQUEST_METHOD'] == 'POST'){

    if ($_POST['action'] === 'update'){
    $ArtistId = htmlspecialchars($_POST['ArtistID']);
    $name = htmlspecialchars($_POST['name']);
    $nickname = htmlspecialchars($_POST['nickname']);
    $year = htmlspecialchars($_POST['year']);
    $country = htmlspecialchars($_POST['country']); 

    if(!empty($ArtistId) && !empty($name) && !empty($nickname) && !empty($year) && !empty($country)){
        $artistUpdate = new Artist();
        $artistUpdate->updateArtistByID($ArtistId,$name,$nickname,$year,$country);
    }
  } else if($_POST['action'] === 'delete'){
      $artistID = htmlspecialchars($_POST['ArtistID']);
      if(!empty($artistID)){
            $artistDelete = new Artist();
            $artistDelete->deleteArtistByID($artistID);
        }else{
             echo "Please fill in all fields correctly.";
        }
  }
  else{
        $name = htmlspecialchars($_POST["name"]);
        $nickname = htmlspecialchars($_POST['nickname']);
        $year = htmlspecialchars($_POST['year']);
        $country = htmlspecialchars($_POST['country']);

        if(!empty($name) && !empty($nickname) && !empty($year) && !empty($country)){
            $artistInsert = new Artist();
            $artistInsert->insertArtist($name,$nickname,$year,$country);
    }
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

    public function updateArtistByID($artistID,$name,$nickname,$year,$country){
       $sqlQuery = "UPDATE artist SET name = :name, nickname = :nickname, year = :year, country = :country WHERE ArtistID = :ArtistID";
       $inProcess = $this->db->pdo->prepare($sqlQuery);

       $inProcess->bindParam(':ArtistID',$artistID);
       $inProcess->bindParam(':name',$name);
       $inProcess->bindParam(':nickname',$nickname);
       $inProcess->bindParam(':year',$year);
       $inProcess->bindParam(':country',$country);

        if($inProcess->execute()){
            echo "Song sucessfully updated";
        }
        else{
            echo "Mistake by updating a song";
        }
    }

    public function deleteArtistByID($artistID){
        $sqlQuery = "DELETE FROM artist WHERE ArtistID= :ArtistID";
        $inProcess = $this->db->pdo->prepare($sqlQuery);
        $inProcess->bindParam(":ArtistID",$artistID);
        if($inProcess->execute()){
        echo "Song deleted";
      }
      else{
         echo "Could not delete song due to error";
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
               <button type="submit">Add Artist</button>
          </form>
     </div>

     <div class="showallartist">
        <h1>Show All the Artist</h1>
            <?php
                $artist = new Artist();
                $artist->showAllArtist();
            ?>
     </div>

     <div class="updateArtist">
        <h1>Update an Artist By ID</h1>
        <form action="artist.php" method="post">
            <input type="hidden" name="action" value="update"> 
            <label for="ArtistID">Artist ID:</label><br>
            <input type="number" name="ArtistID" required><br><br>

            <label for="name">New Name:</label><br>
            <input type="text" name="name" required><br><br>

            <label for="nickname">New nickname:</label><br>
            <input type="text" name="nickname" required><br><br>

            <label for="year">New Year:</label><br>
            <input type="text" name="year" required><br><br>

            <label for="country">New Country:</label><br>
            <input type="text" name="country" required><br><br>

            <button type="submit">Update Song</button>
        </form>
     </div>

      <div class="deleteartistByID">
       <h1>Delete an Artist by ID</h1>
       <form action="artist.php" method="post">
           <input type="hidden" name="action" value="delete">
           <label for="ArtistID">Artist ID:</label><br>
           <input type="number" name="ArtistID" required><br><br>

           <button type="submit">Delete Artist</button>
       </form>
    </div>

    
    
</body>
</html>