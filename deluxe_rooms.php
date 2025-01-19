<?php
session_start(); // Start session

// Dummy Data for Room Types
$rooms = [
    'deluxe' => [
        'name' => 'Deluxe Room',
        'description' => 'Spacious and luxurious room with a breathtaking ocean view.',
        'images' => [
            ['file' => 'Deluxe.jpg', 'description' => 'A panoramic view from the Deluxe Room window.', 'room_name' => 'Starry Night Deluxe Room
', 'sample_price' => 15500],
            ['file' => 'Deluxe2.jpg', 'description' => 'The luxurious king-sized bed in the Deluxe Room.', 'room_name' => 'Milky Way Deluxe Retreat
', 'sample_price' => 15500],
            ['file' => 'deluxe3.jpg', 'description' => 'The elegant sitting area of the Deluxe Room.', 'room_name' => 'Ecliptic Deluxe Room
', 'sample_price' => 15500],
            ['file' => 'Deluxe4.jpg', 'description' => 'The private balcony of the Deluxe Room overlooking the ocean.','room_name' =>  'Polaris Deluxe Room
', 'sample_price' => 15500]
        ],
        'price' => 15500
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Rooms - ZJC Hotel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Lora', serif;
            background-color: #f4f4f4;
        }

        /* Navigation Bar */
        .nav {
            background-color: transparent;
            padding: 10px;
            position: absolute;
            right: 0;
            top: 0;
        }

        .nav a {
            color: black;
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
            color: black;
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
            color: black;
            padding: 10px 15px;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-size: 1.2em;
            transition: background-color 0.3s, padding-left 0.3s ease;
        }

        .dropdown-content a i {
            margin-right: 10px;
            color:rgb(49, 247, 0); /* Icon color */
        }

        .dropdown-content a:hover {
            background-color: #444;
            padding-left: 20px;
        }

        .dropdown-container:hover .dropdown-content {
            display: block;
        }

        /* Main Content */
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        .room-category {
            display: flex;
            justify-content: center;
            margin-bottom: 50px;
        }

        .room-category .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 30%;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .room-category .card:hover {
            transform: scale(1.05);
        }

        .room-category .card img {
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: 250px;
            display: block;
            margin: 0 auto;
        }

        .room-category .card h3 {
            text-align: center;
            color: #333;
            font-size: 1.8em;
            padding: 15px;
            background-color:rgb(136, 128, 14);
            color: white;
            font-weight: 600;
        }

        .room-category .card p {
            padding: 0 15px;
            color: #555;
            font-size: 1.1em;
        }

        .room-category .card .price {
            padding: 10px 15px;
            text-align: center;
            font-weight: bold;
            font-size: 1.3em;
            color:rgb(161, 118, 0);
            background-color: #f9f9f9;
        }

        .gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
        }

        .gallery img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
        }

        .gallery .img-container {
            width: 48%;
        }

        .room-title {
            text-align: center;
            font-size: 2.5em;
            color:rgb(179, 122, 0);
            font-weight: bold;
        }

        .image-description {
            text-align: center;
            font-size: 1em;
            color: #555;
            margin-top: 10px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .room-category {
                flex-direction: column;
                align-items: center;
            }

            .room-category .card {
                width: 80%;
                margin-bottom: 20px;
            }

            .gallery .img-container {
                width: 100%;
            }
        }

        .avail-button button {
            --color: rgb(8, 223, 133);
            font-family: inherit;
            display: inline-block;
            width: 8em;
            height: 2.6em;
            line-height: 2.5em;
            margin: 20px 0;
            position: relative;
            cursor: pointer;
            overflow: hidden;
            border: 2px solid var(--color);
            transition: color 0.5s;
            z-index: 1;
            font-size: 17px;
            border-radius: 6px;
            font-weight: 500;
            color: var(--color);
        }

        .avail-button button:before {
            content: "";
            position: absolute;
            z-index: -1;
            background: var(--color);
            height: 150px;
            width: 200px;
            border-radius: 50%;
        }

        .avail-button button:hover {
            color: #fff;
        }

        .avail-button button:before {
            top: 100%;
            left: 100%;
            transition: all 0.7s;
        }

        .avail-button button:hover:before {
            top: -30px;
            left: -30px;
        }

        .avail-button button:active:before {
            background: rgb(41, 230, 16);
            transition: background 0s;
        }

        button {
            --color:rgb(8, 223, 133);
            font-family: inherit;
            display: inline-block;
            width: 8em;
            height: 2.6em;
            line-height: 2.5em;
            margin: 20px;
            position: relative;
            cursor: pointer;
            overflow: hidden;
            border: 2px solid var(--color);
            transition: color 0.5s;
            z-index: 1;
            font-size: 17px;
            border-radius: 6px;
            font-weight: 500;
            color: var(--color);
        }

        button:before {
            content: "";
            position: absolute;
            z-index: -1;
            background: var(--color);
            height: 150px;
            width: 200px;
            border-radius: 50%;
        }

        button:hover {
            color: #fff;
        }

        button:before {
            top: 100%;
            left: 100%;
            transition: all 0.7s;
        }

        button:hover:before {
            top: -30px;
            left: -30px;
        }

        button:active:before {
            background:rgb(41, 230, 16);
            transition: background 0s;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<!-- Navigation Bar -->
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

<div class="container">
    <h1 class="room-title">Our Exclusive Rooms</h1>

    <div class="room-category">
        <?php foreach ($rooms as $room_key => $room) : ?>
            <div class="card">
                <h3 class="room-name"><?php echo htmlspecialchars($room['name']); ?></h3> <!-- Room Name -->
                <img src="images/<?php echo htmlspecialchars($room['images'][0]['file']); ?>" alt="<?php echo htmlspecialchars($room['name']); ?>">
                <p><?php echo htmlspecialchars($room['description']); ?></p>
                <div class="price">₱<?php echo number_format($room['price'], 2); ?> Per Night</div>
                <a href="fillupform.php?room=<?php echo $room_key; ?>" class="button"><button>Book Now</button></a>
            </div>
        <?php endforeach; ?>
    </div>

    <?php foreach ($rooms as $room_key => $room) : ?>
        <div class="room-gallery">
            <h2 class="room-title"><?php echo htmlspecialchars($room['name']); ?> Gallery</h2>
            <div class="gallery">
                <?php foreach ($room['images'] as $image) : ?>
                    <div class="img-container">
                        <img src="images/<?php echo htmlspecialchars($image['file']); ?>" alt="<?php echo htmlspecialchars($image['room_name']); ?> Image">
                        <p class="image-description"><?php echo htmlspecialchars($image['description']); ?></p>
                        
                        <!-- Room Name Above the Button -->
                        <h3 class="room-name"><?php echo htmlspecialchars($image['room_name']); ?></h3>

                        <a href="fillupform.php?room=<?php echo $room_key; ?>&image=<?php echo urlencode($image['file']); ?>" class="avail-button">
                            <button>Avail</button>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
