<?php
include('db.php');


if (isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    
    $sql = "SELECT bookings.id, bookings.check_in, bookings.check_out, bookings.status, 
                   rooms.room_number, rooms.room_type 
            FROM bookings 
            JOIN rooms ON bookings.room_id = rooms.id 
            WHERE bookings.id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);  
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        
        echo "<p><strong>Room Number:</strong> " . $row['room_number'] . "</p>";
        echo "<p><strong>Room Type:</strong> " . $row['room_type'] . "</p>";
        echo "<p><strong>Check-in:</strong> " . $row['check_in'] . "</p>";
        echo "<p><strong>Check-out:</strong> " . $row['check_out'] . "</p>";
        echo "<p><strong>Status:</strong> " . ucfirst($row['status']) . "</p>";
    } else {
        echo "<p>Booking not found.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

