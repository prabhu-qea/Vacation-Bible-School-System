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
<title><?php echo $church_name;?> | <?php echo $vbs_title;?> | <?php echo $vbs_year;?> | Admin | Teacher | Children | Class Attendance</title>
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
        <h2>Children >> Class Attendance</h2>
</div>
<p></p>
<div class="w3-container">
<?php
// include '../inc/db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {    
    
    $day = $_POST["day"];    
   
    if (!empty($_POST['amAtt']) && !empty($_POST['pmAtt']) && !empty($day)) {
        foreach ($_POST['amAtt'] as $rowId) {  
            $amAttSession = 'AM';
            $stmt = $conn->prepare("INSERT INTO attendance (att_day, att_cid, att_session) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $day, $rowId, $amAttSession);
            $stmt->execute();
            $stmt->close();           
        }
        foreach ($_POST['pmAtt'] as $rowId) {  
            $pmAttSession = 'PM';
            $stmt = $conn->prepare("INSERT INTO attendance (att_day, att_cid, att_session) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $day, $rowId, $pmAttSession);
            $stmt->execute();
            $stmt->close();           
        }
        echo "<div class='w3-panel w3-pale-blue w3-leftbar w3-border-green'><p>Attendance marked for <b>".$day." ".$amAttSession." and ".$pmAttSession." Session</b></p></div>";        
        
    } elseif (empty($_POST['pmAtt']) && !empty($_POST['amAtt'])){
        foreach ($_POST['amAtt'] as $rowId) {  
            $amAttSession = 'AM';
            $stmt = $conn->prepare("INSERT INTO attendance (att_day, att_cid, att_session) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $day, $rowId, $amAttSession);
            $stmt->execute();
            $stmt->close();           
        }
        echo "<div class='w3-panel w3-pale-blue w3-leftbar w3-border-green'><p>Attendance marked for <b>".$day." ".$amAttSession." </b></p></div>";
    } elseif (empty($_POST['amAtt']) && !empty($_POST['pmAtt'])){
        foreach ($_POST['pmAtt'] as $rowId) {  
            $pmAttSession = 'PM';
            $stmt = $conn->prepare("INSERT INTO attendance (att_day, att_cid, att_session) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $day, $rowId, $pmAttSession);
            $stmt->execute();
            $stmt->close();   
        }
        echo "<div class='w3-panel w3-pale-blue w3-leftbar w3-border-green'><p>Attendance marked for <b>".$day." ".$pmAttSession." </b></p></div>";
    }else {
        echo "<div class='w3-panel w3-pale-blue w3-leftbar w3-border-red'><p>You have not selected any Children to mark their attendance.</p></div>";
    }

}

$conn->close();
?>
</div>
<p></p>
<?php echo $footer; ?>