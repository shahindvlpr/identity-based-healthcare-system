<?php
include_once('../dbconn.php'); 

if (isset($_GET['license'])) {
    $license = $_GET['license'];
    $conn->query("DELETE FROM hospital WHERE license = '$license'");
}

header("Location: manage_hospitals.php");
exit();
?>
