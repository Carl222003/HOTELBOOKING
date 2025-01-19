<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotelbooking";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to update booking status
function updateBookingStatus($conn, $booking_id, $new_status) {
    $sql = "UPDATE bookings SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("si", $new_status, $booking_id);
        $stmt->execute();
        $stmt->close();
        return true;
    } else {
        return false;
    }
}

// Function to delete a user
function deleteUser($conn, $user_id) {
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
        return true;
    } else {
        return false;
    }
}

// Update the user function to handle password and avatar
function updateUser($conn, $user_id, $name, $email, $password = null, $avatar = null) {
    // If password is provided, update it along with other fields
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $email, $hashed_password, $user_id);
    } else {
        // Only update name and email
        $sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $email, $user_id);
    }
    
    return $stmt->execute();
}

// Add new function to fetch booking by ID
function getBookingById($conn, $booking_id) {
    $sql = "SELECT id, room_type, room_name, check_in, check_out, guest_name, guest_email, phone, reg_date, updated_check_out, status, user_id, room_id 
            FROM bookings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $booking = $result->fetch_assoc();
        $stmt->close();
        return $booking;
    }
    return null;
}

// Add new function to update booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_booking'])) {
    $booking_id = $_POST['booking_id'];
    $guest_name = $_POST['guest_name'];
    $guest_email = $_POST['guest_email'];
    $phone = $_POST['phone'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $room_type = $_POST['room_type'];
    $room_name = $_POST['room_name'];
    $status = $_POST['status'];

    $sql = "UPDATE bookings SET 
            guest_name = ?, 
            guest_email = ?,
            phone = ?,
            check_in = ?,
            check_out = ?,
            room_type = ?,
            room_name = ?,
            status = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssssssi", $guest_name, $guest_email, $phone, $check_in, $check_out, $room_type,$room_name, $status, $booking_id);
        if ($stmt->execute()) {
            echo "<script>alert('Booking updated successfully!');</script>";
        } else {
            echo "<script>alert('Error updating booking.');</script>";
        }
        $stmt->close();
    }
}

// Modify the user update handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? $_POST['password'] : null;
    
    if (updateUser($conn, $user_id, $name, $email, $password)) {
        echo "<script>alert('User updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating user.');</script>";
    }
}

// Handle form submission to update status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $booking_id = intval($_POST['booking_id']);
    $new_status = $_POST['update_status']; // "Confirmed" or "Cancelled"
    
    if (updateBookingStatus($conn, $booking_id, $new_status)) {
        echo "<script>alert('Booking status updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating booking status.');</script>";
}}

// Handle form submission to delete user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = intval($_POST['user_id']);
    
    if (deleteUser($conn, $user_id)) {
        echo "<script>alert('User deleted successfully!');</script>";
    } else {
        echo "<script>alert ('Error deleting user.');</script>";
    }
}

// Fetch total users
$sql_users = "SELECT COUNT(*) AS total_users FROM users";
$result_users = $conn->query($sql_users);
$total_users = $result_users->fetch_assoc()['total_users'];

// Fetch total bookings
$sql_bookings = "SELECT COUNT(*) AS total_bookings FROM bookings";
$result_bookings = $conn->query($sql_bookings);
$total_bookings = $result_bookings->fetch_assoc()['total_bookings'];

$sql_rejected_bookings = "SELECT COUNT(*) AS rejected_bookings FROM bookings WHERE status = 'Rejected'";
$result_rejected_bookings = $conn->query($sql_rejected_bookings);
$rejected_bookings = $result_rejected_bookings->fetch_assoc()['rejected_bookings'];

$sql_deluxe_bookings = "SELECT COUNT(*) AS deluxe_bookings FROM bookings WHERE room_type = 'Deluxe'";
$result_deluxe_bookings = $conn->query($sql_deluxe_bookings);
$deluxe_bookings = $result_deluxe_bookings->fetch_assoc()['deluxe_bookings'];

$sql_standard_bookings = "SELECT COUNT(*) AS standard_bookings FROM bookings WHERE room_type = 'Standard'";
$result_standard_bookings = $conn->query($sql_standard_bookings);
$standard_bookings = $result_standard_bookings->fetch_assoc()['standard_bookings'];

$sql_suite_bookings = "SELECT COUNT(*) AS suite_bookings FROM bookings WHERE room_type = 'Suite'";
$result_suite_bookings = $conn->query($sql_suite_bookings);
$suite_bookings = $result_suite_bookings->fetch_assoc()['suite_bookings'];
// Fetch pending bookings
$sql_pending_bookings = "SELECT COUNT(*) AS pending_bookings FROM bookings WHERE status = 'Pending'";
$result_pending_bookings = $conn->query($sql_pending_bookings);
$pending_bookings = $result_pending_bookings->fetch_assoc()['pending_bookings'];

// Fetch total confirmed bookings
$sql_confirmed_bookings = "SELECT COUNT(*) AS confirmed_bookings FROM bookings WHERE status = 'Confirmed'";
$result_confirmed_bookings = $conn->query($sql_confirmed_bookings);
$confirmed_bookings = $result_confirmed_bookings->fetch_assoc()['confirmed_bookings'];

