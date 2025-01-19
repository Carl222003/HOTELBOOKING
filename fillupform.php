<?php
session_start();
// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotelbooking";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$bookingConfirmed = false;

// Initialize variables to avoid warnings
$room_type = $check_in = $check_out = $guest_name = $guest_email = $phone = '';
$selectedRoom = null;

// Room data with images and multiple room names for each type
$rooms = [
    'Deluxe' => [
        'name' => 'Deluxe Room',
        'description' => 'Spacious and luxurious room with a breathtaking ocean view.',
        'room_names' => ['Starry Night Deluxe Room', 'Milky Way Deluxe Retreat', 'Ecliptic Deluxe Room', 'Polaris Deluxe Room'],
        'images' => [
            ['file' => 'Deluxe.jpg', 'description' => 'A panoramic view from the Deluxe Room window.'],
            ['file' => 'Deluxe2.jpg', 'description' => 'The luxurious king-sized bed in the Deluxe Room.'],
            ['file' => 'deluxe3.jpg', 'description' => 'The elegant sitting area of the Deluxe Room.'],
            ['file' => 'Deluxe4.jpg', 'description' => 'The private balcony of the Deluxe Room overlooking the ocean.']
        ],
        'price' => 15500
    ],
    'Standard' => [
        'name' => 'Standard Room',
        'description' => 'Comfortable room with all the basic amenities for a relaxing stay.',
        'room_names' => ['Galaxy Standard Room', 'Nebula Standard Room', 'Celestial Standard Room', 'Cosmic Standard Room', 'Starfield Standard Room', 'Orion Standard Room'],
        'images' => [
            ['file' => 'standard.jpg', 'description' => 'A view of the Standard Room with a cozy bed.'],
            ['file' => 'standard2.jpg', 'description' => 'The work desk and TV area in the Standard Room.'],
            ['file' => 'standard3.jpg', 'description' => 'A close-up of the bed and nightstands in the Standard Room.'],
            ['file' => 'standard4.jpg', 'description' => 'The bathroom amenities in the Standard Room.'],
            ['file' => 'standard5.webp', 'description' => 'The bright and modern design of the Standard Room.'],
            ['file' => 'standard6.jpg', 'description' => 'The comfortable seating area in the Standard Room.']
        ],
        'price' => 12000
    ],
    'Suite' => [
        'name' => 'Suite Room',
        'description' => 'Exclusive and elegant suite with a separate living area and top-notch amenities.',
        'room_names' => ['Nimbus Luxury Suite', 'Cumulus Sky Suite', 'Stratus Serenity Suite', 'Altocumulus Loft Suite', 'Silver Lining Executive Suite', 'Cloudburst Presidential Suite'],
        'images' => [
            ['file' => 'suite.jpg', 'description' => 'The spacious living area of the Suite Room.'],
            ['file' => 'suite2.webp', 'description' => 'A comfortable seating area in the Suite Room.'],
            ['file' => 'suite3.jpg', 'description' => 'The luxurious king-sized bed in the Suite Room.'],
            ['file' => 'suite4.webp', 'description' => 'The beautiful decor and lighting of the Suite Room.'],
            ['file' => 'suite5.jpg', 'description' => 'A panoramic view from the Suite Room’s window.'],
            ['file' => 'suite6.jpg', 'description' => 'The elegant bathroom of the Suite Room with modern amenities.']
        ],
        'price' => 20000
    ]
];

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_type = $_POST['room_type'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $guest_name = $_POST['guest_name'];
    $guest_email = $_POST['guest_email'];
    $phone = $_POST['phone'];
    $user_id = $_SESSION['user_id']; // Get user_id from session

    // Validate inputs
    if (empty($guest_name) || empty($guest_email) || empty($phone) || empty($check_in) || empty($check_out) || !array_key_exists($room_type, $rooms)) {
        echo "<p class='error-message'>Please fill in all the required fields correctly.</p>";
        exit();
    }

    // Assign the room name based on the selected room type
    $room_name_list = $rooms[$room_type]['room_names'];
    $room_name = $room_name_list[array_rand($room_name_list)]; // Randomly pick one room name

    // Calculate the number of nights
    $check_in_date = new DateTime($check_in);
    $check_out_date = new DateTime($check_out);
    $interval = $check_in_date->diff($check_out_date);
    $nights = $interval->days;

    // Calculate the total price (price per night * number of nights)
    $room_price = $rooms[$room_type]['price'];
    $total_price = $room_price * $nights;

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO bookings (room_type, room_name, check_in, check_out, guest_name, guest_email, phone, user_id, total_price) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("sssssssis", $room_type, $room_name, $check_in, $check_out, $guest_name, $guest_email, $phone, $user_id, $total_price);

        if ($stmt->execute()) {
            $bookingConfirmed = true;
        } else {
            // Improved error handling
            echo "<p class='error-message'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p class='error-message'>Error: " . $conn->error . "</p>";
    }

    // Select the room for confirmation
    $selectedRoom = $rooms[$room_type];
}

