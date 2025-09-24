<?php
header('Content-Type: application/json');
require_once 'config.php';

$booked_dates = [];

// Query for all confirmed bookings
$result = $conn->query("SELECT check_in, check_out FROM bookings WHERE status = 'confirmed'");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $start_date = new DateTime($row['check_in']);
        $end_date = new DateTime($row['check_out']);

        // Loop from the start date to the end date, adding each date to the array
        // We include the start date but exclude the end date, as the checkout day is available for a new check-in.
        for ($date = $start_date; $date < $end_date; $date->modify('+1 day')) {
            $booked_dates[] = $date->format('Y-m-d');
        }
    }
    $result->free();
}

$conn->close();

echo json_encode($booked_dates);
?>
