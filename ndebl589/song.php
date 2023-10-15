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
    <div class='info'>
        <?php
        if (isset($song['title'])) {
            echo "<div><h1>" . $song['title'] . "</h1></div>";
            echo "<div><p>" . $song['loudness'] . " dB</p></div>";
            echo "<div><h2>" . $song['artist_name'] . "</h2></div>";
            echo "<div><p>" . $song['energy'] . "% Energy</p></div>";
            echo "<div><h3>" . $song['popularity'] . "% Rating</h3></div>";
            echo "<div><p>" . $song['danceability'] . "% Danceability</p></div>";
            echo "<div><h4>" . $song['year'] . "</h4></div>";
            echo "<div><p>" . $song['liveness'] . " % Liveness</p></div>";
            echo "<div><h4>" . $song['genre_name'] . "</h4></div>";
            echo "<div><p>" . $song['valence'] . "% Positive Vibs</p></div>";
            echo "<div><p>" . floor(($song['duration'] / 60) % 60) . ":" . ($song['duration'] % 60) . "</p></div>";
            echo "<div><p>" . $song['acousticness'] . "% Acousticness </p></div>";
            echo "<div><p>" . $song['bpm'] . " BPM</p></div>";
            echo "<div><p>" . $song['speechiness'] . "% Wordiness</p></div>";
        } else {
            echo "<h1> NO SONG SELECTED </h1>";
        }
        ?>
    </div>
</body>
<?php $builder->generateFooter(); ?>

</html>