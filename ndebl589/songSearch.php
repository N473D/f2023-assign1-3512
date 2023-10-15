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
if (isset($_POST['search'])) {
    if (isset($_POST['title'])) {
        $_SESSION['title'] = $_POST['title'];
        $_SESSION['search'] = 'Title Search: ';
    } else if (isset($_POST['artist'])) {
        $_SESSION['artist'] = $_POST['artist'];
        $_SESSION['search'] = 'Artist Search: ';
    } else if (isset($_POST['genre'])) {
        $_SESSION['genre'] = $_POST['genre'];
        $_SESSION['search'] = 'Genre Search: ';
    } else if (isset($_POST['year'], $_POST['time'])) {
        $_SESSION['year'] = $_POST['year'];
        $_SESSION['time'] = $_POST['time'];
        $_SESSION['search'] = 'Year Search: ';
    } else {
        $_SESSION['search'] = 'List of Songs';
    }
    header("Location: songSearchResults.php");
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
<?php $builder->generateHeader(2); ?>

<body>
    <form method="post" action="">
        <fieldset>
            <legend>
                Search Songs
            </legend>
            <div>
                <label for="title">Title
                    <input type="text" name="title" placeholder="Enter Song Title">
                </label>
            </div>
            <div>
                <label for="artist">Artist
                    <select name="artist" id="artist">
                        <option disabled selected value> Choose an Artist </option>
                        <option value="canada">Canada</option>
                    </select>
                </label>
            </div>
            <div>
                <label for="genre">Genre
                    <select name="genre" id="genre">
                        <option disabled selected value> Choose a Genre </option>
                        <option value="canada">Canada</option>
                    </select>
                </label>
            </div>
            <div>
                <label for="time">
                    <input type="radio" name="time" value="<">Older Than
                    <input type="radio" name="time" value=">">Newer Than
                </label>
                <label for="year">Year <input type="text" name="year" placeholder="Enter Song Year"></label>
            </div>
            <div>
                <input type="submit" value="search">
            </div>
        </fieldset>

    </form>
</body>
<footer>
    <?php $builder->generateFooter(); ?>
</footer>

</html>