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
<title><?php echo $church_name;?> | <?php echo $vbs_title;?> | <?php echo $vbs_year;?> | Admin | All Registered</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="../inc/style.css">
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
    document.addEventListener('DOMContentLoaded', function() {
            // Fetch categories on page load
            document.getElementById('checkedInData').hidden = true;
                      
            document.getElementById('day').addEventListener('change', function() {
                document.getElementById('checkedInData').hidden = false;
                let dayField = document.getElementById('day').value;
                let temp = this.value;
                
                if (dayField) {
                    fetch('fetch_checkedin.php?day=' + dayField)
                        .then(response => response.json())                        
                        .then(data => {                           
                            let tbody = document.getElementById('resultsTable').querySelector('tbody');
                            tbody.innerHTML = '';
                            data.forEach(children => {
                                let row = document.createElement('tr');
                                let coCheck = document.createElement('td');
                                coCheck.innerHTML = '<center><input type="checkbox" name="co_cid[]" value="'+ children.cid+'"></center>' 
                                let nameCell = document.createElement('td');
                                let childID = document.createElement('td');
                                childID.textContent = children.cid;
                                nameCell.textContent = children.fname + "  " + children.lname;                                
                                let childGroup = document.createElement('td');
                                childGroup.textContent = children.gname;                                
                                row.appendChild(coCheck);
                                row.appendChild(childID);
                                row.appendChild(nameCell);                                
                                row.appendChild(childGroup);
                                
                                tbody.appendChild(row);
                            });
                        });
                }
            });
        });
</script>
</head>

<body>

<?php include 'nav.php';?>

<div class="w3-container">        
        <h3><u>Children >> Checkout</u></h3>
</div>        
<div class="w3-container">
<?php
// include '../inc/db.php';
?>
            <p></p>            
            <form class="w3-container" id="checkoutForm" method="POST" action="checkoutDay.php">
            <p></p>
            
            <label class="w3-text-black"><b>Select Day</b></label><p></p>        
            <select class="w3-select w3-border" id="day" name="day">
                <option value="">Select Day</option>
                <option value="Day1">Day 1</option>
                <option value="Day2">Day 2</option>
                <option value="Day3">Day 3</option>           
            </select> 
            <p></p>
            <div class="w3-container" id="checkedInData">
            <table id="resultsTable" class="w3-table-all w3-centered w3-hoverable">    
            <thead>            
            <tr>
                <th>Checkout</th>
                <th>ID</th>
                <th>Name</th>                
                <th>Group</th>                
            </tr>
            </thead>
            <tbody>            

            </tbody>    
            </table>
            </div>            
            <p></p>        
            <button class="w3-btn w3-black" type="submit" id="submitButton">Checkout</button>
            <p></p>
    </form>
</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('checkoutForm');
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