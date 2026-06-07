<?php
include_once('../dbconn.php');
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: index.php");
    exit();
}

$success_msg = '';
$error_msg = '';

// Handle appointment status update
if (isset($_POST['update_status']) && isset($_POST['p_nid']) && isset($_POST['d_nid']) && isset($_POST['status'])) {
    $p_nid = $_POST['p_nid'];
    $d_nid = $_POST['d_nid'];
    $status = $_POST['status'];
    
    $appointment_value = ($status == 'confirmed' || $status == 'yes') ? 'yes' : 'no';
    
    $update_stmt = $conn->prepare("UPDATE appointment SET appointment = ? WHERE p_nid = ? AND d_nid = ?");
    if ($update_stmt) {
        $update_stmt->bind_param("sss", $appointment_value, $p_nid, $d_nid);
        if ($update_stmt->execute()) {
            $success_msg = "Appointment status updated successfully!";
        } else {
            $error_msg = "Failed to update status: " . $update_stmt->error;
        }
        $update_stmt->close();
    }
}

// Handle appointment deletion
if (isset($_GET['delete_p_nid']) && isset($_GET['delete_d_nid'])) {
    $delete_p_nid = $_GET['delete_p_nid'];
    $delete_d_nid = $_GET['delete_d_nid'];
    
    $delete_stmt = $conn->prepare("DELETE FROM appointment WHERE p_nid = ? AND d_nid = ?");
    if ($delete_stmt) {
        $delete_stmt->bind_param("ss", $delete_p_nid, $delete_d_nid);
        if ($delete_stmt->execute()) {
            $success_msg = "Appointment deleted successfully!";
        } else {
            $error_msg = "Failed to delete appointment: " . $delete_stmt->error;
        }
        $delete_stmt->close();
    }
}

// Get filter parameters
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$search_filter = isset($_GET['search']) ? $_GET['search'] : '';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';

// Simple query without specialist column
$query = "SELECT a.*, 
          p.name as patient_name, p.mobile_no as patient_mobile, p.blood as patient_blood,
          d.name as doctor_name, d.mobile_no as doctor_mobile
          FROM appointment a
          LEFT JOIN person p ON a.p_nid = p.nid
          LEFT JOIN doctor doc ON a.d_nid = doc.d_nid
          LEFT JOIN person d ON doc.d_nid = d.nid
          WHERE 1=1";

if ($status_filter != 'all') {
    if ($status_filter == 'confirmed') {
        $query .= " AND a.appointment = 'yes'";
    } elseif ($status_filter == 'pending') {
        $query .= " AND a.appointment = 'no'";
    }
}

if (!empty($search_filter)) {
    $query .= " AND (p.name LIKE '%$search_filter%' OR d.name LIKE '%$search_filter%' OR a.p_nid LIKE '%$search_filter%' OR a.d_nid LIKE '%$search_filter%')";
}

if (!empty($date_filter)) {
    $query .= " AND DATE(a.date) = '$date_filter'";
}

$query .= " ORDER BY a.date DESC";

$appointments = $conn->query($query);

if (!$appointments) {
    $error_msg = "Query Error: " . $conn->error;
}

// Get statistics
$total_query = $conn->query("SELECT COUNT(*) as total FROM appointment");
$total_appointments = $total_query ? $total_query->fetch_assoc()['total'] : 0;

$confirmed_query = $conn->query("SELECT COUNT(*) as total FROM appointment WHERE appointment = 'yes'");
$confirmed_appointments = $confirmed_query ? $confirmed_query->fetch_assoc()['total'] : 0;

$pending_query = $conn->query("SELECT COUNT(*) as total FROM appointment WHERE appointment = 'no'");
$pending_appointments = $pending_query ? $pending_query->fetch_assoc()['total'] : 0;

