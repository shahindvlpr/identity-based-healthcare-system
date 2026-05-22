<?php include('dbconn.php'); ?>
<?php include('session.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Patient Dashboard - IBHS">
    <meta name="author" content="">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title>Patient Dashboard | IBHS</title>

    <link href="css/blog-home.css" rel="stylesheet">
    <link rel="stylesheet" href="css/net.css"/>
    <link rel="stylesheet" href="css/myprofile.css"/>

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
            padding-top: 85px;
        }

        /* ============ NAVIGATION ============ */
        .navbar {
            background: #ffffff !important;
            padding: 12px 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
            height: 72px;
        }

        .logo_img img { height: 44px; }

        .navbar-nav .nav-link {
            color: #555 !important;
            font-size: 14px;
            font-weight: 500;
            margin: 0 3px;
            padding: 9px 16px !important;
            border-radius: 8px;
            transition: var(--transition);
            white-space: nowrap;
        }

        .navbar-nav .nav-link i { margin-right: 5px; font-size: 13px; }
        .navbar-nav .nav-link:hover { background: #f0fdf4; color: var(--primary) !important; }
        .navbar-nav .nav-link.active { background: var(--primary); color: #fff !important; }

        .nav-user-badge {
            background: var(--primary-light);
            border-radius: 10px;
            padding: 6px 14px !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-user-badge .divname {
            font-size: 12px !important;
            font-weight: 600 !important;
            color: var(--text-dark) !important;
            line-height: 1.2;
        }

        .nav-user-badge .divid {
            font-size: 9px !important;
            font-weight: 500 !important;
            color: var(--text-light) !important;
        }

        .btn-logout-nav {
            background: #fff !important;
            color: var(--danger) !important;
            border: 2px solid var(--danger) !important;
            padding: 8px 18px !important;
            border-radius: 25px !important;
            font-weight: 600 !important;
            font-size: 13px !important;
        }

        .btn-logout-nav:hover { background: var(--danger) !important; color: #fff !important; }

        /* ============ HERO HEADER ============ */
        .hero-header {
            background: linear-gradient(135deg, #009B46 0%, #007a38 100%);
            padding: 35px 0;
            margin-bottom: 28px;
            border-radius: 0 0 30px 30px;
            position: relative;
            overflow: hidden;
        }

        .hero-header::before {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            top: -50px;
            right: -50px;
        }

        .hero-header::after {
            content: '';
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
            bottom: -40px;
            left: -30px;
        }

        .welcome-content {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .welcome-left {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .welcome-avatar {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            border: 4px solid rgba(255,255,255,0.4);
            object-fit: cover;
            flex-shrink: 0;
        }

        .welcome-text h3 {
            color: #fff;
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0;
        }

        .welcome-text span {
            color: rgba(255,255,255,0.85);
            font-size: 0.82rem;
        }

        /* ============ STATS ROW ============ */
        .stats-row { margin-bottom: 28px; }

        .stat-card {
            background: #fff;
            border-radius: var(--radius);
            padding: 22px 20px;
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: var(--transition);
            height: 100%;
            cursor: pointer;
        }

        .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }

        .stat-icon-circle {
            width: 55px;
            height: 55px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #fff;
            flex-shrink: 0;
        }

        .stat-icon-circle.green { background: linear-gradient(135deg, #27ae60, #2ecc71); }
        .stat-icon-circle.blue { background: linear-gradient(135deg, #3498db, #2980b9); }
        .stat-icon-circle.orange { background: linear-gradient(135deg, #f39c12, #e67e22); }
        .stat-icon-circle.purple { background: linear-gradient(135deg, #9b59b6, #8e44ad); }

        .stat-info h4 { font-size: 1.6rem; font-weight: 700; margin: 0; line-height: 1.1; color: var(--text-dark); }
        .stat-info small { color: var(--text-light); font-size: 0.76rem; font-weight: 500; }

        /* ============ SECTION TITLES ============ */
        .section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .section-title h5 { font-weight: 700; font-size: 1.1rem; margin: 0; display: flex; align-items: center; gap: 10px; }
        .section-title h5 i { color: var(--primary); }

        .btn-view-all {
            padding: 8px 18px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.8rem;
            transition: var(--transition);
            text-decoration: none;
            background: var(--primary-light);
            color: var(--primary);
        }

        .btn-view-all:hover { background: var(--primary); color: #fff; text-decoration: none; }

        /* ============ QUICK ACTIONS ============ */
        .quick-actions-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 28px;
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
            cursor: pointer;
        }

        .quick-action-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); text-decoration: none; }

        .quick-action-icon {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 22px;
            color: #fff;
        }

        .quick-action-icon.doctor { background: linear-gradient(135deg, #3498db, #2980b9); }
        .quick-action-icon.hospital { background: linear-gradient(135deg, #e74c3c, #c0392b); }
        .quick-action-icon.prescription { background: linear-gradient(135deg, #27ae60, #2ecc71); }
        .quick-action-icon.message { background: linear-gradient(135deg, #9b59b6, #8e44ad); }

        .quick-action-card h6 { font-weight: 600; font-size: 0.85rem; color: var(--text-dark); margin: 0; }
        .quick-action-card small { color: var(--text-light); font-size: 0.72rem; }

        /* ============ LIST CARDS ============ */
        .list-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            overflow: hidden;
            margin-bottom: 25px;
        }

        .list-card-header {
            padding: 18px 22px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fafafa;
        }

        .list-card-header h5 { margin: 0; font-weight: 600; font-size: 0.95rem; }
        .list-card-header h5 i { color: var(--primary); margin-right: 8px; }

        .list-card-body { padding: 10px; max-height: 350px; overflow-y: auto; }
        .list-card-body::-webkit-scrollbar { width: 4px; }
        .list-card-body::-webkit-scrollbar-track { background: transparent; }
        .list-card-body::-webkit-scrollbar-thumb { background: #ddd; border-radius: 10px; }

        .list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            border-radius: 10px;
            transition: var(--transition);
            margin-bottom: 3px;
        }

        .list-item:hover { background: #f8fdf9; }
        .list-item-left { display: flex; align-items: center; gap: 14px; }

        .list-item-num {
            width: 36px;
            height: 36px;
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

        .list-item-name { font-weight: 500; font-size: 0.9rem; color: var(--text-dark); }
        .list-item-sub { font-size: 0.75rem; color: #999; }

        .badge-status-sm {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 0.7rem;
        }

        .badge-confirmed { background: #e8f5e9; color: #27ae60; }

        /* ============ PRESCRIPTION LIST ============ */
        .prescription-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px;
            border-radius: 10px;
            transition: var(--transition);
            margin-bottom: 3px;
        }

        .prescription-item:hover { background: #f8fdf9; }
        .prescription-left { display: flex; align-items: center; gap: 14px; }

        .prescription-num {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #e3f2fd;
            color: #1565c0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .btn-view-sm {
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.72rem;
            text-decoration: none;
            transition: var(--transition);
            background: var(--primary-light);
            color: var(--primary);
        }

        .btn-view-sm:hover { background: var(--primary); color: #fff; text-decoration: none; }

        /* ============ EMPTY STATE ============ */
        .empty-state { text-align: center; padding: 30px; color: #bbb; }
        .empty-state i { font-size: 2.5rem; display: block; margin-bottom: 10px; }
        .empty-state small { font-size: 0.82rem; }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 992px) { .quick-actions-row { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px) {
            body { padding-top: 75px; }
            .hero-header { padding: 22px 0; border-radius: 0 0 20px 20px; }
            .welcome-text h3 { font-size: 1.1rem; }
            .welcome-avatar { width: 48px; height: 48px; }
            .quick-actions-row { grid-template-columns: 1fr 1fr; gap: 10px; }
            .stat-card { margin-bottom: 10px; }
        }
        @media (max-width: 480px) { .quick-actions-row { grid-template-columns: 1fr; } }
    </style>
</head>

<body>

    <?php
    // Get user info - FIXED: null-safe
    $my_query = mysqli_query($conn,"SELECT * from person inner join gender on person.gender = gender.gender_id where person.nid ='$user_id'");
    $my = ($my_query) ? mysqli_fetch_array($my_query) : null;
    
    // Count appointments - FIXED: null-safe
    $appt_count_query = mysqli_query($conn,"SELECT COUNT(*) as total FROM appointment WHERE p_nid = '$user_id' AND appointment = 'yes'");
    $appt_data = ($appt_count_query) ? mysqli_fetch_assoc($appt_count_query) : null;
    $appointment_count = ($appt_data && isset($appt_data['total'])) ? $appt_data['total'] : 0;
    
    // Count prescriptions - FIXED: null-safe
    $presc_count_query = mysqli_query($conn,"SELECT COUNT(*) as total FROM prescription WHERE p_nid = '$user_id'");
    $presc_data = ($presc_count_query) ? mysqli_fetch_assoc($presc_count_query) : null;
    $prescription_count = ($presc_data && isset($presc_data['total'])) ? $presc_data['total'] : 0;
    
    // Count messages - FIXED: null-safe
    $msg_count_query = mysqli_query($conn,"SELECT COUNT(*) as total FROM message WHERE p_nid = '$user_id' OR d_nid = '$user_id'");
    $msg_data = ($msg_count_query) ? mysqli_fetch_assoc($msg_count_query) : null;
    $message_count = ($msg_data && isset($msg_data['total'])) ? $msg_data['total'] : 0;
    
    // Count doctors - FIXED: null-safe
    $doc_count_query = mysqli_query($conn,"SELECT COUNT(*) as total FROM doctor");
    $doc_data = ($doc_count_query) ? mysqli_fetch_assoc($doc_count_query) : null;
    $doctor_count = ($doc_data && isset($doc_data['total'])) ? $doc_data['total'] : 0;
    ?>

    <!-- ============ NAVIGATION ============ -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <div class="logo_img">
                <a href="index.html"><img src="img/bg_logo1.png" alt="IBHS Logo"></a>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto align-items-center">
                    <?php if($user_type=='Doctor'): ?>
                    <li class="nav-item"><a class="nav-link" href="patient.php"><i class="fas fa-users"></i> Patients</a></li>
                    <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="doctor.php"><i class="fas fa-user-md"></i> Doctors</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="hospital.php"><i class="fas fa-hospital"></i> Hospitals</a></li>
                    <?php if($user_type == "Patient"): ?>
                    <li class="nav-item"><a class="nav-link" href="myprescription.php"><i class="fas fa-prescription"></i> Prescriptions</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="message.php?id=0"><i class="fas fa-envelope"></i> Messages</a></li>
                    <li class="nav-item ml-2">
                        <a class="nav-link nav-user-badge" href="myprofile.php?id=<?php echo $user_id; ?>">
                            <i class="fas fa-user-circle" style="font-size:15px;color:var(--primary);"></i>
                            <div><div class="divname"><?php echo $my['name'] ?? 'User'; ?></div><div class="divid"><?php echo $my['nid'] ?? ''; ?></div></div>
                        </a>
                    </li>
                    <li class="nav-item ml-2">
                        <a class="nav-link btn-logout-nav" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ============ HERO HEADER ============ -->
    <section class="hero-header">
        <div class="container">
            <div class="welcome-content">
                <div class="welcome-left">
                    <img src="img/<?php echo $my['image'] ?? 'patient1.png'; ?>" alt="Profile" class="welcome-avatar" onerror="this.src='img/patient1.png'">
                    <div class="welcome-text">
                        <h3>Welcome back, <?php echo $my['name'] ?? 'Patient'; ?>! 👋</h3>
                        <span><i class="fas fa-id-card mr-1"></i> NID: <?php echo $my['nid'] ?? 'N/A'; ?> | <i class="fas fa-tint mr-1"></i> Blood: <?php echo $my['blood'] ?? 'N/A'; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="container">
        
        <!-- Stats Row -->
        <div class="row stats-row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card" onclick="window.location.href='doctor.php'">
                    <div class="stat-icon-circle blue"><i class="fas fa-user-md"></i></div>
                    <div class="stat-info"><h4><?php echo $doctor_count; ?></h4><small>Available Doctors</small></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon-circle green"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-info"><h4><?php echo $appointment_count; ?></h4><small>My Appointments</small></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon-circle orange"><i class="fas fa-prescription"></i></div>
                    <div class="stat-info"><h4><?php echo $prescription_count; ?></h4><small>Prescriptions</small></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card" onclick="window.location.href='message.php?id=0'">
                    <div class="stat-icon-circle purple"><i class="fas fa-envelope"></i></div>
                    <div class="stat-info"><h4><?php echo $message_count; ?></h4><small>Messages</small></div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="section-title"><h5><i class="fas fa-bolt"></i> Quick Actions</h5></div>
        <div class="quick-actions-row">
            <a href="doctor.php" class="quick-action-card">
                <div class="quick-action-icon doctor"><i class="fas fa-user-md"></i></div>
                <h6>Find Doctors</h6><small>Browse specialists</small>
            </a>
            <a href="hospital.php" class="quick-action-card">
                <div class="quick-action-icon hospital"><i class="fas fa-hospital"></i></div>
                <h6>View Hospitals</h6><small>Nearby hospitals</small>
            </a>
            <a href="myprescription.php" class="quick-action-card">
                <div class="quick-action-icon prescription"><i class="fas fa-prescription"></i></div>
                <h6>My Prescriptions</h6><small>View & manage</small>
            </a>
            <a href="message.php?id=0" class="quick-action-card">
                <div class="quick-action-icon message"><i class="fas fa-comments"></i></div>
                <h6>Messages</h6><small>Chat with doctors</small>
            </a>
        </div>

        <!-- Appointments & Prescriptions Row -->
        <div class="row">
            
            <!-- Recent Appointments -->
            <div class="col-lg-6 mb-4">
                <div class="list-card">
                    <div class="list-card-header">
                        <h5><i class="fas fa-calendar-check"></i> My Appointments</h5>
                        <a href="#" class="btn-view-all">View All <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                    <div class="list-card-body">
                        <?php 
                            // FIXED: Using person table through doctor for doctor name
                            $appt_query = mysqli_query($conn,"SELECT a.*, p.name as doctor_name, p.nid as doctor_nid 
                                FROM appointment a 
                                INNER JOIN doctor d ON a.d_nid = d.d_nid 
                                INNER JOIN person p ON d.d_nid = p.nid 
                                WHERE a.p_nid = '$user_id' AND a.appointment = 'yes' 
                                ORDER BY a.date DESC LIMIT 5");
                            if($appt_query && mysqli_num_rows($appt_query) > 0):
                                $c = 1;
                                while($appt = mysqli_fetch_array($appt_query)):
                        ?>
                        <div class="list-item">
                            <div class="list-item-left">
                                <div class="list-item-num"><?php echo $c; ?></div>
                                <div>
                                    <div class="list-item-name"><?php echo $appt['doctor_name'] ?? 'Unknown'; ?></div>
                                    <div class="list-item-sub">
                                        <i class="far fa-calendar mr-1"></i> 
                                        <?php echo isset($appt['date']) ? $appt['date'] : 'N/A'; ?>
                                    </div>
                                </div>
                            </div>
                            <span class="badge-status-sm badge-confirmed">Confirmed</span>
                        </div>
                        <?php $c++; endwhile; ?>
                        <?php else: ?>
                        <div class="empty-state">
                            <i class="far fa-calendar-times"></i>
                            <small>No appointments yet</small>
                            <br>
                            <a href="doctor.php" class="btn-view-all mt-2">Find a Doctor</a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Prescriptions -->
            <div class="col-lg-6 mb-4">
                <div class="list-card">
                    <div class="list-card-header">
                        <h5><i class="fas fa-prescription"></i> Recent Prescriptions</h5>
                        <a href="myprescription.php" class="btn-view-all">View All <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                    <div class="list-card-body">
                        <?php 
                            // FIXED: Removed 'date' from ORDER BY - using prescription_id DESC instead
                            $presc_query = mysqli_query($conn,"SELECT * FROM prescription WHERE p_nid = '$user_id' AND childbirth_id = 'N/A' ORDER BY prescription_id DESC LIMIT 5");
                            if($presc_query && mysqli_num_rows($presc_query) > 0):
                                $c = 1;
                                while($presc = mysqli_fetch_array($presc_query)):
                        ?>
                        <div class="prescription-item">
                            <div class="prescription-left">
                                <div class="prescription-num"><?php echo $c; ?></div>
                                <div>
                                    <div class="list-item-name">Prescription #<?php echo $presc['prescription_id']; ?></div>
                                    <div class="list-item-sub"><i class="far fa-id-badge mr-1"></i> ID: <?php echo $presc['prescription_id']; ?></div>
                                </div>
                            </div>
                            <a href="prescriptionview.php?id=<?php echo $presc['prescription_id']; ?>" class="btn-view-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </div>
                        <?php $c++; endwhile; ?>
                        <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-prescription-bottle"></i>
                            <small>No prescriptions yet</small>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ============ SCRIPTS ============ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
</body>
</html>