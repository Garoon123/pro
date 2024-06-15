<?php
require './includes/conn.php';

$location_id = intval($_GET['location_id']);
$xaafada = [];

$sql = "SELECT distinct id, xaafada FROM xaafada WHERE location_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $location_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $xaafada[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($xaafada);
