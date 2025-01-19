<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql_check_email = "SELECT COUNT(*) AS email_count FROM users WHERE email = ?";
    $stmt_check_email = $conn->prepare($sql_check_email);
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $result_check_email = $stmt_check_email->get_result();
    $email_count = $result_check_email->fetch_assoc()['email_count'];

    if ($email_count > 0) {
        
        echo "<script>
                alert('Email is already registered!');
              </script>";
    } else {
       
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Signup successful!');
                    window.location.href = 'login.php'; // Redirect to login page
                  </script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $stmt_check_email->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <style>
       
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
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

        .container {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    text-align: center;
    min-height: 100vh;
    background: url('your-background-image.jpg') center center / cover no-repeat; /* Background image */
    padding: 20px;
    position: relative;
    overflow: hidden; /* Ensure content fits within the container */
}

.form_area {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    background-color: transparent;
    backdrop-filter: blur(20px); /* Enhanced blur effect */
    -webkit-backdrop-filter: blur(15px); /* Safari compatibility */
    height: auto;
    width: 100%;
    max-width: 400px;
    border: 1px solid rgba(220, 220, 220, 0.8); /* Subtle transparent border */
    border-radius: 20px; /* Softer, more rounded corners */
    box-shadow: 0 8px 30px rgba(217, 255, 0, 0.2); /* Stronger shadow for contrast */
    padding: 40px 30px; /* Generous padding for spacing */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.form_area:hover {
    transform: translateY(-5px); /* Hover lift effect */
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3); /* Stronger hover shadow */
}

.title {
    color:rgb(255, 255, 255);
    font-weight: 700;
    font-size: 2em; /* Slightly larger title font */
    margin-bottom: 20px;
}

.sub_title {
    font-weight: 500;
    color:rgb(255, 255, 255);
    margin: 5px 0 20px 0;
    font-size: 1.1em; /* Enhanced readability */
}

.form_group {
    display: flex;
    flex-direction: column;
    align-items: baseline;
    margin-bottom: 25px; /* More spacing between inputs */
    width: 100%;
}

.form_style {
    outline: none;
    border: 1px solid #ced4da; 
    width: 100%;
    padding: 12px 15px;
    border-radius: 8px;
    font-size: 16px; /* Larger input font */
    color:rgb(38, 0, 255);
    background-color: rgba(248, 249, 250, 0.9); /* Light, translucent background */
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form_style:focus {
    border-color: #e99f4c;
    box-shadow: 0 0 5px rgba(233, 159, 76, 0.5);
    background-color: #ffffff;
}

.btn {
    padding: 15px;
    width: 100%;
    font-size: 16px; /* Slightly larger button font */
    background: linear-gradient(90deg, #de5499, #e99f4c);
    color: #ffffff;
    border: none;
    border-radius: 30px; /* Fully rounded button */
    font-weight: bold;
    text-transform: uppercase;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Stronger shadow */
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease, opacity 0.3s ease;
}

.btn:hover {
    transform: scale(1.05); /* Slightly larger hover effect */
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    opacity: 0.95;
}

.link {
    font-weight: 600;
    color:rgb(179, 255, 0);
    text-decoration: none;
    font-size: 0.9em;
    transition: color 0.3s ease, text-decoration 0.3s ease;
}

.link:hover {
    color: #de5499;
    text-decoration: underline;
}
    </style>
</head>
<body>
<video class="video-bg" autoplay loop muted>
        <source src="0107(1).mp4" type="video/mp4">
    </video>
    <div class="container">
        <div class="form_area">
            <h2 class="title">Signup</h2>
            <form method="POST" action="">
                <div class="form_group">
                    <label for="name" class="sub_title">Name:</label>
                    <input type="text" name="name" class="form_style" required><br>
                </div>

                <div class="form_group">
                    <label for="email" class="sub_title">Email:</label>
                    <input type="email" name="email" class="form_style" required><br>
                </div>

                <div class="form_group">
                    <label for="password" class="sub_title">Password:</label>
                    <input type="password" name="password" class="form_style" required><br>
                </div>

                <button type="submit" class="btn">Signup</button>
            </form>

            <p>Do you have already an account? <a href="login.php" class="link">Login here</a></p>
        </div>
    </div>
</body>
</html>
