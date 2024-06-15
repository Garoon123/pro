<?php
session_start();
require './includes/conn.php';

$location_id = $_SESSION['location'] ?? '';

$query = "SELECT DISTINCT * FROM xaafada WHERE location_id = $location_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    error_log("Query Error: " . mysqli_error($conn));
    echo json_encode([]);
    exit();
}

$xaafada = [];
while ($row = mysqli_fetch_assoc($result)) {
    $xaafada[] = $row;
}

echo json_encode($xaafada);
