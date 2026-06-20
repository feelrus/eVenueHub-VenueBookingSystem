<?php
// Include database connection
include 'src/database.php';

// Create a new Database instance
$db = new Database();
$conn = $db->getConnection();

// Fetch locations from the database
$location_query = "SELECT * FROM locations";
$location_result = $conn->query($location_query);

// Fetch event types from the database
$event_query = "SELECT * FROM events"; 
$event_result = $conn->query($event_query);
?>

<form method="post" action="process-filter.php" class="filter-form">
    <select id="location" name="location" class="icon" style="background-image: url('images/map.png');" required>
        <option value="">Select by area</option>
        <?php while ($location = $location_result->fetch_assoc()): ?>
            <option value="<?php echo $location['location_id']; ?>">
                <?php echo htmlspecialchars($location['location_name']); ?>
            </option>
        <?php endwhile; ?>
    </select>

    <select id="event" name="event" class="icon" style="background-image: url('images/occasion.png');" required>
        <option value="">Select by event</option>
        <?php while ($event = $event_result->fetch_assoc()): ?>
            <option value="<?php echo $event['event_id']; ?>"> 
                <?php echo htmlspecialchars($event['event_name']); ?>
            </option>
        <?php endwhile; ?>
    </select>

    <input type="number" id="pax" name="pax" class="icon" style="background-image: url('images/pax.png');"
        placeholder="Number of people" min="1" required>

    <input type="number" id="budget" name="budget" class="icon" style="background-image: url('images/rm.png');"
        placeholder="Estimated budget" min="1.00" step="0.01" required>

    <input type="submit" name="submit" value="Search">
</form>
