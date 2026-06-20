<?php
session_start();
require 'src/database.php';

$database = new Database();
$conn = $database->getConnection();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: login.php');
    exit;
}

$booking_id = intval($_GET['booking_id']);
$user_id = $_SESSION['user_id'];

$booking_query = "
    SELECT 
        b.bookingid AS booking_id, 
        v.name AS venue_name, 
        v.image AS venue_image, 
        b.special_request AS special_request, 
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
        b.bookingid = ? AND b.user_id = ?
";

$stmt = $conn->prepare($booking_query);
if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    echo "<br><a href='index.php'>< Go back</a>";
}
$stmt->bind_param("ii", $booking_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Booking not found or you don't have access to this booking.");
}

$booking = $result->fetch_assoc();

// Handle payment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pay_now'])) {
    if ($booking['payment_status'] == 'paid') {
        echo "This booking has already been paid";
        echo "<br><a href='index.php'>< Go back</a>";
    }

    $total_cost = $booking['total_cost'];

    $update_payment_status_query = "UPDATE payments SET status = 'paid' WHERE booking_id = ?";
    $stmt = $conn->prepare($update_payment_status_query);
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        echo "<br><a href='index.php'>< Go back</a>";
    }
    $stmt->bind_param("i", $booking_id);
    if ($stmt->execute()) {
        $update_booking_status_query = "UPDATE bookings SET status = 'accepted' WHERE bookingid = ?";
        $stmt = $conn->prepare($update_booking_status_query);
        if (!$stmt) {
            echo "Error preparing statement: " . $conn->error;
            echo "<br><a href='index.php'>< Go back</a>";
        }
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();

        header('Location: success-payment.php');
        exit;
    } else {
        echo "<p style='color: red;'>Error updating payment status: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Payment</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .main-container {
            display: flex;
            justify-content: space-between;
        }

        .left-column,
        .right-column {
            flex: 1;
            padding: 20px;
        }

        .image-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .image-container img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <div style="background-image: url('images/front.jpg'); background-size:cover; background-repeat:no-repeat;">
        <?php include 'src/header.php'; ?>
    </div>

    <div class="main-container">
        <div class="dual-container">
            <div class="dual-column left-column">
                <div class="divider">
                    <h1>Contact details</h1>
                </div>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?booking_id=<?php echo htmlspecialchars($booking_id); ?>">
                    <fieldset>
                        <legend>Full Name</legend>
                        <input type="text" id="fullname" name="fullname">
                    </fieldset>
                    <fieldset>
                        <legend>Email</legend>
                        <input type="email" id="email" name="email">
                    </fieldset>
                    <fieldset>
                        <legend>Phone Number</legend>
                        <input type="text" id="phone" name="phone">
                    </fieldset>
                    <div class="divider">
                        <h1>Payment method</h1>
                    </div>
                    <div class="payment-options">
                        <div class="option">
                            <input type="radio" id="debit-card" name="payment-method" value="debit-card">
                            <label for="debit-card">Debit/Credit card</label>
                        </div>
                        <div class="option">
                            <input type="radio" id="bank-transfer" name="payment-method" value="bank-transfer">
                            <label for="bank-transfer">Bank Transfer</label>
                        </div>
                    </div>
                    <fieldset>
                        <legend>Name on Card</legend>
                        <input type="text" id="cardname" name="cardname">
                    </fieldset>
                    <fieldset>
                        <legend>Debit/Credit card number</legend>
                        <input type="text" id="creditcard" name="creditcard" placeholder="0000 0000 0000 0000" minlength="16" maxlength="19">
                    </fieldset>
                    <p>Expiration Date</p>
                    <div class="expiration-container">
                        <fieldset>
                            <legend>Month</legend>
                            <select id="month" name="month">
                                <option value="">Select month</option>
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </fieldset>
                        <fieldset>
                            <legend>Year</legend>
                            <select id="year" name="year">
                                <option value="">Select year</option>
                                <?php
                                $currentYear = date("Y");
                                for ($i = 0; $i <= 8; $i++) {
                                    $year = $currentYear + $i;
                                    echo "<option value=\"$year\">$year</option>";
                                }
                                ?>
                            </select>
                        </fieldset>
                        <fieldset>
                            <legend>Security code</legend>
                            <input type="number" id="securitycode" name="securitycode" min="100" max="999">
                        </fieldset>
                        <fieldset>
                            <legend>Billing ZIP code</legend>
                            <input type="number" id="zipcode" name="zipcode" max="99999">
                        </fieldset>
                    </div>
                    <input type="submit" class="link-button" name="pay_now" value="Submit Payment">
                </form>
            </div>
            <div class="dual-column right-column">
                <div class="divider">
                    <h1>Venue details</h1>
                </div>
                <div class="image-container">
                    <img src="images/<?php echo htmlspecialchars($booking['venue_image']); ?>" alt="Venue Image">
                </div>
                <h2>Booking Details</h2>
                <p><strong>Venue Name:</strong> <?php echo htmlspecialchars($booking['venue_name']); ?></p>
                <p><strong>Special Request:</strong> <?php echo htmlspecialchars($booking['special_request']); ?></p>
                <p><strong>Start Date:</strong> <?php echo htmlspecialchars($booking['start_date']); ?></p>
                <p><strong>End Date:</strong> <?php echo htmlspecialchars($booking['end_date']); ?></p>
                <p><strong>Total Days:</strong> <?php echo htmlspecialchars($booking['total_days']); ?></p>
                <p><strong>Total Cost:</strong> <?php echo htmlspecialchars($booking['total_cost']); ?></p>
                <?php if ($booking['payment_status'] == 'paid'): ?>
                    <p><strong>Payment Status:</strong> <span ></span></p>
                <?php else: ?>
                    <p><strong>Payment Status:</strong> Unpaid</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <footer class="footer">
        <?php include 'src/footer.php'; ?>
    </footer>
</body>

</html>
