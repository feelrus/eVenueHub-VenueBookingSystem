<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>About Us</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<div style="background-image: url('images/front.jpg'); background-size:cover; background-repeat:no-repeat;">
    <?php include 'src/header.php'; ?>
    <div class="banner">
        <h1>ABOUT US</h1>
        <h3>Home / About Us</h3>
    </div>

</div>

<div style="background-color:beige">
<!-- main container -->
<div class="main-container" style="padding-bottom:0;">

    <div class="about-us-container">
        <img src="images/front.jpg" alt="about-us">
        <div class="about-us">
            <h2>About Our Company</h2>

            <h4>Welcome to Evenuehub, your ultimate destination for seamless venue
                booking! Whether you are planning a wedding, event, birthday party,
                or any special occasion, Evenuehub is here to make your
                event planning experience effortless and enjoyable.</h4>
        </div>
    </div>

</div>

<div class="divider">
    <div
        style="background-image: url('images/front.jpg'); background-size:cover; background-repeat:no-repeat;width:100%;padding:10px 0;">
        <h1 style="color: white;">What are you looking for?</h1>
        <br>
        <We style="color: white;">We've got you covered</p>
    </div>
</div>
<div class="main-container" style="padding-bottom:0;">

    <div class="service-container">
        <div class="service">
            <h2>Security</h2>
            <img src="images/security.png" alt="feature1">
            <p>Our venues are regularly inspected and
                adhere to stringent security protocols
                to ensure a safe and secure environment
                for your events. Enjoy peace of mind
                knowing that your safety is in good hands.</p>
        </div>
        <div class="service">
            <h2>Support 24/7</h2>
            <img src="images/occasion.png" alt="feature2">
            <p>Our dedicated customer support team is
                available 24/7 to assist you with any
                queries or concerns. Whether you need
                help with booking, venue details, or
                last-minute changes, we are here to
                support you every step of the way.</p>
        </div>
        <div class="service">
            <h2>Trusted Agents</h2>
            <img src="images/pax.png" alt="feature3">
            <p>Evenuehub works with a network of trusted
                agents who are experienced in the event
                planning industry. Our agents are reliable,
                professional, and committed to helping
                you find the perfect venue that meets your needs and expectations.</p>
        </div>
        <div class="service">
            <h2>Friendly Service</h2>
            <img src="images/friendly.png" alt="feature4">
            <p>We believe in providing friendly and
                approachable service. Our team is here
                to make your experience as enjoyable
                as possible, offering personalized
                assistance and ensuring that your event
                planning process is smooth and stress-free.</p>
        </div>
        <div class="service">
            <h2>Clean Venues</h2>
            <img src="images/clean.png" alt="feature5">
            <p>Cleanliness is a top priority at Evenuehub.
                We ensure that all our venues are meticulously
                maintained and hygienic, providing a spotless
                and welcoming environment for you and your guests to enjoy.</p>
        </div>
        <div class="service">
            <h2>Affordable Pricing</h2>
            <img src="images/rm.png" alt="feature6">
            <p>We offer a wide range of venues at competitive
                prices to fit every budget. Our transparent pricing
                ensures that you get the best value for your money
                without compromising on quality or service. Discover
                affordable options that meet your needs and exceed your expectations.</p>
        </div>
    </div>

</div>

<div class="divider">
    <div
        style="background-image: url('images/front.jpg'); background-size:cover; background-repeat:no-repeat;width:100%;padding:10px 0;">
        <h1 style="color: white;">Meet Our Team</h1>
    </div>
</div>


<div class="main-container">

    <div class="item-container">
        <div class="item">
            <img src="images/photo1.jpg" alt="team 1" width="100%" height="auto">
            <h2>Ethan Mercer</h2>
            <p>Creative Director</p>
        </div>

        <div class="item">
            <img src="images/photo2.jpg" alt="team 2" width="100%" height="auto">
            <h2>Lucas Bennett</h2>
            <p>Support Manager</p>
        </div>

        <div class="item">
            <img src="images/photo3.jpg" alt="team 3" width="100%" height="auto">
            <h2>Caleb Foster</h2>
            <p>Office Manager</p>
        </div>

        <div class="item">
            <img src="images/photo4.jpg" alt="team 4" width="100%" height="auto">
            <h2>Mason Reed</h2>
            <p>Product Manager</p>
        </div>

        <div class="item">
            <img src="images/photo5.jpg" alt="team 5" width="100%" height="auto">
            <h2>Noah Patel</h2>
            <p>Web Developer</p>
        </div>
    </div>


    <!-- main container end -->
</div>
</div>

<footer class="footer">
    <?php include 'src/footer.php'; ?>
</footer>

</body>

</html>