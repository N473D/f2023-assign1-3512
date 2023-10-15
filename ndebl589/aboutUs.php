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
    <link rel="stylesheet" href="style/default.css" />
    <!--<link rel="stylesheet" 
        href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    -->
    <!-- <link rel="stylesheet" href="ch05-proj1.css" /> -->
</head>
<?php $builder->generateHeader(6); ?>

<body>
    <h1>Musicool About</h1>
    <p>
        A music browser assignment for COMP 3512 created by <a href="https://github.com/N473D/f2023-assign1-3512">N473D</a>
        but you can also call me Nathan DeBliek. I took this on alone with the hopes of challenging myself and really diving back into
        web development projects. Although I may not currently have met my imagined attempting to make my site look like I hope to get it there.
        The problem with imagining is the fact that you can't hook your imagination up to a get things made instantly machine. and as much as I started
        off styling this thing with big hopes I may have gotten on a tangent and had to rework changes I made to the style and structure in favor of
        currently obtaining full functionality. Eventually I will figre out and be able to use all the components I need to achive the drawer like
        home page I wanted, the biggest issue is all the ways I could see of adding it when into new territories around JS and other libraries I
        wasn't prepared to use. Ultimatle I hope eventualy I can turn this into a site that reflects how Musicool.
    </p>
</body>
<?php $builder->generateFooter(); ?>

</html>