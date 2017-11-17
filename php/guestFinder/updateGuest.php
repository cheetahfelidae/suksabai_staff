<?php
header('Content-Type: text/plain; charset=UTF-8');
include "../configuration.php";
connDB();

/* Prepared statement, stage 1: prepare */
if (!($stmtGuests = $mysqli->prepare("UPDATE Guests SET title = ?, firstName = ?, lastName = ?, email = ?, tel = ?, address = ?, district = ?, amphur = ?, province = ?, taxNumber = ?, otherDetails  = ? WHERE id = ?"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

/* Prepared statement, stage 2: bind and execute */
if (!$stmtGuests->bind_param("sssssssssssi", $title, $firstName, $lastName, $email, $tel, $address, $district, $amphur, $province, $taxNumber, $otherDetails, $id)) {
    echo "Binding parameters failed: (" . $stmtGuests->errno . ") " . $stmtGuests->error;
}

// get guest details from javascript
$guest = json_decode(file_get_contents('php://input'), true);

// set parameters and execute
$id = $guest["id"];
$title = $guest["title"];
$firstName = $guest["firstName"];
$lastName = $guest["lastName"];
$email = $guest["email"];
$tel = $guest["tel"];
$address = $guest["address"];
$district = $guest["district"];
$amphur = $guest["amphur"];
$province = $guest["province"];
$taxNumber = $guest["taxNumber"];
$otherDetails = $guest["otherDetails"];

if (!$stmtGuests->execute()) {
    echo "Execute failed: (" . $stmtGuests->errno . ") " . $stmtGuests->error;
}

/* explicit close recommended */
$stmtGuests->close();

closeDB();
?>