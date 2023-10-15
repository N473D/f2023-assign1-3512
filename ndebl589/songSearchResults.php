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
    <?php
    if (isset($_SESSION["featured"])) {
    } else if (isset($_SESSION["search"])) {
    }
    ?>
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
            <h3>Liked</h3>
        </div>
        <div>
            <h3>View</h3>
        </div>
        <?php
    if (isset($_SESSION["featured"])) {
        $builder->generateSongList($_SESSION["featured"]["query"] . " LIMIT 10");
    } else if (isset($_SESSION["search"])) {
        $builder->generateSongList($_SESSION["query"] . ";");
    } else {
        $builder->generateSongList("");
    }
    ?>
    </section>
</body>
<footer>
    <?php $builder->generateFooter(); ?>
</footer>

</html>