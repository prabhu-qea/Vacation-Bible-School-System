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
<title><?php echo $church_name;?> | <?php echo $vbs_title;?> | <?php echo $vbs_year;?> | Admin | Teacher | Children | Class Attendance</title>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch categories on page load
            document.getElementById('attData').hidden = true;            
            fetch('fetch_group.php')
                .then(response => response.json())
                .then(data => {
                    let group = document.getElementById('group');
                    data.forEach(groups => {
                        let option = document.createElement('option');                        
                        option.value = groups.grpid;
                        option.textContent = groups.gname;
                        group.appendChild(option);
                    });                    
                });                
            document.getElementById('group').addEventListener('change', function() {
                let groupid = this.value;
                let teacherList = document.getElementById('teacher');
                teacherList.innerHTML = '<option value="">Select Teacher</option>';

                if (groupid) {                    
                    fetch('fetch_teacher.php?groupID=' + groupid)
                        .then(response => response.json())
                        .then(data => {                            
                            data.forEach(teachers => {
                                let option = document.createElement('option');
                                option.value = teachers.tid;
                                option.textContent = teachers.tname + "  &  " + teachers.at_name;
                                teacherList.appendChild(option);
                            });
                        });
                }
            });

            document.getElementById('teacher').addEventListener('change', function() {
                document.getElementById('attData').hidden = false;
                let groupID = document.getElementById('group').value;
                let teacherID = this.value;

                if (groupID && teacherID) {
                    fetch('fetch_class.php?groupID=' + groupID + '&teacherID=' + teacherID)
                        .then(response => response.json())                        
                        .then(data => {
                            // console.log(Object.keys(data).length);                                                        
                            // document.getElementById('attData').hidden = false;                            
                            let tbody = document.getElementById('resultsTable').querySelector('tbody');
                            tbody.innerHTML = '';
                            data.forEach(children => {
                                let row = document.createElement('tr');
                                let nameCell = document.createElement('td');
                                nameCell.textContent = children.fname + "  " + children.lname;
                                // let descCell = document.createElement('td');
                                // descCell.textContent = children.dob;
                                let amAtt = document.createElement('td');
                                amAtt.innerHTML = '<center><input type="checkbox" id="am" name="amAtt[]" value="'+ children.cid+'"></center>' 
                                let pmAtt = document.createElement('td');
                                pmAtt.innerHTML = '<center><input type="checkbox" id="pm" name="pmAtt[]" value="'+ children.cid+'"></center>'
                                row.appendChild(nameCell);
                                // row.appendChild(descCell);
                                row.appendChild(amAtt);
                                row.appendChild(pmAtt);
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
    <p></p>
    <div class="w3-container w3-light-blue">
        <h3><u>Children >> Class Attendance</u></h3>
    </div>
    <p></p>    
    
    <form class="w3-container w3-border" id="trackingForm" method="POST" action="attendanceSubmission.php">
        <p></p>
        <label class="w3-text-black"><b>Select Day</b></label><p></p>        
        <select class="w3-select w3-border" id="day" name="day" required>
            <option value="">Select Day</option>
            <option value="Day1">Day 1</option>
            <option value="Day2">Day 2</option>
            <option value="Day3">Day 3</option>           
        </select> 
        <p></p>
        <label class="w3-text-black"><b>Select Group</b></label><p></p>        
        <select class="w3-select w3-border" id="group" name="group" required>
            <option value="">Select Group</option>           
        </select>        
        <p></p>
        <label class="w3-text-black"><b>Select the Teacher</b></label><p></p>       
        <select class="w3-select w3-border" id="teacher" name="teacher" required>
            <option value="">Select Teacher</option>            
        </select>            
        <p></p>
        <div class="w3-container" id="attData">
        <table id="resultsTable" border="1">
            <thead>
                <tr>
                    <th>Name</th>                    
                    <th>AM Session</th>
                    <th>PM Session</th>
                </tr>
            </thead>
            <tbody>
                <!-- Results will be displayed here -->
            </tbody>
        </table>
        </div>
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