<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>VBS System Config</title>
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
        <h3><u>VBS System | Configuration</u></h3>
</div>
<div class="w3-container">
<?php
include '../inc/db.php';
include '../inc/global.php';
// Initialize variables
$church_name = '';
$vbs_web = '';
$vbs_title = '';
$vbs_year = '';
$vbs_active = 'N';
$update = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save'])) {
        // print_r($_POST);
        $church_name = $conn->real_escape_string($_POST['church_name']);
        $vbs_web = $conn->real_escape_string($_POST['vbs_web']);
        $vbs_title = $conn->real_escape_string($_POST['vbs_title']);
        $vbs_year = $conn->real_escape_string($_POST['vbs_year']);
        $vbs_active = $conn->real_escape_string($_POST['vbs_active']);
        $conn->query("INSERT INTO config (church_name, web, vbs_title, vbs_year) VALUES ('$church_name', '$vbs_web', '$vbs_title','$vbs_year')");
        header('Location: config.php');
    } elseif (isset($_POST['update'])) {
        $vbs_id = $conn->real_escape_string($_POST['vbs_id']);
        $church_name = $conn->real_escape_string($_POST['church_name']);
        $vbs_web = $conn->real_escape_string($_POST['vbs_web']);
        $vbs_title = $conn->real_escape_string($_POST['vbs_title']);
        $vbs_year = $conn->real_escape_string($_POST['vbs_year']);
        $vbs_active = $conn->real_escape_string($_POST['vbs_active']);
        $conn->query("UPDATE config SET church_name='$church_name', web='$vbs_web', vbs_title='$vbs_title', vbs_year='$vbs_year', vbs_active='$vbs_active' WHERE vbs_id=$vbs_id");
        header('Location: config.php');
    }
}

// Handle edit request
if (isset($_GET['edit'])) {
    $vbs_id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM config WHERE vbs_id=$vbs_id");
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $church_name = $row['church_name'];
        $vbs_title = $row['vbs_title'];
        $vbs_year = $row['vbs_year'];
        $vbs_web = $row['web'];
        $vbs_active = $row['vbs_active'];
        $update = true;
    }
}
?>
<div class="w3-container">
        <p></p>
        <div class="w3-container w3-light-blue">
            <h3><u>VBS System Configuration</u></h3>
        </div>
    <!-- Form for Create/Update -->
    <form class="w3-container w3-border" method="POST" action="">
        <p></p>
        <input type="hidden" name="vbs_id" value="<?= $vbs_id ?>">
        <label class="w3-text-black"><b>Church Name:</b></label>
        <input class="w3-input w3-border w3-light-grey" type="text" name="church_name" value="<?= $church_name ?>" required>
        <p></p>
        <label class="w3-text-black"><b>Church Website URL</b></label>
        <input class="w3-input w3-border w3-light-grey" type="url" name="vbs_web" value="<?= $vbs_web ?>" required>
        <p></p>
        <label class="w3-text-black"><b>VBS Title:</b></label>
        <input class="w3-input w3-border w3-light-grey" type="text" name="vbs_title" value="<?= $vbs_title ?>" required>
        <p></p>        
        <label class="w3-text-black"><b>VBS Year</b></label>
        <select class="w3-select w3-border" name="vbs_year">
        <option value="">--Select the year--</option>
        <?php
            $currentYear = date("Y");
            $nextFiveYears = $currentYear + 5;

            for ($year = $currentYear; $year <= $nextFiveYears; $year++) {                
                echo "<option value=\"$year\" " . ($year == $vbs_year ? "selected" : "") . ">$year</option>";
            }
        ?>
        </select>
        <p></p>
        <?php if ($update): ?>
            <label class="w3-text-black"><b>VBS Status</b></label>
            <select class="w3-select w3-border" name="vbs_active">
                <option value="">--Select--</option>
                <option value="Y" <?= $vbs_active === 'Y' ? 'selected' : '' ?>>Y</option>
                <option value="N" <?= $vbs_active === 'N' ? 'selected' : '' ?>>N</option>
            </select>
            <p></p>
            <button  class="w3-btn w3-black" type="submit" name="update">Update</button><p></p>
        <?php else: ?>
            <button  class="w3-btn w3-black" type="submit" name="save">Save</button><p></p>
        <?php endif; ?>
    </form>
    <p></p>
                <?php
                $result = $conn->query("SELECT * FROM config");
                if ($result->num_rows > 0) 
                {?>
                <div class="w3-container w3-light-blue">
                    <h3><u>VBS System Records</u></h3>
                </div>
                <p></p>   
                <!-- Table displaying records -->
                <div class="w3-container">
                <table border="1"  class="w3-table-all w3-hoverable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Church Name</th>
                            <th>Church Website</th>
                            <th>VBS Title</th>
                            <th>VBS Year</th>
                            <th>VBS Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                while ($row = $result->fetch_assoc()): 
                ?>
                 <tr>
                        <td><?= $row['vbs_id'] ?></td>
                        <td><?= $row['church_name'] ?></td>
                        <td><?= $row['web'] ?></td>
                        <td><?= $row['vbs_title'] ?></td>
                        <td><?= $row['vbs_year'] ?></td>
                        <td><?= $row['vbs_active'] ?></td>
                        <td>
                            <a href="?edit=<?= $row['vbs_id'] ?>">Edit</a>                            
                        </td>
                    </tr>
                <?php 
                endwhile;    
                }                 
                ?>
            </tbody>
        </table>
    </div>
</div>
</div>
<p></p>
<?php echo $footer; ?>