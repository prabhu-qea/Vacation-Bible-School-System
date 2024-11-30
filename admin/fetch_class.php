<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
?>
<?php
include "../inc/db.php";

$groupID = intval($_GET['groupID']);
$teacherID = intval($_GET['teacherID']);
// $sql = "SELECT name, description FROM items WHERE category_id = $category_id AND subcategory_id = $subcategory_id";
$sql = "SELECT * from reg_entries where cid in (select cls_cid from cls WHERE clsgrpid=$groupID AND cls_tid=$teacherID)";
$result = $conn->query($sql);

$children = [];
while($row = $result->fetch_assoc()) {
    $children[] = $row;
}

echo json_encode($children);

$conn->close();
?>
