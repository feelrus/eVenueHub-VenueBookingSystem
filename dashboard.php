<?php
session_start();
require 'src/database.php';

$database = new Database();
$conn = $database->getConnection();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: index.php');
    exit;
}

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

// Handle password update
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

$booking_query = "
    SELECT 
        b.bookingid AS booking_id, 
        b.created_at, 
        v.name AS venue_name, 
        b.start_date, 
        b.end_date, 
        b.total_days, 
        b.status AS booking_status,
        p.total_cost,
        p.status AS payment_status
    FROM 
        bookings b
    JOIN 
        venues v ON b.venue_id = v.venueid
    LEFT JOIN 
        payments p ON b.bookingid = p.booking_id
    WHERE 
        b.user_id = ?
    ORDER BY 
        b.start_date DESC
";


$stmt = $conn->prepare($booking_query);
if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit;
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div style="background-image: url('images/front.jpg'); background-size: cover; background-repeat: no-repeat;">
        <?php include 'src/header.php'; ?>
    </div>

    <!-- main container -->
    <div class="main-container">

        <div class="divider">
            <h1>My Account</h1>
        </div>

        <div class="white-container">
            <div class="white-form">
                <h2>Profile Information</h2>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                    <fieldset>
                        <legend>Name</legend>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($username); ?>"
                            required>
                        <?php if (!empty($name_error))
                            echo "<p style='color: red;'>$name_error</p>"; ?>
                    </fieldset>

                    <fieldset>
                        <legend>Email</legend>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>"
                            required>
                        <?php if (!empty($email_error))
                            echo "<p style='color: red;'>$email_error</p>"; ?>
                    </fieldset>

                    <input type="submit" name="submit" value="Save">
                    <?php if (!empty($profile_update_error))
                        echo "<p style='color: red;'>$profile_update_error</p>"; ?>

                </form>
            </div>
        </div>

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
            </div>
        </div>

        <div class="white-container">
            <div class="white-form">
                <h2>Booking History</h2>

                <table style="text-align: center; width: 100%;">
    <thead>
        <tr>
            <th>#</th>
            <th>Booking Created At</th> <!-- Moved created_at here -->
            <th>Venue Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Total Days</th>
            <th>Booking Status</th>
            <th>Total Cost</th>
            <th>Payment Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $counter = 1; 
        while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $counter++; ?></td> 
                <td><?php echo htmlspecialchars($row['created_at']); ?></td> <!-- Display created_at -->
                <td><?php echo htmlspecialchars($row['venue_name']); ?></td>
                <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                <td><?php echo htmlspecialchars($row['total_days']); ?></td>
                <td><?php echo htmlspecialchars($row['booking_status']); ?></td>
                <td><?php echo htmlspecialchars($row['total_cost']); ?></td>
                <td>
                    <?php if ($row['booking_status'] == 'accepted'): ?>
                        <?php echo htmlspecialchars($row['payment_status']); ?>
                    <?php else: ?>
                        <span style="color: gray;">N/A</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($row['booking_status'] == 'accepted' && $row['payment_status'] != 'paid'): ?>
                        <a href="payment.php?booking_id=<?php echo htmlspecialchars($row['booking_id']); ?>"
                            class="pay-now-btn">Pay Now</a>
                    <?php else: ?>
                        <span style="color: gray;">N/A</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>


            </div>
        </div>
    </div>
</body>

</html>
<?php $conn->close(); ?>