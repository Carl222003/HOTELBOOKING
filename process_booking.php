<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotelbooking";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $room_type = $_POST['room_type'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $guest_name = $_POST['guest_name'];
    $guest_email = $_POST['guest_email'];
    $phone = $_POST['phone'];


    $sql = "INSERT INTO bookings (room_type, check_in, check_out, guest_name, guest_email, phone)
            VALUES (?, ?, ?, ?, ?, ?)";


    if ($stmt = $conn->prepare($sql)) {
  
        $stmt->bind_param("ssssss", $room_type, $check_in, $check_out, $guest_name, $guest_email, $phone);


        if ($stmt->execute()) {
            echo "<h2 class='success-message'>Booking Successful!</h2>";
        } else {
            echo "<p class='error-message'>Error: " . $stmt->error . "</p>";
        }


        $stmt->close();
    } else {
        echo "<p class='error-message'>Error: " . $conn->error . "</p>";
    }


    $rooms = [
        'Deluxe' => [
            'name' => 'Deluxe Room',
            'price' => 15500,
            'description' => 'Spacious and luxurious room with a breathtaking ocean view.'
        ],
        'Standard' => [
            'name' => 'Standard Room',
            'price' => 12000,
            'description' => 'Comfortable room with all the basic amenities for a relaxing stay.'
        ],
        'Suite' => [
            'name' => 'Suite Room',
            'price' => 20000,
            'description' => 'Exclusive and elegant suite with a separate living area and top-notch amenities.'
        ]
    ];


    if (!array_key_exists($room_type, $rooms)) {
        die('Invalid room type selected.');
    }

    $selectedRoom = $rooms[$room_type];


    echo "<div class='confirmation'>";
    echo "<h1>Booking Confirmation</h1>";
    echo "<p><strong>Guest Name:</strong> {$guest_name}</p>";
    echo "<p><strong>Room Type:</strong> {$selectedRoom['name']}</p>";
    echo "<p><strong>Description:</strong> {$selectedRoom['description']}</p>";
    echo "<p><strong>Price:</strong> ₱" . number_format($selectedRoom['price'], 2) . " per night</p>";
    echo "<p><strong>Check-in:</strong> {$check_in}</p>";
    echo "<p><strong>Check-out:</strong> {$check_out}</p>";
    echo "<p><strong>Email:</strong> {$guest_email}</p>";
    echo "<p><strong>Phone:</strong> {$phone}</p>";
    echo "</div>";
} else {
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking</title>
    <style>
   
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .booking-form {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 2.5rem;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 1.1rem;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="email"], input[type="tel"], input[type="date"], select {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button.submit-btn {
            background-color: #4CAF50;
            color: #fff;
            font-size: 1.2rem;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.submit-btn:hover {
            background-color: #45a049;
        }

        .confirmation {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 2.5rem;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        h2.success-message {
            color: #4CAF50;
            font-size: 1.8rem;
            text-align: center;
            padding: 20px;
            background-color: #e8f5e9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        p {
            font-size: 1.1rem;
            margin: 10px 0;
            color: #555;
        }

        strong {
            color: #333;
        }


        .error-message {
            color: #D32F2F;
            background-color: #FFEBEE;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }


        @media screen and (max-width: 768px) {
            .booking-form {
                padding: 15px;
            }

            h1 {
                font-size: 2rem;
            }

            p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

    <div class="booking-form">
        <h1>Hotel Room Booking</h1>
        <form action="" method="POST">
            <div class="form-group">
                <label for="guest_name">Guest Name</label>
                <input type="text" id="guest_name" name="guest_name" required>
            </div>

            <div class="form-group">
                <label for="guest_email">Guest Email</label>
                <input type="email" id="guest_email" name="guest_email" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" required>
            </div>

            <div class="form-group">
                <label for="room_type">Room Type</label>
                <select id="room_type" name="room_type" required>
                    <option value="Deluxe">Deluxe</option>
                    <option value="Standard">Standard</option>
                    <option value="Suite">Suite</option>
                </select>
            </div>

            <div class="form-group">
                <label for="check_in">Check-in Date</label>
                <input type="date" id="check_in" name="check_in" required>
            </div>

            <div class="form-group">
                <label for="check_out">Check-out Date</label>
                <input type="date" id="check_out" name="check_out" required>
            </div>

            <button type="submit" class="submit-btn">Book Now</button>
        </form>
    </div>

</body>
</html>

<?php
}
?>
