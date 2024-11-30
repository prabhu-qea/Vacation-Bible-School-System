<?php
include "../inc/db.php";

$dayField = $_GET['day'];

if ($dayField == '') {
    exit;
} else {
    $query = $conn->prepare("SELECT * FROM `reg_entries` LEFT JOIN grp ON reg_entries.stgrpid=grp.grpid LEFT JOIN checkin ON reg_entries.cid=checkin.check_cid WHERE checkin.check_day = ? ");
    $query->bind_param('s', $dayField);
    $query->execute();
    $result = $query->get_result();

    $children = [];
    while($row = $result->fetch_assoc()) {
        $children[] = $row;
    }
    echo json_encode($children);
}
$conn->close();
?>
