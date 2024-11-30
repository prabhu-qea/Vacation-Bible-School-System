<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
?>
<?php
include "../inc/db.php";

$sql = "SELECT grpid, gname FROM grp";
$result = $conn->query($sql);

// echo $result;

$groups = [];
while($row = $result->fetch_assoc()) {
    $groups[] = $row;
}

echo json_encode($groups);

$conn->close();
?>
