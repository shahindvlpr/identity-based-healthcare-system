<?php include('dbconn.php'); ?>
<?php include('session.php'); ?>
<?php
  if($user_type !="Hospital"){
    header("location:index.html");
  }
  
  // Get hospital info
  $hos_query = mysqli_query($conn,"SELECT * from hospital where hospital_id = '$user_id'");
  $hos_info = mysqli_fetch_array($hos_query);
  
  // Count total patients
  $count_query = mysqli_query($conn,"SELECT COUNT(*) as total FROM person inner join patient on person.nid = patient.p_nid");
  $total_patients = mysqli_fetch_assoc($count_query)['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Hospital Patient Management - IBHS">
    <meta name="author" content="">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <title>Patient Management | IBHS</title>

    <link href="css/blog-home.css" rel="stylesheet">
    <link rel="stylesheet" href="css/net.css"/>

    <style>
        :root {
            --primary: #009B46;
            --primary-dark: #007a38;
            --primary-light: #e8f5e9;
            --info: #3498db;
            --info-light: #e3f2fd;
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

        .logo_img img {
            height: 44px;
        }

        .navbar-nav .nav-link {
            color: #555 !important;
            font-size: 14px;
            font-weight: 500;
            margin: 0 3px;
            padding: 9px 18px !important;
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
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            top: -40px;
            right: -40px;
        }

        .page-header::after {
            content: '';
            position: absolute;
            width: 120px;
            height: 120px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
            bottom: -30px;
            left: -20px;
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

        .hospital-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .hospital-icon-box {
            width: 55px;
            height: 55px;
            background: rgba(255,255,255,0.2);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #fff;
            flex-shrink: 0;
        }

        .hospital-info h4 {
            color: #fff;
            font-weight: 700;
            font-size: 1.3rem;
            margin: 0;
        }

        .hospital-info span {
            color: rgba(255,255,255,0.8);
            font-size: 0.82rem;
        }

        .patient-count-badge {
            background: rgba(255,255,255,0.2);
            color: #fff;
            padding: 12px 22px;
            border-radius: 14px;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .patient-count-badge .count {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
        }

        .patient-count-badge .label {
            font-size: 0.75rem;
            opacity: 0.9;
        }

        /* ============ MAIN LAYOUT ============ */
        .main-content {
            padding-bottom: 40px;
        }

        /* ============ PATIENT CARDS GRID ============ */
        .patient-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 18px;
        }

        .patient-card {
            background: #fff;
            border-radius: var(--radius);
            padding: 22px;
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            transition: var(--transition);
            display: flex;
            gap: 16px;
            align-items: flex-start;
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

        .patient-card:hover::before {
            opacity: 1;
        }

        .patient-avatar {
            width: 75px;
            height: 75px;
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
            font-size: 1.05rem;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .patient-name a {
            color: var(--text-dark);
            text-decoration: none;
            transition: var(--transition);
        }

        .patient-name a:hover {
            color: var(--primary);
        }

        .patient-details {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 10px;
        }

        .detail-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .blood-tag {
            background: #ffebee;
            color: #c62828;
        }

        .blood-tag i {
            font-size: 0.65rem;
        }

        .id-tag {
            background: #e3f2fd;
            color: #1565c0;
        }

        .view-profile-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.78rem;
            text-decoration: none;
            transition: var(--transition);
        }

        .view-profile-btn:hover {
            background: var(--primary);
            color: #fff;
            text-decoration: none;
            transform: translateX(3px);
        }

        /* ============ SIDEBAR ============ */
        .sidebar-search-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            overflow: hidden;
            position: sticky;
            top: 90px;
        }

        .sidebar-header {
            padding: 18px 22px;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-dark);
        }

        .sidebar-header .icon-circle {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 15px;
        }

        .sidebar-body {
            padding: 20px;
        }

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
            padding: 12px 20px;
            cursor: pointer;
            transition: var(--transition);
            font-size: 14px;
        }

        .search-input-group button:hover {
            background: var(--primary-dark);
        }

        /* Quick Stats */
        .quick-stats {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #f0f0f0;
        }

        .quick-stat-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            font-size: 0.82rem;
            color: #666;
        }

        .quick-stat-item i {
            width: 20px;
            color: var(--primary);
            text-align: center;
        }

        /* ============ EMPTY STATE ============ */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
        }

        .empty-state i {
            font-size: 5rem;
            color: #e0e0e0;
            margin-bottom: 15px;
        }

        .empty-state h5 {
            font-weight: 600;
            color: #999;
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 992px) {
            .sidebar-search-card {
                position: static;
                margin-bottom: 20px;
            }
            
            .patient-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            body {
                padding-top: 75px;
            }
            
            .page-header {
                padding: 20px 0;
                border-radius: 0 0 20px 20px;
            }
            
            .hospital-info h4 {
                font-size: 1.1rem;
            }
            
            .hospital-icon-box {
                width: 44px;
                height: 44px;
                font-size: 20px;
                border-radius: 10px;
            }
            
            .patient-count-badge .count {
                font-size: 1.5rem;
            }
            
            .patient-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .patient-details {
                justify-content: center;
            }
            
            .patient-avatar {
                width: 60px;
                height: 60px;
            }
        }

        @media (max-width: 576px) {
            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .patient-count-badge {
                width: 100%;
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
                        <a class="nav-link" href="hospitaldoctor.php">
                            <i class="fas fa-user-md"></i> Doctors
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="hospatient.php">
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
            <div class="header-content">
                <div class="hospital-info">
                    <div class="hospital-icon-box">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <div>
                        <h4><?php echo $hos_info['name'] ?? 'Hospital Name'; ?></h4>
                        <span><i class="fas fa-map-marker-alt mr-1"></i> Patient Management</span>
                    </div>
                </div>
                <div class="patient-count-badge">
                    <div class="count"><?php echo $total_patients; ?></div>
                    <div class="label">Total Registered Patients</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="container main-content">
        <div class="row">
            
            <!-- Patient Cards Column -->
            <div class="col-lg-8">
                
                <?php
                    $query = mysqli_query($conn,"SELECT * FROM person inner join patient on person.nid = patient.p_nid");
                    if(mysqli_num_rows($query) > 0):
                ?>
                
                <div class="patient-grid">
                    <?php while($patient_row = mysqli_fetch_array($query)): ?>
                    <div class="patient-card animate__animated animate__fadeInUp">
                        <img src="img/<?php echo $patient_row['image'] ? $patient_row['image'] : 'patient1.png'; ?>" 
                             alt="Patient" class="patient-avatar"
                             onerror="this.src='img/patient1.png'">
                        
                        <div class="patient-info">
                            <div class="patient-name">
                                <a href="hospatientprofile.php?id=<?php echo $patient_row['nid']; ?>">
                                    <?php echo $patient_row['name']; ?>
                                </a>
                            </div>
                            
                            <div class="patient-details">
                                <span class="detail-tag blood-tag">
                                    <i class="fas fa-tint"></i> <?php echo $patient_row['blood']; ?>
                                </span>
                                <span class="detail-tag id-tag">
                                    <i class="fas fa-id-card"></i> <?php echo $patient_row['nid']; ?>
                                </span>
                            </div>
                            
                            <a href="hospatientprofile.php?id=<?php echo $patient_row['nid']; ?>" class="view-profile-btn">
                                <i class="fas fa-eye"></i> View Profile
                            </a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h5>No patients registered yet</h5>
                    <p class="text-muted">Patients will appear here once they register</p>
                </div>
                <?php endif; ?>
                
            </div>

            <!-- Sidebar Column -->
            <div class="col-lg-4">
                <div class="sidebar-search-card">
                    <div class="sidebar-header">
                        <div class="icon-circle">
                            <i class="fas fa-search"></i>
                        </div>
                        Search Patients
                    </div>
                    <div class="sidebar-body">
                        <form action="hossearchpatient.php" method="POST">
                            <div class="search-input-group">
                                <input type="text" name="search" placeholder="Search by name or NID...">
                                <button type="submit" name="go">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                        
                        <!-- Quick Stats -->
                        <div class="quick-stats">
                            <div class="quick-stat-item">
                                <i class="fas fa-users"></i>
                                <span>Total Patients: <strong><?php echo $total_patients; ?></strong></span>
                            </div>
                            <div class="quick-stat-item">
                                <i class="fas fa-hospital"></i>
                                <span>Hospital: <strong><?php echo $hos_info['name'] ?? 'N/A'; ?></strong></span>
                            </div>
                            <div class="quick-stat-item">
                                <i class="fas fa-clock"></i>
                                <span>Last Updated: <strong><?php echo date('d M Y'); ?></strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- ============ SCRIPTS ============ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js"></script>
    
    <script>
        // Auto-hide animation for empty search
        $(document).ready(function() {
            $('.patient-card').hover(
                function() { $(this).addClass('animate__pulse'); },
                function() { $(this).removeClass('animate__pulse'); }
            );
        });
    </script>
</body>
</html>