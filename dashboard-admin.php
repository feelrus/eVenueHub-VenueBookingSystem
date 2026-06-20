<?php
session_start();

// Check if the user is logged in & user role is 0
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin'] || $_SESSION['user_role'] != 0) {
    header('Location: index.php');
    exit;
}

require 'src/database.php';
$database = new Database();
$conn = $database->getConnection();

// Check connection
if (!$conn) {
    echo "Error creating database: " . $conn->connect_error;
    echo "<br><a href='index.php'>< Go back</a>";
    exit;
}

$removeVenueMessage = "";

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['user_email'];

$name_error = $email_error = $current_password_error = $new_password_error = $confirm_password_error = $profile_update_error = $password_update_error = '';

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];

    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format.";
    } else {
        if (!empty($new_name) && !empty($new_email)) {
            $update_query = "UPDATE users SET username = ?, email = ? WHERE userid = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ssi", $new_name, $new_email, $user_id);

            if ($stmt->execute()) {
                $_SESSION['username'] = $new_name;
                $_SESSION['user_email'] = $new_email;
                echo "<p style='color: green;'>Profile updated successfully.</p>";
            } else {
                $profile_update_error = "Error updating profile: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $profile_update_error = "Please fill in all fields.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_password'])) {
    $current_password = $_POST['password'];
    $new_password = $_POST['newpassword'];
    $confirm_password = $_POST['confirmpassword'];

    if (strlen($new_password) < 8) {
        $new_password_error = "Password must be at least 8 characters long.";
    } elseif ($new_password !== $confirm_password) {
        $confirm_password_error = "New passwords do not match.";
    } else {
        $query = "SELECT password FROM users WHERE userid = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (password_verify($current_password, $user['password'])) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $update_query = "UPDATE users SET password = ? WHERE userid = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("si", $hashed_password, $user_id);

            if ($stmt->execute()) {
                echo "<p style='color: green;'>Password updated successfully.</p>";
            } else {
                $password_update_error = "Error updating password: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $current_password_error = "Current password is incorrect.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $booking_id = intval($_POST['booking_id']);
    $action = $_POST['action'];

    if ($action === 'Approve' || $action === 'Reject') {
        $new_status = ($action === 'Approve') ? 'accepted' : 'rejected';

        $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE bookingid = ?");
        $stmt->bind_param("si", $new_status, $booking_id);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Booking request $new_status successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error updating booking request: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p style='color: red;'>Invalid action.</p>";
    }
}

$create_venue_error = $create_venue_success = '';

// Handle create venue

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['createvenue'])) {
    $venueName = $_POST['venueName'];
    $description = $_POST['description'];
    $event_id = $_POST['event_id'];
    $location_id = $_POST['location_id'];
    $address = $_POST['address'];
    $price = $_POST['price'];
    $pax = $_POST['pax'];

    if (empty($venueName) || empty($description) || empty($event_id) || empty($location_id) || empty($address) || empty($price) || empty($pax)) {
        $create_venue_error = "All fields are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO venues (name, description, event_id, location_id, address, price, pax, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssiisdi", $venueName, $description, $event_id, $location_id, $address, $price, $pax);

        if ($stmt->execute()) {
            $venueID = $stmt->insert_id;

            if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $imageTmpName = $_FILES['image']['tmp_name'];
                $imageExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

                $newImageName = 'image_' . $venueID . '.' . $imageExtension;
                $imagePath = 'images/' . $newImageName;

                if (move_uploaded_file($imageTmpName, $imagePath)) {
                    $updateStmt = $conn->prepare("UPDATE venues SET image = ? WHERE venueid = ?");
                    $updateStmt->bind_param("si", $newImageName, $venueID);

                    if ($updateStmt->execute()) {
                        $create_venue_success = "New venue created successfully with image.";
                    } else {
                        $create_venue_error = "Error updating image: " . $updateStmt->error;
                    }

                    $updateStmt->close();
                } else {
                    $create_venue_error = "Error moving the uploaded file.";
                }
            } else {
                $create_venue_success = "New venue created successfully.";
            }

        } else {
            $create_venue_error = "Error creating venue: " . $stmt->error;
        }

        $stmt->close();
    }
}

$events = [];
$events_query = "SELECT * FROM events";
$events_result = $conn->query($events_query);

if ($events_result->num_rows > 0) {
    while ($row = $events_result->fetch_assoc()) {
        $events[] = $row;
    }
}

