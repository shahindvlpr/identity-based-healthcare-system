<?php
include_once('../dbconn.php'); 
session_start();

if (!isset($_SESSION['admin_username'])) {
    header("Location: index.php");
    exit();
}

// Improved getCount function with error handling
function getCount($table) {
    global $conn;
    try {
        // Validate table name to prevent SQL injection
        $allowed_tables = ['doctor', 'patient', 'hospital', 'appointment', 'prescription', 'message'];
        if (!in_array($table, $allowed_tables)) {
            return 0;
        }
        
        $res = $conn->query("SELECT COUNT(*) AS count FROM $table");
        if($res && $res->num_rows > 0) {
            return $res->fetch_assoc()['count'];
        }
        return 0;
    } catch (Exception $e) {
        error_log("Error counting $table: " . $e->getMessage());
        return 0;
    }
}

// Get counts with error suppression
$total_doctors = getCount("doctor");
$total_patients = getCount("patient");
$total_hospitals = getCount("hospital");
$total_appointments = getCount("appointment");
$total_prescriptions = getCount("prescription");
$total_messages = getCount("message");

// Open registrations (hospitals with docreg = '0')
$open_registrations = 0;
$open_reg_query = $conn->query("SELECT COUNT(*) AS count FROM hospital WHERE docreg = '0'");
if ($open_reg_query && $open_reg_query->num_rows > 0) {
    $open_registrations = $open_reg_query->fetch_assoc()['count'];
}

