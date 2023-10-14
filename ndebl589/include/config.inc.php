<?php
define('DBHOST', 'localhost');
define('DBNAME', 'music');
define('DBUSER', 'root');
define('DBPASS', '');
// define('DBCONNSTRING',"mysql:host=" . DBHOST . ";dbname=" . DBNAME .
// ";charset=utf8mb4;");
define('DBCONNSTRING','sqlite:./data/music.db');
?>