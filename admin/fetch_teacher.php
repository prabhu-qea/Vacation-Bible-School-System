<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
?>
<?php
include "../inc/db.php";

// $groupField = intval($_GET['groupField']);

$groupID = $_GET['groupID'];
// echo $groupField;

 $sql = "SELECT tid, tname, at_name FROM teacher WHERE tgrpid = $groupID";

$result = $conn->query($sql);

$teachers = [];
while($row = $result->fetch_assoc()) {
    $teachers[] = $row;
}

echo json_encode($teachers);

$conn->close();
?>
