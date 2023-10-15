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
if (isset($_GET['all'])) {
    unset($_SESSION['featured']);
    header("Location: songSearchResults.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Musico Browser Home</title>
    <!-- <link rel="stylesheet" href="style/default.css?v=1" /> -->
    <link rel="stylesheet" href="style/default.css" />
    <!--<link rel="stylesheet" 
        href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    -->
    <!-- <link rel="stylesheet" href="ch05-proj1.css" /> -->
</head>
<?php $builder->generateHeader(3); ?>

<body>
    <?php
    $query = "";
    $search = "";
    if (isset($_GET['title'])) {
        if ($_GET['title'] != '') {
            $query = "WHERE title like '%" . $_GET['title'] . "%' ";
            // echo "<script>console.log('check point title' );</script>";
            $search = "Title with " . $_GET['title'];
        }
    }
    if (isset($_GET['year'])) {
        // echo "<script>console.log('check point genre' );</script>";
        if ($_GET['year'] != '') {
            if (isset($_GET['old'])) {
                $query = "WHERE year  <=" . $_GET['year'] . " ORDER BY year DESC";
                $search = "Older than " . $_GET['year'];
            } else {
                $query = "WHERE year  >=" . $_GET['year'] . " ORDER BY year";
                $search = "Newer than " . $_GET['year'];
            }
        }
    }
    if (isset($_GET['artist'])) {
        $query = "WHERE songs.artist_id=" . $_GET['artist'] . " ";
        // echo "<script>console.log('check point artist' );</script>";
        $search = "Artist";
    }
    if (isset($_GET['genre'])) {
        $query = "WHERE songs.genre_id=" . $_GET['genre'] . " ";
        $search = "Genre";
        // echo "<script>console.log('check point genre' );</script>";
    }


    if (isset($_SESSION["featured"])) {
        echo "<h1> Listing " . $_SESSION["featured"]['title'] . "</h1>";
    }
    if (isset($_GET["title"])) {
        echo "<h1> Searching for " . $search . "</h1>";
    }
    ?>
    <a href='?all=true'> Show all </a>
    <section class='listings'>
        <div>
            <h3>Title</h3>
        </div>
        <div>
            <h3>Artist</h3>
        </div>
        <div>
            <h3>Year</h3>
        </div>
        <div>
            <h3>Genre</h3>
        </div>
        <div>
            <h3>Popularity</h3>
        </div>
        <div>
            <h3>Liked</h3>
        </div>
        <div>
            <h3>View</h3>
        </div>
        <?php
        if (isset($_SESSION["featured"])) {
            $builder->generateSongList($_SESSION["featured"]["query"] . " LIMIT 10");
        } else {
            $builder->generateSongList($query);
        }
        ?>
    </section>
</body>
<?php $builder->generateFooter(); ?>

</html>