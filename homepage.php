<?php
session_start(); 
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user_name = $_SESSION['user']['name'];
$user_avatar = $_SESSION['user']['avatar']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZJC Hotel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Lora', serif;
            overflow: hidden; 
        }

        .user-info {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            align-items: center;
            color: white;
            font-size: 1.2em;
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            cursor: pointer;
        }

        .user-info span {
            font-weight: bold;
        }

        .dropdown {
            position: absolute;
            top: 60px;
            left: 0;
            display: none;
            background-color: #333;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            min-width: 160px;
            z-index: 10;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .dropdown.show {
            display: block;
            opacity: 1;
            pointer-events: all; 
        }

        .dropdown a {
            color: white;
            padding: 10px;
            text-decoration: none;
            display: block;
            font-size: 1.2em;
            transition: background-color 0.3s, padding-left 0.3s ease;
        }

        .dropdown a:hover {
            background-color: #444;
            padding-left: 15px; 
        }

        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .video-background video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .nav {
            position: absolute;
            top: 0;
            right: 0;
            padding: 20px;
            display: flex;
            gap: 20px;
            font-size: 1.2em;
            font-family: 'Lora'; 
        }

        .nav a {
            text-decoration: none;
            color: white;
            font-weight: bold;
            font-size: 1.2em;
            position: relative;
            padding-bottom: 5px; 
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .nav a:hover {
            color: #ffcc00; 
            transform: scale(1.1); 
        }

        .nav a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background-color:rgb(0, 238, 107);
            transition: width 0.3s ease; 
        }

        .nav a:hover::after {
            width: 100%; 
        }
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
            cursor: pointer;
            transition: color 0.3s ease-in-out;
        }

        .dropdown-toggle:hover {
            color: #ffcc00; /* Bright hover color */
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background: transparent;/* Gradient background */
            min-width: 220px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            z-index: 1;
            border-radius: 10px;
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.4s ease, transform 0.4s ease;
        }

        .dropdown-content a {
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-size: 1.2em;
            transition: background 0.3s ease, transform 0.3s ease;
            border-radius: 5px;
        }

        .dropdown-content a i {
            margin-right: 10px;
            color:rgb(49, 247, 0); /* Icon color */
        }

        .dropdown-content a:hover {
            background: rgba(255, 255, 255, 0.2); /* Semi-transparent hover */
            transform: translateX(10px); /* Slight sliding effect */
        }

        .dropdown-container:hover .dropdown-content {
            display: block;
            opacity: 1;
            transform: translateY(0); /* Smooth drop-down */
        }


        .content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .content h1 {
            font-size: 4em;
            margin: 0;
            font-family: 'Lora', serif; 
        }

        .content p {
            font-size: 1.5em;
            margin: 10px 0;
        }

        button {
    --color: rgb(8, 223, 133);
    display: inline-block;
    width: 8em;
    height: 2.6em;
    line-height: 2.5em;
    margin: 20px;
    border: 2px solid var(--color);
    background-color: transparent; /* Ensure the default background is transparent */
    transition: all 0.3s ease; /* Smooth transition for all properties */
    font-size: 17px;
    border-radius: 6px;
    font-weight: 500;
    color: var(--color);
    text-align: center;
    cursor: pointer;
}

button:hover {
    color: #fff; /* Change text color to white */
    background-color: var(--color); /* Add background color on hover */
    border-color: var(--color); /* Ensure border color matches the background */
}
    </style>
</head>
<body>
    <div class="user-info">
        <img src="avatar.avif"<?php echo htmlspecialchars($user_avatar); ?> alt="User Avatar" onclick="toggleDropdown()">
        <span>Welcome, <?php echo htmlspecialchars($user_name); ?></span> 
    </div>

    <div id="dropdownMenu" class="dropdown">
        <a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a> 
    </div>

    <div class="video-background">
        <video autoplay muted loop>
            <source src="0107(1).mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

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

    <div class="content">
        <h1>ZJC Hotel</h1>
        <p>Experience luxury and comfort</p>
        <a href="fillupform.php" class="button"><button>Book Now</button></a>
    </div>

    <script>
        function toggleDropdown() {
            var dropdown = document.getElementById("dropdownMenu");
            dropdown.classList.toggle("show"); 
        }
    </script>
</body>
</html>
