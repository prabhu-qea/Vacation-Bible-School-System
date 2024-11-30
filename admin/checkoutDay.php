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
<title><?php echo $church_name;?> | <?php echo $vbs_title;?> | <?php echo $vbs_year;?> | Admin | Checkout</title>
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
        <div class="w3-container">            
            <h3><u>Children >> Checkout</u></h3>
        </div>
<div class="w3-container w3-center">
<?php
// nqrcheckin.php

// include '../inc/db.php';

// Get form data

if ($_SERVER["REQUEST_METHOD"] == "POST") {    
    
    $day = $_POST["day"];
    $childID = $_POST['co_cid'];    
   
    if (!empty($childID) && !empty($day)) {
        foreach ($childID as $rowId) {
            
            // $sq = "SELECT * FROM `checkin` WHERE check_cid='$rowId' AND check_day='$day'";
            $sq = "SELECT * FROM `checkin` WHERE check_cid='$rowId'";
            
            $result = $conn->query($sq);
            // print_r($result);            
                if ($result->num_rows > 0) {
                    
                        $q = "SELECT * FROM checkout WHERE co_cid='$rowId' AND co_day='$day'";
                        
                        $r = $conn->query($q);
                        
                        if ($r->num_rows > 0) {
                            $qu = "SELECT * FROM reg_entries WHERE cid='$rowId'";
                            $re = $conn->query($qu);
                            while($row = $re->fetch_assoc()) {
                            echo "<div class='w3-panel w3-pale-blue w3-leftbar w3-border-red'><p><b>".$row["fname"]." ".$row["lname"]."</b> Already checked out for <b>".$day."</b></p></div>";
                            }
                        } else {
                            $qu = "SELECT * FROM reg_entries WHERE cid='$rowId'";
                            $re = $conn->query($qu);
                            // echo $row["fname"]." ".$row["lname"]." checked in for ".$day." please proceed <br><br>";
                            // echo $rowId." Not Checked out for ".$day."<br>";
                            while($row = $re->fetch_assoc()) {
                                $stmt = $conn->prepare("INSERT INTO checkout (co_cid, co_day) VALUES (?, ?)");
                                $stmt->bind_param('ss', $rowId, $day);
                                $stmt->execute();
                                $stmt->close();
                                echo "<div class='w3-panel w3-pale-blue w3-leftbar w3-border-green'><p><b>".$row["fname"]." ".$row["lname"]."</b> Successfully checked out for <b>".$day."</b></p></div>";
                            }                        
                        }                
                } else {
                    echo $rowId." Not checked in for  ".$day."  Unable to proceed <br>";
                }
        }         
    }        
}else {
        echo "<div class='w3-panel w3-pale-blue w3-leftbar w3-border-red'><p>You have not selected any Children to check them out.</p></div>";
}

$conn->close();

?>
</div>
<p></p>
<?php echo $footer; ?>