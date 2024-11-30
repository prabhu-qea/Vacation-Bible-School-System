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
<title><?php echo $church_name;?> | <?php echo $vbs_title;?> | <?php echo $vbs_year;?> | Admin | Class Assignment</title>
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
        <h3><u>Children >> Class Assignment</u></h3>
</div>
<p></p>
<div class="w3-container">
<?php
// include '../inc/db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //print_r($_POST);

    $teacher = $_POST['teacher'];
    $className = $_POST['className'];
    $grp = $_POST['groupField'];
    
    if (!empty($_POST['selectedRows']) && !empty($teacher)) {
        foreach ($_POST['selectedRows'] as $rowId) {            
            $stmt = $conn->prepare("INSERT INTO cls (clsname, clsgrpid, cls_tid, cls_cid) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('ssss', $className, $grp, $teacher, $rowId);
            $stmt->execute();
            $stmt->close();
            
            $stmt = $conn->prepare("UPDATE reg_entries SET child_clsid= '1' WHERE cid=? ");
            $stmt->bind_param('s', $rowId);
            $stmt->execute();
            $stmt->close();
        }
        echo "<div class='w3-panel w3-pale-blue w3-leftbar w3-border-green'><p>Selected children are assigned to class <b>".$className."</b></p></div>";           
        
    } else {
        echo "<div class='w3-panel w3-pale-blue w3-leftbar w3-border-red'><p>You have not selected any Children to assign a class.</p></div>";        
    }
}

$conn->close();
?>
</div>
<p></p>
<?php echo $footer; ?>