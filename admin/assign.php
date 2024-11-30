<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}
?>
<?php
// Database connection
include '../inc/db.php';
include '../inc/global.php';
// Fetch distinct group fields for the first dropdown
$groupFields = $conn->query("SELECT * FROM grp");

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $church_name;?> | <?php echo $vbs_title;?> | <?php echo $vbs_year;?> | Admin | Children - Class Assignment</title>
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
<script>
        function fetchGroupedData() {
            var groupField = document.getElementById('groupField').value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_data.php?groupField=' + groupField, true);
            xhr.onload = function() {
                if (this.status == 200) {
                    document.getElementById('groupedData').innerHTML = this.responseText;
                    var disButton = document.getElementById('groupedData').innerText
                    
                    if (disButton.trim() == "No children available to assign a class"){                        
                        document.getElementById("submitButton").disabled =true;
                    }else {                        
                        document.getElementById("submitButton").disabled =false;
                    }
                    
                }
            };
            xhr.send();
            
            fetchRelatedDropdown(groupField);
        }

        function fetchRelatedDropdown(groupField) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_dropdown.php?groupField=' + groupField, true);
            xhr.onload = function() {
                if (this.status == 200) {
                    document.getElementById('teacher').innerHTML = this.responseText;
                }
            };
            xhr.send();
        }
</script>
</head>
<body>

<?php include 'nav.php';?>

    <div class="w3-container">
    <p></p>
    <div class="w3-container w3-light-blue">
        <h3><u>Children >> Class Assignment</u></h3>
    </div>
    <!-- <div class="w3-container"> -->
    
    <form class="w3-container w3-border" id="trackingForm" method="POST" action="assignSubmit.php">
        <p></p>
        <label class="w3-text-black"><b>Select Group</b></label><p></p>        
        <select class="w3-select w3-border" id="groupField" name="groupField" onchange="fetchGroupedData()">
            <option value="">Select</option>
            <?php while ($row = $groupFields->fetch_assoc()): ?>
                <option value="<?php echo $row['grpid']; ?>"><?php echo $row['gname']; ?></option>
            <?php endwhile; ?>
        </select>

        <div class="w3-container" id="groupedData">
            <!-- Grouped data will be loaded here by AJAX -->
        </div>
        <p></p>        
        
            <label class="w3-text-black"><b>Select the Teacher</b></label><p></p>       
            <select class="w3-select w3-border" id="teacher" name="teacher">
                <option value="">Select</option>
                <!-- Options populated by AJAX based on groupField selection -->
            </select>        
            <p></p>    
            <label class="w3-text-black"><b>Class Name</b></label><p></p>
            <input class="w3-input w3-border w3-light-grey" type="text" name="className" size="50" required>
        
        
        <p></p>
        <button class="w3-btn w3-black" type="submit" id="submitButton">Submit</button>
        <p></p>
    </form>
    </div>   
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('trackingForm');
        const formElements = form.elements;
        let formChanged = false;

        function markFormAsChanged() {
            formChanged = true;
            
            document.getElementById("submitButton").disabled =false;
            // console.log('Form has been changed');
        }

        function trackChanges() {
            for (let i = 0; i < formElements.length; i++) {
                const element = formElements[i];
                // console.log(element);
                if (element.type !== 'submit' && element.type !== 'button') {
                    element.addEventListener('change', markFormAsChanged);                    
                } else {
                    document.getElementById("submitButton").disabled =true;
                }
            }
        }

        form.addEventListener('submit', function (event) {            
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            for (var i = 0; i < checkboxes.length; i++) {            
                if (!checkboxes[i].checked){
                    document.getElementById("submitButton").disabled =true;
                    event.preventDefault();
                    trackChanges();
                } else {
                    form.submit();
                }
            }
            if (formChanged) {
                // alert('Form is being submitted with changes');
            } else {
                alert('Form is being submitted without any changes');                
            }
        });

        trackChanges();
    });
    function toggle(source) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
    }
    }    
</script>
<p></p>
<?php echo $footer; ?>