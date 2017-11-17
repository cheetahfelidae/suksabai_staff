<?php
header('Content-Type: text/plain; charset=UTF-8');
include "../configuration.php";
include "../utilities.php";
connDB();

/* Prepared statement, stage 1: prepare */
if (!($stmtGuests = $mysqli->prepare("INSERT INTO Guests (title, firstName, lastName, email, tel, address, district, amphur, province, taxNumber, otherDetails) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

/* Prepared statement, stage 2: bind and execute */
if (!$stmtGuests->bind_param("sssssssssss", $title, $firstName, $lastName, $email, $tel, $address, $district, $amphur, $province, $taxNumber, $otherDetails)) {
    echo "Binding parameters failed: (" . $stmtGuests->errno . ") " . $stmtGuests->error;
}

// get booking from javascript
$booking = json_decode(file_get_contents('php://input'), true);

// set parameters and execute
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

// get lasted id
$latestID = $stmtGuests->insert_id;

/* explicit close recommended */
$stmtGuests->close();

/**************************************************** ADD ROOM *************************************************************************** */

if (!empty($booking["selectedRooms"]) && !empty($latestID)) {
    addRooms($mysqli, $booking["selectedRooms"], $latestID);
} else {
    echo "EITHER SELECTEDROOMS OR LASTESTID VARIABLE IS EMPTY";
}

closeDB();
?>