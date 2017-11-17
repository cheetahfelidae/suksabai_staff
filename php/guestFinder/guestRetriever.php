<?php
header('Content-Type: text/plain; charset=UTF-8');
include "../configuration.php";
connDB();
$name = json_decode(file_get_contents('php://input'), true);
/* Prepared statement, stage 1: prepare */
if (!($stmt = $mysqli->prepare("SELECT * FROM Guests WHERE firstName = ? AND lastName = ?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
/* Prepared statement, stage 2: bind and execute */
if (!$stmt->bind_param("ss", $firstName, $lastName)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
// set parameters and execute
$firstName = $name["firstName"];
$lastName = $name["lastName"];
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
$out_id = NULL;
$out_title = NULL;
$out_firstName = NULL;
$out_lastName = NULL;
$out_email = NULL;
$out_tel = NULL;
$out_address = NULL;
$out_district = NULL;
$out_amphur = NULL;
$out_province = NULL;
$out_taxNumber = NULL;
$out_otherDetails = NULL;
$out_regDate = NULL;
if (!$stmt->bind_result($out_id, $out_title, $out_firstName, $out_lastName, $out_email, $out_tel, $out_address, $out_district, $out_amphur, $out_province, $out_taxNumber, $out_otherDetails, $out_regDate)) {
    echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
$temp = [];
// get result and convert to fullcalendar format
if ($stmt->fetch()) {
    $temp = array('id' => $out_id, 'title' => $out_title, 'firstName' => $out_firstName, 'lastName' => $out_lastName, 'email' => $out_email, 'tel' => $out_tel, 'address' => $out_address, 'district' => $out_district, 'amphur' => $out_amphur, 'province' => $out_province, 'taxNumber' => $out_taxNumber, 'otherDetails' => $out_otherDetails, 'regDate' => $out_regDate);
}
if (!empty($temp)) {
    echo json_encode($temp);
}
else {
    echo "json is empty";
}
/* explicit close recommended */
$stmt->close();
/* close connection */
$mysqli->close();
?>