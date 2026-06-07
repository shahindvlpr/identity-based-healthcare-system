<?php
include_once('../dbconn.php');
session_start();

$patients = $conn->query("
    SELECT person.nid, person.name, person.mobile_no AS phone, 
           person.gender, person.blood, person.image
    FROM patient
    INNER JOIN person ON patient.p_nid = person.nid
    ORDER BY person.name ASC
");

// Count total patients
$total_query = $conn->query("SELECT COUNT(*) as total FROM patient");
$total_patients = $total_query->fetch_assoc()['total'];

// Count male patients
$male_query = $conn->query("SELECT COUNT(*) as total FROM patient INNER JOIN person ON patient.p_nid = person.nid WHERE person.gender = 1");
$male_count = $male_query->fetch_assoc()['total'];

// Count female patients
$female_query = $conn->query("SELECT COUNT(*) as total FROM patient INNER JOIN person ON patient.p_nid = person.nid WHERE person.gender = 2");
$female_count = $female_query->fetch_assoc()['total'];

// Count unique blood groups
$blood_query = $conn->query("SELECT COUNT(DISTINCT blood) as total FROM person WHERE nid IN (SELECT p_nid FROM patient)");
$blood_groups = $blood_query->fetch_assoc()['total'];

function getGenderText($gender) {
    switch ($gender) {
        case 1: return "Male";
        case 2: return "Female";
        case 3: return "Other";
        default: return "Unknown";
    }
}

function getGenderIcon($gender) {
    switch ($gender) {
        case 1: return "fa-mars";
        case 2: return "fa-venus";
        default: return "fa-genderless";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Admin - Patient Management | IBHS">
    <meta name="author" content="">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title>Patient Management | Admin IBHS</title>

    <style>
        :root {
            --primary: #009B46;
            --primary-dark: #007a38;
            --primary-light: #e8f5e9;
            --info: #3498db;
            --info-light: #e3f2fd;
            --warning: #f39c12;
            --warning-light: #fff8e1;
            --danger: #e74c3c;
            --danger-light: #ffebee;
            --purple: #9b59b6;
            --purple-light: #f3e5f5;
            --dark: #1a1a2e;
            --bg-light: #f5f6fa;
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
            --shadow-md: 0 5px 20px rgba(0,0,0,0.08);
            --shadow-lg: 0 15px 40px rgba(0,0,0,0.10);
            --radius: 16px;
            --radius-sm: 12px;
            --transition: all 0.3s ease;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f2f5;
            color: var(--text-dark);
            min-height: 100vh;
        }

        /* ============ ADMIN LAYOUT ============ */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: 260px;
            background: linear-gradient(135deg, #1a1a2e, #2c3e50);
            color: #fff;
            padding: 25px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            flex-shrink: 0;
        }

        .admin-sidebar .sidebar-logo {
            padding: 0 25px 25px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
            text-align: center;
        }

        .admin-sidebar .sidebar-logo img {
            height: 40px;
            margin-bottom: 10px;
        }

        .admin-sidebar .sidebar-logo h5 {
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            margin: 0;
        }

        .admin-sidebar .sidebar-logo span {
            color: rgba(255,255,255,0.6);
            font-size: 0.75rem;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 25px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: var(--transition);
            font-size: 0.88rem;
            font-weight: 500;
            border-left: 3px solid transparent;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: rgba(255,255,255,0.08);
            color: #fff;
            border-left-color: var(--primary);
        }

        .sidebar-menu li a i {
            width: 20px;
            text-align: center;
            font-size: 0.9rem;
        }

        .sidebar-menu li a.active i {
            color: var(--primary);
        }

        .admin-main {
            flex: 1;
            margin-left: 260px;
            padding: 30px;
        }

        /* ============ PAGE HEADER ============ */
        .page-header {
            background: linear-gradient(135deg, #1a1a2e, #2c3e50);
            padding: 25px 30px;
            border-radius: 0 0 20px 20px;
            margin: -30px -30px 30px -30px;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
            top: -50px;
            right: -50px;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 1;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-icon-box {
            width: 55px;
            height: 55px;
            background: rgba(255,255,255,0.15);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #fff;
            flex-shrink: 0;
        }

        .header-title h4 {
            color: #fff;
            font-weight: 700;
            font-size: 1.4rem;
            margin: 0;
        }

        .header-title span {
            color: rgba(255,255,255,0.8);
            font-size: 0.82rem;
        }

        .header-badge {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 12px 22px;
            border-radius: 14px;
            color: #fff;
            text-align: center;
        }

        .header-badge .count {
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1;
        }

        .header-badge .label {
            font-size: 0.72rem;
            opacity: 0.85;
        }

        /* ============ STATS ROW ============ */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-mini-card {
            background: #fff;
            border-radius: var(--radius);
            padding: 18px 20px;
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: var(--transition);
        }

        .stat-mini-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .stat-mini-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #fff;
            flex-shrink: 0;
        }

        .stat-mini-icon.green { background: linear-gradient(135deg, #27ae60, #2ecc71); }
        .stat-mini-icon.blue { background: linear-gradient(135deg, #3498db, #2980b9); }
        .stat-mini-icon.pink { background: linear-gradient(135deg, #e91e63, #c2185b); }
        .stat-mini-icon.red { background: linear-gradient(135deg, #e74c3c, #c0392b); }

        .stat-mini-info h3 {
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.1;
            color: var(--text-dark);
        }

        .stat-mini-info small {
            color: var(--text-light);
            font-size: 0.72rem;
            font-weight: 500;
        }

        /* ============ TABLE CARD ============ */
        .table-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            overflow: hidden;
        }

        .table-card-header {
            padding: 18px 25px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fafafa;
            flex-wrap: wrap;
            gap: 15px;
        }

        .table-card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-card-header h5 i {
            color: var(--primary);
        }

        .btn-add-patient {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn-add-patient:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 155, 70, 0.3);
            color: #fff;
            text-decoration: none;
        }

        .btn-back-dashboard {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            background: #fff;
            color: var(--text-dark);
            border: 1px solid #e0e0e0;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.82rem;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn-back-dashboard:hover {
            background: #f0f0f0;
            text-decoration: none;
            color: var(--text-dark);
        }

        .custom-table { width: 100%; margin: 0; }
        
        .custom-table thead th {
            background: #f8f9fa;
            border: none;
            border-bottom: 2px solid #eee;
            padding: 14px 16px;
            font-weight: 600;
            color: #555;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: center;
        }

        .custom-table tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f5f5f5;
            font-size: 0.85rem;
            text-align: center;
        }

        .custom-table tbody tr {
            transition: var(--transition);
        }

        .custom-table tbody tr:hover {
            background: #f8fdf9;
        }

        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }

        .patient-name-cell {
            display: flex;
            align-items: center;
            gap: 10px;
            text-align: left;
        }

        .patient-avatar-sm {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #f0f0f0;
            flex-shrink: 0;
        }

        .gender-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.78rem;
        }

        .gender-badge.male {
            background: #e3f2fd;
            color: #1565c0;
        }

        .gender-badge.female {
            background: #fce4ec;
            color: #c62828;
        }

        .gender-badge.other {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .blood-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 12px;
            background: #ffebee;
            color: #c62828;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.78rem;
        }

        .btn-edit {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 14px;
            background: #fff3e0;
            color: #e65100;
            border: none;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn-edit:hover {
            background: #e65100;
            color: #fff;
            text-decoration: none;
        }

        .btn-delete {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 14px;
            background: #ffebee;
            color: #c62828;
            border: none;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn-delete:hover {
            background: #c62828;
            color: #fff;
            text-decoration: none;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        /* ============ ALERT ============ */
        .alert-custom {
            padding: 15px 20px;
            border-radius: 12px;
            border: none;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-custom.info {
            background: #e3f2fd;
            color: #1565c0;
            border-left: 4px solid #1565c0;
        }

        .alert-custom .close-alert {
            margin-left: auto;
            cursor: pointer;
            background: none;
            border: none;
            font-size: 1.2rem;
            color: inherit;
            opacity: 0.7;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #bbb;
        }

        .empty-state i {
            font-size: 3rem;
            display: block;
            margin-bottom: 10px;
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 992px) {
            .admin-sidebar {
                width: 70px;
            }
            .admin-sidebar .sidebar-logo h5,
            .admin-sidebar .sidebar-logo span,
            .sidebar-menu li a span {
                display: none;
            }
            .sidebar-menu li a {
                justify-content: center;
                padding: 15px;
            }
            .admin-main {
                margin-left: 70px;
            }
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .admin-layout {
                flex-direction: column;
            }
            .admin-main {
                margin-left: 0;
                padding: 15px;
            }
            .page-header {
                margin: -15px -15px 20px -15px;
                border-radius: 0 0 15px 15px;
            }
            .stats-row {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }
            .custom-table thead {
                display: none;
            }
            .custom-table tbody td {
                display: block;
                text-align: right;
                padding: 10px 15px;
            }
            .custom-table tbody td::before {
                content: attr(data-label);
                float: left;
                font-weight: 600;
                color: #555;
                font-size: 0.75rem;
            }
            .custom-table tbody tr {
                display: block;
                border-bottom: 2px solid #eee;
                padding: 5px 0;
            }
            .action-buttons {
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body>

    <div class="admin-layout">
        
        <!-- ============ SIDEBAR ============ -->
        <aside class="admin-sidebar">
            <div class="sidebar-logo">
                <img src="../img/bg_logo1.png" alt="IBHS Logo">
                <h5>IBHS Admin</h5>
                <span>Management Panel</span>
            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href="dashboard.php">
                        <i class="fas fa-th-large"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="manage_doctors.php">
                        <i class="fas fa-user-md"></i>
                        <span>Doctors</span>
                    </a>
                </li>
                <li>
                    <a href="manage_patients.php" class="active">
                        <i class="fas fa-users"></i>
                        <span>Patients</span>
                    </a>
                </li>
                <li>
                    <a href="manage_hospitals.php">
                        <i class="fas fa-hospital"></i>
                        <span>Hospitals</span>
                    </a>
                </li>
                <li>
                    <a href="manage_appointments.php">
                        <i class="fas fa-calendar-check"></i>
                        <span>Appointments</span>
                    </a>
                </li>
                <li style="margin-top: 30px;">
                    <a href="../index.html">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Back to Site</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- ============ MAIN CONTENT ============ -->
        <main class="admin-main">
            
            <!-- Page Header -->
            <div class="page-header">
                <div class="header-content">
                    <div class="header-title">
                        <div class="header-icon-box">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h4>Patient Management</h4>
                            <span>Manage all registered patients</span>
                        </div>
                    </div>
                    <div class="header-badge">
                        <div class="count"><?php echo $total_patients; ?></div>
                        <div class="label">Total Patients</div>
                    </div>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="stats-row">
                <div class="stat-mini-card">
                    <div class="stat-mini-icon green">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-mini-info">
                        <h3><?php echo $total_patients; ?></h3>
                        <small>Total Patients</small>
                    </div>
                </div>
                <div class="stat-mini-card">
                    <div class="stat-mini-icon blue">
                        <i class="fas fa-mars"></i>
                    </div>
                    <div class="stat-mini-info">
                        <h3><?php echo $male_count; ?></h3>
                        <small>Male Patients</small>
                    </div>
                </div>
                <div class="stat-mini-card">
                    <div class="stat-mini-icon pink">
                        <i class="fas fa-venus"></i>
                    </div>
                    <div class="stat-mini-info">
                        <h3><?php echo $female_count; ?></h3>
                        <small>Female Patients</small>
                    </div>
                </div>
                <div class="stat-mini-card">
                    <div class="stat-mini-icon red">
                        <i class="fas fa-tint"></i>
                    </div>
                    <div class="stat-mini-info">
                        <h3><?php echo $blood_groups; ?></h3>
                        <small>Blood Groups</small>
                    </div>
                </div>
            </div>

            <!-- Alert Message -->
            <?php if (isset($_GET['msg'])): ?>
            <div class="alert-custom info">
                <i class="fas fa-info-circle"></i>
                <?= htmlspecialchars($_GET['msg']) ?>
                <button class="close-alert" onclick="this.parentElement.remove()">&times;</button>
            </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="dashboard.php" class="btn-back-dashboard">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <a href="add_patient.php" class="btn-add-patient">
                    <i class="fas fa-plus-circle"></i> Add New Patient
                </a>
            </div>

            <!-- Patients Table -->
            <div class="table-card">
                <div class="table-card-header">
                    <h5><i class="fas fa-list-ul"></i> Patient List</h5>
                    <span style="font-size:0.8rem;color:#999;"><?php echo $total_patients; ?> Records</span>
                </div>
                
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>NID</th>
                                <th>Patient Name</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Blood Group</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($patients && $patients->num_rows > 0): ?>
                                <?php while ($row = $patients->fetch_assoc()): 
                                    $gender_text = getGenderText($row['gender']);
                                    $gender_icon = getGenderIcon($row['gender']);
                                    $gender_class = ($row['gender'] == 1) ? 'male' : (($row['gender'] == 2) ? 'female' : 'other');
                                    $patient_image = !empty($row['image']) ? $row['image'] : 'patient1.png';
                                ?>
                                <tr>
                                    <td data-label="NID">
                                        <span style="font-weight:500;"><?= htmlspecialchars($row['nid']) ?></span>
                                    </td>
                                    <td data-label="Patient Name">
                                        <div class="patient-name-cell">
                                            <img src="../img/<?= $patient_image ?>" alt="Patient" class="patient-avatar-sm" onerror="this.src='../img/patient1.png'">
                                            <span style="font-weight:600;"><?= htmlspecialchars($row['name']) ?></span>
                                        </div>
                                    </td>
                                    <td data-label="Phone">
                                        <i class="fas fa-phone-alt" style="color:var(--primary);font-size:0.7rem;"></i>
                                        <?= htmlspecialchars($row['phone']) ?>
                                    </td>
                                    <td data-label="Gender">
                                        <span class="gender-badge <?= $gender_class ?>">
                                            <i class="fas <?= $gender_icon ?>"></i> <?= $gender_text ?>
                                        </span>
                                    </td>
                                    <td data-label="Blood Group">
                                        <span class="blood-badge">
                                            <i class="fas fa-tint"></i> <?= htmlspecialchars($row['blood']) ?>
                                        </span>
                                    </td>
                                    <td data-label="Actions">
                                        <div class="action-buttons">
                                            <a href="edit_patient.php?nid=<?= urlencode($row['nid']) ?>" class="btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="delete_patient.php?nid=<?= urlencode($row['nid']) ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete <?= htmlspecialchars($row['name']) ?>?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <i class="fas fa-users"></i>
                                            <p>No patients found.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <!-- ============ SCRIPTS ============ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js"></script>
    
    <script>
        // Auto-dismiss alert after 5 seconds
        setTimeout(function() {
            const alert = document.querySelector('.alert-custom');
            if(alert) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    </script>
</body>
</html>