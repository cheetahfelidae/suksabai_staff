<?php
date_default_timezone_set("Asia/Bangkok");
// $servername = "localhost";
// $username = "cheetah_staff";
// $password = "Z9yT]}Kiqd.x";
// $dbname = "cheetah_suksabai";
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "suksabai";

function connDB()
{
    global $mysqli;
    global $servername;
    global $username;
    global $password;
    global $dbname;

    $mysqli = new mysqli($servername, $username, $password, $dbname);

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }

    // set characters set
    $mysqli->set_charset('utf8');
}

function closeDB()
{
    global $mysqli;

    $mysqli->close();
}

?>