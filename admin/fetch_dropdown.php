<?php
include "../inc/db.php";

$groupField = $_GET['groupField'];


$query = $conn->prepare("SELECT * FROM teacher WHERE tgrpid = ?");
$query->bind_param('s', $groupField);
$query->execute();
$result = $query->get_result();

while ($row = $result->fetch_assoc()) {
    echo '<option value="' . $row['tid'] . '">' . $row['tname'] . ' & '. $row['at_name'] . '</option>';
}

$query->close();
$conn->close();
?>
