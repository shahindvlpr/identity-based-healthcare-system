<?php include('dbconn.php'); ?>
<?php include('session.php'); ?>
<?php

if($user_type !="Hospital"){
    header("location:index.html");
  }
$c1 = 1;

$query = mysqli_query($conn,"SELECT * from hospital where docreg = '0' and hospital_id = '$user_id'");
$check=mysqli_fetch_array($query);
$hoscheck = mysqli_query($conn,"SELECT * from docregistry where hospital_id = '$user_id'");

// Get hospital name
$hos_query = mysqli_query($conn,"SELECT * from hospital where hospital_id = '$user_id'");
$hos_info = mysqli_fetch_array($hos_query);

// Count active doctors
$active_doc_query = mysqli_query($conn,"SELECT COUNT(*) as total FROM doctorworking where hospital_id = '$user_id'");
$active_docs = mysqli_fetch_assoc($active_doc_query)['total'];

// Count pending requests
$pending_count = ($hoscheck) ? $hoscheck->num_rows : 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Hospital Doctor Management - IBHS">
    <meta name="author" content="">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title>Doctor Management | IBHS</title>

    <!-- Custom styles -->
    <link href="css/blog-home.css" rel="stylesheet">
    <link rel="stylesheet" href="css/myprofile.css"/>

    <style>
        :root {
            --primary: #009B46;
            --primary-dark: #007a38;
            --primary-light: #e8f5e9;
            --warning: #f39c12;
            --warning-light: #fff8e1;
            --danger: #e74c3c;
            --danger-light: #ffebee;
            --info: #3498db;
            --dark: #1a1a2e;
            --bg-light: #f5f6fa;
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.06);
            --shadow-md: 0 5px 20px rgba(0,0,0,0.08);
            --shadow-lg: 0 15px 40px rgba(0,0,0,0.12);
            --radius: 14px;
            --radius-sm: 10px;
            --transition: all 0.3s ease;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f2f5;
            color: var(--text-dark);
            min-height: 100vh;
            padding-top: 80px;
        }

        /* ============ NAVIGATION ============ */
        .navbar {
            background: #ffffff !important;
            padding: 12px 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            height: 70px;
        }

        .logo_img img {
            height: 42px;
        }

        .navbar-nav .nav-link {
            color: #555 !important;
            font-size: 14px;
            font-weight: 500;
            margin: 0 3px;
            padding: 10px 18px !important;
            border-radius: 8px;
            transition: var(--transition);
        }

        .navbar-nav .nav-link i {
            margin-right: 6px;
            font-size: 13px;
        }

        .navbar-nav .nav-link:hover {
            background: #f0fdf4;
            color: var(--primary) !important;
        }

        .navbar-nav .nav-link.active {
            background: var(--primary);
            color: #fff !important;
        }

        .btn-logout {
            background: #fff !important;
            color: var(--danger) !important;
            border: 2px solid var(--danger) !important;
            padding: 8px 20px !important;
            border-radius: 25px !important;
            font-weight: 600 !important;
            transition: var(--transition);
        }

        .btn-logout:hover {
            background: var(--danger) !important;
            color: #fff !important;
        }

        /* ============ PAGE HEADER ============ */
        .page-header {
            background: linear-gradient(135deg, #009B46 0%, #007a38 100%);
            padding: 30px 0;
            margin-bottom: 30px;
            border-radius: 0 0 30px 30px;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
            top: -50px;
            right: -50px;
        }

        .page-header::after {
            content: '';
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
            bottom: -40px;
            left: -30px;
        }

        .hospital-info {
            display: flex;
            align-items: center;
            gap: 18px;
            position: relative;
            z-index: 1;
        }

        .hospital-icon-box {
            width: 65px;
            height: 65px;
            background: rgba(255,255,255,0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: #fff;
            flex-shrink: 0;
        }

        .hospital-info h4 {
            color: #fff;
            font-weight: 700;
            font-size: 1.4rem;
            margin: 0;
        }

        .hospital-info span {
            color: rgba(255,255,255,0.85);
            font-size: 0.85rem;
        }

        /* ============ STAT CARDS ============ */
        .stats-row {
            margin-bottom: 25px;
        }

        .stat-card {
            background: #fff;
            border-radius: var(--radius);
            padding: 22px 20px;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: var(--transition);
            border: 1px solid #f0f0f0;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon-box {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #fff;
            flex-shrink: 0;
        }

        .stat-icon-box.green { background: linear-gradient(135deg, #27ae60, #2ecc71); }
        .stat-icon-box.orange { background: linear-gradient(135deg, #f39c12, #e67e22); }
        .stat-icon-box.blue { background: linear-gradient(135deg, #2980b9, #3498db); }

        .stat-content h3 {
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }

        .stat-content small {
            color: var(--text-light);
            font-size: 0.78rem;
            font-weight: 500;
        }

        /* ============ MAIN LAYOUT ============ */
        .main-content {
            padding-bottom: 40px;
        }

        /* ============ REGISTRATION CARD ============ */
        .reg-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            border: 1px solid #f0f0f0;
            position: sticky;
            top: 90px;
        }

        .reg-card-header {
            padding: 20px 22px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .reg-card-header .icon-circle {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #fff;
        }

        .icon-circle.open { background: linear-gradient(135deg, #27ae60, #2ecc71); }
        .icon-circle.closed { background: linear-gradient(135deg, #e74c3c, #c0392b); }

        .reg-card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-dark);
        }

        .reg-card-body {
            padding: 22px;
        }

        .status-pill {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            margin-bottom: 18px;
        }

        .status-pill.open {
            background: #e8f5e9;
            color: #27ae60;
        }

        .status-pill.closed {
            background: #ffebee;
            color: #e74c3c;
        }

        .reg-card-body p {
            color: #888;
            font-size: 0.82rem;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .input-group-custom {
            margin-bottom: 18px;
        }

        .input-group-custom .input-group-prepend .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-right: none;
            border-radius: 10px 0 0 10px;
            padding: 10px 15px;
            font-weight: 500;
            font-size: 0.82rem;
            color: #555;
        }

        .input-group-custom .form-control {
            border: 2px solid #e0e0e0;
            border-left: none;
            border-radius: 0 10px 10px 0;
            padding: 10px 15px;
            font-size: 0.85rem;
            transition: var(--transition);
        }

        .input-group-custom .form-control:focus {
            border-color: var(--primary);
            box-shadow: none;
        }

        .btn-submit {
            width: 100%;
            padding: 11px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }

        .btn-submit.btn-start {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: #fff;
        }

        .btn-submit.btn-stop {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: #fff;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        /* ============ PENDING TABLE CARD ============ */
        .pending-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            border: 1px solid #f0f0f0;
            margin-bottom: 25px;
        }

        .pending-card-header {
            padding: 18px 22px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fffdf5;
        }

        .pending-card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        .pending-card-header h5 i {
            color: #f39c12;
            margin-right: 8px;
        }

        .pending-count {
            background: #f39c12;
            color: #fff;
            padding: 4px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .custom-table {
            width: 100%;
            margin: 0;
        }

        .custom-table thead th {
            background: #fafafa;
            border: none;
            border-bottom: 2px solid #eee;
            padding: 14px 20px;
            font-weight: 600;
            color: #555;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .custom-table tbody td {
            padding: 14px 20px;
            vertical-align: middle;
            border-bottom: 1px solid #f5f5f5;
        }

        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }

        .custom-table tbody tr:hover {
            background: #fafdf9;
        }

        .doc-link {
            color: var(--text-dark);
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
        }

        .doc-link:hover {
            color: var(--primary);
            text-decoration: none;
        }

        .doc-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .btn-table {
            padding: 7px 20px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.78rem;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
        }

        .btn-table:hover {
            transform: translateY(-1px);
        }

        .btn-confirm {
            background: #e8f5e9;
            color: #27ae60;
        }

        .btn-confirm:hover {
            background: #27ae60;
            color: #fff;
        }

        .btn-reject {
            background: #ffebee;
            color: #e74c3c;
        }

        .btn-reject:hover {
            background: #e74c3c;
            color: #fff;
        }

        /* ============ DOCTOR LIST CARD ============ */
        .doclist-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            border: 1px solid #f0f0f0;
        }

        .doclist-header {
            padding: 18px 22px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .doclist-header .list-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #009B46, #007a38);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
        }

        .doclist-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .doclist-header .count-badge {
            margin-left: auto;
            background: #f0fdf4;
            color: var(--primary);
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.78rem;
        }

        .doclist-body {
            padding: 8px;
            max-height: 480px;
            overflow-y: auto;
        }

        .doclist-body::-webkit-scrollbar {
            width: 4px;
        }

        .doclist-body::-webkit-scrollbar-track {
            background: transparent;
        }

        .doclist-body::-webkit-scrollbar-thumb {
            background: #ddd;
            border-radius: 10px;
        }

        .doc-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 13px 16px;
            border-radius: 10px;
            transition: var(--transition);
            text-decoration: none;
            margin-bottom: 3px;
        }

        .doc-item:hover {
            background: #f8fdf9;
            text-decoration: none;
        }

        .doc-item-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .doc-num {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: #f0fdf4;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .doc-name {
            font-weight: 500;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .doc-item:hover .doc-name {
            color: var(--primary);
        }

        .doc-arrow {
            color: #ccc;
            font-size: 0.8rem;
            transition: var(--transition);
        }

        .doc-item:hover .doc-arrow {
            color: var(--primary);
            transform: translateX(4px);
        }

        /* ============ EMPTY STATE ============ */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }

        .empty-state i {
            font-size: 3.5rem;
            color: #e0e0e0;
            margin-bottom: 15px;
        }

        .empty-state h6 {
            color: #999;
            font-weight: 600;
        }

        .empty-state small {
            color: #bbb;
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 992px) {
            .reg-card {
                position: static;
                margin-bottom: 25px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }
            
            .page-header {
                padding: 20px 0;
                border-radius: 0 0 20px 20px;
            }
            
            .hospital-info h4 {
                font-size: 1.1rem;
            }
            
            .hospital-icon-box {
                width: 48px;
                height: 48px;
                font-size: 22px;
                border-radius: 12px;
            }
            
            .stat-card {
                padding: 16px;
                gap: 12px;
            }
            
            .stat-icon-box {
                width: 40px;
                height: 40px;
                font-size: 18px;
                border-radius: 10px;
            }
            
            .stat-content h3 {
                font-size: 1.2rem;
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
            }
            
            .btn-table {
                width: 100%;
                margin: 3px 0;
            }
        }
    </style>
</head>

<body>

    <!-- ============ NAVIGATION ============ -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <div class="logo_img">
                <a href="hospitalhome.php"><img src="img/bg_logo1.png" alt="IBHS Logo"></a>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="hospitaldoctor.php">
                            <i class="fas fa-user-md"></i> Doctors
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="hospatient.php">
                            <i class="fas fa-users"></i> Patients
                        </a>
                    </li>
                    <li class="nav-item ml-3">
                        <a class="nav-link btn-logout" href="index.html">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ============ PAGE HEADER ============ -->
    <div class="page-header">
        <div class="container">
            <div class="hospital-info">
                <div class="hospital-icon-box">
                    <i class="fas fa-hospital"></i>
                </div>
                <div>
                    <h4><?php echo $hos_info['name'] ?? 'Hospital Name'; ?></h4>
                    <span><i class="fas fa-map-marker-alt mr-1"></i> Doctor Management Panel</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="container main-content">
        
        <!-- Stats Row -->
        <div class="row stats-row">
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="stat-icon-box green">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $active_docs; ?></h3>
                        <small>Active Doctors</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="stat-icon-box orange">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $pending_count; ?></h3>
                        <small>Pending Requests</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="stat-icon-box blue">
                        <i class="fas fa-toggle-<?php echo $check ? 'on' : 'off'; ?>"></i>
                    </div>
                    <div class="stat-content">
                        <h3 style="font-size:1.1rem;font-weight:700;"><?php echo $check ? 'Open' : 'Closed'; ?></h3>
                        <small>Registration Status</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="row">
            
            <!-- LEFT - Registration Control -->
            <div class="col-lg-4 mb-4">
                <div class="reg-card">
                    <div class="reg-card-header">
                        <div class="icon-circle <?php echo $check ? 'open' : 'closed'; ?>">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h5>Registration Control</h5>
                    </div>
                    <div class="reg-card-body">
                        <?php if($check): ?>
                            <span class="status-pill open">
                                <i class="fas fa-check-circle mr-1"></i> Open
                            </span>
                            <p>Doctors can currently apply to join your hospital. Enter a vacancy notice below.</p>
                        <?php else: ?>
                            <span class="status-pill closed">
                                <i class="fas fa-times-circle mr-1"></i> Closed
                            </span>
                            <p>Registration is currently closed. No new applications will be accepted.</p>
                        <?php endif; ?>

                        <form action="hosdocreg.php" method="POST">
                            <div class="input-group-custom">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-bullhorn mr-1"></i> Notice
                                    </span>
                                </div>
                                <input type="text" class="form-control" name="notice" 
                                       placeholder="Enter vacancy announcement...">
                            </div>
                            
                            <?php if($check): ?>
                                <button type="submit" name="submit" class="btn-submit btn-start">
                                    <i class="fas fa-play mr-2"></i> Start Registration
                                </button>
                            <?php else: ?>
                                <button type="submit" name="submit" class="btn-submit btn-stop">
                                    <i class="fas fa-stop mr-2"></i> Stop Registration
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

            <!-- RIGHT - Doctor Management -->
            <div class="col-lg-8">
                
                <!-- Pending Requests -->
                <?php if($hoscheck && $hoscheck->num_rows > 0): ?>
                <div class="pending-card">
                    <div class="pending-card-header">
                        <h5><i class="fas fa-user-clock"></i> Pending Registration Requests</h5>
                        <span class="pending-count"><?php echo $hoscheck->num_rows; ?> Pending</span>
                    </div>
                    <div class="table-responsive">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Doctor Name</th>
                                    <th style="width:110px;">Confirm</th>
                                    <th style="width:110px;">Reject</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($hosreg_row = mysqli_fetch_array($hoscheck)):
                                    $nid = $hosreg_row['d_nid'];
                                    $query = mysqli_query($conn,"SELECT * from person where nid = '$nid'");
                                    $name = mysqli_fetch_array($query);
                                    $initial = strtoupper(substr($name['name'], 0, 1));
                                ?>
                                <tr>
                                    <td data-label="Doctor Name">
                                        <a href="hosdoctorprofile.php?id=<?php echo $nid; ?>" class="doc-link">
                                            <div class="doc-avatar"><?php echo $initial; ?></div>
                                            <?php echo $name['name']; ?>
                                        </a>
                                    </td>
                                    <td data-label="Confirm">
                                        <a href="doctorinserthos.php?hosid=<?php echo $user_id ?>&id=<?php echo $nid; ?>">
                                            <button class="btn-table btn-confirm">
                                                <i class="fas fa-check"></i> Confirm
                                            </button>
                                        </a>
                                    </td>
                                    <td data-label="Reject">
                                        <a href="doctordelinehos.php?hosid=<?php echo $user_id ?>&id=<?php echo $nid; ?>" 
                                           onclick="return confirm('Reject Dr. <?php echo $name['name']; ?>?')">
                                            <button class="btn-table btn-reject">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Doctor List -->
                <div class="doclist-card">
                    <div class="doclist-header">
                        <div class="list-icon">
                            <i class="fas fa-list-ul"></i>
                        </div>
                        <h5>Medical Team</h5>
                        <span class="count-badge"><?php echo $active_docs; ?> Members</span>
                    </div>
                    <div class="doclist-body">
                        <?php 
                            $queryp = mysqli_query($conn,"SELECT * from doctorworking where hospital_id = '$user_id' order by joined_date ASC");
                            if(mysqli_num_rows($queryp) > 0):
                                while($doclist = mysqli_fetch_array($queryp)):
                                    $nid = $doclist['d_nid'];
                                    $doc = mysqli_query($conn,"SELECT name from person where nid = '$nid'");
                                    $name = mysqli_fetch_array($doc);
                        ?>
                            <a href="hosdoctorprofile.php?id=<?php echo $nid; ?>" class="doc-item">
                                <div class="doc-item-left">
                                    <div class="doc-num"><?php echo $c1; ?></div>
                                    <span class="doc-name"><?php echo $name['name']; ?></span>
                                </div>
                                <i class="fas fa-chevron-right doc-arrow"></i>
                            </a>
                        <?php $c1++; endwhile; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-user-md"></i>
                                <h6>No doctors yet</h6>
                                <small>Open registration to build your team</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ============ SCRIPTS ============ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js"></script>
</body>
</html>