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
</head>
<body>

<?php include 'nav.php';?>

    <div class="w3-container">
        <p></p>
        <div class="w3-container w3-light-blue">
            <h3><u>QR Code Check-In System</u></h3>
        </div>
        <p></p>
        <div class="w3-container w3-light-blue">
                <video id="video" width="300" height="200" autoplay playsinline hidden></video>
                <canvas id="canvas" hidden></canvas>
                <button class="w3-button w3-black" id="startBtn">Start Scanner</button>
                <button class="w3-button w3-black" id="stopBtn" disabled>Stop Scanner</button>
                <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
        </div>
            <form class="w3-container w3-border" id="checkinForm" action="qrcheckin.php" method="post">
                <p></p>
                <label class="w3-text-black"><b>QR Code Data:</b></label>
                <p></p>
                            
                <input class="w3-input w3-border w3-light-grey" type="text" id="qrraw" name="qrraw" required autofocus readonly placeholder="QR Code data will appear here"><p></p>
                <label class="w3-text-black"><b>Select the day</b></label><p></p>
                <select class="w3-select w3-border" name="day" id="day">
                    <option value="Day1">Day 1</option>
                    <option value="Day2">Day 2</option>
                    <option value="Day3">Day 3</option>
                </select>
                <p></p>
                <button class="w3-btn w3-black" type="submit">Check-in</button>
                <p></p>
            </form>
    </div>
    <script>
        let video = document.getElementById("video");
        let canvas = document.getElementById("canvas");
        let context = canvas.getContext("2d");
        let qrInput = document.getElementById("qrraw");
        let startBtn = document.getElementById("startBtn");
        let stopBtn = document.getElementById("stopBtn");
        let stream = null;
        let scanning = false;

        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: "environment" } // Rear camera
                });
                video.srcObject = stream;
                video.hidden = false;
                scanning = true;
                requestAnimationFrame(scanQRCode);
                startBtn.disabled = true;
                stopBtn.disabled = false;
            } catch (error) {
                console.error("Camera error:", error);
                alert("Camera access denied or not supported.");
            }
        }

        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop()); // Stop camera
            }
            video.hidden = true;
            scanning = false;
            startBtn.disabled = false;
            stopBtn.disabled = true;
        }

        function scanQRCode() {
            if (!scanning) return;

            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: "dontInvert" });

                if (code) {
                    qrInput.value = code.data; // Autofill the text field
                    stopCamera(); // Stop scanning after successful scan
                }
            }
            requestAnimationFrame(scanQRCode);
        }

        startBtn.addEventListener("click", startCamera);
        stopBtn.addEventListener("click", stopCamera);
    </script> 
<p></p>
<?php echo $footer; ?>