<!DOCTYPE html>
<html>
<head>
<title><?php echo $church_name;?> | <?php echo $vbs_title;?> | <?php echo $vbs_year;?> | QR Code E-Mail Reminder</title>
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
<div class="w3-container">
    <div class="w3-container w3-center">
        <h2><img class="w3-border w3-padding w3-image" src="../img/logo.jpg" style="width:100%;max-width:150px"></h2>
        <h2><?php echo $church_name;?> | <?php echo $vbs_title;?> | <?php echo $vbs_year;?> | QR Code E-Mail Utility</h2>
    </div>
    <?php
// Use PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Include PHPMailer classes
require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

include '../inc/db.php';
include '../inc/global.php';
require_once '../phpqrcode/qrlib.php';


$sql = "SELECT * FROM reg_entries WHERE qr_gen='N'";
$result = $conn->query($sql);


if ($result->num_rows > 0){
    // echo $result->num_rows." Children<br><br>";
    echo "<div class='w3-panel w3-yellow w3-leftbar w3-border-orange'><p><b>".$result->num_rows."</b> children don't have QR Code</p></div>";
    while($row = $result->fetch_assoc()) {
        $cid = $row["cid"];
        $fname = $row["fname"];
        $lname = $row["lname"];
        $dob = $row["dob"];
        $email = $row["ec_email"];
        $contactName = $row["ec_fullname"];

        $data = $cid.':'.$lname.':'.$dob;

        $encry = openssl_encrypt($data,'AES-128-ECB','vbs');
                
        $tmpDir = dirname(__DIR__)."/tmpqr/";
                       
        $fileName = $cid.'_VBS2024_'.str_replace(' ', '', str_shuffle($lname)).'.png';

        
        $pngAbsoluteFilePath = $tmpDir.$fileName;
                
            QRcode::png($encry, $pngAbsoluteFilePath,QR_ECLEVEL_L, 4);
            
            $stmt = $conn->prepare("UPDATE reg_entries SET qr_gen= 'Y' WHERE cid=? ");
            $stmt->bind_param('s', $cid);
            $stmt->execute();
            $stmt->close();    
            
            //E-Mail routine
    
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';        
            $mail->Port = 587;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );        
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->SMTPSecure = "tls";
            $mail->SMTPAuth = true;
            $mail->Username = 'Your Email Address';
            $mail->Password = 'Your Password';
            $mail->setFrom('Your Email Address', $church_name.' '.$vbs_year.' '.$vbs_title);
            $mail->addReplyTo('Your Email Address', $church_name.' VBS Team');
            $mail->addAddress($email, $contactName);            
            $mail->addCC('Your CC Email adddress',$church_name.' VBS Team');
            $mail->Subject = $church_name.' - '.$vbs_year.' - '.$vbs_title.' - Registration - QR Code';
            $mail->isHTML(true);        
            $mail->Body    = 'Hi '.$contactName.',<br><br>You are receiving this email because you did not generate the unique QR code for one of your child/children <b>'.$fname.' '.$lname.'</b> during Step 2 of the VBS 2024 - Destiny Registration.<br><br> Do not worry; we have attached the QR code for you to use during check-in.<br><br><img src="cid:qrcode"><br><br> Please present this code at the venue for expedited check-in on all three days. <br><br> Regards,<br>'.$church_name.' VBS Team<br>'.$church_web;
            $qrFileName = $pngAbsoluteFilePath;
            $mail->addEmbeddedImage($qrFileName, 'qrcode');
            $mail->addAttachment($qrFileName);
            if (!$mail->send()) {
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {                
                echo "<div class='w3-panel w3-pale-blue w3-leftbar w3-border-green'><p>Unique QR Code for <b>".$fname." ".$lname."</b> Successfully emailed to <b>".$contactName."</b> at <b>".$email."</b></p></div>";
            }           
    }                        
}
else {
    echo "<div class='w3-panel w3-yellow w3-leftbar w3-border-red'><p>Error: No results found.</p></div>";
    $conn->close();      
}
?>

</div>
<p></p>
<?php echo $footer; ?>