<?php
include "../inc/db.php";

$groupField = $_GET['groupField'];

if ($groupField == '') {
    exit;
} else {

$q = "SELECT * FROM `reg_entries` WHERE stgrpid =".$groupField;
$r = $conn->query($q);


$query = $conn->prepare("SELECT * FROM `reg_entries` WHERE stgrpid= ? AND child_clsid IS NULL");
$query->bind_param('s', $groupField);
$query->execute();
$result = $query->get_result();

// qprint_r ($result);
// echo $result->num_rows;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Assignment</title>
    <link rel="stylesheet" href="../inc/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: 'Roboto', sans-serif;
        }
        h1,h2,h3{
            font-family: 'Roboto', sans-serif;
        }
    </style>
    <script>
        function toggle(source) {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i] != source)
                checkboxes[i].checked = source.checked;
        }
        }
    </script>
    </head>      
<?php
if ($result->num_rows > 0) {

    // echo $groupField;

    if ($groupField == '3') {
        echo '<p></p><div class="w3-panel w3-pale-blue w3-leftbar w3-border-green"><p><b>'.$result->num_rows.' </b> children available out of <b> '.$r->num_rows.'</b> to be assigned a class</p></div>';
        echo '<p></p><input type="checkbox" onclick="toggle(this);"> Check All';
        echo '<p></p><table class="w3-table-all w3-centered">';        
        echo '<tr><th>#</th><th>Select</th><th>Child Name</th></tr>';
        $counter = 0;
        while ($row = $result->fetch_assoc()) {
            $counter++;
        echo '<tr>';        
        echo '<td>'.$counter.'</td>';
        echo '<td><input type="checkbox" name="selectedRows[]" value="' . $row['cid'] . '"></td>';
        echo '<td>' . $row['fname'] . ' '.$row['lname']. '</td>';
        echo '</tr>';

       } 
    }
    else{
        echo '<p></p><div class="w3-panel w3-pale-blue w3-leftbar w3-border-green"><p><b>'.$result->num_rows.'</b> children available out of <b>'.$r->num_rows.'</b> to be assigned a class</p></div>';
        echo '<p></p><table class="w3-table-all w3-centered">';
        echo '<tr><th>#</th><th>Select</th><th>Child Name</th></tr>';
        $counter = 0;
        while ($row = $result->fetch_assoc()) {
            $counter++;
        echo '<tr>';
        echo '<td>'.$counter.'</td>';
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