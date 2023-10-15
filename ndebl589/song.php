<?php
require_once 'include/config.inc.php';
require_once 'include/musicLoaderClasses.inc.php';

$conn = DatabaseHelper::createConnection(
    array(
        DBCONNSTRING,
        DBUSER,
        DBPASS
    )
);
$builder = new PageBuilder($conn);
if (isset($_GET['song_id'])) {
    $song = $builder->getAll("WHERE song_id=" . $_GET['song_id'])[0];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Musico Browser Home</title>
    <link rel="stylesheet" href="style/default.css" />
    <!--<link rel="stylesheet" 
        href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    -->
    <!-- <link rel="stylesheet" href="ch05-proj1.css" /> -->
</head>
<?php $builder->generateHeader(4); ?>
<body>
    <?php
        if (isset($song['title'])) {
            echo "<h1>" . $song['title'] . "</h1>";
            echo "<h2>" . $song['artist_name'] . "</h2>";
            echo "<h3>" . $song['year'] . "</h3>";
            echo "<h3>" . $song['genre_name'] . "</h3>";
            echo "<h4>" . $song['popularity'] . "% Rating</h4>";
            echo "<p>" . floor(($song['duration'] / 60) % 60) . ":" . ($song['duration'] % 60) . "</p>";
            echo "<p>" . $song['bpm'] . " BPM</p>";
            echo "<p>" . $song['energy'] . "% Energy</p>";
            echo "<p>" . $song['danceability'] . "% Danceability</p>";
            echo "<p>" . $song['loudness'] . " dB</p>";
            echo "<p>" . $song['liveness'] . " % Liveness</p>";
            echo "<p>" . $song['valence'] . "% Positive Vibs</p>";
            echo "<p>" . $song['acousticness'] . "% Acousticness </p>";
            echo "<p>" . $song['speechiness'] . "% Wordiness</p>";
        } else {
            echo "<h1> NO SONG SELECTED </h1>";
        }
    ?>
</body>
<footer>
    <?php $builder->generateFooter(); ?>
</footer>

</html>