<?php

require_once "Database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action'] === 'update') {
        // the code blok below hecks to see if you click the update button
        $SongId = htmlspecialchars($_POST['SongId']);
        $name = htmlspecialchars($_POST['name']);
        $year = htmlspecialchars($_POST['year']);
        $genre = htmlspecialchars($_POST['genre']);

        // if the inputs are not empty then we call the function 
        if (!empty($SongId) && !empty($name) && !empty($genre) && is_numeric($year)) {
            $songUpdate = new Songs();
            $songUpdate->UpdateSongById($SongId, $name, $year, $genre);
        } else {
            echo "Please fill in all fields correctly.";
        }
    } 
    elseif($_POST['action'] === 'delete'){
        $SongId = htmlspecialchars($_POST['SongId']);
        //if its not empty
        if(!empty($SongId)){
            $songDelete = new Songs();
            $songDelete->deleteSongById($SongId);
        }else{
             echo "Please fill in all fields correctly.";
        }
    }
    else {
        // Insert logic
        $name = htmlspecialchars($_POST['name']);
        $year = htmlspecialchars($_POST['year']);
        $genre = htmlspecialchars($_POST['genre']);

        if (!empty($name) && !empty($genre) && is_numeric($year)) {
            $songInsert = new Songs();
            $songInsert->insertSong($name, $year, $genre);
        }
    }
}


class Songs{

    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function insertSong($name, $year, $genre){
    try{
        $query = "INSERT INTO songs(name, year, genre) VALUES (:name, :year, :genre)";
        $inProcess = $this->db->pdo->prepare($query);
        $inProcess->bindParam(":name", $name);
        $inProcess->bindParam(":year", $year);
        $inProcess->bindParam(":genre", $genre);
        $inProcess->execute();
        echo "Song Added";
    } catch (PDOException $e){
        if ($e->getCode() == 23000) { // SQLSTATE code for integrity constraint violation
            echo "Duplicate song entry is not allowed!";
        } else {
            echo "Song not added: " . $e->getMessage();
        }
    }
}


   public function showAllSongs(){
    try {
        // Use GROUP BY to fetch only unique rows
        $sqlQuery = "SELECT * FROM songs";
        $inProcess = $this->db->pdo->query($sqlQuery);

        // Making a table
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%; text-align: left;'>";
        echo "<tr>
                <th>SongId</th>
                <th>Name</th>
                <th>Year</th>
                <th>Genre</th>
              </tr>";

        // Fetch rows and populate the table
        while($row = $inProcess->fetch(PDO::FETCH_ASSOC)){
            echo "<tr>
                    <td>{$row['SongId']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['year']}</td>
                    <td>{$row['genre']}</td>
                  </tr>";
        }

        // End the table
        echo "</table>";
    } catch (PDOException $e) {
        echo "Error fetching songs: " . $e->getMessage();
    }
}

   public function UpdateSongById($SongId,$name,$year,$genre){
        $sqlQuery = "UPDATE songs SET name = :name, year = :year, genre = :genre WHERE SongId = :SongId";
        $inProcess = $this->db->pdo->prepare($sqlQuery);
        
        //parameter verbinding
        $inProcess->bindParam(':SongId',$SongId);
        $inProcess->bindParam(":name",$name);
        $inProcess->bindParam(":year",$year);
        $inProcess->bindParam(":genre",$genre);

        if($inProcess->execute()){
            echo "Song sucessfully updated";
        }
        else{
            echo "Mistake by updating a song";
        }
   }

   public function deleteSongById($SongId){
      $sqlQuery = "DELETE FROM songs WHERE SongId= :SongId";
      $inProcess = $this->db->pdo->prepare($sqlQuery);
      $inProcess->bindParam(":SongId",$SongId);

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
    <title>Songs</title>
    <link rel="stylesheet" href="../functionstyles/songs.css">
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



     <div class="addsong">
          <h1>Add a Song</h1>

          <form action="songs.php" method="post">
               <label for="name" name="name">Name of the Song :</label><br>
               <input type="text" name="name"><br><br>
               <label for="year" name="year">Year:</label><br>
               <input type="number" name="year"><br><br>
               <label for="genre" name="genre">Genre:</label><br>
               <input type="text" name="genre"><br><br>
               <button type="submit">Add Song</button>
          </form>

     </div>

     <div class="showsongs">
        <h1>List of Songs</h1>
         <?php
            $songs = new Songs();
            $songs->showAllSongs();
         ?>
     </div>

     <div class="updatesongByID">
        <h1>Update a Song By ID</h1>
        <form action="songs.php" method="post">
            <input type="hidden" name="action" value="update"> 
            <label for="SongId">Song ID:</label><br>
            <input type="number" name="SongId" required><br><br>

            <label for="name">New Name:</label><br>
            <input type="text" name="name" required><br><br>

            <label for="year">New Year:</label><br>
            <input type="number" name="year" required><br><br>

            <label for="genre">New Genre:</label><br>
            <input type="text" name="genre" required><br><br>

            <button type="submit">Update Song</button>
        </form>
    </div>

    <div class="deletesongByID">
       <h1>Delete a Song by ID</h1>
       <form action="songs.php" method="post">
           <input type="hidden" name="action" value="delete">
           <label for="SongId">Song ID:</label><br>
           <input type="number" name="SongId" required><br><br>

           <button type="submit">Delete Song</button>
       </form>
    </div>



    
</body>
</html>