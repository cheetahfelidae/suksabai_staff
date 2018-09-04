<?php

function addRooms($mysqli, $selectedRooms, $guestID)
{
    if (!($stmt = $mysqli->prepare("INSERT INTO Bookings (roomNum, roomPrice, numAdults, numChildren, breakfastPrice, extraBedPrice, stayDate, checkinTime, guestID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    if (!$stmt->bind_param("iiiiiissi", $roomNum, $roomPrice, $numAdults, $numChildren, $breakfastPrice, $extraBedPrice, $stayDate, $checkinTime, $guestID)) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    foreach ($selectedRooms as $room) {

        // set parameters and execute
        $roomNum = $room["num"];
        $roomPrice = $room["price"];
        $numAdults = $room["numAdults"];
        $numChildren = $room["numChildren"];
        $breakfastPrice = $room["breakfastPrice"];
        $extraBedPrice = $room["extraBedPrice"];
        $stayDate = $room["stayDate"];
        $checkinTime = $room["checkinTime"];
//      $guestID = $latestID;

        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
    }

    $stmt->close();
}

