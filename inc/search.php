<?php

header('Content-Type: application/json');
include '../inc/db.php';

$output = [];

if (isset($_GET['query'])) {
    $search = $conn->real_escape_string($_GET['query']);
    $result = $conn->query("SELECT lname FROM reg_entries WHERE lname LIKE '%$search%' LIMIT 10");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $output[] = $row['lname'];
        }
    }
    echo json_encode($output);
    exit;
}

if (isset($_GET['allergy'])) {
    $search = $conn->real_escape_string($_GET['allergy']);
    $result = $conn->query("SELECT name FROM allergies WHERE name LIKE '%$search%'");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $output[] = $row['name'];
        }
    }
    echo json_encode($output);
    exit;
}

// If neither parameter is set, return empty array
echo json_encode([]);
exit;
?>
