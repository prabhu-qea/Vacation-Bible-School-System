<?php
include "../inc/db.php";

$groupField = $_GET['groupField'];


if ($groupField == '') {
    exit;
} else {

$query = $conn->prepare("SELECT * FROM `reg_entries` WHERE stgrpid= ? AND child_clsid IS NULL");
$query->bind_param('s', $groupField);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Assignment</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function toggle(source) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
    }
    }
    </script>      
<?php
if ($result->num_rows > 0) {

    // echo $groupField;

    if ($groupField == '3') {
        echo '<input type="checkbox" onclick="toggle(this);"> Check All';
        echo '<p></p><table class="w3-table-all w3-centered">';        
        echo '<tr><th>Select</th><th>Child Name</th></tr>';
        while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td><input type="checkbox" name="selectedRows[]" value="' . $row['cid'] . '"></td>';
        echo '<td>' . $row['fname'] . ' '.$row['lname']. '</td>';
        echo '</tr>'; 
       } 
    }
    else{
        echo '<p></p><table class="w3-table-all w3-centered">';
        echo '<tr><th>Select</th><th>Child Name</th></tr>';
        while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td><input type="checkbox" name="selectedRows[]" value="' . $row['cid'] . '"></td>';
        echo '<td>' . $row['fname'] . ' '.$row['lname']. '</td>';
        echo '</tr>';
        }
    }    

echo '</table>';
}
 else {
    echo "<div class='w3-panel w3-pale-blue w3-leftbar w3-border-orange'><p><b>No children available from this group to assign a class</p></div>";      
}
}
$query->close();
$conn->close();
?>
