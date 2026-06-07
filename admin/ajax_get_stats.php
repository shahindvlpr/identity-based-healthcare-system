<?php
include_once('../dbconn.php');
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['admin_username'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$response = [];

// Total doctors
$result = $conn->query("SELECT COUNT(*) as total FROM doctor");
$response['total_doctors'] = $result->fetch_assoc()['total'] ?? 0;

// Total hospitals
$result = $conn->query("SELECT COUNT(*) as total FROM hospital");
$response['total_hospitals'] = $result->fetch_assoc()['total'] ?? 0;

// Today's appointments
$today = date('Y-m-d');
$result = $conn->query("SELECT COUNT(*) as total FROM appointment WHERE DATE(appointment_date) = '$today'");
$response['today_appointments'] = $result->fetch_assoc()['total'] ?? 0;

// Active doctors (has appointments in last 30 days)
$result = $conn->query("SELECT COUNT(DISTINCT d_nid) as total FROM appointment WHERE appointment_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
$response['active_doctors'] = $result->fetch_assoc()['total'] ?? 0;

echo json_encode($response);
?>