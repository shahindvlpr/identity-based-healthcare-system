<?php
include_once('../dbconn.php');

if (isset($_GET['nid'])) {
    $nid = $_GET['nid'];
    $conn->query("DELETE FROM doctor WHERE nid = '$nid'");
}

header("Location: manage_doctors.php");
exit();
?>
