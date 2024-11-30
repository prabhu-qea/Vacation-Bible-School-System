<!DOCTYPE html>
<html>
<head>
<title><?php echo $church_name;?> | <?php echo $vbs_title;?> | <?php echo $vbs_year;?> | Admin | Status E-Mail</title>
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
// $sql = "SELECT * FROM reg_entries";

$sq ="SELECT * FROM reg_entries"; 
$res = $conn->query($sq);
// $sql ="SELECT gname, COUNT(stgrpid) as count_name FROM reg_entries RIGHT JOIN grp ON grp.grpid=reg_entries.stgrpid GROUP BY stgrpid";
$sql ="SELECT gname, COUNT(stgrpid) as count_name, SUM(CASE WHEN allergies NOT IN('None','Nothing','NA','N/A','Not Applicable','Na','No','N','nO','N0','nA',' ','Please enter None if nothing to state') THEN 1 ELSE 0 END) as allergy_count FROM reg_entries RIGHT JOIN grp ON grp.grpid=reg_entries.stgrpid GROUP BY stgrpid"; 
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    
    $regs = $res->num_rows;
        
        //E-Mail routine Please add your email server details as relevant

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
        $mail->Username = '';
        $mail->Password = '';
        $mail->setFrom('Your Email Address', $church_name.' '.$vbs_year.' '.$vbs_title);
        $mail->addReplyTo('Your Email Address', $church_name.' VBS Team');
        $mail->addAddress('Email Address of Recepient 1', 'Name of Recepient 1');
        $mail->addAddress('Email Address of Recepient 2', 'Name of Recepient 2');        
        $mail->Subject = $church_name.' '.$vbs_year.' '.$vbs_title.' - Registration Status';
        $mail->isHTML(true);
        $date = new DateTimeImmutable();        
        $content = 'Hello,<br><br> Praise the Lord!<br><br>We have  <b>'.$regs.' </b> registrations as of '.$date->format('l jS \o\f F Y').'.<br><br>';
        $content .= '<table border="1"><thead><tr><th>Group Name</th><th>No of Children</th><th>Children with Allergies</th></tr></thead>';
        while($row = $result->fetch_assoc()) {
            $content .= "<tr><td>".$row["gname"] ."</td><td align='center'>". $row["count_name"] . "</td><td align='center'>". $row["allergy_count"] . "</td></tr>";
        }
        $content .=  '</table><br><br> Regards,<br>'.$church_name.' VBS Team<br>'.$church_web;
        
        $message = $content;
        $mail->Body = $message;
        
        if (!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo '<div class="w3-container w3-center">E-Mail Notification Sent!!</div>';            
        }    
    $conn->close(); 
} else {
    // exit;
    echo "need to do something";
}
?>

</div>
<p></p>
<?php echo $footer; ?>