$today = date('Y-m-d');
$today_query = $conn->query("SELECT COUNT(*) as total FROM appointment WHERE DATE(date) = '$today'");
$today_appointments = $today_query ? $today_query->fetch_assoc()['total'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments | IBHS Admin</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.03)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') repeat-x bottom;
            background-size: cover;
            opacity: 0.4;
            pointer-events: none;
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .page-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 25px 30px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.1);
        }

        .header-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px -10px rgba(0, 0, 0, 0.2);
        }

        .stat-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 800;
            color: #2d3748;
            line-height: 1;
        }

        .stat-label {
            font-size: 12px;
            color: #718096;
            margin-top: 5px;
            font-weight: 500;
        }

        .filter-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .filter-input {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .filter-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .table-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.1);
        }

        .table-header {
            padding: 20px 25px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-confirmed {
            background: #d1fae5;
            color: #059669;
        }

        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 12px;
            transition: all 0.3s ease;
            margin: 2px;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            .page-header {
                padding: 20px;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .modal-content-custom {
            border-radius: 20px;
            border: none;
        }

        .detail-row {
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .detail-label {
            font-weight: 600;
            color: #4a5568;
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: white;
        }
        
        .btn-gradient:hover {
            background: linear-gradient(135deg, #5a67d8, #6b46c1);
            color: white;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>

<div class="main-container">
    
    <!-- Page Header -->
    <div class="page-header" data-aos="fade-down">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <h2 class="mb-0 fw-bold" style="color: #2d3748;">Appointment Management</h2>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-calendar-alt me-1"></i> 
                        <?php echo date('l, F j, Y'); ?>
                    </p>
                </div>
            </div>
            <div>
                <div class="text-end">
                    <div class="text-muted small">
                        <i class="fas fa-shield-alt me-1"></i> Admin Panel
                    </div>
                    <div class="fw-bold">
                        <i class="fas fa-user-circle me-1"></i>
                        <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="stats-grid" data-aos="fade-up">
        <div class="stat-card" onclick="filterByStatus('all')">
            <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="stat-number"><?php echo $total_appointments; ?></div>
            <div class="stat-label">Total Appointments</div>
        </div>
        <div class="stat-card" onclick="filterByStatus('confirmed')">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-number"><?php echo $confirmed_appointments; ?></div>
            <div class="stat-label">Confirmed</div>
        </div>
        <div class="stat-card" onclick="filterByStatus('pending')">
            <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
            <div class="stat-number"><?php echo $pending_appointments; ?></div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-card" onclick="filterByDate('<?php echo $today; ?>')">
            <div class="stat-icon"><i class="fas fa-sun"></i></div>
            <div class="stat-number"><?php echo $today_appointments; ?></div>
            <div class="stat-label">Today's</div>
        </div>
    </div>
    
    <!-- Alert Messages -->
    <?php if ($success_msg): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?php echo $success_msg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($error_msg): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> <?php echo $error_msg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- Filter Section -->
    <div class="filter-section" data-aos="fade-up">
        <form method="GET" action="" id="filterForm">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-search"></i> Search
                    </label>
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute" style="left: 15px; top: 12px; color: #a0aec0;"></i>
                        <input type="text" name="search" class="form-control filter-input" 
                               style="padding-left: 40px;"
                               placeholder="Search by patient name, doctor name, NID..."
                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-flag"></i> Status
                    </label>
                    <select name="status" class="form-select filter-input" id="statusSelect">
                        <option value="all" <?php echo $status_filter == 'all' ? 'selected' : ''; ?>>All Status</option>
                        <option value="confirmed" <?php echo $status_filter == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                        <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        <i class="fas fa-calendar"></i> Date
                    </label>
                    <div class="position-relative">
                        <i class="fas fa-calendar-day position-absolute" style="left: 15px; top: 12px; color: #a0aec0;"></i>
                        <input type="date" name="date" class="form-control filter-input" 
                               style="padding-left: 40px;"
                               value="<?php echo htmlspecialchars($date_filter); ?>">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-gradient w-100" style="border-radius: 12px;">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="manage_appointments.php" class="btn btn-secondary w-100" style="border-radius: 12px;">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Appointments Table -->
    <div class="table-card" data-aos="fade-up">
        <div class="table-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-list-ul me-2" style="color: #667eea;"></i>
                    Appointment List
                </h5>
                <div>
                    <span class="badge bg-primary"><?php echo $appointments->num_rows; ?> Records</span>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="appointmentsTable">
                <thead class="table-light">
                    <tr>
                        <th>SL</th>
                        <th>Patient Name</th>
                        <th>Patient NID</th>
                        <th>Doctor Name</th>
                        <th>Doctor NID</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($appointments && $appointments->num_rows > 0): ?>
                        <?php $sl = 1; while ($row = $appointments->fetch_assoc()): 
                            $status = $row['appointment'];
                            $is_confirmed = ($status == 'yes');
                        ?>
                            <tr>
                                <td><?php echo $sl++; ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-light p-2">
                                            <i class="fas fa-user-circle text-secondary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?php echo htmlspecialchars($row['patient_name'] ?? 'Unknown'); ?></div>
                                            <small class="text-muted"><?php echo htmlspecialchars($row['patient_mobile'] ?? 'N/A'); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($row['p_nid']); ?></td>
                                <td>
                                    <div class="fw-semibold">Dr. <?php echo htmlspecialchars($row['doctor_name'] ?? 'Unknown'); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($row['doctor_mobile'] ?? 'N/A'); ?></small>
                                 </td>
                                <td><?php echo htmlspecialchars($row['d_nid']); ?></td>
                                <td>
                                    <div>
                                        <i class="fas fa-calendar-alt me-1 text-muted"></i>
                                        <?php echo date('M d, Y', strtotime($row['date'])); ?>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        <?php echo date('h:i A', strtotime($row['date'])); ?>
                                    </small>
                                 </td>
                                <td>
                                    <?php if ($is_confirmed): ?>
                                        <span class="status-badge status-confirmed">
                                            <i class="fas fa-check-circle"></i> Confirmed
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge status-pending">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    <?php endif; ?>
                                 </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-action btn btn-sm btn-outline-info" onclick="viewAppointment('<?php echo $row['p_nid']; ?>', '<?php echo $row['d_nid']; ?>')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-action btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#statusModal" 
                                            onclick="setAppointmentDetails('<?php echo $row['p_nid']; ?>', '<?php echo $row['d_nid']; ?>', '<?php echo $is_confirmed ? 'confirmed' : 'pending'; ?>')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="?delete_p_nid=<?php echo $row['p_nid']; ?>&delete_d_nid=<?php echo $row['d_nid']; ?>" 
                                           class="btn-action btn btn-sm btn-outline-danger" 
                                           onclick="return confirm('Are you sure you want to delete this appointment?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                 </td>
                             </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3 d-block"></i>
                                <h5 class="text-muted">No appointments found</h5>
                                <p class="text-muted small">Try adjusting your filters</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <div class="modal-header btn-gradient" style="color: white; border: none;">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i> Update Appointment Status
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="p_nid" id="modal_p_nid">
                    <input type="hidden" name="d_nid" id="modal_d_nid">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select New Status</label>
                        <select name="status" class="form-select" style="border-radius: 12px; padding: 12px;" required>
                            <option value="confirmed">✅ Confirmed</option>
                            <option value="pending">⏳ Pending</option>
                        </select>
                    </div>
                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle"></i> Changing status to "Confirmed" will set appointment = 'yes'
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_status" class="btn btn-gradient">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Appointment Modal -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-content-custom">
            <div class="modal-header btn-gradient" style="color: white; border: none;">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-check me-2"></i> Appointment Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="appointmentDetails">
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                    <p class="mt-2">Loading appointment details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    AOS.init({ duration: 800, once: true, offset: 50 });
    
    $(document).ready(function() {
        $('#appointmentsTable').DataTable({
            pageLength: 10,
            responsive: true,
            language: {
                search: "<i class='fas fa-search'></i> Search:",
                searchPlaceholder: "Search appointments..."
            },
            order: [[0, 'asc']]
        });
    });
    
    function setAppointmentDetails(p_nid, d_nid, currentStatus) {
        document.getElementById('modal_p_nid').value = p_nid;
        document.getElementById('modal_d_nid').value = d_nid;
        const statusSelect = document.querySelector('#statusModal select[name="status"]');
        if (statusSelect) {
            statusSelect.value = currentStatus;
        }
    }
    
    function viewAppointment(p_nid, d_nid) {
        $('#viewModal').modal('show');
        
        $.ajax({
            url: 'ajax_get_appointment_details.php',
            method: 'POST',
            data: { p_nid: p_nid, d_nid: d_nid },
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    document.getElementById('appointmentDetails').innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <div class="detail-label">Patient Name</div>
                                    <div>${data.patient_name}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Patient NID</div>
                                    <div>${data.p_nid}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Patient Mobile</div>
                                    <div>${data.patient_mobile}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Patient Blood Group</div>
                                    <div>${data.patient_blood}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <div class="detail-label">Doctor Name</div>
                                    <div>Dr. ${data.doctor_name}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Doctor NID</div>
                                    <div>${data.d_nid}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Doctor Mobile</div>
                                    <div>${data.doctor_mobile}</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="detail-row">
                                    <div class="detail-label">Appointment Date & Time</div>
                                    <div><i class="fas fa-calendar"></i> ${data.appointment_date} | <i class="fas fa-clock"></i> ${data.appointment_time}</div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Status</div>
                                    <div><span class="status-badge ${data.status == 'yes' ? 'status-confirmed' : 'status-pending'}">
                                        ${data.status == 'yes' ? 'Confirmed' : 'Pending'}
                                    </span></div>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    document.getElementById('appointmentDetails').innerHTML = `
                        <div class="text-center py-4 text-danger">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                            <p class="mt-2">${data.message || 'Failed to load appointment details'}</p>
                        </div>
                    `;
                }
            },
            error: function() {
                document.getElementById('appointmentDetails').innerHTML = `
                    <div class="text-center py-4 text-danger">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                        <p class="mt-2">Error loading details</p>
                    </div>
                `;
            }
        });
    }
    
    function filterByStatus(status) {
        document.getElementById('statusSelect').value = status;
        document.getElementById('filterForm').submit();
    }
    
    function filterByDate(date) {
        document.querySelector('input[name="date"]').value = date;
        document.getElementById('filterForm').submit();
    }
    
    document.getElementById('statusSelect')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
    
    document.querySelector('input[name="date"]')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
</script>

</body>
</html>