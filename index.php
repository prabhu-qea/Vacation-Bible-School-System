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
<script src="inc/jquery.min.js"></script>
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

<?php
if ($vbs_active == "N") {
    echo "<div class='w3-container'><div class='w3-container w3-center'><h2>No Vacation Bible School Active at the moment. Please check back later. Thanks!</h2></div><p></p>";
    echo $footer;
    exit;
}
?>

<?php

$sq = "SELECT * FROM reg_entries";

$resCount = $conn->query($sq);

$totalReg = $resCount->num_rows;

if ($totalReg >= 150) {
?>
<div class="w3-container">    
    <div class="w3-container w3-center">
        <h2><img class="w3-border w3-padding w3-image" src="img/logo.jpg" style="width:100%;max-width:150px"></h2>
        <h2><?php echo $church_name;?> - <?php echo $vbs_title;?> - <?php echo $vbs_year;?> </h2>
    </div>
    <div class="w3-container w3-card">
        <div class="w3-row-padding w3-center">
            <div>
                <p></p>
                <b>Sorry! Registrations are now closed for <?php echo $church_name;?> - <?php echo $vbs_title;?> - <?php echo $vbs_year;?> </b>
                <p></p>
                <p>Thank you for your interest in our event. We regret to inform you that registrations are now closed. We appreciate your enthusiasm and support.</p>
                <p>Please check back next year for a brand new themed Vacation Bible School to join us. We look forward to welcoming you in the future.</p>                
            </div>            
        </div>        
    </div>    
</div>
<p></p>
<?php echo $footer; ?>
<?php
} 
else {  
?>

<div class="w3-container">
    <!-- <img src="img/bg.jpg" alt="Lights" style="width:100%"> -->
    <div class="w3-container w3-center">
        <h2><img class="w3-border w3-padding w3-image" src="img/logo.jpg" style="width:100%;max-width:150px"></h2>
        <h2><?php echo $church_name;?> - <?php echo $vbs_title;?> - <?php echo $vbs_year;?>  - Registration Form</h2>
    </div>
    <div class="w3-container w3-card">
        <div class="w3-row-padding">
            <div class="w3-half">
                <b><?php echo $church_name;?> - <?php echo $vbs_title;?> - <?php echo $vbs_year;?></b>
                <p></p>
                Enter your own contect here...
                <p></p>
                <b>Time/Date/Venue:</b> Enter your own contect here...
                <p></p>                
                Enter your own contect here...
                <p></p>
                Enter your own contect here...
                <p></p>
            </div>
            <div class="w3-half">
                <b>Few Important bits:</b>
                <p></p>
                <!--Please edit the below as needed -->                
                I understand it is my responsibility to inform the church of any medical conditions, allergies, or relevant health information about my child, and I have disclosed all necessary details.
                <p></p>
                In case of a medical emergency, I consent to my child receiving medical care, including first aid, CPR, or ambulance services, understanding the church will attempt to contact me or the provided emergency contact before seeking care, but may proceed if urgent. 
                <p></p>
                I also consent to the church using photographs and videos of my child for promotional purposes, ensuring my child's name will not be associated without separate written consent.
                <p></p>
                <b><i>By submitting this form, I agree to release, hold harmless, and indemnify <?php echo $church_name;?> and its representatives from any claims or liabilities arising from my child's participation in the activities, to the fullest extent permitted by law.</i></b>
                <p></p>                
            </div>  
        </div>        
    </div>
    <p></p>
    <form class="w3-container w3-card-4 w3-light-grey" id="registrationForm" action="register.php" method="post">
        <div class="w3-panel w3-pale-blue w3-leftbar w3-border-blue">
            <p><i>Please fill all the fields below and provide as much information as possible.</i></p>
        </div>        
        
        <div class="w3-row-padding">
            <div class="w3-half">
                <label>Child's First Name:</label>
                <input class="w3-input w3-border w3-round-large" type="text" id="first_name" name="first_name" required>
            </div>
            <div class="w3-half">
                <label>Child's Last Name:</label>
                <input class="w3-input w3-border w3-round-large" type="text" id="last_name" name="last_name" required>
            </div>  
        </div>
        <p></p>
        <div class="w3-row-padding">
            <div class="w3-half">
                <label>Child's Date of Birth:</label>
                <input class="w3-input w3-border w3-round-large" type="date" id="dob" name="dob" onchange="calculateAge()" required>
            </div>
            <div class="w3-half">
                <label>Child's Age:</label>    
                <input class="w3-input w3-border w3-round-large" type="number" id="age" name="age" readonly required>
            </div>  
        </div>
        <div class="w3-panel w3-red" id="ageWarning" style="display: none;">
            Warning: Age is either less than 5 or greater than 15. Sorry, you cannot register.
        </div>
        <p></p>
        <div class="w3-row-padding">
            <div class="w3-half">
                <label>Child's School:</label>
                <input class="w3-input w3-border w3-round-large" type="text" id="school" name="school" required>
            </div>
            <div class="w3-half">
                <label>Allergies/Medical Conditions(if any):</label>                   
                <input class="w3-input w3-border" name="allergies" type="text" id="searchBox" placeholder="Start typing and select from the list..." autocomplete="off">
                    <div id="results"></div>
                    <script>
                        const searchBox = document.getElementById('searchBox');
                        const results = document.getElementById('results');

                        searchBox.addEventListener('input', function () {
                        const query = this.value;            
                        if (query.length > 0) {
                            fetch(`inc/search.php?allergy=${query}`)
                                .then(response => response.json())
                                .then(data => {
                                    results.innerHTML = '';
                                    if (data.length > 0) {
                                        results.style.display = 'block';
                                        data.forEach(item => {
                                            const div = document.createElement('div');
                                            div.textContent = item;
                                            results.appendChild(div);
                                        });
                                    } else {
                                        results.style.display = 'none';
                                    }
                                });
                        } else {
                            results.style.display = 'none';
                        }
                        });

                        results.addEventListener('click', function (e) {
                        if (e.target && e.target.nodeName === 'DIV') {
                            searchBox.value = e.target.textContent;
                            results.style.display = 'none';
                        }
                        });
                    </script>
                    </div>  
        </div>
        <p></p>
        <div class="w3-panel w3-pale-blue w3-leftbar w3-border-green">
            <p>(Emergency) Contact Details:</p>
        </div>
        
        <div class="w3-row-padding">
            <div class="w3-half">
                <label>Child's Contact Name:</label>
                <input class="w3-input w3-border w3-round-large" type="text" id="contact_name" name="contact_name" required>
            </div>
            <div class="w3-half">
                <label>Relationship to child:</label>    
                <input class="w3-input w3-border w3-round-large" type="text" id="relationship" name="relationship" required>
            </div>  
        </div>
        <p></p>

        <div class="w3-row-padding">
            <div class="w3-third">
                <label>Child's Contact E-Mail:</label>
                <input class="w3-input w3-border w3-round-large" type="email" id="email" name="email" required>
            </div>
            <div class="w3-third">
                <label>Child's Contact Mobile Number:</label>    
                <input class="w3-input w3-border w3-round-large" type="text" id="phone" name="phone" required value="Please enter the valid UK mobile number" onfocus="this.value=''">
            </div> 
            <div class="w3-third">
                <label>Contact Address:</label>    
                <input class="w3-input w3-border w3-round-large" type="text" id="address" name="address" required>
            </div>               
        </div>
        <p></p>
        <div calss="w3-panel" id="result"></div>      
        <p></p>
        <div class="w3-container w3-center">
            <button class="w3-button w3-black" type="submit" id="submitButton">Register</button>
        </div>
        <p></p>
      </form>
</div>
<script>
    function calculateAge() {
        const dob = document.getElementById('dob').value;
        if (dob) {
            const dobDate = new Date(dob);
            const diffMs = Date.now() - dobDate.getTime();
            const ageDt = new Date(diffMs);
            const age = Math.abs(ageDt.getUTCFullYear() - 1970);
            document.getElementById('age').value = age;

            const ageWarning = document.getElementById('ageWarning');
            const submitButton = document.getElementById('submitButton');
            if (age > 15 || age < 5){
                ageWarning.style.display = 'block';
                submitButton.disabled = true;
                
            } else {
                ageWarning.style.display = 'none';
                submitButton.disabled = false;
            }
        }
    }

    function validateForm() {
        const age = document.getElementById('age').value;
        if (age > 15) {
            alert('Age is greater than 15. Form cannot be submitted.');
            return false;
        }
        return true;
    }

    const validateEmail = (email) => {
    return email.match(
      /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    );
  };
  
  const validate = () => {
    const $result = $('#result');
    const email = $('#email').val();
    $result.text('');
  
    if(validateEmail(email)){
      $result.text(email + ' is valid.');
      $result.css('color', 'green');
    } else{
      $result.text(email + ' is invalid.');
      $result.css('color', 'red');
    }
    return false;
  }
  
  $('#email').on('input', validate);

</script>   
<p></p>
<?php echo $footer; ?>
<?php
}
$conn->close();
?>
