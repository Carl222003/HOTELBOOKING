<?php
include 'db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['cancel_booking']) && isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    $booking_id = $_POST['booking_id'];
    
    $updateSql = "UPDATE bookings SET status = 'canceled' WHERE id = ? AND user_id = ? AND status = 'pending'";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ii", $booking_id, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Booking cancelled successfully!";
    } else {
        $_SESSION['error'] = "Failed to cancel booking. Please try again.";
    }
    $stmt->close();
    
    header("Location: status.php");
    exit();
}

if (isset($_POST['approve_booking']) || isset($_POST['reject_booking'])) {
    $booking_id = $_POST['booking_id'];
    $status = isset($_POST['approve_booking']) ? 'confirmed' : 'rejected'; 

    echo '<script>
            var result = confirm("Are you sure you want to ' . ($status == 'confirmed' ? 'approve' : 'reject') . ' this booking?");
            if(result) {
                // Proceed with the update if confirmed
                var form = document.getElementById("bookingForm_' . $booking_id . '");
                form.submit();
            }
          </script>';
}

$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Fetch bookings with different statuses (pending, confirmed, rejected)
$sql = "SELECT * FROM bookings WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$pending_bookings = [];
$confirmed_bookings = [];
$rejected_bookings = [];

while ($row = $result->fetch_assoc()) {
    if ($row['status'] == 'pending') {
        $pending_bookings[] = $row;
    } elseif ($row['status'] == 'confirmed') {
        $confirmed_bookings[] = $row;
    } elseif ($row['status'] == 'rejected') {
        $rejected_bookings[] = $row;
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Status</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #fff;
            background-image: url('images/Deluxe2.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .booking-status {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .booking-status table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .booking-status table, th, td {
            border: 1px solid #fff;
        }

        .booking-status th, td {
            padding: 10px;
            text-align: left;
        }

        .booking-status th {
            background-color: #ffcc00;
            color: #333;
        }

        .booking-status td {
            background-color: #333;
            color: #fff;
        }

        .cancel-btn {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .cancel-btn:hover {
            background-color: #d32f2f;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .success {
            background-color: #4CAF50;
            color: white;
        }
        .error {
            background-color: #f44336;
            color: white;
        }
        .back-btn {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
        .back-btn:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<div class="booking-status">
    <h1>Your Bookings Status</h1>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="message success"><?= $_SESSION['message']; ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="message error"><?= $_SESSION['error']; ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Pending Bookings Section -->
    <h2>Pending Bookings</h2>
    <?php if (empty($pending_bookings)): ?>
        <p>No pending bookings found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Room Type</th>
                    <th>Guest Name</th>
                    <th>Guest Email</th>
                    <th>Phone Number</th>
                    <th>Check-in Date</th>
                    <th>Check-out Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pending_bookings as $booking): ?>
                    <tr>
                        <td><?= htmlspecialchars($booking['room_type']) ?></td>
                        <td><?= htmlspecialchars($booking['guest_name']) ?></td>
                        <td><?= htmlspecialchars($booking['guest_email']) ?></td>
                        <td><?= htmlspecialchars($booking['phone']) ?></td>
                        <td><?= htmlspecialchars($booking['check_in']) ?></td>
                        <td><?= htmlspecialchars($booking['check_out']) ?></td>
                        <td>Pending</td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <button type="submit" name="cancel_booking" class="cancel-btn" 
                                        onclick="return confirm('Are you sure you want to cancel this booking?')">
                                    Cancel Booking
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Confirmed Bookings Section -->
    <h2>Confirmed Bookings</h2>
    <?php if (empty($confirmed_bookings)): ?>
        <p>No confirmed bookings found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Room Type</th>
                    <th>Guest Name</th>
                    <th>Guest Email</th>
                    <th>Phone Number</th>
                    <th>Check-in Date</th>
                    <th>Check-out Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($confirmed_bookings as $booking): ?>
                    <tr>
                        <td><?= htmlspecialchars($booking['room_type']) ?></td>
                        <td><?= htmlspecialchars($booking['guest_name']) ?></td>
                        <td><?= htmlspecialchars($booking['guest_email']) ?></td>
                        <td><?= htmlspecialchars($booking['phone']) ?></td>
                        <td><?= htmlspecialchars($booking['check_in']) ?></td>
                        <td><?= htmlspecialchars($booking['check_out']) ?></td>
                        <td>Confirmed</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Rejected Bookings Section -->
    <h2>Rejected Bookings</h2>
    <?php if (empty($rejected_bookings)): ?>
        <p>No rejected bookings found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Room Type</th>
                    <th>Guest Name</th>
                    <th>Guest Email</th>
                    <th>Phone Number</th>
                    <th>Check-in Date</th>
                    <th>Check-out Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rejected_bookings as $booking): ?>
                    <tr>
                        <td><?= htmlspecialchars($booking['room_type']) ?></td>
                        <td><?= htmlspecialchars($booking['guest_name']) ?></td>
                        <td><?= htmlspecialchars($booking['guest_email']) ?></td>
                        <td><?= htmlspecialchars($booking['phone']) ?></td>
                        <td><?= htmlspecialchars($booking['check_in']) ?></td>
                        <td><?= htmlspecialchars($booking['check_out']) ?></td>
                        <td>Rejected</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="homepage.php" class="back-btn">Back to Home</a>
</div>

</body>
</html>
