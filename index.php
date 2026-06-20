<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Homepage</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <!-- header -->
    <div style="background-image: url('images/front.jpg'); background-size:cover; background-repeat:no-repeat;">

        <?php include 'src/header.php'; ?>

        <div class="main-banner" >
            <h1>YOUR VENUE, YOUR VISION, OUR EXPERTISE</h1>
            <h3>Find & book your perfect venue from across Selangor areas</h3>

            <div class="filter-container">
                <?php include 'src/filter.php'; ?>
            </div>

        </div>
    </div>

    <!-- main container -->
    <div
        style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), url('images/front1.jpg'); background-size:cover; background-repeat:no-repeat;">
        <div class="main-container">

            <div class="divider" style="color:white;">
                <h1>Venues Types</h1>
                <p>for events</p>
            </div>

            <div class="gallery-container">
                <div class="gallery">
                    <a href="venue-conferences.php">
                        <img src="images/conference.jpg" alt="feature 1">
                        <div class="text-center">
                            <h1 style="color:white;">Conferences</h1>
                        </div>
                    </a>
                </div>
                <div class="gallery">
                    <a href="venue-meetings.php">
                        <img src="images/meeting.jpg" alt="feature 2">
                        <div class="text-center">
                        <h1 style="color:white;">Meetings</h1>
                        </div>
                    </a>
                </div>
                <div class="gallery">
                    <a href="venue-weddings.php">
                        <img src="images/wedding.jpg" alt="feature 3">
                        <div class="text-center">
                        <h1 style="color:white;">Weddings</h1>
                        </div>
                    </a>
                </div>
                <div class="gallery">
                    <a href="venue-parties.php">
                        <img src="images/party.jpg" alt="feature 4">
                        <div class="text-center">
                        <h1 style="color:white;">Parties</h1>
                        </div>
                    </a>
                </div>
            </div>

            <!-- main container end -->
        </div>
    </div>

    <!-- footer -->
    <footer class="footer">
        <?php include 'src/footer.php'; ?>
    </footer>

</body>

</html>