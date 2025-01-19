<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
      body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background: linear-gradient(135deg, #004d7a, #0078a4);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
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

        

        header {
    text-align: center;
    padding: 20px 0;
    color: white;
}

header h1 {
    font-size: 3em;
    font-weight: bold;
    color: #fff;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    margin-bottom: 10px;
}

header p {
    font-size: 1.2em;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 20px;
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.contact-form {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 20px;
    backdrop-filter: blur(10px);
}

.form-section, .map-section {
    flex: 1 1 45%;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.form-section h2, .map-section h2 {
    margin-bottom: 20px;
    color: #fff;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: rgba(255, 255, 255, 0.8);
    font-weight: bold;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    border-radius: 10px;
    font-size: 1em;
    color: #fff;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.2);
    transition: background 0.3s ease, box-shadow 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus {
    background: rgba(255, 255, 255, 0.3);
    box-shadow: inset 0 4px 8px rgba(0, 0, 0, 0.3);
    outline: none;
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

button {
    background: rgba(0, 77, 122, 0.8);
    color: white;
    border: none;
    padding: 12px 24px;
    font-size: 1em;
    border-radius: 10px;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
}

button:hover {
    background: rgba(0, 120, 164, 0.9);
    transform: translateY(-3px);
}

button:active {
    transform: translateY(0);
}

@media screen and (max-width: 768px) {
    .form-section, .map-section {
        flex: 1 1 100%;
    }
}

        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .social-links a {
            text-decoration: none;
            font-size: 1.5em;
            color: #004d7a;
            transition: color 0.3s;
        }

        .social-links a:hover {
            color: #0078a4;
        }

        iframe {
            width: 100%;
            border-radius: 8px;
            border: none;
        }

        @media (max-width: 768px) {
            .form-section, .map-section {
                flex: 1 1 100%;
            }
        }

   /* Navigation styling */
   nav {
            background-color: transparent;
            padding: 10px;
            position: absolute;
            right: 0;
            top: 0;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: flex-start;
        }

        nav ul li {
            margin-left: 20px;
        }

        nav ul li a {
            color: black;
            text-decoration: none;
            font-size: 1.2em;
            padding: 8px 15px;
            transition: background-color 0.3s;
        }

        nav ul li a:hover {
            background-color: transparent;
            color:rgb(173, 156, 0);
            border-radius: 5px;
        }
/* Dropdown menu positioning */
.dropdown-container {
    position: relative;
}

.dropdown-container .dropdown-content {
    display: none;
    position: absolute;
    left: 0; /* Align dropdown under the parent link */
    top: 100%; /* Position below the parent link */
    background-color:transparent;
    padding: 10px 0;
    border-radius: 5px;
    box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
    z-index: 1000;
    min-width: 200px; /* Ensure a consistent width for the dropdown */
}

.dropdown-container:hover .dropdown-content {
    display: block; /* Show dropdown on hover */
}

.dropdown-content a {
    padding: 10px 15px;
    text-decoration: none;
    color: black;
    display: block;
    transition: background-color 0.3s;
}
.dropdown-content a i {
            margin-right: 10px;
            color:rgb(49, 247, 0); /* Icon color */
        }

.dropdown-content a:hover {
    background-color: #0078a4;
    color: #ffe600;
}
/* Responsive Design */
@media (max-width: 768px) {
    nav {
        padding: 10px 15px;
    }

    nav ul li a {
        font-size: 1em;
        padding: 6px 10px;
    }
}
  </style>
  <script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js">
</script>
<script type="text/javascript">
   (function(){
      emailjs.init({
        publicKey: "ViGP1kP2GBNKCHvxb",
      });
   })();
</script>
</head>
<body>
<video class="video-bg" autoplay loop muted>
    <source src="0107(1).mp4" type="video/mp4">
</video>

<nav>
    <ul>
        <li><a href="homepage.php">𝙷𝙾𝙼𝙴</a></li>
        <li class="dropdown-container">
            <a class="dropdown-toggle" href="#">𝚁𝙾𝙾𝙼𝚂</a>
            <ul class="dropdown-content">
                <li><a href="standard_rooms.php"><i class="fas fa-bed"></i> Standard Rooms</a></li>
                <li><a href="deluxe_rooms.php"><i class="fas fa-bed"></i> Deluxe Rooms</a></li>
                <li><a href="suites.php"><i class="fas fa-bed"></i> Suites Rooms</a></li>
            </ul>
        </li>
        <li><a href="contact.php">𝙲𝙾𝙽𝚃𝙰𝙲𝚃</a></li>
        <li><a href="status.php">𝚂𝚃𝙰𝚃𝚄𝚂</a></li>
    </ul>
</nav>

<header>
    <div class="container">
        <h1>Contact Us</h1>
        <p>We're here to help! Reach out to us using the form below or find us on social media.</p>
    </div>
</header>

<div class="container">
    <div class="contact-form">
        <div class="form-section">
            <h2>Send us a Message</h2>
            <form id="contactForm">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Your Name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="Your Email" required>
                </div>
                <div class="form-group">
                    <label for="message">Your Message</label>
                    <textarea id="message" name="message" rows="5" placeholder="Type your message here" required></textarea>
                </div>
                <button type="submit" onclick>Submit</button>
            </form>
        </div>

        <div class="map-section">
            <h2>Find Us Here</h2>
            <iframe src="https://www.google.com/maps/embed?pb=!3m2!1sen!2sph!4v1736561263473!5m2!1sen!2sph!6m8!1m7!1s4wObEWybGcB7fRHiLJ3Tjw!2m2!1d8.481899309612553!2d124.6360556848662!3f212.29752!4f0!5f0.7820865974627469" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>

    <!-- Social Media Links -->
    <div class="social-links">
        <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook"></i></a>
        <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
        <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
        <a href="https://linkedin.com" target="_blank"><i class="fab fa-linkedin"></i></a>
    </div>
</div>

<script>
    document.getElementById("contactForm").addEventListener("submit", function(event) {
        event.preventDefault();

        const formData = {
            name: document.getElementById("name").value.trim(),
            email: document.getElementById("email").value.trim(),
            message: document.getElementById("message").value.trim(),
        };

        emailjs.send("service_pzhbcem", "template_jc5v9j7", formData)
            .then(() => {
                alert("Your message has been sent successfully!");
                document.getElementById("contactForm").reset();
            })
            .catch(error => {
                alert("Failed to send your message. Please try again.");
                console.error("EmailJS Error:", error);
            });
    });
</script>

</body>
</html>
