<!DOCTYPE html>
<html>
<head><?php 
include 'inc/db.php';
include 'inc/global.php';
?>    
<title><?php echo $church_name;?> | <?php echo $vbs_title;?> | <?php echo $vbs_year;?> | Registration QR Code</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="inc/style.css">
<script src="inc/script.js"></script>
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
        <h2><?php echo $church_name;?> - <?php echo $vbs_title;?> - <?php echo $vbs_year;?> - Registration - Step 2 - QR Code</h2>
    </div>
<?php
// Use PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Include PHPMailer classes
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// include 'inc/db.php';
require_once 'phpqrcode/qrlib.php';
session_start();
    
if (isset($_SESSION["cid"])){

    $data = $_SESSION["cid"].':'.$_SESSION["lname"].':'.$_SESSION["dob"];

    $encry = openssl_encrypt($data,'AES-128-ECB','vbs');
    // echo $encry;
    // echo '<p></p>';
    // $decry = openssl_decrypt($encry,'AES-128-ECB','vbs');
    // echo $decry;
    // echo '<p></p>';
    $email = $_SESSION["email"];
    $cname = $_SESSION["cname"];
    
    $tmpDir = dirname(__DIR__)."/vbs/qrcode/";
    
    $fileName = $_SESSION["cid"].'_'.$vbs_year.'_'.str_replace(' ', '', str_shuffle($_SESSION["lname"])).'.png';
    $pngAbsoluteFilePath = $tmpDir.$fileName;
    
    $urlRelativeFilePath = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT'])).'/qrcode/'.$fileName;
    
    if (!file_exists($pngAbsoluteFilePath)) {
        QRcode::png($encry, $pngAbsoluteFilePath,QR_ECLEVEL_L, 4);
        echo "<div class='w3-panel w3-pale-blue w3-leftbar w3-border-green w3-center'><p><b>Step 2</b> of Registration Successful!</p><p>QR Code generated for <b>".$_SESSION["fname"].' '.$_SESSION["lname"]."</b>!</p><p>Please click the below code to download.</p></div>";
        echo "<div class='w3-container w3-center'>";        
        echo '<a href="'.$urlRelativeFilePath.'" download> <img src="'.$urlRelativeFilePath.'"> </a>';
        echo '</div>';        

        $stmt = $conn->prepare("UPDATE reg_entries SET qr_gen= 'Y' WHERE cid=? ");
        $stmt->bind_param('s', $_SESSION["cid"]);
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
        $mail->setFrom('Your From Email Address', $church_name.' '.$vbs_year.' '.$vbs_title);        
        $mail->addAddress($email, $cname);
        $mail->Subject = $church_name.' - '.$vbs_year.' - '.$vbs_title.' - Registration';
        $mail->isHTML(true);        
        $mail->Body    = 'Hi '.$cname.',<br><br>Please find below the unique QR code for '.$_SESSION["fname"].' '.$_SESSION["lname"].' <br><br><img src="cid:qrcode"><br><br> Please present this code at the venue for expedited check-in on all the days. <br><br> Regards,<br>'.$church_name.' VBS Team<br>'.$church_web;
        $qrFileName = $pngAbsoluteFilePath;
        $mail->addEmbeddedImage($qrFileName, 'qrcode');
        $mail->addAttachment($qrFileName);
        if (!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo '<div class="w3-container w3-center">Your unique QR Code for '.$_SESSION["fname"].' '.$_SESSION["lname"].' has been emailed to you at <b>'.$email.'</b></div>';
            echo "<div class='w3-container w3-center'><p>You can close this window now. Thanks!</p>";
        }

    } else {
        
        echo "<div class='w3-container w3-center'>";
        echo '<br>Your QR Code already created. Click the below code to download.<br>';
        echo '<a href="'.$urlRelativeFilePath.'" download> <img src="'.$urlRelativeFilePath.'"> </a>';
        echo '</div>';        

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
        $mail->setFrom('Your From Email Address', $church_name.' '.$vbs_year.' '.$vbs_title);        
        $mail->addAddress($email, $cname);
        $mail->Subject = $church_name.' - '.$vbs_year.' - '.$vbs_title.' - Registration';
        $mail->isHTML(true);        
        $mail->Body    = 'Hi '.$cname.',<br><br>Please find below the unique QR code for '.$_SESSION["fname"].' '.$_SESSION["lname"].' <br><br><img src="cid:qrcode"><br><br> Please present this code at the venue for expedited check-in on all the days. <br><br> Regards,<br>'.$church_name.' VBS Team<br>'.$church_web;
        $qrFileName = $urlRelativeFilePath;
        $mail->addEmbeddedImage($qrFileName, 'qrcode');
        $mail->addAttachment($qrFileName);
        if (!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo '<div class="w3-container w3-center">Your unique QR Code has been emailed to you at <b>'.$email.'</b></div>';
            echo "<div class='w3-container w3-center'><p>You can close this window now. Thanks!</p>";
        }
    }
$conn->close();    
session_destroy();
}
else {
    echo "<div class='w3-panel w3-yellow w3-leftbar w3-border-red'><p><b>Error!</b></p><p>Something went wrong. Your session expired!!</p></div>";        
}    
?>

</div>
<p></p>
<?php echo $footer; ?>