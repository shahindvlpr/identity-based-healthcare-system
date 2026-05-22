<?php include('dbconn.php'); ?>
<?php include('session.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="My Patients - IBHS">
    <meta name="author" content="">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title>My Patients | IBHS</title>

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
            --danger: #e74c3c;
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

        /* ============ PAGE HEADER ============ */
        .page-header {
            background: linear-gradient(135deg, #1a1a2e, #2c3e50);
            padding: 30px 0;
            margin-bottom: 28px;
            border-radius: 0 0 30px 30px;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            width: 180px;
            height: 180px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
            top: -40px;
            right: -40px;
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

        /* ============ PATIENT CARDS ============ */
        .patient-grid {
            display: grid;
            gap: 16px;
        }

        .patient-card {
            background: #fff;
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            transition: var(--transition);
            display: flex;
            gap: 16px;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .patient-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: var(--primary);
            border-radius: 4px 0 0 4px;
            opacity: 0;
            transition: var(--transition);
        }

        .patient-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            border-color: #e0e0e0;
        }

        .patient-card:hover::before { opacity: 1; }

        .patient-avatar {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #f0f0f0;
            flex-shrink: 0;
            transition: var(--transition);
        }

        .patient-card:hover .patient-avatar {
            border-color: var(--primary);
            transform: scale(1.05);
        }

        .patient-info {
            flex: 1;
            min-width: 0;
        }

        .patient-name {
            font-weight: 700;
            font-size: 1rem;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .patient-name a {
            color: var(--text-dark);
            text-decoration: none;
            transition: var(--transition);
        }

        .patient-name a:hover { color: var(--primary); }

        .patient-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .patient-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 500;
        }

        .tag-blood {
            background: #ffebee;
            color: #c62828;
        }

        .tag-blood i { font-size: 0.6rem; }

        .tag-id {
            background: #e3f2fd;
            color: #1565c0;
        }

        .view-profile-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.78rem;
            text-decoration: none;
            transition: var(--transition);
            flex-shrink: 0;
            white-space: nowrap;
        }

        .view-profile-btn:hover {
            background: var(--primary);
            color: #fff;
            text-decoration: none;
            transform: translateX(3px);
        }

        /* ============ SIDEBAR ============ */
        .sidebar-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            overflow: hidden;
            position: sticky;
            top: 90px;
        }

        .sidebar-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fafafa;
            color: var(--text-dark);
        }

        .sidebar-header i { color: var(--primary); }

        .sidebar-body { padding: 18px; }

        .search-input-group {
            display: flex;
            border: 2px solid #e8e8e8;
            border-radius: 12px;
            overflow: hidden;
            transition: var(--transition);
        }

        .search-input-group:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 155, 70, 0.1);
        }

        .search-input-group input {
            flex: 1;
            border: none;
            padding: 12px 15px;
            font-size: 0.85rem;
            outline: none;
            font-family: 'Poppins', sans-serif;
        }

        .search-input-group button {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 12px 18px;
            cursor: pointer;
            transition: var(--transition);
        }

        .search-input-group button:hover { background: var(--primary-dark); }

        /* ============ EMPTY STATE ============ */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
        }

        .empty-state i {
            font-size: 4rem;
            color: #e0e0e0;
            margin-bottom: 15px;
        }

        .empty-state h5 { color: #999; font-weight: 600; }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 992px) {
            .sidebar-card { position: static; margin-top: 20px; }
        }

        @media (max-width: 768px) {
            body { padding-top: 75px; }
            .page-header { padding: 20px 0; border-radius: 0 0 20px 20px; }
            .header-title h4 { font-size: 1.1rem; }
            .header-icon-box { width: 42px; height: 42px; font-size: 20px; border-radius: 10px; }
            .patient-card { flex-wrap: wrap; }
            .view-profile-btn { width: 100%; justify-content: center; margin-top: 10px; }
        }
    </style>
</head>

