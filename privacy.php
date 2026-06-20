<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div style="background-image: url('images/front.jpg'); background-size:cover; background-repeat:no-repeat;">
        <?php include 'src/header.php'; ?>
    </div>

    <div
        style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), url('images/bg1.jpg'); background-size:cover; background-repeat:no-repeat;">
        <div class="main-container">
            <div class="divider"></div>
            <div class="white-container">
                <div class="white-form">
                    <h1>Evenuehub Privacy Policy</h1>
                    <p>Last Updated: October 26, 2023</p>

                    <p>This Privacy Policy describes how Evenuehub ("we", "us", or "our") collects, uses, discloses, and
                        protects your personal information when you use our website, mobile applications, and related
                        services (collectively, the "Platform").</p>

                    <h2>1. Information We Collect</h2>
                    <h3>1.1 Information You Provide:</h3>
                    <ul>
                        <li><strong>Account Information:</strong> When you create an account on Evenuehub, you provide
                            us with personal information such as your name, email address, phone number, and password.
                        </li>
                        <li><strong>Venue Information:</strong> If you are a venue owner, you will provide us with
                            information about your venue, including its name, address, website, contact details, photos,
                            and descriptions.</li>
                        <li><strong>Booking Information:</strong> When you book a venue through Evenuehub, you provide
                            us with your booking details, including date, time, number of guests, and payment
                            information.</li>
                        <li><strong>Communication:</strong> When you contact us or communicate with us through the
                            Platform, we collect your messages and any other information you provide.</li>
                    </ul>

                    <h3>1.2 Information We Automatically Collect:</h3>
                    <ul>
                        <li><strong>Usage Data:</strong> We collect information about how you use the Platform,
                            including the pages you visit, features you use, and your actions on the Platform.</li>
                        <li><strong>Device Information:</strong> We collect information about your device, including its
                            type, operating system, and unique device identifier.</li>
                        <li><strong>Location Information:</strong> If you enable location services on your device, we
                            may collect your location data.</li>
                    </ul>

                    <h3>1.3 Information We Collect from Third Parties:</h3>
                    <ul>
                        <li>We may collect information about you from third-party sources, such as social media
                            platforms, if you choose to connect your account with those platforms.</li>
                    </ul>

                    <h2>2. Use of Information</h2>
                    <p>We use the information we collect for various purposes, including:</p>
                    <ul>
                        <li><strong>Operating and Improving the Platform:</strong> To provide and improve the Platform,
                            including features, functionalities, and services.</li>
                        <li><strong>Processing Bookings:</strong> To process venue bookings and manage your bookings.
                        </li>
                        <li><strong>Communicating with You:</strong> To send you important information about your
                            account, bookings, and the Platform.</li>
                        <li><strong>Marketing and Advertising:</strong> To send you marketing and promotional materials,
                            if you opt-in to receive them.</li>
                        <li><strong>Analyzing User Behavior:</strong> To understand how users interact with the Platform
                            and to improve the Platform's features and services.</li>
                        <li><strong>Preventing Fraud and Abuse:</strong> To detect and prevent fraud and abuse on the
                            Platform.</li>
                    </ul>

                    <h2>3. Sharing of Information</h2>
                    <p>We may share your information with:</p>
                    <ul>
                        <li><strong>Third-Party Service Providers:</strong> We may use third-party service providers to
                            perform functions on our behalf, such as data analytics, marketing, and customer support.
                        </li>
                        <li><strong>Venue Owners:</strong> If you book a venue, we will share your contact information
                            with the venue owner for the purpose of facilitating your booking.</li>
                        <li><strong>Legal Requirements:</strong> We may disclose your information if required by law,
                            regulation, or legal process, such as in response to a court order or subpoena.</li>
                    </ul>

                    <h2>4. Data Security</h2>
                    <p>We use industry-standard security measures to protect your information from unauthorized access,
                        disclosure, alteration, or destruction. However, no website or internet transmission is
                        completely secure. Therefore, while we strive to protect your personal information, we cannot
                        guarantee its absolute security.</p>

                    <h2>5. Your Choices</h2>
                    <p>You have several choices about how we collect and use your information:</p>
                    <ul>
                        <li><strong>Account Information:</strong> You can access, modify, and delete your account
                            information at any time.</li>
                        <li><strong>Marketing Communications:</strong> You can opt out of receiving marketing
                            communications from us by following the unsubscribe instructions provided in those
                            communications.</li>
                        <li><strong>Location Services:</strong> You can disable location services on your device.</li>
                    </ul>

                    <h2>6. Children's Privacy</h2>
                    <p>The Platform is not intended for use by children under 13 years of age. We do not knowingly
                        collect personal information from children under 13. If you are a parent or guardian and you
                        believe that your child has provided us with personal information, please contact us.</p>

                    <h2>7. Cookies and Similar Technologies</h2>
                    <p>We use cookies and similar tracking technologies to collect and store information. You can manage
                        your cookie preferences through your browser settings.</p>

                    <h2>8. Changes to This Policy</h2>
                    <p>We may update this Privacy Policy from time to time. We will post any changes on the Platform.
                        Your continued use of the Platform after the posting of any changes constitutes your acceptance
                        of the changes.</p>

                    <h2>9. Contact Us</h2>
                    <p>If you have any questions about this Privacy Policy, please contact us at evenuehub.com</p>

                    <h2>10. Governing Law</h2>
                    <p>This Privacy Policy will be governed and construed in accordance with the laws of
                        Malaysia, without regard to its conflict of laws provisions.</p>

                    <h2>11. Entire Agreement</h2>
                    <p>This Privacy Policy constitutes the entire agreement between you and Evenuehub relating to your
                        personal information and supersedes all prior or contemporaneous communications,
                        representations, or agreements, whether oral or written.</p>

                    <h2>12. Severability</h2>
                    <p>If any provision of this Privacy Policy is held to be invalid or unenforceable, such provision
                        will be struck from this Privacy Policy and the remaining provisions will remain in full force
                        and effect.</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <?php include 'src/footer.php'; ?>
    </footer>
</body>

</html>