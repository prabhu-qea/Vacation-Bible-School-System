<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
?>
<?php
include '../inc/db.php';
include '../inc/global.php';
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $church_name;?> | <?php echo $vbs_title;?> | <?php echo $vbs_year;?> | Admin | Attendance List</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="../inc/style.css">
<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<link href="https://nightly.datatables.net/css/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<script src="https://nightly.datatables.net/js/jquery.dataTables.js"></script>
<link href="https://cdn.datatables.net/rowgroup/1.5.0/css/rowGroup.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/rowgroup/1.5.0/js/dataTables.rowGroup.min.js"></script>    
<script src="https://kit.fontawesome.com/aecf7b02d6.js" crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
    body{
        font-family: 'Roboto', sans-serif;
    }
    h1,h2,h3{
        font-family: 'Roboto', sans-serif;
    }
</style>
</head>
</head>

<body>

<?php include 'nav.php';?>
 
<div class="w3-container">        
        <h3><u>Children >> Attendance List</u></h3>
</div>
<p></p>
<div class="w3-container">
<?php
// include '../inc/db.php';

$sql = "SELECT * FROM `attendance` RIGHT JOIN reg_entries ON attendance.att_cid=reg_entries.cid RIGHT JOIN cls ON cls.cls_cid=attendance.att_cid RIGHT JOIN grp ON grp.grpid=cls.clsgrpid RIGHT JOIN teacher ON teacher.tid=cls.cls_tid WHERE attendance.att_cid IS NOT NULL AND attendance.att_day IS NOT NULL AND attendance.att_cid IS NOT NULL";
$result = $conn->query($sql);
?>
            <?php
            if ($result->num_rows > 0) {
            ?>
            <table id="example" class="w3-table-all w3-hoverable">    
        <thead>            
            <tr>
                <th>ID</th>
                <th>Name</th>                
                <th>Class Name</th>                
                <th>Day of Attendance</th>                
                <th>Teacher</th>                
                <th>Session</th>                
            </tr>
        </thead>
        <tbody>
            <?php                
                // Output data of each row
                while($row = $result->fetch_assoc()) {                  
                    echo "<tr>
                            <td>" . $row["att_cid"]. "</td>
                            <td>" . $row["fname"]." ".$row["lname"]. "</td>
                            <td>" . $row["clsname"]. "</td>
                            <td>" . $row["att_day"]. "</td>
                            <td>" . $row["tname"]. " & ". $row["at_name"]."</td>
                            <td>" . $row["att_session"]. "</td>                                                        
                          </tr>";    


                }
            } else {
                echo "<div class='w3-panel w3-yellow w3-leftbar w3-border-red'><p>Error: No Results</p></div>";
            }
            $conn->close();
            ?>
            </table>
</div>            

<script>
    $(document).ready(function() {
    var collapsedGroups = {};
    var top = '';

    var table = $('#example').DataTable({
        paging: false,
        order: [
            [2, 'asc'],
            [3, 'asc']
        ],
        rowGroup: {
            dataSrc: [2, 3],
            startRender: function(rows, group, level) {
                var all;

              
                if (level === 0) {
                  // If parent group not defined then initializing so default it to collapsed (true)
                  if (collapsedGroups[group] === undefined) {
                    collapsedGroups[group] = true;
                  }
                    top = group;
                    all = group;
                } else {
                    // if parent collapsed, nothing to do
                    if (collapsedGroups[top]) {
                        return;
                    }
                    all = top + group;
                  if (collapsedGroups[all] === undefined) {
                    collapsedGroups[all] = true;  // Collapse second level by default (true)
                  }
                }

                 var collapsed = collapsedGroups[all];
                rows.nodes().each(function(r) {
                    r.style.display = collapsed ? 'none' : '';
                });

                // Add category name to the <tr>. NOTE: Hardcoded colspan
                return $('<tr/>')
                    .append('<td colspan="4"><b>' + group + ' (' + rows.count() + ')</b></td>')
                    .attr('data-name', all)
                    .toggleClass('collapsed', collapsed);
            }
        },
    });

    $('#example tbody').on('click', 'tr.dtrg-start', function() {
        var name = $(this).data('name');

        collapsedGroups[name] = !collapsedGroups[name];
        table.draw(false);

        });

    });    
</script>
<p></p>
<?php echo $footer; ?>