<body>

    <?php
    // FIXED: Null-safe user query
    $my_query = mysqli_query($conn,"SELECT * from person inner join gender on person.gender = gender.gender_id where person.nid ='$user_id'");
    $my = ($my_query) ? mysqli_fetch_array($my_query) : null;
    
    // Count total patients for this doctor
    $count_query = mysqli_query($conn,"SELECT COUNT(DISTINCT patient.p_nid) as total FROM patient INNER JOIN prescription ON prescription.p_nid = patient.p_nid WHERE prescription.d_nid = '$user_id'");
    $count_data = ($count_query) ? mysqli_fetch_assoc($count_query) : null;
    $total_patients = ($count_data && isset($count_data['total'])) ? $count_data['total'] : 0;
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
                    <li class="nav-item"><a class="nav-link active" href="patient.php"><i class="fas fa-users"></i> Patients</a></li>
                    <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="doctor.php"><i class="fas fa-user-md"></i> Doctors</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="hospital.php"><i class="fas fa-hospital"></i> Hospitals</a></li>
                    <?php if($user_type == "Patient"): ?>
                    <li class="nav-item"><a class="nav-link" href="myprescription.php"><i class="fas fa-prescription"></i> Prescriptions</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="message.php?id=0"><i class="fas fa-envelope"></i> Messages</a></li>
                    <li class="nav-item ml-2">
                        <?php if($user_type=='Doctor'): ?>
                        <a class="nav-link nav-user-badge" href="doctorprofile.php?id=<?php echo $user_id; ?>">
                            <i class="fas fa-user-circle" style="font-size:15px;color:var(--primary);"></i>
                            <div><div class="divname"><?php echo $my['name'] ?? 'User'; ?></div><div class="divid"><?php echo $my['nid'] ?? ''; ?></div></div>
                        </a>
                        <?php else: ?>
                        <a class="nav-link nav-user-badge" href="myprofile.php?id=<?php echo $user_id; ?>">
                            <i class="fas fa-user-circle" style="font-size:15px;color:var(--primary);"></i>
                            <div><div class="divname"><?php echo $my['name'] ?? 'User'; ?></div><div class="divid"><?php echo $my['nid'] ?? ''; ?></div></div>
                        </a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item ml-2">
                        <a class="nav-link btn-logout-nav" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ============ PAGE HEADER ============ -->
    <section class="page-header">
        <div class="container">
            <div class="header-content">
                <div class="header-title">
                    <div class="header-icon-box">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h4>My Patients</h4>
                        <span>Patients you've prescribed to</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="container">
        <div class="row">
            
            <!-- Patient Cards Column -->
            <div class="col-lg-8">
                
                <?php
                // FIXED: Using mysqli directly with proper error handling
                $date_columns_to_try = ['date', 'prescription_date', 'created_at', 'timestamp'];
                $valid_date_column = null;

                foreach ($date_columns_to_try as $date_column) {
                    $safe_column = mysqli_real_escape_string($conn, $date_column);
                    $check_query = mysqli_query($conn, "SHOW COLUMNS FROM prescription LIKE '$safe_column'");
                    if ($check_query && mysqli_num_rows($check_query) > 0) {
                        $valid_date_column = $safe_column;
                        break;
                    }
                }

                // Build query
                if ($valid_date_column) {
                    $sql = "SELECT 
                               patient.p_nid as nid,
                               person.name,
                               person.image,
                               person.blood,
                               MAX(prescription.$valid_date_column) as last_prescription
                            FROM patient 
                            INNER JOIN person ON person.nid = patient.p_nid 
                            INNER JOIN prescription ON prescription.p_nid = patient.p_nid 
                            WHERE prescription.d_nid = '$user_id' 
                            GROUP BY patient.p_nid, person.name, person.image, person.blood
                            ORDER BY last_prescription DESC";
                } else {
                    $sql = "SELECT 
                               patient.p_nid as nid,
                               person.name,
                               person.image,
                               person.blood
                            FROM patient 
                            INNER JOIN person ON person.nid = patient.p_nid 
                            INNER JOIN prescription ON prescription.p_nid = patient.p_nid 
                            WHERE prescription.d_nid = '$user_id' 
                            GROUP BY patient.p_nid, person.name, person.image, person.blood";
                }

                $result = mysqli_query($conn, $sql);
                
                if($result && mysqli_num_rows($result) > 0):
                ?>
                
                <div class="patient-grid">
                    <?php while($patient_row = mysqli_fetch_assoc($result)): 
                        $patient_image = !empty($patient_row['image']) ? $patient_row['image'] : 'patient1.png';
                    ?>
                    <div class="patient-card animate__animated animate__fadeInUp">
                        <img src="img/<?php echo htmlspecialchars($patient_image); ?>" 
                             alt="Patient" class="patient-avatar"
                             onerror="this.src='img/patient1.png'">
                        
                        <div class="patient-info">
                            <div class="patient-name">
                                <a href="docseepatient.php?id=<?php echo htmlspecialchars($patient_row['nid'] ?? ''); ?>&a=mo">
                                    <?php echo htmlspecialchars($patient_row['name'] ?? 'Unknown'); ?>
                                </a>
                            </div>
                            <div class="patient-tags">
                                <span class="patient-tag tag-blood">
                                    <i class="fas fa-tint"></i> <?php echo htmlspecialchars($patient_row['blood'] ?? 'N/A'); ?>
                                </span>
                                <span class="patient-tag tag-id">
                                    <i class="fas fa-id-card"></i> <?php echo htmlspecialchars($patient_row['nid'] ?? ''); ?>
                                </span>
                            </div>
                        </div>
                        
                        <a href="docseepatient.php?id=<?php echo htmlspecialchars($patient_row['nid'] ?? ''); ?>&a=mo" class="view-profile-btn">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </div>
                    <?php endwhile; ?>
                </div>
                
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h5>No patients found</h5>
                    <p class="text-muted">Patients you've prescribed to will appear here.</p>
                </div>
                <?php endif; ?>
                
            </div>

            <!-- Sidebar Column -->
            <div class="col-lg-4">
                
                <!-- Search Card -->
                <div class="sidebar-card">
                    <div class="sidebar-header">
                        <i class="fas fa-search"></i> Search Patients
                    </div>
                    <div class="sidebar-body">
                        <form action="searchpatient.php" method="POST">
                            <div class="search-input-group">
                                <input type="text" name="search" placeholder="Search by name or NID...">
                                <button type="submit" name="go">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
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