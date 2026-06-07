<?php
include_once('../dbconn.php');
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['admin_username'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if (!isset($_POST['p_nid']) || !isset($_POST['d_nid'])) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit();
}

$p_nid = $_POST['p_nid'];
$d_nid = $_POST['d_nid'];

$query = "SELECT a.*, 
          p.name as patient_name, p.mobile_no as patient_mobile, p.blood as patient_blood,
          d.name as doctor_name, d.mobile_no as doctor_mobile
          FROM appointment a
          LEFT JOIN person p ON a.p_nid = p.nid
          LEFT JOIN doctor doc ON a.d_nid = doc.d_nid
          LEFT JOIN person d ON doc.d_nid = d.nid
          WHERE a.p_nid = ? AND a.d_nid = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $p_nid, $d_nid);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'success' => true,
        'p_nid' => $row['p_nid'],
        'd_nid' => $row['d_nid'],
        'patient_name' => $row['patient_name'] ?? 'Unknown',
        'patient_mobile' => $row['patient_mobile'] ?? 'N/A',
        'patient_blood' => $row['patient_blood'] ?? 'N/A',
        'doctor_name' => $row['doctor_name'] ?? 'Unknown',
        'doctor_mobile' => $row['doctor_mobile'] ?? 'N/A',
        'appointment_date' => date('M d, Y', strtotime($row['date'])),
        'appointment_time' => date('h:i A', strtotime($row['date'])),
        'status' => $row['appointment']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Appointment not found']);
}
?>