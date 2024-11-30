<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}


include '../inc/db.php';



// Handle AJAX request
if (isset($_GET['query'])) {
    $search = $conn->real_escape_string($_GET['query']);
    $result = $conn->query("SELECT lname FROM reg_entries WHERE lname LIKE '%$search%' LIMIT 10");

    $output = [];
    while ($row = $result->fetch_assoc()) {
        $output[] = $row['lname'];
    }
    echo json_encode($output);
    exit;
}
elseif (isset($_GET['allergy'])) {
    $search = $conn->real_escape_string($_GET['allergy']);
    $result = $conn->query("SELECT name FROM allergies WHERE name LIKE '%$search%'");

    $output = [];
    while ($row = $result->fetch_assoc()) {
        $output[] = $row['name'];
    }
    echo json_encode($output);
    exit;
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search as You Type</title>
    <style>
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
<!-- <body>
    <h1>Search as You Type</h1>
    <input type="text" id="searchBox" placeholder="Start typing..." autocomplete="off">
    <div id="results"></div>

    <script>
        const searchBox = document.getElementById('searchBox');
        const results = document.getElementById('results');

        searchBox.addEventListener('input', function () {
            const query = this.value;

            if (query.length > 0) {
                fetch(`search.php?query=${query}`)
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
</body>
</html> -->
