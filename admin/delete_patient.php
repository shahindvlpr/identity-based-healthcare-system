<?php
include_once('../dbconn.php');

// Ensure a valid NID is passed for deletion
if (isset($_GET['nid'])) {
    $nid = $_GET['nid'];

    // Deleting patient record by NID
    $stmt = $conn->prepare("DELETE FROM patient WHERE nid = ?");
    $stmt->bind_param("s", $nid);
    $stmt->execute();

    // Redirect back to the manage patients page
    header("Location: manage_patients.php");
    exit();
} else {
    echo "No NID provided for deletion.";
}
?>
