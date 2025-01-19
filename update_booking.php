<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotelbooking";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $updated_check_out = $_POST['updated_check_out'];
    $status = $_POST['status'];

    $sql = "UPDATE bookings SET updated_check_out = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $updated_check_out, $status, $booking_id);

    if ($stmt->execute()) {
        echo "Booking updated successfully!";
    } else {
        echo "Error updating booking: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
