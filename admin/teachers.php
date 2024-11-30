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
<title><?php echo $church_name;?> | <?php echo $vbs_title;?> | <?php echo $vbs_year;?> | Admin | Teachers | Add/Edit</title>
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
        <h3><u>Teachers | Add/Edit</u></h3>
</div>
<div class="w3-container">
<?php
// include '../inc/db.php';

// Initialize variables
$name = '';
$atname = '';
$grpid = '';
$edit_id = 0;
$update = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save'])) {
        $tname = $conn->real_escape_string($_POST['tname']);
        $at_name = $conn->real_escape_string($_POST['at_name']);
        $tgrpid = $conn->real_escape_string($_POST['tgrpid']);
        $conn->query("INSERT INTO teacher (tname, at_name, tgrpid) VALUES ('$tname', '$at_name','$tgrpid')");
        header('Location: teachers.php');
    } elseif (isset($_POST['update'])) {
        $tid = $conn->real_escape_string($_POST['tid']);
        $tname = $conn->real_escape_string($_POST['tname']);
        $atname = $conn->real_escape_string($_POST['at_name']);
        $tgrpid = $conn->real_escape_string($_POST['tgrpid']);
        $conn->query("UPDATE teacher SET tname='$tname', at_name='$atname', tgrpid='$tgrpid' WHERE tid=$tid");
        header('Location: teachers.php');
    }
}

// Handle edit request
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM teacher WHERE tid=$edit_id LIMIT 1");
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['tname'];
        $atname = $row['at_name'];
        $grpid = $row['tgrpid'];
        $update = true;
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$delete_id");
    header('Location: teachers.php');
}
?>
<div class="w3-container">
        <p></p>
        <div class="w3-container w3-light-blue">
            <h3><u>Manage Teachers</u></h3>
        </div>
    <!-- Form for Create/Update -->
    <form class="w3-container w3-border" method="POST" action="">
        <p></p>
        <input type="hidden" name="tid" value="<?= $edit_id ?>">
        <label class="w3-text-black"><b>Name:</b></label>
        <input class="w3-input w3-border w3-light-grey" type="text" name="tname" value="<?= $name ?>" required>
        <p></p>
        <label class="w3-text-black"><b>Assistant Teacher:</b></label>
        <input class="w3-input w3-border w3-light-grey" type="text" name="at_name" value="<?= $atname ?>" required>
        <p></p>
        <label class="w3-text-black"><b>Group</b></label>        
        <select class="w3-select w3-border" name="tgrpid">
            <option value="">--Select--</option>
            <option value="1" <?= $grpid === '1' ? 'selected' : '' ?>>Beginner</option>
            <option value="2" <?= $grpid === '2' ? 'selected' : '' ?>>Junior</option>
            <option value="3" <?= $grpid === '3' ? 'selected' : '' ?>>Seniors</option>
        </select>
        <?php if ($update): ?>
            <button  class="w3-btn w3-black" type="submit" name="update">Update</button><p></p>
        <?php else: ?>
            <button  class="w3-btn w3-black" type="submit" name="save">Save</button><p></p>
        <?php endif; ?>
    </form>
    <p></p>    
        
                <?php
                $result = $conn->query("SELECT * FROM teacher");
                if ($result->num_rows > 0) 
                {?>
                <div class="w3-container w3-light-blue">
            <h3><u>Teachers List</u></h3>
        </div>
        <p></p>   
        <!-- Table displaying records -->
        <div class="w3-container">
        <table border="1"  class="w3-table-all w3-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Assistant Teacher</th>
                    <th>Group ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['tid'] ?></td>
                        <td><?= $row['tname'] ?></td>
                        <td><?= $row['at_name'] ?></td>
                        <td><?= $row['tgrpid'] ?></td>
                        <td>
                            <a href="?edit=<?= $row['tid'] ?>">Edit</a>
                            <!--<a href="?delete=<?= $row['tid'] ?>" onclick="return confirm('Are you sure?')">Delete</a>-->
                        </td>
                    </tr>
                <?php endwhile; 
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</div>
<p></p>
<?php echo $footer; ?>