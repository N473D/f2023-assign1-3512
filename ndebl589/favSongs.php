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
// $_SESSION["favorites"] = [];
if (isset($_GET["song_id"])) {
    if (!isset($_SESSION["favorites"])) {
        $_SESSION["favorites"] = [];
    }
    if (!in_array($_GET["song_id"], $_SESSION["favorites"])) {
        $favorites = $_SESSION["favorites"];
        $favorites[] = $_GET["song_id"];
        $_SESSION["favorites"] = $favorites;
    }
    header("Location: favSongs.php");
}

if (isset($_GET["song_id_rm"])) {
    // echo "<script>console.log('check point 1' );</script>";
    if (isset($_SESSION["favorites"])) {
        // echo "<script>console.log('check point 2' );</script>";
        if (in_array($_GET["song_id_rm"], $_SESSION["favorites"])) { 
            // echo "<script>console.log('check point 3' );</script>";
            $favorites = $_SESSION["favorites"];
            unset($favorites[array_search($_GET["song_id_rm"], $favorites)]);
    
            $_SESSION["favorites"] = $favorites;
        }
    }
    header("Location: favSongs.php");
}

if (isset($_GET['all'])){
    unset($_SESSION["favorites"]);
}
$builder = new PageBuilder($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Musico Browser Home</title>
    <link rel="stylesheet" href="style/default.css?v=1" />
    <!-- <link rel="stylesheet" href="style/default.css" /> -->
    <!--<link rel="stylesheet" 
        href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    -->
    <!-- <link rel="stylesheet" href="ch05-proj1.css" /> -->
</head>
<?php $builder->generateHeader(3); ?>

<body>
    <h1> Favorites </h1>
    <a href='?all=true'> Remove all </a>
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
        if (isset($_SESSION["favorites"])) {
            $builder->generateFavsList($_SESSION["favorites"]);
        }
        ?>
    </section>
</body>
<?php $builder->generateFooter(); ?>

</html>