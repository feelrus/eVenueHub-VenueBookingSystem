<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Booking</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        function validateDates() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            const startDateTime = new Date(startDate + 'T00:00:00');
            const endDateTime = new Date(endDate + 'T00:00:00');

            if (endDateTime < startDateTime) {
                alert('End date must be the same or after the start date.');
                return false;
            }

            return true;
        }
    </script>
</head>

<body>
    <?php
    if (isset($_SESSION['error'])) {
        echo '<p style="color:red;">' . $_SESSION['error'] . '</p>';
        unset($_SESSION['error']);
    }
    ?>

    <div style="background-image: url('images/front.jpg'); background-size:cover; background-repeat:no-repeat;">
        <?php include 'src/header.php'; ?>
    </div>

    <div class="main-container">

        <div class="divider">
            <h1>Selected Venue</h1>
        </div>

        <div class="booking-top-container">
            <?php
            require 'src/database.php';
            $database = new Database();
            $conn = $database->getConnection();

            if ($conn->connect_error) {
                echo "Error connecting to database: " . $conn->connect_error;
                exit;
            }

            $venueID = (int)$_GET['venueID'];

            $sql = "SELECT v.*, l.location_name 
                    FROM venues v
                    JOIN locations l ON v.location_id = l.location_id
                    WHERE v.venueid = ?";
            if (!$stmt = $conn->prepare($sql)) {
                die("Prepare failed: " . $conn->error);
            }

            if (!$stmt->bind_param("i", $venueID)) {
                die("Bind failed: " . $stmt->error);
            }

            if (!$stmt->execute()) {
                die("Execute failed: " . $stmt->error);
            }

            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $venueName = htmlspecialchars($row['name']);
                $venueLocation = htmlspecialchars($row['location_name']);
                $venueAddress = htmlspecialchars($row['address']);
                $venuePrice = htmlspecialchars($row['price']);
                $venueImage = 'images/' . htmlspecialchars($row['image']);
                $venueDescription = htmlspecialchars($row['description']);
                $venuePax = htmlspecialchars($row['pax']);
            } else {
                echo "Venue not found.";
                exit;
            }

            $stmt->close();
            $conn->close();
            ?>

            <img src="<?php echo $venueImage; ?>" alt="booking-top">
            <div class="booking-top">
                <h2><?php echo $venueName; ?></h2>
                <p><?php echo $venueLocation; ?></p>
                <p><?php echo $venueAddress; ?></p>
                <h2>Guide Price</h2>
                <p>RM<?php echo $venuePrice; ?> Per Day</p>
                <h2>Capacity</h2>
                <p><?php echo $venuePax; ?> people</p>
            </div>
        </div>

        <div class="divider">
            <h1>Booking Details</h1>
        </div>

        <div class="booking-container">
            <form method="POST" action="process-booking.php" onsubmit="return validateDates()">
                <div class="booking-form">

                    <fieldset>
                        <legend>Special Request</legend>
                        <select id="request" name="request" required>
                            <option value="">Select by request</option>
                            <option value="none">None</option>
                            <option value="furnished">Furnished only (RM500)</option>
                            <option value="catering">Catering only (RM20/pax)</option>
                            <option value="furnished and catering">Furnished and Catering (RM500 + RM20/pax)</option>
                        </select>
                    </fieldset>

                    <fieldset>
                        <legend>Remarks</legend>
                        <textarea id="remarks" name="remarks" style="width:100%;"></textarea>
                    </fieldset>
                </div>

                <div class="divider">
                    <h1>Time & Date Details</h1>
                </div>

                <div class="booking-form">
                    <fieldset>
                        <legend>Start Date</legend>
                        <input type="date" id="startDate" name="startDate" min="<?php
                        date_default_timezone_set('Asia/Kuala_Lumpur');
                        echo date('Y-m-d', strtotime('+3 day'));
                        ?>" value="<?php echo isset($_SESSION['startDate']) ? $_SESSION['startDate'] : ''; ?>"
                            required>
                    </fieldset>

                    <fieldset>
                        <legend>End Date</legend>
                        <input type="date" id="endDate" name="endDate" min="<?php
                        date_default_timezone_set('Asia/Kuala_Lumpur');
                        echo date('Y-m-d', strtotime('+3 day'));
                        ?>" value="<?php echo isset($_SESSION['endDate']) ? $_SESSION['endDate'] : ''; ?>" required>
                    </fieldset>
                </div>

                <input type="hidden" name="venueID" value="<?php echo $venueID; ?>">
                <input type="hidden" name="userID" value="<?php echo $_SESSION['user_id']; ?>">
                <input type="hidden" name="venueName" value="<?php echo urlencode($venueName); ?>">
                <input type="hidden" name="venueLocation" value="<?php echo urlencode($venueLocation); ?>">
                <input type="hidden" name="venuePrice" value="<?php echo urlencode($venuePrice); ?>">
                <input type="hidden" name="venueImage" value="<?php echo urlencode($venueImage); ?>">
                <input type="hidden" name="venueDescription" value="<?php echo urlencode($venueDescription); ?>">
                <input type="hidden" name="venuePax" value="<?php echo urlencode($venuePax); ?>">

                <div class="button-container" style="padding-top: 30px;">
                    <input type="submit" class="link-button" name="submit" value="Submit Request">
                </div>
            </form>
        </div>
    </div>

    <footer class="footer">
        <?php include 'src/footer.php'; ?>
    </footer>
</body>

</html>
