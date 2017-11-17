<?php
header('Content-Type: text/plain; charset=UTF-8');
include "../configuration.php";
include "../utilities.php";
connDB();

/* Prepared statement, stage 1: prepare */
if (!($stmtGuests = $mysqli->prepare("UPDATE Guests SET title = ?, firstName = ?, lastName = ?, email = ?, tel = ?, address = ?, district = ?, amphur = ?, province = ?, taxNumber = ?, otherDetails  = ? WHERE id = ?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

/* Prepared statement, stage 2: bind and execute */
if (!$stmtGuests->bind_param("sssssssssssi", $title, $firstName, $lastName, $email, $tel, $address, $district, $amphur, $province, $taxNumber, $otherDetails, $id)) {
    echo "Binding parameters failed: (" . $stmtGuests->errno . ") " . $stmtGuests->error;
}

// get booking from javascript
$booking = json_decode(file_get_contents('php://input'), true);

// set parameters and execute
$id = $booking["guest"]["id"];
$title = $booking["guest"]["title"];
$firstName = $booking["guest"]["firstName"];
$lastName = $booking["guest"]["lastName"];
$email = $booking["guest"]["email"];
$tel = $booking["guest"]["tel"];
$address = $booking["guest"]["address"];
$district = $booking["guest"]["district"];
$amphur = $booking["guest"]["amphur"];
$province = $booking["guest"]["province"];
$taxNumber = $booking["guest"]["taxNumber"];
$otherDetails = $booking["guest"]["otherDetails"];

if (!$stmtGuests->execute()) {
    echo "Execute failed: (" . $stmtGuests->errno . ") " . $stmtGuests->error;
}

/* explicit close recommended */
$stmtGuests->close();

/**************************************************** ADD ROOM *************************************************************************** */

if (!empty($booking["selectedRooms"])) {
addRooms($mysqli, $booking["selectedRooms"], $id);
} else {
echo "EITHER SELECTEDROOMS OR LASTESTID VARIABLE IS EMPTY";
}

closeDB();
?>