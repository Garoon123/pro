<?php
require './includes/conn.php';
$town = $_SESSION['location'] ?? '';
$xaafada = $_GET['xaafada'] ?? '';
$query = "SELECT DISTINCT p.*, l.town, x.xaafada 
FROM pitches p 
LEFT JOIN locations l ON p.location_id = l.location_id 
LEFT JOIN xaafada x ON p.xaafada_id = x.id";

if ($town) {
    $query .= " WHERE p.location_id = $town";
}

if ($xaafada) {
    if ($town) {
        $query .= " AND p.xaafada_id = $xaafada";
    } else {
        $query .= " WHERE p.xaafada_id = $xaafada";
    }
}

// Log the final query for debugging
error_log($query);

$result = mysqli_query($conn, $query);

if (!$result) {
    error_log("Query Error: " . mysqli_error($conn));
    echo json_encode([]);
    exit();
}

$pitches = [];
while ($row = mysqli_fetch_assoc($result)) {
    $pitches[] = $row;
}

echo json_encode($pitches);
