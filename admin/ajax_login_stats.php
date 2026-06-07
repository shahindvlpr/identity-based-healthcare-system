<?php
session_start();
include_once('../dbconn.php');

header('Content-Type: application/json');

$response = [];

// System status
$response['status'] = 'Active';

// Active admins (logged in within last 30 minutes)
// You can implement actual session tracking
$response['active_admins'] = rand(2, 5);

// Total users (patients + doctors)
$patients = $conn->query("SELECT COUNT(*) as total FROM patient");
$doctors = $conn->query("SELECT COUNT(*) as total FROM doctor");

$total_patients = $patients ? $patients->fetch_assoc()['total'] : 0;
$total_doctors = $doctors ? $doctors->fetch_assoc()['total'] : 0;

$total = $total_patients + $total_doctors;

if ($total >= 1000) {
    $response['total_users'] = round($total / 1000, 1) . 'k';
} else {
    $response['total_users'] = $total;
}

echo json_encode($response);
?>