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
        // $this->gallery[0] = array(
        //     'title' => 'Musicool Introduction',
        //     'priority' => '0',
        //     'link' => null,
        //     'query' => '',
        //     'text' => '<p>
        //                    A music browsing site developed as an assignment for COMP 3512. Full development of this site was done
        //                    by Nathan DeBliek, original github <a href="">N473D</a>.
        //                </p>'
        // );
        $this->gallery[0] = array(
            'title' => 'Top Genres',
            'priority' => '1',
            'link' => null,
            'query' => 'GROUP BY songs.genre_id
                        ORDER BY count(songs.genre_id) DESC LIMIT 10'
        );
        $this->gallery[1] = array(
            'title' => 'Top Artist',
            'priority' => '1',
            'link' => null,
            'query' => 'GROUP BY songs.artist_id
                        ORDER BY count(songs.artist_id) DESC  LIMIT 10'
        );
        $this->gallery[2] = array(
            'title' => 'Wanna be hits',
            'priority' => '1',
            'link' => 'songSearchResults.php',
            'query' => 'ORDER BY popularity DESC '
        );
        $this->gallery[3] = array(
            'title' => 'One-hit Wonders',
            'priority' => '1',
            'link' => 'songSearchResults.php',
            'query' => 'GROUP BY songs.artist_id
                        HAVING count(songs.artist_id) = 1
                        ORDER BY popularity DESC '
        );
        $this->gallery[4] = array(
            'title' => 'Acoustic Harmony',
            'priority' => '1',
            'link' => 'songSearchResults.php',
            'query' => 'WHERE acousticness >= 40
                        GROUP BY songs.artist_id
                        ORDER BY duration DESC '
        );
        $this->gallery[5] = array(
            'title' => 'Join the Club',
            'priority' => '1',
            'link' => 'songSearchResults.php',
            'query' => 'WHERE danceability >= 80
                        ORDER BY (danceability*1.6+energy*1.4) DESC '
        );
        $this->gallery[6] = array(
            'title' => 'Running Away',
            'priority' => '1',
            'link' => 'songSearchResults.php',
            'query' => 'WHERE bpm >= 120 AND bpm <= 125
                        ORDER BY (energy*1.3+valence*1.6) DESC '
        );
        $this->gallery[7] = array(
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
            echo "<div class='cell col_Tab'><a href='?id=" . array_search($featured, $this->gallery) . "' >";
            echo "<div>";
            echo "<h2>" . $featured['title'] . "</h2>";
            if(array_search($featured, $this->gallery) == 0) {
                echo "<div class='alter'>";
                $this->generateGenreList($this->gallery[0]['query']);
                echo "</div>";
            } else if(array_search($featured, $this->gallery) == 1) {
                echo "<div class='alter'>";
                $this->generateArtistList($this->gallery[1]['query']);
                echo "</div>";
            }
            echo "</div></a></div>";
        }
    }

    public function generateHeader($current)
    {
        ?>
        <header>
            <h1>
                COMP 3512 Assign 1 (Musicool)
            </h1>
            <nav>
                <?php
                if ($current != 0) {
                    echo "<h3><a href='home.php'>Home</a></h3>";
                }
                if ($current != 1) {
                    echo "<h3><a href='favSongs.php'>Favorites</a></h3>";
                }
                if ($current != 2) {
                    echo "<h3><a href='songSearch.php'>Search</a></h3>";
                }
                if ($current != 3) {
                    echo "<h3><a href='aboutUs.php'>About Us</a></h3>";
                }
                ?>
            </nav>
        </header>
        <?php
    }
    public function generateFooter()
    {
        echo "<footer><p>
                COMP 3512 <br/> Nathan DeBliek &copy; <br/> <a href='https://github.com/N473D/f2023-assign1-3512'>Github</a>
                </p></footer>";
    }

    public function getAll($query)
    {
        $sql = $this->baseSQL . $query . ";";
        $statement =
            DatabaseHelper::runQuery($this->pdo, $sql, null);
        return $statement->fetchAll();
    }

    public function getArtist($query)
    {
        // echo "<script>console.log('check point 1' );</script>";
        $sql = "SELECT artists.artist_name, artists.artist_id, count(songs.artist_id)  as number_of_songs
                FROM artists
                LEFT OUTER JOIN songs ON songs.artist_id = artists.artist_id " . $query;
        $statement =
            DatabaseHelper::runQuery($this->pdo, $sql, null);
        // echo "<script>console.log('check point 1' );</script>";
        return $statement->fetchAll();
    }

    public function getGenre($query)
    {
        $sql = "SELECT genres.genre_name, genres.genre_id, count(songs.genre_id) as number_of_songs
                FROM genres
                LEFT OUTER JOIN songs ON songs.genre_id = genres.genre_id " . $query;
        $statement =
            DatabaseHelper::runQuery($this->pdo, $sql, null);
        // echo "<script>console.log('check point 1' );</script>";
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
            echo "<div><a href='songSearchResults.php?title=&artist=" . $song['artist_id'] . "'><h4>" . $song['artist_name'] . "</h4></a></div>";
            echo "<div><a href='songSearchResults.php?title=&year=" . $song['year'] . "'><h4>" . $song['year'] . "</h4></a></div>";
            echo "<div><a href='songSearchResults.php?title=&genre=" . $song['genre_id'] . "'><h4>" . $song['genre_name'] . "</h4></a></div>";
            echo "<div><h4>" . $song['popularity'] . "</h4></div>";
            echo "<div><a href='favSongs.php?song_id=" . $song['song_id'] . "' class='favorite not'> &#9825; </a></div>";
            echo "<div><a href='song.php?song_id=" . $song['song_id'] . "' class='linkIcon'> View More</a></div>";
        }
        unset($_SESSION['featured']);
    }

    public function generateArtistList($query)
    {
        $artists = $this->getArtist($query);
        foreach ($artists as $artist) {
            $namef = $artist['artist_name'];
            if (strlen($namef) > 25) {
                $namef = substr($namef, 0, 23) . "&hellip;";
            }
            echo "<div><a href=''><h4>" . $namef . " - " . $artist['number_of_songs'] . " Songs</h4></div>";
        }
    }

    public function generateGenreList($query)
    {
        $genres = $this->getGenre($query);
        foreach ($genres as $genre) {
            $namef = $genre['genre_name'];
            if (strlen($namef) > 25) {
                $namef = substr($namef, 0, 23) . "&hellip;";
            }
            echo "<div><a href=''><h4>" . $namef . " - " . $genre['number_of_songs'] . " Songs</h4></a></div>";
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
            echo "<div><a href='song.php?song_id=" . $song['song_id'] . "'><h4>" . $namef . "</h4></a></div>";
            echo "<div><a href='songSearchResults.php?title=&artist=" . $song['artist_id'] . "'><h4>" . $song['artist_name'] . "</h4></a></div>";
            echo "<div><a href='songSearchResults.php?title=&year=" . $song['year'] . "'><h4>" . $song['year'] . "</h4></a></div>";
            echo "<div><a href='songSearchResults.php?title=&genre=" . $song['genre_id'] . "'><h4>" . $song['genre_name'] . "</h4></a></div>";
            echo "<div><h4>" . $song['popularity'] . "</h4></div>";
            echo "<div><a href='favSongs.php?song_id_rm=" . $song['song_id'] . "' class='favorite'> &#9825; </a></div>";
            echo "<div><a href='song.php?song_id=" . $song['song_id'] . "'class='linkIcon'>View More</a></div>";
        }
    }
}

?>