$locations = [];
$locations_query = "SELECT * FROM locations";
$locations_result = $conn->query($locations_query);

if ($locations_result->num_rows > 0) {
    while ($row = $locations_result->fetch_assoc()) {
        $locations[] = $row;
    }
}

$booking_requests = [];
$query = "SELECT b.bookingid, v.name AS venue_name, u.username AS user_name, b.start_date, b.end_date, b.total_days, b.status, b.special_request, b.remarks
          FROM bookings b
          JOIN users u ON b.user_id = u.userid
          JOIN venues v ON b.venue_id = v.venueid
          WHERE b.status = 'pending'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $booking_requests[] = $row;
    }
}

// Handle user role update request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_role'])) {
    $selected_user_id = $_POST['username'];
    $new_role = $_POST['role'];

    // Prevent Superadmin from changing their own role
    if ($selected_user_id == $_SESSION['username']) {
        $role_update_error = "You cannot change your own role.";
    } else {
        $update_role_query = "UPDATE users SET role = ? WHERE userid = ?";
        $stmt = $conn->prepare($update_role_query);
        $stmt->bind_param("ii", $new_role, $selected_user_id);

        if ($stmt->execute()) {
            echo "User role updated successfully.";
        } else {
            echo "Error updating user role: " . $stmt->error;
        }
        $stmt->close();
    }
}

