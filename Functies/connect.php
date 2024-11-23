<?php
require_once "Database.php";

class Connection {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Fetch artist details by ArtistID
    public function fetchArtistById($artistID) {
        try {
            $sqlQuery = "SELECT ArtistID, name FROM artist WHERE ArtistID = :artistID";
            $stmt = $this->db->pdo->prepare($sqlQuery);
            $stmt->execute(['artistID' => $artistID]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching artist: " . $e->getMessage();
            return [];
        }
    }

    // Fetch song details by SongID
    public function fetchSongById($songID) {
        try {
            $sqlQuery = "SELECT SongId, name FROM songs WHERE SongId = :songID";
            $stmt = $this->db->pdo->prepare($sqlQuery);
            $stmt->execute(['songID' => $songID]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching song: " . $e->getMessage();
            return [];
        }
    }

    // Render Artist Dropdown
    public function renderArtistDropdown() {
        $artists = $this->fetchAllNames();
        $dropdownHtml = "<select name='ArtistID' id='artistDropdown' required>";
        $dropdownHtml .= "<option value=''>--Select an Artist--</option>";

        if (!empty($artists)) {
            foreach ($artists as $artist) {
                $dropdownHtml .= "<option value='{$artist['ArtistID']}'>{$artist['name']}</option>";
            }
        } else {
            $dropdownHtml .= "<option value=''>No artists available</option>";
        }

        $dropdownHtml .= "</select>";
        return $dropdownHtml;
    }

    // Render Song Dropdown
    public function renderSongDropdown() {
        $songs = $this->fetchAllSongs();
        $dropdownHtml = "<select name='SongID' id='songDropdown' required>";
        $dropdownHtml .= "<option value=''>--Select a Song--</option>";

        if (!empty($songs)) {
            foreach ($songs as $song) {
                $dropdownHtml .= "<option value='{$song['SongId']}'>{$song['name']}</option>";
            }
        } else {
            $dropdownHtml .= "<option value=''>No songs available</option>";
        }

        $dropdownHtml .= "</select>";
        return $dropdownHtml;
    }

    // Method to insert the connection into the database
    public function connectArtistAndSong($artistID, $songID) {
        try {
            // Fetch the artist and song details
            $artist = $this->fetchArtistById($artistID);
            $song = $this->fetchSongById($songID);

            // If we get valid artist and song details, insert them into the artist_song table
            if ($artist && $song) {
                $sqlQuery = "INSERT INTO artist_song (ArtistID, SongID, artist_name, song_name) 
                             VALUES (:artistID, :songID, :artist_name, :song_name)";
                $stmt = $this->db->pdo->prepare($sqlQuery);
                $stmt->execute([
                    'artistID' => $artistID, 
                    'songID' => $songID, 
                    'artist_name' => $artist['name'], 
                    'song_name' => $song['name']
                ]);
                return true;
            } else {
                return false; // If artist or song not found
            }
        } catch (PDOException $e) {
            echo "Error connecting artist and song: " . $e->getMessage();
            return false;
        }
    }

    // Fetch all artists (for dropdown)
    public function fetchAllNames() {
        try {
            $sqlQuery = "SELECT ArtistID, name FROM artist";
            $inProcess = $this->db->pdo->query($sqlQuery);
            return $inProcess->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching names: " . $e->getMessage();
            return [];
        }
    }

    // Fetch all songs (for dropdown)
    public function fetchAllSongs() {
        try {
            $sqlQuery = "SELECT SongId, name FROM songs";
            $inProcess = $this->db->pdo->query($sqlQuery);
            return $inProcess->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching names: " . $e->getMessage();
            return [];
        }
    }

    public function fetchAllArtistSongs() {
    try {
        $sqlQuery = "SELECT * FROM artist_song";
        $stmt = $this->db->pdo->query($sqlQuery);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching artist-song data: " . $e->getMessage();
        return [];
    }
  }

// Method to render the table of artist-song connections
   public function renderArtistSongTable() {
    $artistSongs = $this->fetchAllArtistSongs();
    $tableHtml = "<table border='1'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ArtistID</th>
                            <th>SongID</th>
                            <th>Artist Name</th>
                            <th>Song Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>";

    if (!empty($artistSongs)) {
        foreach ($artistSongs as $row) {
            $tableHtml .= "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['ArtistID']}</td>
                            <td>{$row['SongID']}</td>
                            <td>{$row['artist_name']}</td>
                            <td>{$row['song_name']}</td>
                            <td>
                                <form action='connect.php' method='post' style='display:inline;'>
                                    <input type='hidden' name='delete_id' value='{$row['id']}'>
                                    <button type='submit' name='delete' style='background-color:black;color:white;border:none;padding:5px 10px;cursor:pointer;'>Delete</button>
                                </form>
                            </td>
                        </tr>";
        }
    } else {
        $tableHtml .= "<tr><td colspan='6'>No artist-song connections found</td></tr>";
    }

    $tableHtml .= "</tbody></table>";
    return $tableHtml;
}

// Method to delete a connection by ID
public function deleteArtistSongById($id) {
    try {
        $sqlQuery = "DELETE FROM artist_song WHERE id = :id";
        $stmt = $this->db->pdo->prepare($sqlQuery);
        $stmt->execute(['id' => $id]);
        return true;
    } catch (PDOException $e) {
        echo "Error deleting connection: " . $e->getMessage();
        return false;
    }
}


}

// Initialize the $connection object
$connection = new Connection();

// Handle form submission
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['connect'])) {
        // Connect artist and song logic
        $artistID = $_POST['ArtistID'] ?? null;
        $songID = $_POST['SongID'] ?? null;

        if ($artistID && $songID) {
            if ($connection->connectArtistAndSong($artistID, $songID)) {
                echo "<p>Connection successfully created!</p>";
            } else {
                echo "<p>Failed to create connection. Please check if both Artist and Song exist.</p>";
            }
        } else {
            echo "<p>Both Artist and Song must be selected.</p>";
        }
    } elseif (isset($_POST['delete'])) {
        // Delete artist-song connection logic
        $deleteId = $_POST['delete_id'] ?? null;

        if ($deleteId) {
            if ($connection->deleteArtistSongById($deleteId)) {
                echo "<p>Connection successfully deleted!</p>";
            } else {
                echo "<p>Failed to delete connection.</p>";
            }
        } else {
            echo "<p>Invalid connection ID.</p>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect Music</title>
    <link rel="stylesheet" href="../functionstyles/connect.css">
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


    <div class="table">
        <h1>Connect an Artist with a Song</h1>
        <form action="connect.php" method="post">
            <!-- Artist Dropdown -->
            <label for="artistDropdown">Choose an Artist:</label><br>
            <?php echo $connection->renderArtistDropdown(); ?>
            <br><br>

            <!-- Song Dropdown -->
            <label for="songDropdown">Choose a Song:</label><br>
            <?php echo $connection->renderSongDropdown(); ?>
            <br><br>

            <!-- Submit Button -->
            <button type="submit" name="connect">Connect</button>
        </form>
    </div>

    <div class="table-artist">
       <h1>Artist-Song Connections</h1>
        <?php echo $connection->renderArtistSongTable(); ?>
    </div>

    <div class="deleteartistByID">

    </div>
</body>
</html>
