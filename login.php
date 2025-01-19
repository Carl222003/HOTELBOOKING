<?php
session_start(); 

include 'db.php'; 


$admin_email = "admin@gmail.com";
$admin_password = "123";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

   
    if ($email === $admin_email && $password === $admin_password) {
      
        $_SESSION['admin'] = true; 
        header('Location: admin.php');
        exit(); 
    }


    $sql = "SELECT * FROM users WHERE email = ?"; 
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
         
            $_SESSION['user'] = $user; 
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['name'] = $user['name'];
            $_SESSION['avatar'] = $user['avatar'] ?? 'default-avatar.jpg'; 


            header('Location: homepage.php');
            exit();
        } else {
          
            echo "<script>alert('Invalid password.');</script>";
        }
    } else {
       
        echo "<script>alert('No account found with that email.');</script>";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
       
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
           .video-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }
        .form_main {
    width: 350px; /* Slightly wider for better proportions */
    padding: 40px; /* Adequate spacing */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.4); /* Dark translucent background */
    backdrop-filter: blur(20px); /* Strong blur effect */
    -webkit-backdrop-filter: blur(20px); /* Safari compatibility */
    border: 1px solid rgba(255, 255, 255, 0.2); /* Subtle border */
    border-radius: 20px; /* Smooth rounded edges */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); /* Depth shadow */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.form_main:hover {
    transform: translateY(-10px); /* Slight lift on hover */
    box-shadow: 0 8px 40px rgba(0, 0, 0, 0.4); /* Enhanced shadow on hover */
}

.heading {
    font-size: 2.2em;
    color: #ffffff; /* Bright white for contrast */
    font-weight: bold;
    margin-bottom: 30px;
    text-align: center;
    text-shadow: 0 2px 5px rgba(0, 0, 0, 0.5); /* Glow effect */
}

.inputContainer {
    width: 100%;
    margin-bottom: 20px;
}

.inputField {
    width: 100%;
    height: 45px; /* Comfortable height */
    background: rgba(255, 255, 255, 0.1); /* Subtle translucent input background */
    border: 1px solid rgba(255, 255, 255, 0.3); /* Light border for input fields */
    border-radius: 10px; /* Smooth rounded edges */
    padding: 10px 15px; /* Spacing inside inputs */
    color: #ffffff; /* White text for contrast */
    font-size: 1em;
    font-weight: 500;
    outline: none;
    transition: background 0.3s ease, border-color 0.3s ease;
}

.inputField:focus {
    background: rgba(255, 255, 255, 0.2); /* Slightly brighter background on focus */
    border-color: #ff8c00; /* Highlighted border */
}

.inputField::placeholder {
    color: rgba(255, 255, 255, 0.7); /* Softer white for placeholder text */
}

#button {
    width: 100%;
    height: 50px; /* Comfortable size */
    background: linear-gradient(90deg, #ff8c00, #ff4500); /* Vibrant gradient */
    border: none;
    color: #ffffff;
    font-size: 1em;
    font-weight: bold;
    border-radius: 25px;
    cursor: pointer;
    text-transform: uppercase;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
}

#button:hover {
    transform: scale(1.05); /* Subtle grow on hover */
    box-shadow: 0 4px 15px rgba(255, 140, 0, 0.5); /* Glow effect */
}

#button::after {
    content: "";
    position: absolute;
    background: rgba(255, 255, 255, 0.2); /* Overlay for movement */
    height: 100%;
    width: 150px;
    top: 0;
    left: -200px;
    border-bottom-right-radius: 100px;
    border-top-left-radius: 100px;
    filter: blur(20px);
    transition: transform 0.6s ease-out;
}

#button:hover::after {
    transform: translateX(600px);
}

.signupContainer p {
    font-size: 0.9em;
    color: rgba(255, 255, 255, 0.8); /* Softer white */
}

.signupContainer a {
    text-decoration: none;
    color: #ff8c00;
    font-weight: bold;
    transition: color 0.3s ease;
}

.signupContainer a:hover {
    color: #ff4500; /* Vibrant orange on hover */
}
    </style>
</head>
<body>
<video class="video-bg" autoplay loop muted>
        <source src="0107(1).mp4" type="video/mp4">
    </video>
    <div class="form_main">
        <h2 class="heading">Login</h2>
        <form method="POST" action="">
            <div class="inputContainer">
                <input type="email" name="email" placeholder="Email" class="inputField" required>
            </div>
            <div class="inputContainer">
                <input type="password" name="password" placeholder="Password" class="inputField" required>
            </div>
            <button type="submit" id="button">Login</button>
        </form>
        <div class="signupContainer">
            <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
        </div>
    </div>
</body>
</html>
