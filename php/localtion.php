<?php
header("content-type: text/html; charset=utf-8");
header("Expires: Wed, 21 Aug 2013 13:13:13 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

// $servername = "localhost";
// $username = "cheetah_staff";
// $password = "Z9yT]}Kiqd.x";
// $dbname = "cheetah_thailand";
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "thailand";
$conn = mysql_connect($servername, $username, $password);

mysql_query("SET NAMES 'utf8'");
mysql_select_db($dbname);
if (!$conn) die("ไม่สามารถติดต่อกับฐานข้อมูลได้");

mysql_select_db($dbname, $conn) or die("ไม่สามารถเลือกใช้งานฐานข้อมูลได้");

$data = $_GET['data'];
$val = $_GET['val'];

if ($data == 'province') {
    echo "<option value=\"\">- เลือกจังหวัด -</option>\n";
    $result = mysql_query("SELECT * FROM province ORDER BY PROVINCE_NAME");
    while ($row = mysql_fetch_array($result)) {
        echo "<option value='$row[PROVINCE_ID]' >$row[PROVINCE_NAME]</option>\n";
    }
} 
else if ($data == 'amphur') {
    echo "<option value=\"\">- เลือกอำเภอ -</option>\n";
    $result = mysql_query("SELECT * FROM amphur WHERE PROVINCE_ID= '$val' ORDER BY AMPHUR_NAME");
    while ($row = mysql_fetch_array($result)) {
        echo "<option value=\"$row[AMPHUR_ID]\" >$row[AMPHUR_NAME]</option>\n";
    }
} 
else if ($data == 'district') {
    echo "<option value=\"\">- เลือกตำบล -</option>\n";
    $result = mysql_query("SELECT * FROM district WHERE AMPHUR_ID= '$val' ORDER BY DISTRICT_NAME");
    while ($row = mysql_fetch_array($result)) {
        echo "<option value=\"$row[DISTRICT_ID]\" >$row[DISTRICT_NAME]</option>\n";
    }
}

echo mysql_error();
mysql_close($conn);
?>