// Fetch all users
$sql_all_users = "SELECT * FROM users ORDER BY id DESC";
$result_all_users = $conn->query($sql_all_users);

// Fetch all pending bookings
$sql_pending_booking_list = "SELECT * FROM bookings WHERE status = 'Pending' ORDER BY id DESC";
$result_pending_booking_list = $conn->query($sql_pending_booking_list);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Hotel Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* General Styling */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f1f3f8;
            color: #444;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background-color: #212529;
            color: #fff;
            position: fixed;
            height: 100%;
            padding-top: 30px;
            transition: width 0.3s;
        }
        .sidebar:hover {
            width: 300px;
        }
        .sidebar h2 {
            text-align: center;
            color: #fff;
            margin-bottom: 30px;
            font-size: 24px;
            letter-spacing: 1px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 15px 20px;
            transition: background 0.2s;
        }
        .sidebar ul li a {
            color: #fff;
            display: block;
            font-size: 16px;
        }
        .sidebar ul li:hover {
            background-color: #495057;
            border-radius: 4px;
        }
        .sidebar ul li a:hover {
            color: #ffc107;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 20px;
            background-color: #f1f3f8;
            min-height: 100vh;
            transition: margin-left 0.3s;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
        }
        .header h1 {
            font-size: 24px;
            color: #212529;
        }
        .logout-btn {
            background-color: #dc3545;
            color: #fff;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }

        /* Dashboard Metrics */
        .dashboard-metrics {
            display: flex;
            justify-content: space-between;
            gap: 30px;
            margin-top: 40px;
            padding: 0 20px;
            flex-wrap: wrap;
        }

        .metric-card {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            text-align: center;
            flex: 1;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            max-width: 320px;
            min-width: 250px;
        }

        .metric-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .metric-card h3 {
            font-size: 20px;
            color: #6c757d;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .metric-card p {
            font-size: 36px;
            color: #007bff;
            font-weight: bold;
            margin: 0;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f8f9fc;
            color: #343a40;
        }
        table tr:hover {
            background-color: #f1f3f5;
        }

        /* Buttons */
        .btn {
            padding: 8px 16px;
            font-size: 14px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            transition: opacity 0.2s;
        }
        .btn-confirm {
            background-color: #28a745;
            color: #fff;
        }
        .btn-cancel {
            background-color: #dc3545;
            color: #fff;
        }
        .btn-edit {
            background-color: #ffc107;
            color: #212529;
        }
        .btn-delete {
            background-color: #dc3545;
            color: #fff;
        }
        .btn:hover {
            opacity: 0.8;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
            position: relative;
        }

        .close {
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 28px;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .update-btn {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .update-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="javascript:void(0);" id="dashboard-link">Dashboard</a></li>
            <li><a href="javascript:void(0);" id="users-link">User List</a></li>
            <li><a href="javascript:void(0);" id="bookings-link">Pending Bookings</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>Admin Dashboard</h1>
            <a href="login.php" class="logout-btn">Logout</a>
        </div>

        <!-- Dashboard Section -->
        <div id="dashboard" class="dashboard-section">
            <div class="dashboard-metrics">
                <div class="metric-card">
                    <h3>Total Users</h3>
                    <p><?php echo $total_users; ?></p>
                </div>
                <div class="metric-card">
                    <h3>Total Bookings</h3>
                    <p><?php echo $total_bookings; ?></p>
                </div>
                <div class="metric-card">
                    <h3>Pending Bookings</h3>
                    <p><?php echo $pending_bookings; ?></p>
                </div>
                <div class="metric-card">
                    <h3>Confirmed Bookings</h3>
                    <p><?php echo $confirmed_bookings; ?></p>
                </div>
                <div class="metric-card">
                    <h3>Total Rejected</h3>
                    <p><?php echo $rejected_bookings; ?></p>
                </div>
                <div class="metric-card">
                    <h3>Deluxe Bookings</h3>
                    <p><?php echo $deluxe_bookings; ?></p>
                </div>
                <div class="metric-card">
                    <h3>Standard Bookings</h3>
                    <p><?php echo $standard_bookings; ?></p>
                </div>
                <div class="metric-card">
                    <h3>Suite Bookings</h3>
                    <p><?php echo $suite_bookings; ?></p>
                </div>
            </div>
        </div>

        <!-- Users Section -->
        <div id="users-section" style="display:none;">
            <h2>Users</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $result_all_users->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['name']; ?></td>
                            <td><?php echo $user['email']; ?></td>
            
                            <td>
                                <button onclick="openUserModal(<?php echo $user['id']; ?>)" class="btn btn-edit">Edit</button>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" name="delete_user" class="btn btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Bookings Section -->
        <div id="bookings-section" style="display:none;">
            <h2>Pending Bookings</h2>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Room Type</th>
                        <th>Room Name</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Reg_Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($booking = $result_pending_booking_list->fetch_assoc()): ?>
                        <tr>
                            <td><?= $booking['id'] ?></td>
                            <td><?= $booking['room_type'] ?></td>
                            <td><?= $booking['room_name'] ?></td>
                            <td><?= $booking['guest_name'] ?></td>
                            <td><?= $booking['guest_email'] ?></td>
                            <td><?= $booking['phone'] ?></td>
                            <td><?= $booking['check_in'] ?></td>
                            <td><?= $booking['check_out'] ?></td>
                            <td><?= $booking['reg_date'] ?></td>
                            <td><?= $booking['status'] ?></td>
                            <td>
                                <form action="" method="POST" style="display: inline;">
                                    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                    <input type="hidden" name="update_status" value="Confirmed">
                                    <button type="submit" class="btn btn-confirm">Confirm</button>
                                </form>
                                <form action="" method="POST" style="display: inline;">
                                    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                    <input type="hidden" name="update_status" value="Rejected">
                                    <button type="submit" class="btn btn-cancel">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for updating booking -->
    <div id="updateModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Update Booking</h2>
            <form id="updateForm" method="POST">
                <input type="hidden" name="update_booking" value="1">
                <input type="hidden" name="booking_id" id="modal_booking_id">
                
                <div class="form-group">
                    <label for="guest_name">Guest Name:</label>
                    <input type="text" id="modal_guest_name" name="guest_name" required>
                </div>

                <div class="form-group">
                    <label for="guest_email">Email:</label>
                    <input type="email" id="modal_guest_email" name="guest_email" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" id="modal_phone" name="phone" required>
                </div>

                <div class="form-group">
                    <label for="check_in">Check-in Date:</label>
                    <input type="date" id="modal_check_in" name="check_in" required>
                </div>

                <div class="form-group">
                    <label for="check_out">Check-out Date:</label>
                    <input type="date" id="modal_check_out" name="check_out" required>
                </div>

                <div class="form-group">
                    <label for="room_type">Room Type:</label>
                    <select id="modal_room_type" name="room_type" required>
                        <option value="Standard">Standard</option>
                        <option value="Deluxe">Deluxe</option>
                        <option value="Suite">Suite</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="modal_status" name="status" required>
                        <option value="Pending">Pending</option>
                        <option value="Confirmed">Confirmed</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>

                <button type="submit" class="update-btn">Update Booking</button>
            </form>
        </div>
    </div>

    <!-- Modal for updating user -->
    <div id="userModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit User</h2>
            <form id="userForm" method="POST">
                <input type="hidden" name="update_user" value="1">
                <input type="hidden" name="user_id" id="modal_user_id">

                <div class="form-group">
                    <label for="user_name">Name:</label>
                    <input type="text" id="modal_user_name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="user_email">Email:</label>
                    <input type="email" id="modal_user_email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="user_password">Password:</label>
                    <input type="password" id="modal_user_password" name="password">
                </div>

                <button type="submit" class="update-btn">Update User</button>
            </form>
        </div>
    </div>

    <script>
        // Event listeners to switch between sections
        document.getElementById('dashboard-link').addEventListener('click', function() {
            showSection('dashboard');
        });

        document.getElementById('users-link').addEventListener('click', function() {
            showSection('users-section');
        });

        document.getElementById('bookings-link').addEventListener('click', function() {
            showSection('bookings-section');
        });

        function showSection(sectionId) {
            // Hide all sections
            document.getElementById('dashboard').style.display = 'none';
            document.getElementById('users-section').style.display = 'none';
            document.getElementById('bookings-section').style.display = 'none';
            
            // Show the selected section
            document.getElementById(sectionId).style.display = 'block';
        }

        // Modal handling (for booking)
        const modal = document.getElementById('updateModal');
        const span = document.getElementsByClassName('close')[0];

        async function openUpdateModal(bookingId) {
            try {
                const response = await fetch('get_booking.php?id=' + bookingId);
                const booking = await response.json();
                
                document.getElementById('modal_booking_id').value = booking.id;
                document.getElementById('modal_guest_name').value = booking.guest_name;
                document.getElementById('modal_guest_email').value = booking.guest_email;
                document.getElementById('modal_phone').value = booking.phone;
                document.getElementById('modal_check_in').value = booking.check_in;
                document.getElementById('modal_check_out').value = booking.check_out;
                document.getElementById('modal_room_type').value = booking.room_type;
                document.getElementById('modal_room_name').value = booking.room_name;
                document.getElementById('modal_status').value = booking.status;
                
                modal.style.display = 'block';
            } catch (error) {
                console.error('Error fetching booking details:', error);
            }
        }

        span.onclick = function() {
            modal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
            if (event.target == userModal) {
                userModal.style.display = 'none';
            }
        }

        // Modal handling (for user)
        const userModal = document.getElementById('userModal');

        async function openUserModal(userId) {
            try {
                const response = await fetch('get_user.php?id=' + userId);
                const user = await response.json();
                
                document.getElementById('modal_user_id').value = user.id;
                document.getElementById('modal_user_name').value = user.name;
                document.getElementById('modal_user_email').value = user.email;
                document.getElementById('modal_user_password').value = ''; // Clear password field
                
                userModal.style.display = 'block';
            } catch (error) {
                console.error('Error fetching user details:', error);
            }
        }

        function closeUserModal() {
            userModal.style.display = 'none';
        }
    </script>
</body>
</html>
