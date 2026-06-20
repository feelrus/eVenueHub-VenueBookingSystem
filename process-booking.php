<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require 'src/database.php';

$database = new Database();
$conn = $database->getConnection();

if ($conn->connect_error) {
    die("Error connecting to database: " . $conn->connect_error);
}

try {
    // Get form data
    $venueID = (int)$_POST['venueID'];
    $userID = (int)$_POST['userID'];
    $specialRequest = $_POST['request'];
    $remarks = $_POST['remarks'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    
    // Calculate total days
    $startDateObj = new DateTime($startDate);
    $endDateObj = new DateTime($endDate);
    $totalDays = $startDateObj->diff($endDateObj)->days + 1; // Include end date in the count

    $venueName = $_POST['venueName'];
    $venueLocation = $_POST['venueLocation'];
    $venuePrice = (float)$_POST['venuePrice'];
    $venueImage = $_POST['venueImage'];
    $venueDescription = $_POST['venueDescription'];
    $venueAmenities = json_decode($_POST['venueAmenities'], true);

    // Insert booking
    $sql = "INSERT INTO bookings (venue_id, user_id, special_request, remarks, start_date, end_date, total_days, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("iissssi", $venueID, $userID, $specialRequest, $remarks, $startDate, $endDate, $totalDays);

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $bookingID = $stmt->insert_id;

    // Calculate total cost based on venue price and special requests
    $specialRequestCharge = 0;
    if ($specialRequest == 'furnished') {
        $specialRequestCharge = 500;
    } elseif ($specialRequest == 'catering') {
        $specialRequestCharge = $totalDays * 20;
    } elseif ($specialRequest == 'furnished and catering') {
        $specialRequestCharge = 500 + ($totalDays * 20);
    }
    $totalCost = ($venuePrice * $totalDays) + $specialRequestCharge;

    // Insert payment
    $sql = "INSERT INTO payments (booking_id, total_cost, status) VALUES (?, ?, 'pending')";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("id", $bookingID, $totalCost);

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

    $_SESSION['success'] = "Booking request submitted successfully!";
    header("Location: success-booking.php");
    exit();

} catch (Exception $e) {
    $_SESSION['error'] = "An error occurred: " . $e->getMessage();
    header("Location: booking.php?venueID=" . urlencode($venueID));
    exit();
}
?>
