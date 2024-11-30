<div class="w3-bar">
<div class="w3-sidebar w3-bar-block w3-border-right w3-blue" style="display:none" id="mySidebar">
  <button onclick="w3_close()" class="w3-bar-item w3-large w3-black">CLOSE &times;</button>
    <a href="admin.php" class="w3-bar-item w3-button">Home</a>
    <button class="w3-button w3-block w3-left-align" onclick="myAccFuncCheckIn()">ADMIN <i class="fa fa-caret-down"></i></button>
    <div id="accCheck" class="w3-hide w3-grey w3-card">
        <a href="config.php" class="w3-bar-item w3-button">System Setup</a>
        <a href="checkin.php" class="w3-bar-item w3-button">QR Code Check-In</a>
        <a href="nqcheckin.php" class="w3-bar-item w3-button">Non-QR Code Check-In</a>        
        <a href="checkedin.php" class="w3-bar-item w3-button">Who Checked-In?</a>
        <a href="assign.php" class="w3-bar-item w3-button">Class Assignment</a>
        <a href="move.php" class="w3-bar-item w3-button">Class Movement</a>
        <a href="allergy.php" class="w3-bar-item w3-button">Allergy Info</a>
        <a href="teachers.php" class="w3-bar-item w3-button">Teachers - Add/Edit</a>
        <a href="attendList.php" class="w3-bar-item w3-button">Attendance Register</a>
        <a href="checkout.php" class="w3-bar-item w3-button">Checkout</a>
        <a href="checkedout.php" class="w3-bar-item w3-button">Who Checked out?</a>        
        <a href="qremail.php" class="w3-bar-item w3-button">QR Code E-mail</a>
        <a href="status_email.php" class="w3-bar-item w3-button">Status E-Mail</a>
    </div>        
    <button class="w3-button w3-block w3-left-align" onclick="myAccFuncClass()">TEACHER <i class="fa fa-caret-down"></i></button>
    <div id="accClass" class="w3-hide w3-grey w3-card">        
        <a href="attendance.php" class="w3-bar-item w3-button">Class Attendance</a>        
    </div>
    <a href="logout.php" class="w3-bar-item w3-button">Log out</a>
</div>


<div class="w3-blue">
  <button class="w3-button w3-blue w3-xlarge" onclick="w3_open()">☰ VBS Admin</button>
</div>

<script>
function w3_open() {
  document.getElementById("mySidebar").style.display = "block";
}

function w3_close() {
  document.getElementById("mySidebar").style.display = "none";
}

function myAccFuncCheckIn() {
    var x = document.getElementById("accCheck");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
        x.previousElementSibling.className += " w3-green";
    } else { 
        x.className = x.className.replace(" w3-show", "");
        x.previousElementSibling.className = 
        x.previousElementSibling.className.replace(" w3-green", "");
    }
}
function myAccFuncClass() {
    var x = document.getElementById("accClass");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
        x.previousElementSibling.className += " w3-green";
    } else { 
        x.className = x.className.replace(" w3-show", "");
        x.previousElementSibling.className = 
        x.previousElementSibling.className.replace(" w3-green", "");
    }
}
</script>
