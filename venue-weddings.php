<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Weddings</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <!-- header -->
    <div style="background-image: url('images/front.jpg'); background-size:cover; background-repeat:no-repeat;">
        <?php include 'src/header.php'; ?>
    </div>

    <!-- main container -->
    <div class="main-container">
        <h1>Wedding Venues in Selangor Area</h1>

        <div class="venue-main-container">

            <?php
            require 'src/database.php';
            $database = new Database();
            $conn = $database->getConnection();

            if ($conn->connect_error) {
                echo "Error connecting to database: " . $conn->connect_error;
                echo "<br><a href='index.php'>< Go back</a>";
                exit;
            }

            $sql = "SELECT v.*, l.location_name 
                    FROM venues v
                    JOIN locations l ON v.location_id = l.location_id
                    JOIN events e ON v.event_id = e.event_id
                    WHERE e.event_name = 'wedding'";
            $result = $conn->query($sql);

            if ($result === false) {
                echo "Error executing query: " . $conn->error;
                exit;
            }

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $venueID = $row['venueid'];
                    $venueName = htmlspecialchars($row['name']);
                    $venueDescription = htmlspecialchars($row['description']);
                    $venueLocation = htmlspecialchars($row['location_name']);
                    $venueAddress = htmlspecialchars($row['address']);
                    $venuePrice = htmlspecialchars($row['price']);
                    $venueImage = 'images/' . htmlspecialchars($row['image']);
                    $venuePax = htmlspecialchars($row['pax']);

                    echo '<div class="venue-container">';
                    echo '    <div class="venue">';
                    echo '        <img src="' . $venueImage . '" alt="' . $venueName . '">';
                    echo '        <h3>' . $venueLocation . '</h3>';
                    echo '        <h1>' . $venueName . '</h1>';
                    echo '        <div class="venue-description">';
                    echo '            <h3>FROM RM' . $venuePrice . ' PER DAY</h3>';
                    echo '            <h3 class="icon" style="background-image: url(\'images/pax.png\'); padding-left:20px;">' . $venuePax . '</h3>';
                    echo '        </div>';
                    echo '        <p>' . $venueDescription . '</p>';
                    echo '    </div>';
                    echo '    <div class="button-container">';
                    echo '        <button class="link-button" onclick="openModal(' . $venueID . ')">See venue</button>';
                    echo '    </div>';
                    echo '    <div id="myModal' . $venueID . '" class="modal">';
                    echo '        <div class="modal-content">';
                    echo '            <span class="close" onclick="closeModal(' . $venueID . ')">&times;</span>';
                    echo '            <img src="' . $venueImage . '" alt="' . $venueName . '">';
                    echo '            <div class="venue-description">';
                    echo '                <h3>FROM RM' . $venuePrice . ' PER DAY</h3>';
                    echo '                <h3 class="icon" style="background-image: url(\'images/pax.png\'); padding-left:20px;">' . $venuePax . '</h3>';
                    echo '            </div><br>';
                    echo '            <h1>' . $venueName . '</h1>';
                    echo '            <h3>' . $venueLocation . '</h3>';
                    echo '            <h2>Venue Address</h2>';
                    echo '            <p>' . $venueAddress . '</p>';
                    echo '            <h2>Venue Description</h2>';
                    echo '            <p>' . $venueDescription . '</p>';
                    echo '            <div class="button-container">';
                    echo '                <a href="booking.php?venueID=' . $venueID . '&name=' . urlencode($venueName) . '&location=' . urlencode($venueLocation) . '&address=' . urlencode($venueAddress) . '&price=' . urlencode($venuePrice) . '&pax=' . urlencode($venuePax) . '&image=' . urlencode($venueImage) . '&description=' . urlencode($venueDescription) . '"><button id="sendrequest" class="link-button">Send Request</button></a>';
                    echo '            </div>';
                    echo '        </div>';
                    echo '    </div>';
                    echo '</div>';
                }
            } else {
                echo "No venues found.";
            }

            $conn->close();
            ?>
        </div>

    </div>

    <!-- footer -->
    <footer class="footer">
        <?php include 'src/footer.php'; ?>
    </footer>

    <script src="js/modal.js"></script>
</body>

</html>
