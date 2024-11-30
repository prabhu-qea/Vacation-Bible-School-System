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
<title><?php echo $church_name;?> | <?php echo $vbs_title;?> | <?php echo $vbs_year;?> | Admin | Children | Move to different class</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../inc/style.css">
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
        <h3><u>Children >> Move to different class</u></h3>
</div>
<div class="w3-container">
<?php
// include '../inc/db.php';

$update = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        $tid = $conn->real_escape_string($_POST['teacherid']);
        $cname = $conn->query("SELECT clsname from cls where cls_tid=$tid");
        $res = $cname->fetch_assoc();
        $clname = $res['clsname'];        
        $cid = $conn->real_escape_string($_POST['cid']);        
        $conn->query("UPDATE cls SET clsname='$clname', cls_tid='$tid' WHERE cls_cid=$cid");
        header('Location: move.php');
    }
}

// Handle edit request
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $editor_id = $_GET['editor'];
    $result = $conn->query("SELECT * FROM reg_entries RIGHT JOIN cls ON reg_entries.cid=cls.cls_cid WHERE cid=$edit_id");
    $rs = $conn->query("SELECT cls_tid, clsname FROM `cls` WHERE clsgrpid=$editor_id GROUP BY clsname");
    if ($result->num_rows > 0) {        
        $row = $result->fetch_assoc();        
        $name = $row['fname'].' '.$row['lname'];
        $clsid = $row['child_clsid'];
        $grpid = $row['stgrpid'];
        $clsname = $row['clsname'];
        $teacherID = $row['cls_tid'];
        $update = true;
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$delete_id");
    header('Location: move.php');
}
?>
<div class="w3-container">        
    <!-- Form for Create/Update -->
        <?php if ($update): ?>
            <form class="w3-container w3-border" method="POST" action="">
            <div class="w3-container w3-light-blue">
                <h3><u>Move Children to different class within a group</u></h3>
            </div>
            <p></p>
            <input type="hidden" name="cid" value="<?= $edit_id ?>">
            <!-- <input type="hidden" name="clsname" value="<?= $clsname ?>"> -->
            <label class="w3-text-black"><b>Name:</b></label>
            <input class="w3-input w3-border w3-light-grey" type="text" name="name" value="<?= $name ?>" required>
            <p></p>
            <label class="w3-text-black"><b>Class Name:</b></label>
            <!-- <input class="w3-input w3-border w3-light-grey" type="text" name="tname" value="<?= $clsname ?>" required>
            <p></p> -->
            <select class="w3-select w3-border" name="teacherid">
                <option value="">-- Choose a Class --</option>
                <?php while ($rw = $rs->fetch_assoc()): ?>
                    <option value="<?= $rw['cls_tid'] ?>" <?= $rw['cls_tid'] == $teacherID ? 'selected' : '' ?>>
                        <?= htmlspecialchars($rw['clsname']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <p></p>            
            <button  class="w3-btn w3-black" type="submit" name="update">Update</button><p></p>
            </form>
        <?php else: ?>
            <!-- <button  class="w3-btn w3-black" type="submit" name="save">Save</button><p></p> -->
        <?php endif; ?>    
    <h3><u>Children List</u></h3>
    <!-- Table displaying records -->
    <div class="w3-container">
    <table border="1"  class="w3-table-all w3-hoverable">
        <thead>
            <tr>
                <th>Teacher ID</th>
                <th>Teacher Name</th>
                <th>Child ID</th>
                <th>Child Name</th>                
                <th>Class Name</th>
                <th>Group</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php            
            $result = $conn->query("select * from cls RIGHT JOIN teacher ON cls.cls_tid=teacher.tid RIGHT JOIN reg_entries ON cls.cls_cid=reg_entries.cid WHERE cls.clsgrpid IS NOT NULL");
            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['tid'] ?></td>
                    <td><?= $row['tname'] ?></td>                    
                    <td><?= $row['cid'] ?></td>
                    <td><?= $row['fname'] .' '. $row['lname']?></td>                    
                    <td><?= $row['clsname'] ?></td>
                    <td><?= $row['tgrpid'] ?></td>
                    <td>
                        <a href="?edit=<?= $row['cid'] ?>&editor=<?= $row['tgrpid'] ?>">Edit</a>
                        <!--<a href="?delete=<?= $row['tid'] ?>" onclick="return confirm('Are you sure?')">Delete</a>-->
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    </div>
</div>
<p></p>
<?php echo $footer; ?>