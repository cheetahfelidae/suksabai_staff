<?php
header('Content-Type: text/plain; charset=UTF-8');
include "../configuration.php";
connDB();

$dates = json_decode(file_get_contents('php://input'), true);

/* Prepared statement, stage 1: prepare */
if (!($stmt = $mysqli->prepare("SELECT checkinDate, roomNum, roomPrice, breakfastPrice, extraBedPrice  FROM Bookings WHERE checkinDate >= ? AND checkinDate <= ? ORDER BY checkinDate, roomNum"))) {
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

$out_checkinDate = NULL;
$out_roomNum = NULL;
$out_roomPrice = NULL;
$out_breakfastPrice = NULL;
$out_extraBedPrice = NULL;
if (!$stmt->bind_result($out_checkinDate, $out_roomNum, $out_roomPrice, $out_breakfastPrice, $out_extraBedPrice)) {
    echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}

$temp = array();

while ($stmt->fetch()) {
    array_push($temp, array('checkinDate' => $out_checkinDate, 'roomNums' => $out_roomNum, 'roomPrice' => $out_roomPrice, 'breakfastPrice' => $out_breakfastPrice, 'extraBedPrice' => $out_extraBedPrice));
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