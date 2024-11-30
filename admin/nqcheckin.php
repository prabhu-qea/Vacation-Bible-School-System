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
<title><?php echo $church_name;?> | <?php echo $vbs_title;?> | <?php echo $vbs_year;?> | Admin | Non QR Code Checkin</title>
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
    #results {
            border: 1px solid #ccc;
            max-width: 300px;
            margin-top: 10px;
            padding: 10px;
            display: none;
        }
        #results div {
            padding: 5px;
            cursor: pointer;
        }
        #results div:hover {
            background-color: #f0f0f0;
        }
</style>
</head>
<body>

<?php include 'nav.php';?>
    <div class="w3-container">
        <p></p>
        <div class="w3-container w3-light-blue">
            <h3><u>Non QR Code Check-In System</u></h3>
        </div>
        <form class="w3-container w3-border" id="checkinForm" action="nqrcheckin.php" method="post">
            <p></p>
            <label class="w3-text-black"><b>Child's Last Name</b></label>
            <p></p>            
            <input class="w3-input w3-border w3-light-grey" name="lname" type="text" id="searchBox" placeholder="Start typing the children name..." autocomplete="off">
            <div id="results"></div>
            <script>
                const searchBox = document.getElementById('searchBox');
                const results = document.getElementById('results');

                searchBox.addEventListener('input', function () {
                const query = this.value;            
                if (query.length > 0) {
                    fetch(`../inc/search.php?query=${query}`)
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
            <p></p>
            <label class="w3-text-black"><b>Child's Date of Birth</b></label>
            <p></p>
            <input class="w3-input w3-border w3-light-grey"type="date" id="dob" name="dob" required>
            <p></p>
            <label for="day"><b>Select the day</b></label>
            <p></p>
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
    <p></p>
    <?php echo $footer; ?>