// Get today's date for pre-filling check-in date
$current_date = date("Y-m-d");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking</title>
    <style>
        /* General Page Styling */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            color: #fff;
        }

        /* Video Background */
        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            object-fit: cover;
        }

        /* Booking Form Container */
        .booking-form {
            max-width: 600px;
            margin: 80px auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        h1 {
            font-size: 2.8rem;
            margin-bottom: 20px;
            color: #ffcc00;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 1.2rem;
            color: #fff;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="email"], input[type="tel"], input[type="date"], select {
            width: 100%;
            padding: 12px;
            font-size: 1.1rem;
            border: none;
            border-radius: 6px;
            margin: 8px 0;
            background-color: #fff;
            color: #333;
        }

        button.submit-btn {
            background-color: #ffcc00;
            color: #333;
            font-size: 1.3rem;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.submit-btn:hover {
            background-color: #ff9900;
        }

        /* Confirmation Page */
        .confirmation {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        h2.success-message {
            color: #ffcc00;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.1rem;
            margin: 10px 0;
            color: #fff;
        }

        strong {
            color: #ffcc00;
        }

        .error-message {
            color: #f44336;
            background-color: #ffebee;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Room Image Gallery */
        .room-images {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 20px;
        }

        .room-image {
            width: 45%;
            margin-bottom: 20px;
            text-align: center;
        }

        .room-image img {
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Responsive Design for Mobile */
        @media screen and (max-width: 768px) {
            .booking-form {
                padding: 15px;
            }

            h1 {
                font-size: 2.2rem;
            }

            p {
                font-size: 1rem;
            }

            .room-image {
                width: 100%;
            }
        }
        .nav {
            background-color: transparent;
            padding: 10px;
            position: absolute;
            right: 0;
            top: 0;
        }

        .nav a {
            color: white;
            text-decoration: none;
            font-size: 1.2em;
            padding: 8px 15px;
            transition: background-color 0.3s;
        }

        .nav a:hover {
            background-color: transparent;
            color:rgb(173, 156, 0);
            border-radius: 5px;
        }

        /* Dropdown Styling */
        .dropdown-container {
            position: relative;
            display: inline-block;
        }

        .dropdown-toggle {
            text-decoration: none;
            color: white;
            font-weight: bold;
            font-size: 1.2em;
            position: relative;
            padding-bottom: 5px;
            transition: color 0.3s ease, transform 0.3s ease;
            cursor: pointer;
        }

        .dropdown-toggle:hover {
            color: #ffcc00;
            transform: scale(1.1);
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: transparent;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(251, 255, 0, 0.2);
            z-index: 1;
            border-radius: 8px;
        }

        .dropdown-content a {
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-size: 1.2em;
            transition: background-color 0.3s, padding-left 0.3s ease;
        }

        .dropdown-content a i {
            margin-right: 10px;
            color:rgb(0, 0, 0); /* Icon color */
        }

        .dropdown-content a:hover {
            background-color: #444;
            padding-left: 20px;
        }

        .dropdown-container:hover .dropdown-content {
            display: block;
        }
    </style>
   <script>
    // Prevent past dates for check-in and ensure check-out date follows
    function validateDates() {
        // Get today's date in YYYY-MM-DD format
        const today = new Date();
        const todayString = today.toISOString().split('T')[0]; // Format date to YYYY-MM-DD

        // Set the minimum date and default value for check-in to today
        const checkInDate = document.getElementById('check_in');
        checkInDate.setAttribute('min', todayString);
        if (!checkInDate.value) {
            checkInDate.setAttribute('value', todayString); // Set today's date as default
        }

        // Adjust the minimum check-out date when check-in date changes
        checkInDate.addEventListener('change', function () {
            const checkOutDate = document.getElementById('check_out');
            checkOutDate.setAttribute('min', checkInDate.value); // Set check-out min to selected check-in date
        });

        // Initialize the check-out date minimum
        const checkOutDate = document.getElementById('check_out');
        if (!checkOutDate.value) {
            checkOutDate.setAttribute('min', todayString); // Set today's date if no check-in value yet
        }
    }

    // Ensure the function runs when the DOM is fully loaded
    document.addEventListener('DOMContentLoaded', validateDates);
</script>
</head>
<body>

<!-- Video Background -->
<video autoplay muted loop class="video-background">
    <source src="0107(1).mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>
<div class="nav">
    <a href="homepage.php">𝙷𝙾𝙼𝙴</a>
    <div class="dropdown-container">
        <a class="dropdown-toggle" href="#">𝚁𝙾𝙾𝙼𝚂</a>
        <div class="dropdown-content">
            <a href="standard_rooms.php"><i class="fas fa-bed"></i> Standard Rooms</a>
            <a href="deluxe_rooms.php"><i class="fas fa-bed"></i> Deluxe Rooms</a>
            <a href="suites.php"><i class="fas fa-bed"></i> Suites Rooms</a>
        </div>
    </div>
    <a href="contact.php">𝙲𝙾𝙽𝚃𝙰𝙲𝚃</a>
    <a href="status.php">𝚂𝚃𝙰𝚃𝚄𝚂</a>
</div>

<?php if ($bookingConfirmed): ?>
    <script>
        alert("Booking Successful!");
    </script>
    <div class="confirmation">
        <h1>Booking Confirmation</h1>
        <h2 class="success-message">Thank you for booking with us!</h2>
        <p><strong>Guest Name:</strong> <?= htmlspecialchars($guest_name) ?></p>
        <p><strong>Room Type:</strong> <?= htmlspecialchars($selectedRoom['name']) ?></p>
        <p><strong>Room Name:</strong> <?= htmlspecialchars($_POST['room_name']) ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($selectedRoom['description']) ?></p>
        <p><strong>Price:</strong> ₱<?= number_format($selectedRoom['price'], 2) ?> per night</p>
        <p><strong>Total Price:</strong> ₱<?= number_format($total_price, 2) ?></p>
        <p><strong>Check-in:</strong> <?= htmlspecialchars($check_in) ?></p>
        <p><strong>Check-out:</strong> <?= htmlspecialchars($check_out) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($guest_email) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($phone) ?></p>

        <div class="room-images">
    <?php foreach ($selectedRoom['images'] as $image): ?>
        <div class="room-image">
            <img src="images/<?= $image['file'] ?>" alt="<?= $image['description'] ?>" title="<?= $image['description'] ?>" style="max-width: 100%; height: auto; margin-bottom: 15px;">
            <p><?= $image['description'] ?></p>
        </div>
    <?php endforeach; ?>
</div>

        <button onclick="window.location.href='homepage.php'" class="submit-btn">Okay</button>
    </div>
<?php else: ?>
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
                <select id="room_type" name="room_type" onchange="updateRoomNames()" required>
                    <option value="Deluxe">Deluxe</option>
                    <option value="Standard">Standard</option>
                    <option value="Suite">Suite</option>
                </select>
            </div>

            <div class="form-group">
                <label for="room_name">Room Name</label>
                <select id="room_name" name="room_name" required>
                    <!-- Room names will be populated dynamically based on selected room type -->
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
            <button type="submit" class="submit-btn">Confirm Booking</button>
        </form>
    </div>

    <script>
        // Update room names based on selected room type
        function updateRoomNames() {
            const roomType = document.getElementById('room_type').value;
            const roomNameSelect = document.getElementById('room_name');

            // Clear existing options
            roomNameSelect.innerHTML = '';

            // Define room names based on selected room type
            const roomNames = {
    'Deluxe': ['Starry Night Deluxe Room', 'Milky Way Deluxe Retreat', 'Ecliptic Deluxe Room', 'Polaris Deluxe Room'],
    'Standard': ['Galaxy Standard Room', 'Nebula Standard Room', 'Celestial Standard Room', 'Cosmic Standard Room', 'Starfield Standard Room', 'Orion Standard Room'],
    'Suite': ['Nimbus Luxury Suite', 'Cumulus Sky Suite', 'Stratus Serenity Suite', 'Altocumulus Loft Suite', 'Silver Lining Executive Suite', 'Cloudburst Presidential Suite']
};
            // Populate the room name dropdown with options
            roomNames[roomType].forEach(function(room) {
                const option = document.createElement('option');
                option.value = room;
                option.textContent = room;
                roomNameSelect.appendChild(option);
            });
        }

        // Trigger room name update on page load
        window.onload = updateRoomNames;
    </script>

<?php endif; ?>

</body>
</html>