$users = [];
$query = "SELECT userid, username, email, role FROM users";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div style="background-image: url('images/front.jpg'); background-size:cover; background-repeat:no-repeat;">
        <?php include 'src/header.php'; ?>
    </div>

    <!-- main container -->
    <div class="main-container">

        <div class="divider">
            <h1>My Admin Account</h1>
        </div>

        <div class="white-container">

            <div class="white-form">

                <h2>Profile Control Panel</h2>

                <div class="panel-container">
                    <button class="collapsible">Profile</button>
                    <div class="panel-content">
                        <!-- Add form or panel-content for creating a venue -->

                        <!-- profile information container -->
                        <br>
                        <div class="white-container">

                            <div class="white-form">

                                <h2>Profile Information</h2>

                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                                    <fieldset>
                                        <legend>Name</legend>
                                        <input type="text" id="name" name="name"
                                            value="<?php echo htmlspecialchars($username); ?>" required>
                                        <?php if (!empty($name_error))
                                            echo "<p style='color: red;'>$name_error</p>"; ?>
                                    </fieldset>

                                    <fieldset>
                                        <legend>Email</legend>
                                        <input type="email" id="email" name="email"
                                            value="<?php echo htmlspecialchars($email); ?>" required>
                                        <?php if (!empty($email_error))
                                            echo "<p style='color: red;'>$email_error</p>"; ?>
                                    </fieldset>

                                    <input type="submit" name="submit" value="Save">
                                    <?php if (!empty($profile_update_error))
                                        echo "<p style='color: red;'>$profile_update_error</p>"; ?>

                                </form>

                                <!-- white-form end -->
                            </div>

                            <!-- white container end -->
                        </div>

                        <!-- password container -->
                        <div class="white-container">

                            <div class="white-form">

                                <h2>Update Password</h2>

                                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                                    <fieldset>
                                        <legend>Current Password</legend>
                                        <input type="password" id="password" name="password" required>
                                        <?php if (!empty($current_password_error))
                                            echo "<p style='color: red;'>$current_password_error</p>"; ?>
                                    </fieldset>

                                    <fieldset>
                                        <legend>New Password</legend>
                                        <input type="password" id="newpassword" name="newpassword" required>
                                        <?php if (!empty($new_password_error))
                                            echo "<p style='color: red;'>$new_password_error</p>"; ?>
                                    </fieldset>

                                    <fieldset>
                                        <legend>Confirm Password</legend>
                                        <input type="password" id="confirmpassword" name="confirmpassword" required>
                                        <?php if (!empty($confirm_password_error))
                                            echo "<p style='color: red;'>$confirm_password_error</p>"; ?>
                                    </fieldset>

                                    <input type="submit" name="update_password" value="Save">
                                    <?php if (!empty($password_update_error))
                                        echo "<p style='color: red;'>$password_update_error</p>"; ?>

                                </form>

                                <!-- white-form end -->
                            </div>

                            <!-- white container end -->
                        </div>
                    </div>
                </div>

                <h2>Admin Control Panel</h2>

                <!-- Create Venue Panel -->
                <div class="panel-container">
                    <button class="collapsible">Create Venue</button>
                    <div class="panel-content">
                        <br>
                        <form method="post" enctype="multipart/form-data"
                            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <fieldset>
                                <legend>Event Types</legend>
                                <select name="event_id" id="event" required>
                                    <option value="">Select event</option>
                                    <?php
                                    foreach ($events as $event) {
                                        echo "<option value='{$event['event_id']}'>{$event['event_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </fieldset>
                            <fieldset>
                                <legend>Image</legend>
                                <input type="file" id="image" name="image" accept="image/png,image/jpeg" required>
                                <p id="imageError" class="error-message"></p>
                            </fieldset>
                            <fieldset>
                                <legend>Venue Name</legend>
                                <input type="text" id="venueName" name="venueName" required>
                            </fieldset>
                            <fieldset>
                                <legend>Description</legend>
                                <textarea id="description" style="width:100%;" name="description" required></textarea>
                            </fieldset>
                            <fieldset>
                                <legend>Location</legend>
                                <select name="location_id" id="location" required>
                                    <option value="">Select location</option>
                                    <?php
                                    foreach ($locations as $location) {
                                        echo "<option value='{$location['location_id']}'>{$location['location_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </fieldset>
                            <fieldset>
                                <legend>Address</legend>
                                <input type="text" id="address" name="address" required>
                            </fieldset>
                            <fieldset>
                                <legend>Price</legend>
                                <input type="number" min="0.01" step="0.01" id="price" name="price" required>
                            </fieldset>
                            <fieldset>
                                <legend>PAX (Capacity)</legend>
                                <input type="number" min="1" id="pax" name="pax" required>
                            </fieldset>

                            <input type="submit" name="createvenue" value="Create Venue">
                        </form>
                    </div>
                </div>

                <!-- Booking Panel -->
                <div class="panel-container">
                    <button class="collapsible">Booking Panel</button>
                    <div class="panel-content">
                        <h2>Booking Requests</h2>
                        <div class="scrollable-content">
                            <table id="bookingRequests">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>User</th>
                                        <th>Venue Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Total Days</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($booking_requests)): ?>
                                        <?php foreach ($booking_requests as $request): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($request['bookingid']); ?></td>
                                                <td><?php echo htmlspecialchars($request['user_name']); ?></td>
                                                <td><?php echo htmlspecialchars($request['venue_name']); ?></td>
                                                <td><?php echo htmlspecialchars($request['start_date']); ?></td>
                                                <td><?php echo htmlspecialchars($request['end_date']); ?></td>
                                                <td><?php echo htmlspecialchars($request['total_days']); ?></td>
                                                <td><?php echo htmlspecialchars($request['status']); ?></td>
                                                <td>
                                                    <span>
                                                        <form method="post" style="display:inline;">
                                                            <input type="hidden" name="booking_id"
                                                                value="<?php echo htmlspecialchars($request['bookingid']); ?>">
                                                            <input type="submit" name="action" value="Approve">
                                                        </form>
                                                        <form method="post" style="display:inline;">
                                                            <input type="hidden" name="booking_id"
                                                                value="<?php echo htmlspecialchars($request['bookingid']); ?>">
                                                            <input type="submit" name="action" value="Reject">
                                                        </form>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8">No booking requests available</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- User Control -->
                <div class="panel-container">
                    <button class="collapsible">User Control</button>
                    <div class="panel-content">
                        <?php
                        if (!empty($role_update_success))
                            echo "<p style='color: green;'>$role_update_success</p>";
                        if (!empty($role_update_error))
                            echo "<p style='color: red;'>$role_update_error</p>";
                        ?>
                        <h2>Manage Users</h2>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <fieldset>
                                <legend>Enter Username to Update Role</legend>
                                <input type="text" name="username" required placeholder="Enter username">
                            </fieldset>
                            <fieldset>
                                <legend>New Role</legend>
                                <select name="role" required>
                                    <option value="">Select role</option>
                                    <option value="0">Superadmin</option>
                                    <option value="1">User</option>
                                </select>
                            </fieldset>
                            <input type="submit" name="update_role" value="Update Role">
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <footer class="footer">
        <?php include 'src/footer.php'; ?>
    </footer>

    <script src="js/panel.js"></script>
</body>

</html>