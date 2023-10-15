<?php
class DatabaseHelper
{
    /* Returns a connection object to a database */
    public static function createConnection($values = array())
    {
        $connString = $values[0];
        $user = $values[1];
        $password = $values[2];
        $pdo = new PDO($connString, $user, $password);
        $pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
        $pdo->setAttribute(
            PDO::ATTR_DEFAULT_FETCH_MODE,
            PDO::FETCH_ASSOC
        );
        return $pdo;
    }
    /*
    Runs the specified SQL query using the passed connection and
    the passed array of parameters (null if none)
    */
    public static function runQuery($connection, $sql, $parameters)
    {
        $statement = null;
        // if there are parameters then do a prepared statement
        if (isset($parameters)) {
            // Ensure parameters are in an array
            if (!is_array($parameters)) {
                $parameters = array($parameters);
            }
            // Use a prepared statement if parameters
            $statement = $connection->prepare($sql);
            $executedOk = $statement->execute($parameters);
            if (!$executedOk)
                throw new PDOException;
        } else {
            // Execute a normal query
            // echo "<script>console.log('check point 1' );</script>";
            $statement = $connection->query($sql);
            // echo "<script>console.log('check point 2' );</script>";
            if (!$statement)
                throw new PDOException;
        }
        return $statement;
    }
}

class PageBuilder
{

    public $gallery = array();
    private $baseSQL = "SELECT * 
                        FROM songs 
                        LEFT OUTER JOIN artists ON songs.artist_id = artists.artist_id 
                        LEFT OUTER JOIN genres ON songs.genre_id = genres.genre_id ";

    public function __construct($connection)
    {
        $this->pdo = $connection;
        $this->gallery[0] = array(
            'title' => 'Musicool Introduction',
            'priority' => '0',
            'link' => null,
            'query' => '',
            'text' => '<p>
                           A music browsing site developed as an assignment for COMP 3512. Full development of this site was done
                           by Nathan DeBliek, original github <a href="">N473D</a>.
                       </p>'
        );
        $this->gallery[1] = array(
            'title' => 'Top Genres',
            'priority' => '1',
            'link' => null,
            'query' => 'SELECT genres.genre_name, count(songs.genre_id) as number_of_songs
                FROM songs
                OUTER JOIN genres ON songs.genre_id = genres.genre_id
                GROUP BY songs.genre_id
                ORDER BY count(songs.genre_id) DESC '
        );
        $this->gallery[2] = array(
            'title' => 'Top Artist',
            'priority' => '1',
            'link' => null,
            'query' => 'SELECT artists.artist_name, count(songs.artist_id)  as number_of_songs
                FROM songs
                OUTER JOIN artists ON songs.artist_id = artists.artist_id
                GROUP BY songs.artist_id
                ORDER BY count(songs.artist_id) DESC '
        );
        $this->gallery[3] = array(
            'title' => 'Wanna be hits',
            'priority' => '1',
            'link' => 'songSearchResults.php',
            'query' => 'ORDER BY popularity DESC '
        );
        $this->gallery[4] = array(
            'title' => 'One-hit Wonders',
            'priority' => '1',
            'link' => 'songSearchResults.php',
            'query' => 'GROUP BY songs.artist_id
                        HAVING count(songs.artist_id) = 1
                        ORDER BY popularity DESC '
        );
        $this->gallery[5] = array(
            'title' => 'Acoustic Harmony',
            'priority' => '1',
            'link' => 'songSearchResults.php',
            'query' => 'WHERE acousticness >= 40
                        GROUP BY songs.artist_id
                        ORDER BY duration DESC '
        );
        $this->gallery[6] = array(
            'title' => 'Join the Club',
            'priority' => '1',
            'link' => 'songSearchResults.php',
            'query' => 'WHERE danceability >= 80
                        ORDER BY (danceability*1.6+energy*1.4) DESC '
        );
        $this->gallery[7] = array(
            'title' => 'Running Away',
            'priority' => '1',
            'link' => 'songSearchResults.php',
            'query' => 'WHERE bpm >= 120 AND bpm <= 125
                        ORDER BY (energy*1.3+valence*1.6) DESC '
        );
        $this->gallery[8] = array(
            'title' => 'Study Hacks',
            'priority' => '1',
            'link' => 'songSearchResults.php',
            'query' => 'WHERE bpm >= 100 AND bpm <= 115 AND speechiness <= 20 AND speechiness >= 1
                        ORDER BY (acousticness*0.8)+(100-speechiness)+(100-valence) DESC '
        );
    }

    public function generateFeaturedList()
    {
        if(isset($_GET["id"])){
            if (is_null($this->gallery[$_GET["id"]]['link'])) {
                // echo "Special case";
            } else {
                $_SESSION["featured"] = $this->gallery[$_GET["id"]];
                header("Location: " . $this->gallery[$_GET["id"]]['link']);
            }
        }
        foreach ($this->gallery as $featured) {
            echo "<a href='?id=" . array_search($featured, $this->gallery) . "' class='cell col_Tab'>";
            echo "<div>";
            echo "<h2>" . $featured['title'] . "</h2>";
            echo "</div></a>";
        }
    }

