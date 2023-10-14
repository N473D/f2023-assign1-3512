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
<?php $builder->generateHeader(0); ?>
<body>
    <section>
        <h1>Musicool Home</h1>
        <p>
            A music browsing site developed as an assignment for COMP 3512. Full development of this site was done
            by Nathan DeBliek, original github <a href="">N473D</a>.
        </p>
    </section>
    <section class="gallery">
        <?php $builder->generateFeaturedList(); ?>
    </section>
</body>
<footer>
    <?php $builder->generateFooter(); ?>
</footer>

</html>