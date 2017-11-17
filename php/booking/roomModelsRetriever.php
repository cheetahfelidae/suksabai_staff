<?php
header('Content-Type: text/plain; charset=UTF-8');
include "../configuration.php";
connDB();

$dates = json_decode(file_get_contents('php://input'), true);

/* Prepared statement, stage 1: prepare */
if (!($stmt = $mysqli->prepare("SELECT EV_R_MODELS.num, EV_R_MODELS.type, EV_R_MODELS.curPrice, EV_R_MODELS.brief
        FROM (SELECT num, type, curPrice, brief FROM RoomModels) AS EV_R_MODELS
        LEFT JOIN (SELECT roomNum, stayDate FROM Bookings WHERE stayDate >= ? AND stayDate <= ?) AS B_REQ_NIGHTS
        ON EV_R_MODELS.num = B_REQ_NIGHTS.roomNum WHERE B_REQ_NIGHTS.roomNum IS NULL 
        ORDER BY EV_R_MODELS.type, EV_R_MODELS.num"))) {
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
$out_type = NULL;
$out_curPrice = NULL;
$out_brief = NULL;
if (!$stmt->bind_result($out_num, $out_type, $out_curPrice, $out_brief)) {
    echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}

$temp = array();

// get results and convert to fullcalendar format
while ($stmt->fetch()) {
    array_push($temp, array('num' => $out_num, 'type' => $out_type, 'curPrice' => $out_curPrice, 'brief' => $out_brief));
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