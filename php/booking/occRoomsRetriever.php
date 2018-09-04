<?php
header('Content-Type: text/plain; charset=UTF-8');
include "../configuration.php";
connDB();

$dates = json_decode(file_get_contents('php://input'), true);

/* Prepared statement, stage 1: prepare */
if (!($stmt = $mysqli->prepare("SELECT roomNum, checkinDate, checkinTime, checkoutDate, addDate FROM Bookings WHERE checkinDate >= ? AND checkinDate <= ? ORDER BY checkinDate, roomNum"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

/* Prepared statement, stage 2: bind and execute */
if (!$stmt->bind_param("ss", $startDate, $endDate)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}

// set parameters and execute
$startDate = $dates["start"];
$endDate = $dates["end"];
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}

$out_num = NULL;
$out_checkinDate = NULL;
$out_checkinTime = NULL;
$out_checkoutDate = NULL;
$out_addDate = NULL;
if (!$stmt->bind_result($out_num, $out_checkinDate, $out_checkinTime, $out_checkoutDate, $out_addDate)) {
    echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}

$temp = array();

// get results and convert to fullcalendar format
while ($stmt->fetch()) {
    $datetime = new DateTime($out_addDate);

    array_push($temp, array('title' => $out_num, 'checkinTime' => $out_checkinTime, 'start' => $out_checkinDate, 'end' => $out_checkoutDate, "addDate" => $datetime->format('H:i:s d-m-Y'),));
}

if (!empty($temp)) {
    echo json_encode($temp);
} else {
    echo "json is empty";
}

/* explicit close recommended */
$stmt->close();

/* close connection */
$mysqli->close();
?>