// Debug: Uncomment to check if values are being fetched
// echo "<!-- Debug: Doctors: $total_doctors, Patients: $total_patients, Hospitals: $total_hospitals -->";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Dashboard | IBHS</title>

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* Your existing styles... */
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

        .header-title .welcome-name {
            color: #00d2ff;
            font-weight: 700;
        }

        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            background: var(--danger);
            color: #fff;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: var(--transition);
        }

        .btn-logout:hover {
            background: #c0392b;
            color: #fff;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }

        /* ============ FIXED STATS GRID ============ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #fff;
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none;
            color: var(--text-dark);
            min-width: 0; /* Prevents overflow */
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            text-decoration: none;
            color: var(--text-dark);
        }

        .stat-icon {
            width: 55px;
            height: 55px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #fff;
            flex-shrink: 0;
        }

        .stat-icon.doctor { background: linear-gradient(135deg, #3498db, #2980b9); }
        .stat-icon.patient { background: linear-gradient(135deg, #27ae60, #2ecc71); }
        .stat-icon.hospital { background: linear-gradient(135deg, #e74c3c, #c0392b); }
        .stat-icon.appointment { background: linear-gradient(135deg, #f39c12, #e67e22); }
        .stat-icon.prescription { background: linear-gradient(135deg, #9b59b6, #8e44ad); }
        .stat-icon.registration { background: linear-gradient(135deg, #1abc9c, #16a085); }
        .stat-icon.message { background: linear-gradient(135deg, #e67e22, #d35400); }
        .stat-icon.uptime { background: linear-gradient(135deg, #2c3e50, #34495e); }

        .stat-info {
            flex: 1;
            min-width: 0;
        }

        .stat-info h3 {
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
            color: var(--text-dark);
        }

        .stat-info small {
            color: var(--text-light);
            font-size: 0.7rem;
            font-weight: 500;
            display: block;
        }

        .stat-info .stat-link {
            font-size: 0.65rem;
            color: var(--primary);
            font-weight: 600;
            margin-top: 5px;
            display: inline-block;
        }

        /* ============ QUICK ACTIONS ============ */
        .section-title {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: var(--primary);
        }

        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .quick-action-card {
            background: #fff;
            border-radius: var(--radius);
            padding: 20px;
            text-align: center;
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            transition: var(--transition);
            text-decoration: none;
            color: var(--text-dark);
        }

        .quick-action-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            text-decoration: none;
            color: var(--primary);
        }

        .quick-action-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 20px;
            color: #fff;
        }

        .quick-action-icon.manage-doctor { background: linear-gradient(135deg, #3498db, #2980b9); }
        .quick-action-icon.manage-patient { background: linear-gradient(135deg, #27ae60, #2ecc71); }
        .quick-action-icon.manage-hospital { background: linear-gradient(135deg, #e74c3c, #c0392b); }
        .quick-action-icon.add-doctor { background: linear-gradient(135deg, #f39c12, #e67e22); }

        .quick-action-card h6 {
            font-weight: 600;
            font-size: 0.85rem;
            margin: 0;
        }

        .quick-action-card small {
            color: var(--text-light);
            font-size: 0.7rem;
        }

        /* ============ FOOTER ============ */
        .admin-footer {
            text-align: center;
            padding: 20px;
            color: var(--text-light);
            font-size: 0.8rem;
            border-top: 1px solid #e0e0e0;
            margin-top: 30px;
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
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .quick-actions-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="admin-layout">
        
        <!-- ============ SIDEBAR ============ -->
        <aside class="admin-sidebar">
            <div class="sidebar-logo">
                <img src="../img/bg_logo1.png" alt="IBHS Logo" onerror="this.src='https://via.placeholder.com/40'">
                <h5>IBHS Admin</h5>
                <span>Management Panel</span>
            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href="dashboard.php" class="active">
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
                    <a href="manage_patients.php">
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
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <div>
                            <h4>Welcome, <span class="welcome-name"><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span></h4>
                            <span style="color:rgba(255,255,255,0.8);font-size:0.82rem;">Admin Dashboard Overview</span>
                        </div>
                    </div>
                    <a href="logout.php" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <a href="manage_doctors.php" class="stat-card">
                    <div class="stat-icon doctor">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo isset($total_doctors) ? $total_doctors : '0'; ?></h3>
                        <small>Total Doctors</small>
                        <div class="stat-link">Manage →</div>
                    </div>
                </a>

                <a href="manage_patients.php" class="stat-card">
                    <div class="stat-icon patient">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo isset($total_patients) ? $total_patients : '0'; ?></h3>
                        <small>Total Patients</small>
                        <div class="stat-link">Manage →</div>
                    </div>
                </a>

                <a href="manage_hospitals.php" class="stat-card">
                    <div class="stat-icon hospital">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo isset($total_hospitals) ? $total_hospitals : '0'; ?></h3>
                        <small>Total Hospitals</small>
                        <div class="stat-link">Manage →</div>
                    </div>
                </a>

                <a href="manage_appointments.php">
                    <div class="stat-card">
                    <div class="stat-icon appointment">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo isset($total_appointments) ? $total_appointments : '0'; ?></h3>
                        <small>Appointments</small>
                    </div>
                </div>
                </a>

                <div class="stat-card">
                    <div class="stat-icon prescription">
                        <i class="fas fa-prescription"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo isset($total_prescriptions) ? $total_prescriptions : '0'; ?></h3>
                        <small>Prescriptions</small>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon registration">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo isset($open_registrations) ? $open_registrations : '0'; ?></h3>
                        <small>Open Registrations</small>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon message">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo isset($total_messages) ? $total_messages : '0'; ?></h3>
                        <small>Messages</small>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon uptime">
                        <i class="fas fa-server"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Active</h3>
                        <small>System Status</small>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="section-title">
                <i class="fas fa-bolt"></i> Quick Actions
            </div>
            <div class="quick-actions-grid">
                <a href="manage_doctors.php" class="quick-action-card">
                    <div class="quick-action-icon manage-doctor">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h6>Manage Doctors</h6>
                    <small>View, edit, delete</small>
                </a>
                <a href="manage_patients.php" class="quick-action-card">
                    <div class="quick-action-icon manage-patient">
                        <i class="fas fa-users"></i>
                    </div>
                    <h6>Manage Patients</h6>
                    <small>View, edit, delete</small>
                </a>
                <a href="manage_hospitals.php" class="quick-action-card">
                    <div class="quick-action-icon manage-hospital">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <h6>Manage Hospitals</h6>
                    <small>View, edit, delete</small>
                </a>
                <a href="add_doctor.php" class="quick-action-card">
                    <div class="quick-action-icon add-doctor">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <h6>Add New Doctor</h6>
                    <small>Register a doctor</small>
                </a>
            </div>

            <!-- Footer -->
            <div class="admin-footer">
                <p class="mb-0">&copy; 2025 <strong>Identity Based Healthcare System</strong>. All rights reserved.</p>
            </div>

        </main>
    </div>

    <!-- ============ SCRIPTS ============ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js"></script>
</body>
</html>