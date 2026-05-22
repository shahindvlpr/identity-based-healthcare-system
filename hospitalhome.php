<?php include('dbconn.php'); ?>
<?php include('session.php'); ?>
<?php
    if($user_type !="Hospital"){
      header("location:index.html");
    }
    $query = mysqli_query($conn,"SELECT * FROM hospital where hospital_id = '$user_id'");
    $hospital_row = mysqli_fetch_array($query);
    
    // Get statistics
    $doc_count = mysqli_query($conn,"SELECT COUNT(*) as total FROM doctorworking where hospital_id = '$user_id'");
    $total_docs = mysqli_fetch_assoc($doc_count)['total'];
    
    $pending_count = mysqli_query($conn,"SELECT COUNT(*) as total FROM docregistry where hospital_id = '$user_id'");
    $total_pending = mysqli_fetch_assoc($pending_count)['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Hospital Dashboard - IBHS">
    <meta name="author" content="">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title>Hospital Dashboard | IBHS</title>

    <link href="css/blog-home.css" rel="stylesheet">
    <link rel="stylesheet" href="css/net.css"/>

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
        }

        .btn-logout:hover {
            background: var(--danger) !important;
            color: #fff !important;
        }

        /* ============ PAGE HEADER ============ */
        .page-header {
            background: linear-gradient(135deg, #009B46 0%, #007a38 100%);
            padding: 35px 0;
            margin-bottom: 28px;
            border-radius: 0 0 30px 30px;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            top: -50px;
            right: -50px;
        }

        .page-header::after {
            content: '';
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
            bottom: -40px;
            left: -30px;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 1;
            flex-wrap: wrap;
            gap: 20px;
        }

        .hospital-brand {
            display: flex;
            align-items: center;
            gap: 18px;
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

        .hospital-brand h3 {
            color: #fff;
            font-weight: 700;
            font-size: 1.6rem;
            margin: 0;
        }

        .hospital-brand span {
            color: rgba(255,255,255,0.85);
            font-size: 0.85rem;
        }

        .header-stats {
            display: flex;
            gap: 15px;
        }

        .header-stat-item {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 15px 20px;
            border-radius: 14px;
            text-align: center;
            color: #fff;
            min-width: 90px;
        }

        .header-stat-item .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1;
        }

        .header-stat-item .stat-label {
            font-size: 0.7rem;
            opacity: 0.85;
            margin-top: 3px;
        }

        /* ============ MAIN CONTENT ============ */
        .main-content {
            padding-bottom: 40px;
        }

        /* ============ INFO CARDS GRID ============ */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .info-card {
            background: #fff;
            border-radius: var(--radius);
            padding: 22px 25px;
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
            border-color: #e0e0e0;
        }

        .info-icon-circle {
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

        .icon-green { background: linear-gradient(135deg, #27ae60, #2ecc71); }
        .icon-blue { background: linear-gradient(135deg, #3498db, #2980b9); }
        .icon-purple { background: linear-gradient(135deg, #9b59b6, #8e44ad); }
        .icon-orange { background: linear-gradient(135deg, #f39c12, #e67e22); }
        .icon-teal { background: linear-gradient(135deg, #1abc9c, #16a085); }
        .icon-red { background: linear-gradient(135deg, #e74c3c, #c0392b); }

        .info-content {
            flex: 1;
            min-width: 0;
        }

        .info-label {
            font-size: 0.78rem;
            color: var(--text-light);
            font-weight: 500;
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            word-break: break-all;
        }

        /* ============ QUICK ACTIONS ============ */
        .quick-actions-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            overflow: hidden;
            margin-top: 25px;
        }

        .quick-actions-header {
            padding: 16px 25px;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fafafa;
        }

        .quick-actions-header i {
            color: var(--primary);
        }

        .quick-actions-body {
            padding: 15px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .quick-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 22px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: var(--transition);
            background: #f8f9fa;
            color: var(--text-dark);
            border: 1px solid #eee;
        }

        .quick-action-btn:hover {
            background: var(--primary);
            color: #fff;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 155, 70, 0.2);
            border-color: var(--primary);
        }

        .quick-action-btn i {
            font-size: 1rem;
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 992px) {
            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .header-stats {
                width: 100%;
            }
            
            .header-stat-item {
                flex: 1;
            }
        }

        @media (max-width: 768px) {
            body {
                padding-top: 75px;
            }
            
            .page-header {
                padding: 22px 0;
                border-radius: 0 0 20px 20px;
            }
            
            .hospital-icon-box {
                width: 48px;
                height: 48px;
                font-size: 22px;
                border-radius: 12px;
            }
            
            .hospital-brand h3 {
                font-size: 1.2rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .info-card {
                padding: 18px 20px;
                gap: 14px;
            }
            
            .info-icon-circle {
                width: 44px;
                height: 44px;
                font-size: 18px;
                border-radius: 10px;
            }
            
            .info-value {
                font-size: 0.95rem;
            }
            
            .header-stat-item .stat-value {
                font-size: 1.3rem;
            }
            
            .quick-actions-body {
                flex-direction: column;
            }
            
            .quick-action-btn {
                justify-content: center;
            }
        }
    </style>
</head>

<body>

    <!-- ============ NAVIGATION ============ -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <div class="logo_img">
                <a href="hospitaldoctor.php"><img src="img/bg_logo1.png" alt="IBHS Logo"></a>
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
            <div class="header-content">
                <div class="hospital-brand">
                    <div class="hospital-icon-box">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <div>
                        <h3><?php echo $hospital_row['hospital_name']; ?></h3>
                        <span><i class="fas fa-id-card mr-1"></i> ID: <?php echo $user_id; ?></span>
                    </div>
                </div>
                <div class="header-stats">
                    <div class="header-stat-item">
                        <div class="stat-value"><?php echo $total_docs; ?></div>
                        <div class="stat-label">Doctors</div>
                    </div>
                    <div class="header-stat-item">
                        <div class="stat-value"><?php echo $total_pending; ?></div>
                        <div class="stat-label">Pending</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="container main-content">
        
        <!-- Hospital Information Grid -->
        <div class="info-grid">
            
            <!-- Hospital Name -->
            <div class="info-card">
                <div class="info-icon-circle icon-green">
                    <i class="fas fa-hospital"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Hospital Name</div>
                    <div class="info-value"><?php echo $hospital_row['hospital_name']; ?></div>
                </div>
            </div>

            <!-- Hospital ID -->
            <div class="info-card">
                <div class="info-icon-circle icon-blue">
                    <i class="fas fa-fingerprint"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Hospital ID</div>
                    <div class="info-value"><?php echo $user_id; ?></div>
                </div>
            </div>

            <!-- Number of Wards -->
            <div class="info-card">
                <div class="info-icon-circle icon-purple">
                    <i class="fas fa-bed"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Number of Wards</div>
                    <div class="info-value"><?php echo $hospital_row['numberof_ward']; ?></div>
                </div>
            </div>

            <!-- Ward Fee Per Day -->
            <div class="info-card">
                <div class="info-icon-circle icon-orange">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Ward Fee (Per Day)</div>
                    <div class="info-value">৳ <?php echo $hospital_row['wardfee_perday']; ?></div>
                </div>
            </div>

            <!-- Number of Cabins -->
            <div class="info-card">
                <div class="info-icon-circle icon-teal">
                    <i class="fas fa-door-open"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Number of Cabins</div>
                    <div class="info-value"><?php echo $hospital_row['numberof_cabin']; ?></div>
                </div>
            </div>

            <!-- Cabin Fee Per Day -->
            <div class="info-card">
                <div class="info-icon-circle icon-red">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Cabin Fee (Per Day)</div>
                    <div class="info-value">৳ <?php echo $hospital_row['cabinfee_perday']; ?></div>
                </div>
            </div>

        </div>

        <!-- Quick Actions -->
        <div class="quick-actions-card">
            <div class="quick-actions-header">
                <i class="fas fa-bolt"></i> Quick Actions
            </div>
            <div class="quick-actions-body">
                <a href="hospitaldoctor.php" class="quick-action-btn">
                    <i class="fas fa-user-md"></i> Manage Doctors
                </a>
                <a href="hospatient.php" class="quick-action-btn">
                    <i class="fas fa-users"></i> View Patients
                </a>
                <a href="#" class="quick-action-btn">
                    <i class="fas fa-cog"></i> Edit Profile
                </a>
                <a href="#" class="quick-action-btn">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
            </div>
        </div>

    </div>

    <!-- ============ SCRIPTS ============ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js"></script>
</body>
</html>