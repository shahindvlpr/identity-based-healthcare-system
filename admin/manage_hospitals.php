<?php
include_once('../dbconn.php');
session_start();

$hospitals = $conn->query("SELECT * FROM hospital ORDER BY hospital_name ASC");

// Count total hospitals
$total_query = $conn->query("SELECT COUNT(*) as total FROM hospital");
$total_hospitals = $total_query->fetch_assoc()['total'];

// Count hospitals with open registration
$open_reg_query = $conn->query("SELECT COUNT(*) as total FROM hospital WHERE docreg = '0'");
$open_reg = $open_reg_query->fetch_assoc()['total'];

// Count total wards
$wards_query = $conn->query("SELECT SUM(numberof_ward) as total FROM hospital");
$total_wards = $wards_query->fetch_assoc()['total'] ?? 0;

// Count total cabins
$cabins_query = $conn->query("SELECT SUM(numberof_cabin) as total FROM hospital");
$total_cabins = $cabins_query->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Admin - Hospital Management | IBHS">
    <meta name="author" content="">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title>Hospital Management | Admin IBHS</title>

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
        .stat-mini-icon.orange { background: linear-gradient(135deg, #f39c12, #e67e22); }
        .stat-mini-icon.purple { background: linear-gradient(135deg, #9b59b6, #8e44ad); }

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

        .btn-add-hospital {
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

        .btn-add-hospital:hover {
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

        .hospital-name-cell {
            display: flex;
            align-items: center;
            gap: 10px;
            text-align: left;
        }

        .hospital-icon-sm {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
            flex-shrink: 0;
        }

        .ward-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #e8f5e9;
            color: var(--primary-dark);
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.78rem;
        }

        .cabin-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #e3f2fd;
            color: #1565c0;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.78rem;
        }

        .reg-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .reg-status.open {
            background: #e8f5e9;
            color: #27ae60;
        }

        .reg-status.closed {
            background: #ffebee;
            color: #e74c3c;
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
                    <a href="manage_patients.php">
                        <i class="fas fa-users"></i>
                        <span>Patients</span>
                    </a>
                </li>
                <li>
                    <a href="manage_hospitals.php" class="active">
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
                            <i class="fas fa-hospital"></i>
                        </div>
                        <div>
                            <h4>Hospital Management</h4>
                            <span>Manage all registered hospitals</span>
                        </div>
                    </div>
                    <div class="header-badge">
                        <div class="count"><?php echo $total_hospitals; ?></div>
                        <div class="label">Total Hospitals</div>
                    </div>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="stats-row">
                <div class="stat-mini-card">
                    <div class="stat-mini-icon green">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <div class="stat-mini-info">
                        <h3><?php echo $total_hospitals; ?></h3>
                        <small>Total Hospitals</small>
                    </div>
                </div>
                <div class="stat-mini-card">
                    <div class="stat-mini-icon blue">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="stat-mini-info">
                        <h3><?php echo $open_reg; ?></h3>
                        <small>Open Registration</small>
                    </div>
                </div>
                <div class="stat-mini-card">
                    <div class="stat-mini-icon orange">
                        <i class="fas fa-bed"></i>
                    </div>
                    <div class="stat-mini-info">
                        <h3><?php echo $total_wards; ?></h3>
                        <small>Total Wards</small>
                    </div>
                </div>
                <div class="stat-mini-card">
                    <div class="stat-mini-icon purple">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <div class="stat-mini-info">
                        <h3><?php echo $total_cabins; ?></h3>
                        <small>Total Cabins</small>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="dashboard.php" class="btn-back-dashboard">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <a href="add_hospital.php" class="btn-add-hospital">
                    <i class="fas fa-plus-circle"></i> Add New Hospital
                </a>
            </div>

            <!-- Hospitals Table -->
            <div class="table-card">
                <div class="table-card-header">
                    <h5><i class="fas fa-list-ul"></i> Hospital List</h5>
                    <span style="font-size:0.8rem;color:#999;"><?php echo $total_hospitals; ?> Records</span>
                </div>
                
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Hospital Name</th>
                                <th>Wards</th>
                                <th>Cabins</th>
                                <th>Registration</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($hospitals && $hospitals->num_rows > 0): ?>
                                <?php while ($row = $hospitals->fetch_assoc()): 
                                    $is_open = ($row['docreg'] == '0');
                                ?>
                                <tr>
                                    <td data-label="ID">
                                        <span style="font-weight:600;color:var(--primary);">
                                            #<?= htmlspecialchars($row['hospital_id']) ?>
                                        </span>
                                    </td>
                                    <td data-label="Hospital Name">
                                        <div class="hospital-name-cell">
                                            <div class="hospital-icon-sm">
                                                <i class="fas fa-hospital"></i>
                                            </div>
                                            <span style="font-weight:600;"><?= htmlspecialchars($row['hospital_name']) ?></span>
                                        </div>
                                    </td>
                                    <td data-label="Wards">
                                        <span class="ward-badge">
                                            <i class="fas fa-bed"></i> <?= htmlspecialchars($row['numberof_ward']) ?> Wards
                                        </span>
                                    </td>
                                    <td data-label="Cabins">
                                        <span class="cabin-badge">
                                            <i class="fas fa-door-open"></i> <?= htmlspecialchars($row['numberof_cabin']) ?> Cabins
                                        </span>
                                    </td>
                                    <td data-label="Registration">
                                        <span class="reg-status <?= $is_open ? 'open' : 'closed' ?>">
                                            <i class="fas <?= $is_open ? 'fa-check-circle' : 'fa-times-circle' ?>"></i>
                                            <?= $is_open ? 'Open' : 'Closed' ?>
                                        </span>
                                    </td>
                                    <td data-label="Actions">
                                        <div class="action-buttons">
                                            <a href="edit_hospital.php?hospital_id=<?= urlencode($row['hospital_id']) ?>" class="btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="delete_hospital.php?hospital_id=<?= urlencode($row['hospital_id']) ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete <?= htmlspecialchars($row['hospital_name']) ?>?')">
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
                                            <i class="fas fa-hospital"></i>
                                            <p>No hospitals found.</p>
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
</body>
</html>