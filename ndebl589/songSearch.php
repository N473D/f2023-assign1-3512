<?php
session_start();
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
// if (isset($_GET['submit'])) {
//     if (isset($_GET['title'])) {
//         $_SESSION['title'] = $_GET['title'];
//         $_SESSION['search'] = 'Title Search: ';
//     } else if (isset($_GET['artist'])) {
//         $_SESSION['artist'] = $_GET['artist'];
//         $_SESSION['search'] = 'Artist Search: ';
//     } else if (isset($_GET['genre'])) {
//         $_SESSION['genre'] = $_GET['genre'];
//         $_SESSION['search'] = 'Genre Search: ';
//     } else if (isset($_GET['year']) and (isset($_GET['old']) OR isset($_GET['new']) ) ) {
//         $_SESSION['year'] = $_GET['year'];
//         $_SESSION['time'] = $_GET['old'];
//         $_SESSION['time'] = $_GET['new'];
//         $_SESSION['search'] = 'Year Search: ';
//     } else {
//         $_SESSION['search'] = 'List of Songs';
//     }
//     echo "<script>console.log('check point submited' );</script>";
//     header("Location: songSearchResults.php");
// }
unset($_SESSION['featured']);
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
<?php $builder->generateHeader(2); ?>
<body>
    <form action="songSearchResults.php" method="get">
        <fieldset>
            <legend>
                Search Songs
            </legend>
            <div>
                <label for="title">Title</label>
                    <input type="text" name="title" id="title" placeholder="Enter Song Title">
            </div>
            <div>
                <label for="artist">Artist</label>
                    <select name="artist" id="artist">
                        <option disabled selected value> Choose an Artist </option>
                        <?php
                        echo "<script>console.log('check point 1' );</script>";
                        $artists = $builder->getArtist("GROUP BY songs.artist_id ORDER BY artist_name");
                        // echo "<script>console.log('check point 1' );</script>";
                        foreach ($artists as $artist) {
                            echo "<option value='" . $artist['artist_id'] . "'>" . $artist['artist_name'] . "</option>";
                        }
                        // echo "<script>console.log('check point 1' );</script>";
                        ?>
                    </select>
            </div>
            <div>
                <label for="genre">Genre</label>
                    <select name="genre" id="genre">
                        <option disabled selected value> Choose a Genre </option>
                        <?php
                        $genres = $builder->getGenre("GROUP BY songs.genre_id ORDER BY genre_name");
                        foreach ($genres as $genre) {
                            echo "<option value='" . $genre['genre_id'] . "'>" . $genre['genre_name'] . "</option>";
                        }
                        ?>
                    </select>
            </div>
            <div>
                <label for="old">Older Than</label>
                <input type="radio" name="old" id="old" value="<">
                <label for="new">Newer Than</label>
                <input type="radio" name="new" id="new" value=">">
                <label for="year">Year</label>
                <input type="text" name="year" id="year" placeholder="Enter Song Year">
            </div>
            <div>
                <input type="submit" value="submit">
            </div>
        </fieldset>

    </form>
</body>
<?php $builder->generateFooter(); ?>

</html>