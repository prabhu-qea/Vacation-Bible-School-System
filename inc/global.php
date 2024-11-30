<?php 
$globalresult = $conn->query("SELECT * FROM config WHERE vbs_active='Y'");
    
if ($globalresult->num_rows > 0) {    
    $globalrow = $globalresult->fetch_assoc();
    $church_name = $globalrow['church_name'];
    $vbs_title = $globalrow['vbs_title'];
    $vbs_year = $globalrow['vbs_year'];
    $church_web = $globalrow['web'];    
}
else {
    $church_name = "VBS";
    $vbs_title = "Title";
    $vbs_year = "Current Year";
    $church_web = "Website Address";
}

/* Feel free to change this to your content or leave as is, and that is highly appreciated! */
$footer = <<<HTML
<div class="w3-container w3-light-grey w3-center">
    <p>&copy; Good Shepherd Jesus Christ Church<br>
    <a href="https://www.goodshepherdjcchurch.org">https://www.goodshepherdjcchurch.org</a></p>
    <p class="w3-small">Powered by GSJCC Media Team</p>
</div>
</body>
</html>
HTML;
?>