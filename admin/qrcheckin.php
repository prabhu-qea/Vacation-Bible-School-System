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
<title><?php echo $church_name;?> | <?php echo $vbs_title;?> | <?php echo $vbs_year;?> | Admin | QR Code Checkin</title>
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
<body>

<?php include 'nav.php';?>
    <p></p>
    <div class="w3-container">
        <div class="w3-container w3-center">            
            <h3><u>QR Code Check-In System</u></h3>
        </div>
<div class="w3-container w3-center">
<?php
// qrcheckin.php

// include '../inc/db.php';

// Get form data

$qrraw = openssl_decrypt($_POST['qrraw'],'AES-128-ECB','vbs');

$dParse = explode(':',$qrraw);

if (count($dParse) !=3){
    echo "<div class='w3-panel w3-yellow w3-leftbar w3-border-red'><p>Error: Issue with your QR Code. Please check.</p></div>";
    echo $footer;
    exit;
}elseif (count($dParse) == 3){
    $childId = $dParse[0];
    $childLname = $dParse[1];
    $childDOB = $dParse[2];
}else {
    echo "<div class='w3-panel w3-yellow w3-leftbar w3-border-red'><p>Error: Invalid QR Code. Please check.</p></div>";
    echo $footer;
    exit;
}


$checkinday = $_POST['day'];

// Check for duplicates
$sql = "SELECT * FROM reg_entries WHERE cid = '$childId' AND lname = '$childLname' AND dob = '$childDOB'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    //echo "Registered User. Please proceed to Check in.";    
    $row = $result->fetch_assoc();

    $checkinName = $row["fname"].' '.$row["lname"];

    $allergyFlag = ($row["allergies"] != "No Allegy/Medical Condition") ? "Yes" : "No";      
    
    $sq = "SELECT * FROM checkin WHERE check_cid = '$childId' and check_day = '$checkinday'";

    $result = $conn->query($sq);

    if ($result->num_rows > 0) {
        
        echo "<div class='w3-panel w3-pale-blue w3-leftbar w3-border-orange'><p><b>".$checkinName."</b> Checked in already for ".$checkinday."</p></div>";   
        
        echo "<div class='w3-container w3-center'><img class='w3-border w3-padding w3-image' src='../img/warn.jpg' style='width:100%;max-width:150px'></div><p></p>";         
    }
    else{
        $sql = "INSERT INTO checkin (check_cid, check_day) VALUES ('$childId', '$checkinday')";

        if ($conn->query($sql) === TRUE) {

            echo "<div class='w3-panel w3-pale-blue w3-leftbar w3-border-green'><p><b>".$checkinday."</b> Check-in successful for ".$checkinName."</p></div>";               
        
            echo "<div class='w3-container w3-center'><img class='w3-border w3-padding w3-image' src='../img/done.jpg' style='width:100%;max-width:150px'></div><p></p>";
            
            if ($allergyFlag == "Yes"){
                echo "<div class='w3-panel w3-orange w3-leftbar w3-border-red'><p><b>".$checkinName."</b> has allergy mentioned during registration. Kindly make arrangements while preparing badge.</p></div>";
            }else{
                echo "<div class='w3-panel w3-pale-blue w3-leftbar w3-border-orange'><p>Please prepare the badge for <b>".$checkinName."</b> if not already.</p></div>";
            }

            
        } else {
            echo "<div class='w3-panel w3-yellow w3-leftbar w3-border-red'><p>Error: " . $sql . "<br>" . $conn->error."</p></div>";
        }    
    }    

} else {  
    echo "<div class='w3-panel w3-yellow w3-leftbar w3-border-red'><p>Error: " . $sql . "<br>" . $conn->error."</p></div>";
}

$conn->close();

?>
</div>
<p></p>
<?php echo $footer; ?>