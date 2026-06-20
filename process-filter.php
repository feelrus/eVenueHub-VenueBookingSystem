<?php
session_start();

try {
    if (!isset($_POST['location'], $_POST['event'], $_POST['pax'], $_POST['budget'])) {
        throw new Exception("Missing filter data.");
    }

    $location = $_POST['location'];
    $event = $_POST['event'];
    $pax = $_POST['pax'];
    $budget = $_POST['budget'];

    $_SESSION['filter_data'] = [
        'location' => $location,
        'event' => $event,
        'pax' => $pax,
        'budget' => $budget
    ];

    require 'src/database.php';
    $db = new Database();
    $conn = $db->getConnection();

    if ($conn->connect_error) {
        throw new Exception("Error connecting to database: " . $conn->connect_error);
    }

    $sql = "SELECT v.*, l.location_name 
            FROM venues v
            JOIN locations l ON v.location_id = l.location_id
            WHERE v.location_id = ? 
            AND v.event_id = ?
            AND v.pax >= ? 
            AND v.price <= ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("iidi", $location, $event, $pax, $budget);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $venues = [];
        while ($row = $result->fetch_assoc()) {
            $venues[] = $row;
        }
        $_SESSION['filtered_venues'] = $venues;
    } else {
        $_SESSION['filtered_venues'] = [];
    }
    header("Location: venue-filter.php");
    exit();

} catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
    header("Location: index.php");
    exit();
}
?>