    public function generateHeader($current)
    {
        ?>
        <header>
            <nav>
                <?php
                if ($current != 0) {
                    echo "<a href='home.php'>Home</a> <br/>";
                }
                if ($current != 1) {
                    echo "<a href='favSongs.php'>Favorites</a> <br/>";
                }
                if ($current != 2) {
                    echo "<a href='songSearch.php'>Search</a> <br/>";
                }
                ?>
            </nav>
            <h1>
                Musicool
            </h1>
        </header>
        <?php
    }
    public function generateFooter()
    {

    }

    public function getAll($query)
    {
        $sql = $this->baseSQL . $query . ";";
        $statement =
            DatabaseHelper::runQuery($this->pdo, $sql, null);
        return $statement->fetchAll();
    }

    public function getArtist()
    {
        $sql = "SELECT artists.artist_name, artists.artist_id, count(songs.artist_id)  as number_of_songs
                FROM songs
                OUTER JOIN artists ON songs.artist_id = artists.artist_id
                GROUP BY songs.artist_id
                ORDER BY count(songs.artist_id) DESC LIMIT 10;";
        $statement =
            DatabaseHelper::runQuery($this->pdo, $sql, null);
        return $statement->fetchAll();
    }

    public function getGenre()
    {
        $sql = "SELECT genres.genre_name, genres.genre_id, count(songs.genre_id) as number_of_songs
                FROM songs
                OUTER JOIN genres ON songs.genre_id = genres.genre_id
                GROUP BY songs.genre_id
                ORDER BY count(songs.genre_id) DESC LIMIT 10;";
        $statement =
            DatabaseHelper::runQuery($this->pdo, $sql, null);
        return $statement->fetchAll();
    }

    public function generateSongList($query)
    {
        $songs = $this->getAll($query);
        foreach ($songs as $song) {
            $namef = $song['title'];
            if (strlen($namef) > 25) {
                $namef = substr($namef, 0, 23) . "&hellip;";
            }
            echo "<div><a href='song.php?song_id=" . $song['song_id'] . "'><h4>" . $namef . "</h4></a></div>";
            echo "<div><a href=''><h4>" . $song['artist_name'] . "</h4></a></div>";
            echo "<div><a href=''><h4>" . $song['year'] . "</h4></a></div>";
            echo "<div><a href=''><h4>" . $song['genre_name'] . "</h4></a></div>";
            echo "<div><a href='favSongs.php?song_id=" . $song['song_id'] . "'>like</a></div>";
            echo "<div><a href='song.php?song_id=" . $song['song_id'] . "'>link</a></div>";
        }
    }

    public function generateArtistList()
    {
        $artists = $this->getArtist();
        foreach ($artists as $artist) {
            $namef = $artist['artist_name'];
            if (strlen($namef) > 25) {
                $namef = substr($namef, 0, 23) . "&hellip;";
            }
            echo "<div><a href=''><h4>" . $namef . "</h4></a></div>";
            $namef = $artist['genre_name'];
            if (strlen($namef) > 25) {
                $namef = substr($namef, 0, 23) . "&hellip;";
            }
            echo "<div><a href=''><h4>" . $namef . "</h4></a></div>";
            echo "<div><a href=''><h4>" . $artist['number_of_songs'] . "</h4></a></div>";
        }
    }

    public function generateGenreList()
    {
        $genres = $this->getGenre();
        foreach ($genres as $genre) {
            $namef = $genre['genre_name'];
            if (strlen($namef) > 25) {
                $namef = substr($namef, 0, 23) . "&hellip;";
            }
            echo "<div><a href=''><h4>" . $namef . "</h4></a></div>";
            echo "<div><a href=''><h4>" . $genre['number_of_songs'] . "</h4></a></div>";
        }
    }

    public function generateFavsList($favorites)
    {
        $query = "WHERE song_id IN (" . implode(", ", $favorites) . " )";
        $songs = $this->getAll($query);
        foreach ($songs as $song) {
            $namef = $song['title'];
            if (strlen($namef) > 25) {
                $namef = substr($namef, 0, 23) . "&hellip;";
            }
            echo "<div><a href=''><h4>" . $namef . "</h4></a></div>";
            echo "<div><a href=''><h4>" . $song['artist_name'] . "</h4></a></div>";
            echo "<div><a href=''><h4>" . $song['year'] . "</h4></a></div>";
            echo "<div><a href=''><h4>" . $song['genre_name'] . "</h4></a></div>";
            echo "<div><a href='favSongs.php?song_id_rm=" . $song['song_id'] . "'>like</a></div>";
            echo "<div><a href='song.php?song_id=" . $song['song_id'] . "'>link</a></div>";
        }
    }
}

?>