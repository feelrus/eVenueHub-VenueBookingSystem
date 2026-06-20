<div class="header">
    <div class="top-left">
        <ul>
            <li class="icon" style="background-image: url('images/mail.png'); padding-left:23px;">Email: evenuehub@gmail.com</li>
            <li class="icon" style="background-image: url('images/phone.png'); padding-left:23px;">Phone: +60192483950</li>
        </ul>
    </div>
    <div class="top-right">
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <?php $username = $_SESSION['username']; ?>
            <ul>
                <li class="icon" style="background-image: url('images/register.png'); padding-left:20px;">
                    <a href="redirect.php">Welcome, <?php echo htmlspecialchars($username); ?></a>
                </li>
                <li class="icon" style="background-image: url('images/login.png'); padding-left:20px;"><a href="logout.php">Logout</a></li>
            </ul>
        <?php else: ?>
            <ul>
                <li class="icon" style="background-image: url('images/login.png'); padding-left:20px;"><a href="login.php">Login</a></li>
                <li class="icon" style="background-image: url('images/register.png'); padding-left:20px;"><a href="register.php">Register</a></li>
            </ul>
        <?php endif; ?>
    </div>
</div>

<div class="sub-header">
    <div class="top-left">
        <h1 class="title"><a href="index.php">EVENUEHUB</a></h1>
    </div>
    <nav class="top-right">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li class="dropdown">
                <a href="#" id="venue-nav-toggle">Venue</a>
                <div class="dropdown-content">
                    <table>
                        <tr style="border-bottom: 1px solid grey;">
                            <th>Popular Venue Types</th>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td><a class="drop-links" href="venue-conferences.php">Conferences</a></td>
                            <td><a class="drop-links" href="venue-meetings.php">Meetings</a></td>
                        </tr>
                        <tr>
                            <td><a class="drop-links" href="venue-weddings.php">Weddings</a></td>
                            <td><a class="drop-links" href="venue-parties.php">Parties</a></td>
                        </tr>
                    </table>
                </div>
            </li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>
</div>

<!-- Side Navigator -->
<div id="side-nav" class="side-nav">
    <a href="javascript:void(0)" class="closebtn" id="side-nav-close">&times;</a>
    <h2>Popular Venue Types</h2>
    <ul>
        <li><a href="venue-conferences.php">Conferences</a></li>
        <li><a href="venue-meetings.php">Meetings</a></li>
        <li><a href="venue-weddings.php">Weddings</a></li>
        <li><a href="venue-parties.php">Parties</a></li>
    </ul>
</div>

<script src="js/navigation.js"></script>
