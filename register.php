<!DOCTYPE html>
<html>
<head>
<?php 
include 'inc/db.php';
include 'inc/global.php';
?>    
<title><?php echo $church_name;?> | <?php echo $vbs_title;?> | <?php echo $vbs_year;?> | Registration Form</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="inc/style.css">
<script src="script.js"></script>
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

<div class="w3-container">
    <div class="w3-container w3-center">
        <h2><img class="w3-border w3-padding w3-image" src="img/logo.jpg" style="width:100%;max-width:150px"></h2>
        <h2><?php echo $church_name;?> <?php echo $vbs_title;?> - <?php echo $vbs_year;?> - Registration Form - Step 1</h2>
    </div>
<?php
// Get form data

$first_name = $conn->real_escape_string($_POST['first_name']);
$last_name = $conn->real_escape_string($_POST['last_name']);
$dob = $_POST['dob'];
$age = $_POST['age'];
$school = $conn->real_escape_string($_POST['school']);
$allergies = $conn->real_escape_string($_POST['allergies']);
$contact_name = $conn->real_escape_string($_POST['contact_name']);
$relationship = $conn->real_escape_string($_POST['relationship']);
$email = $conn->real_escape_string($_POST['email']);
$phone = $conn->real_escape_string($_POST['phone']);
$address = $conn->real_escape_string($_POST['address']);


if (in_array(strtolower(trim($allergies)), ['please enter none if nothing to state', ' ', 'none', 'non', 'noe', 'no', 'No'. 'nO', 'N0', 'NA', 'Not Applicable'])) {
    $allergies = 'No Allegy/Medical Condition';
}


$email = filter_var($email, FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL  === false)) {
}else{
    echo "<div class='w3-panel w3-pale-blue w3-leftbar w3-border-red'><p>Error: Invalid E-Mail. <ber><br><ber><br><ber>Sorry you have provided an invalid email, so we are unable to email you the unique QR Code. Please ensure you download the QR Code once it is generated and keep it safe for the Check-In.</p></div>";
}


switch ($age) {
    case (($age >=5) and ($age <=8)):
        $stgrpid = 1;        
    break;
    
    case (($age >=9) and ($age <=12)):
        $stgrpid = 2;        
    break;

    case (($age >=13) and ($age <=15)):
        $stgrpid = 3;        
    break;

    default:
    echo "Sorry, unable to register";    
}

// Check for duplicates
$sql = "SELECT * FROM reg_entries WHERE fname = '$first_name' AND lname = '$last_name' AND dob = '$dob'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div class='w3-panel w3-pale-blue w3-leftbar w3-border-red'><p>Error: Duplicate entry. <ber><br><ber><br><ber>A registration with the same first name, last name, and date of birth already exists.</p></div>";
    
} else {

    $sql = "INSERT INTO reg_entries (fname, lname, dob, age, school, allergies, ec_fullname, ec_relation, ec_email, ec_phone, ec_addr, stgrpid, regdate) VALUES ('$first_name', '$last_name', '$dob', '$age', '$school', '$allergies', '$contact_name', '$relationship', '$email', '$phone', '$address', '$stgrpid', now())";

    if ($conn->query($sql) === TRUE) {        
        $cid = $conn->insert_id; 
        session_start();
        $_SESSION["cid"] = $cid;
        $_SESSION["fname"] = $first_name;
        $_SESSION["lname"] = $last_name;
        $_SESSION["dob"] = $dob;
        $_SESSION["email"] = $email;
        $_SESSION["cname"] = $contact_name;
        echo "<div class='w3-panel w3-pale-blue w3-leftbar w3-border-green'><p>Thanks a lot for all the information! <b>Step 1</b> is complete now.</p><p>You <b>MUST</b> click this <a href='regcomplete.php'><b>Step 2 link</b></a> to generate and download your child's (per child if registering more than one) unique QR Code in order to complete the registration successfully.<br><br>Please note we will e-mail the QR code if you have provided a valid email address in the registration form.</p><p></p><p>Request you to show the QR Code at the venue to get your child checked in for all three days.</p></div>";
        
    } else {
        echo "<div class='w3-panel w3-yellow w3-leftbar w3-border-red'><p>Error: " . $sql . "<br>" . $conn->error."</p></div>";
    }
}

$conn->close();

?>
</div>
<p></p>
<?php echo $footer; ?>
