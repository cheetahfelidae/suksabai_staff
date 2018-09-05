<?php
header('Content-Type: text/plain; charset=UTF-8');
include "../configuration.php";
include "../utilities.php";
connDB();

// get booking from javascript
$booking = json_decode(file_get_contents('php://input'), true);

/**************************************************** ADD ROOM *************************************************************************** */

if (!empty($booking["selectedRooms"])) {
    if (!($stmt = $mysqli->prepare("INSERT INTO Bookings (roomNum, roomPrice, numAdults, numChildren, breakfastPrice, extraBedPrice, checkinDate, checkinTime, checkoutDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!$stmt->bind_param("iiiiiisss", $roomNum, $roomPrice, $numAdults, $numChildren, $breakfastPrice, $extraBedPrice, $checkinDate, $checkinTime, $checkoutDate)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    foreach ($booking["selectedRooms"] as $room) {

        // set parameters and execute
        $roomNum = $room["num"];
        $roomPrice = $room["price"];
        $numAdults = $room["numAdults"];
        $numChildren = $room["numChildren"];
        $breakfastPrice = $room["breakfastPrice"];
        $extraBedPrice = $room["extraBedPrice"];
        $checkinDate = $room["checkinDate"];
        $checkinTime = $room["checkinTime"];
        $checkoutDate = $room["checkoutDate"];

        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
    }

    $stmt->close();

} else {
    echo "SELECTEDROOMS IS EMPTY";
}

closeDB();
?>