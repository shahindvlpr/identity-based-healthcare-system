<?php include('dbconn.php'); ?>
<?php include('session.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Doctor Dashboard - Identity Based Healthcare">
    <meta name="author" content="">
    <title>Doctor Dashboard | IBHS</title>

    <!-- CSS Dependencies -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Custom CSS -->
    <link href="css/blog-home.css" rel="stylesheet">
    <link rel="stylesheet" href="css/net.css"/>
    <link rel="stylesheet" href="css/myprofile.css"/>

    <style>
        :root {
            --primary: #009B46;
            --primary-dark: #007a38;
            --primary-light: #e8f5e9;
            --secondary: #00d2ff;
            --accent: #667eea;
            --warning: #f39c12;
            --danger: #e74c3c;
            --dark: #1a1a2e;
            --bg-light: #f0f4f8;
            --text-dark: #2c3e50;
            --text-light: #666;
            --shadow-sm: 0 2px 10px rgba(0,0,0,0.08);
            --shadow-md: 0 5px 25px rgba(0,0,0,0.12);
            --shadow-lg: 0 15px 45px rgba(0,0,0,0.15);
            --radius: 16px;
            --radius-sm: 10px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-light);
            color: var(--text-dark);
            min-height: 100vh;
        }

        /* ============ NAVIGATION ============ */
        .navbar {
            background: #009B46 !important;
            padding: 10px 0;
            box-shadow: 0 5px 30px rgba(0,0,0,0.3);
        }

        .logo_img img {
            height: 50px;
            transition: var(--transition);
        }

        .logo_img img:hover {
            transform: scale(1.05);
        }

        .navbar-nav .nav-link {
            color: #e0e0e0 !important;
            font-size: 14px;
            font-weight: 500;
            margin: 0 3px;
            padding: 10px 15px !important;
            border-radius: 8px;
            transition: var(--transition);
            position: relative;
        }

        .navbar-nav .nav-link i {
            margin-right: 6px;
        }

        .navbar-nav .nav-link:hover {
            background: rgba(255,255,255,0.12);
            color: #fff !important;
            transform: translateY(-2px);
        }

        .navbar-nav .nav-link.active {
            background: rgba(0, 155, 70, 0.3);
            color: #fff !important;
        }

        /* User Profile in Nav */
        .user-badge {
            background: rgba(255,255,255,0.1);
            border-radius: 50px;
            padding: 5px 15px;
            transition: var(--transition);
        }

        .user-badge:hover {
            background: rgba(255,255,255,0.2);
        }

        .divm .divname {
            color: #fff !important;
            font-weight: 600 !important;
            font-size: 13px !important;
            line-height: 1.2;
        }

        .divm .divid {
            color: #a0d8b0 !important;
            font-weight: 500 !important;
            font-size: 10px !important;
        }

        .btn-logout-nav {
            background: var(--danger);
            color: #fff !important;
            padding: 8px 20px !important;
            border-radius: 25px !important;
            font-weight: 600 !important;
            font-size: 13px !important;
            transition: var(--transition);
        }

        .btn-logout-nav:hover {
            background: #c0392b !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
        }

        /* ============ HERO HEADER ============ */
        .hero-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 0 80px;
            margin-top: 70px;
            position: relative;
            overflow: hidden;
        }

        .hero-header::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            top: -150px;
            right: -100px;
            animation: float 8s ease-in-out infinite;
        }

        .hero-header::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
            bottom: -100px;
            left: -50px;
            animation: float 6s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }

        .welcome-text h1 {
            color: #fff;
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 5px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .welcome-text p {
            color: rgba(255,255,255,0.85);
            font-size: 1rem;
        }

        .welcome-text .doctor-name {
            color: #ffd700;
            font-weight: 700;
        }

        /* ============ STATS CARDS ============ */
        .stats-container {
            margin-top: 60px;
            position: relative;
            z-index: 10;
        }

        .stat-card {
            background: #fff;
            border-radius: var(--radius);
            padding: 25px 20px;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            text-align: center;
            position: relative;
            overflow: hidden;
            height: 100%;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
        }

        .stat-card.card-patients::before { background: linear-gradient(90deg, #3498db, #2980b9); }
        .stat-card.card-appointments::before { background: linear-gradient(90deg, #e67e22, #d35400); }
        .stat-card.card-prescriptions::before { background: linear-gradient(90deg, #27ae60, #229954); }
        .stat-card.card-messages::before { background: linear-gradient(90deg, #9b59b6, #8e44ad); }

        .stat-icon-wrapper {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 28px;
            color: #fff;
        }

        .card-patients .stat-icon-wrapper { background: linear-gradient(135deg, #3498db, #2980b9); }
        .card-appointments .stat-icon-wrapper { background: linear-gradient(135deg, #e67e22, #d35400); }
        .card-prescriptions .stat-icon-wrapper { background: linear-gradient(135deg, #27ae60, #229954); }
        .card-messages .stat-icon-wrapper { background: linear-gradient(135deg, #9b59b6, #8e44ad); }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .stat-label {
            color: var(--text-light);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .stat-trend {
            font-size: 0.75rem;
            margin-top: 8px;
            font-weight: 600;
        }

        .stat-trend.up { color: #27ae60; }
        .stat-trend.down { color: #e74c3c; }

        /* ============ MAIN CONTENT GRID ============ */
        .main-content-wrapper {
            padding: 30px 0 60px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 25px;
        }

        /* ============ FORUM SECTION ============ */
        .forum-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .forum-header {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            color: #fff;
            padding: 20px 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .forum-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .forum-header h5 i {
            margin-right: 10px;
            color: var(--secondary);
        }

        /* Post Creation Area */
        .post-create-area {
            padding: 25px;
            border-bottom: 1px solid #f0f0f0;
        }

        .post-input-group {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .post-avatar-small {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary);
        }

        .post-textarea-wrapper {
            flex: 1;
        }

        .post-textarea {
            width: 100%;
            border: 2px solid #e8e8e8;
            border-radius: var(--radius-sm);
            padding: 15px;
            font-size: 14px;
            resize: none;
            transition: var(--transition);
            background: #fafafa;
            font-family: 'Poppins', sans-serif;
        }

        .post-textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 155, 70, 0.1);
            background: #fff;
            outline: none;
        }

        .post-bottom-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .category-select-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .category-badge {
            background: var(--primary-light);
            color: var(--primary-dark);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .category-select {
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 13px;
            background: #fafafa;
            transition: var(--transition);
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
        }

        .category-select:focus {
            border-color: var(--primary);
            outline: none;
        }

        .btn-post-submit {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            transition: var(--transition);
            cursor: pointer;
        }

        .btn-post-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 155, 70, 0.35);
        }

        .btn-post-submit i {
            margin-right: 8px;
        }

        /* Post Items */
        .post-item {
            padding: 25px;
            border-bottom: 1px solid #f0f0f0;
            transition: var(--transition);
        }

        .post-item:hover {
            background: #fafdfb;
        }

        .post-item-header {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 15px;
        }

        .post-user-avatar {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary);
            flex-shrink: 0;
        }

        .post-meta {
            flex: 1;
        }

        .post-user-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        .post-user-name a {
            color: var(--text-dark);
            text-decoration: none;
            transition: var(--transition);
        }

        .post-user-name a:hover {
            color: var(--primary);
        }

        .post-category-tag {
            display: inline-block;
            background: var(--primary-light);
            color: var(--primary-dark);
            padding: 3px 12px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 5px;
        }

        .post-content-text {
            color: #444;
            font-size: 0.9rem;
            line-height: 1.7;
            margin-bottom: 15px;
        }

        .post-actions-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-action-sm {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-edit-sm {
            background: #e3f2fd;
            color: #1565c0;
        }

        .btn-edit-sm:hover {
            background: #1565c0;
            color: #fff;
        }

        .btn-delete-sm {
            background: #ffebee;
            color: #c62828;
        }

        .btn-delete-sm:hover {
            background: #c62828;
            color: #fff;
        }

        /* Comment Section */
        .comment-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px dashed #e8e8e8;
        }

        .comment-label {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.85rem;
            margin-bottom: 10px;
        }

        .comment-label i {
            color: var(--primary);
            margin-right: 5px;
        }

        .comment-input-group {
            display: flex;
            gap: 10px;
        }

        .comment-textarea {
            flex: 1;
            border: 2px solid #e8e8e8;
            border-radius: var(--radius-sm);
            padding: 10px 15px;
            font-size: 13px;
            resize: none;
            transition: var(--transition);
            background: #fafafa;
            font-family: 'Poppins', sans-serif;
        }

        .comment-textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 155, 70, 0.1);
            outline: none;
            background: #fff;
        }

        .btn-comment-submit {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
        }

        .btn-comment-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .comment-item {
            background: #f9fafb;
            border-radius: var(--radius-sm);
            padding: 15px;
            margin-top: 10px;
            border-left: 4px solid var(--primary);
        }

        .comment-author {
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 5px;
        }

        .comment-author a {
            color: var(--text-dark);
            text-decoration: none;
        }

        .comment-author a:hover {
            color: var(--primary);
        }

        .comment-author .doctor-badge {
            font-size: 0.7rem;
            color: var(--primary);
            font-weight: 600;
        }

        .comment-content {
            color: #555;
            font-size: 0.85rem;
        }

        .comment-actions {
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }

        /* ============ SIDEBAR ============ */
        .sidebar-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 25px;
        }

        .sidebar-header {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            color: #fff;
            padding: 18px 20px;
            font-weight: 600;
            font-size: 1rem;
        }

        .sidebar-header i {
            margin-right: 10px;
            color: var(--secondary);
        }

        .sidebar-body {
            padding: 20px;
        }

        /* Quick Actions */
        .quick-action-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            border-radius: var(--radius-sm);
            transition: var(--transition);
            cursor: pointer;
            margin-bottom: 8px;
            text-decoration: none;
            color: var(--text-dark);
        }

        .quick-action-item:hover {
            background: var(--primary-light);
            transform: translateX(5px);
            text-decoration: none;
        }

        .quick-action-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
            flex-shrink: 0;
        }

        .quick-action-icon.patients { background: linear-gradient(135deg, #3498db, #2980b9); }
        .quick-action-icon.prescriptions { background: linear-gradient(135deg, #27ae60, #229954); }
        .quick-action-icon.messages { background: linear-gradient(135deg, #9b59b6, #8e44ad); }
        .quick-action-icon.hospital { background: linear-gradient(135deg, #e67e22, #d35400); }

        .quick-action-text {
            font-weight: 500;
            font-size: 0.9rem;
        }

        /* Category List */
        .category-list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            border-radius: var(--radius-sm);
            transition: var(--transition);
            text-decoration: none;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .category-list-item:hover {
            background: var(--primary-light);
            text-decoration: none;
        }

        .category-count {
            background: var(--primary);
            color: #fff;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* ============ FOOTER ============ */
        .footer-area {
            background: linear-gradient(135deg, #0f2027, #203a43);
            color: #e0e0e0;
            padding: 25px 0;
            text-align: center;
        }

        .footer-area a {
            color: var(--secondary);
            text-decoration: none;
        }

        .footer-area a:hover {
            text-decoration: underline;
        }

        /* ============ MODALS ============ */
        .modal-content {
            border-radius: var(--radius);
            border: none;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
        }

        .modal-header .close {
            color: #fff;
            opacity: 1;
        }

        .btn-modal-primary {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-modal-primary:hover {
            background: var(--primary-dark);
            box-shadow: 0 5px 15px rgba(0, 155, 70, 0.3);
        }

        .btn-modal-secondary {
            background: #e0e0e0;
            color: #555;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-modal-secondary:hover {
            background: #ccc;
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 992px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
            
            .welcome-text h1 {
                font-size: 1.6rem;
            }
            
            .stat-card {
                margin-bottom: 20px;
            }
        }

        @media (max-width: 768px) {
            .hero-header {
                padding: 40px 0 60px;
            }
            
            .post-bottom-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .btn-post-submit {
                width: 100%;
                text-align: center;
            }
            
            .navbar-nav .nav-link {
                font-size: 13px;
                padding: 8px 12px !important;
            }
        }
    </style>
</head>

<body>
    <?php
    $query = mysqli_query($conn,"SELECT * from person inner join gender on person.gender = gender.gender_id where person.nid ='$user_id'");
    $my = mysqli_fetch_array($query);
    
    // Get statistics
    $total_patients_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM patient");
    $total_patients = mysqli_fetch_assoc($total_patients_query)['total'];
    
    $total_posts_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM post");
    $total_posts = mysqli_fetch_assoc($total_posts_query)['total'];
    
    $total_comments_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM commentpost");
    $total_comments = mysqli_fetch_assoc($total_comments_query)['total'];
    ?>

    <!-- ============ NAVIGATION ============ -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <div class="logo_img">
                <a href="index.html"><img src="img/bg_logo1.png" alt="IBHS Logo"></a>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto align-items-center">
                    <?php if($user_type=='Doctor'): ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="patient.php"><i class="fas fa-users"></i> Patients</a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="doctor.php"><i class="fas fa-user-md"></i> Doctors</a>
                    </li>
                    <?php endif; ?>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="hospital.php"><i class="fas fa-hospital"></i> Hospitals</a>
                    </li>
                    
                    <?php if($user_type == "Patient"): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="myprescription.php"><i class="fas fa-prescription"></i> Prescriptions</a>
                    </li>
                    <?php endif; ?>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="message.php?id=0"><i class="fas fa-envelope"></i> Messages</a>
                    </li>
                    
                    <!-- User Profile Dropdown -->
                    <li class="nav-item dropdown ml-3">
                        <a class="nav-link user-badge dropdown-toggle" href="#" id="userDropdown" data-toggle="dropdown">
                            <i class="fas fa-user-circle mr-2"></i>
                            <span style="color:#fff;font-weight:600;"><?php echo $my['name']; ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <?php if($user_type=='Doctor'): ?>
                            <a class="dropdown-item" href="doctorprofile.php?id=<?php echo $user_id; ?>">
                                <i class="fas fa-id-card"></i> View Profile
                            </a>
                            <?php else: ?>
                            <a class="dropdown-item" href="myprofile.php?id=<?php echo $user_id; ?>">
                                <i class="fas fa-id-card"></i> View Profile
                            </a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ============ HERO HEADER ============ -->
    <section class="hero-header">
        <div class="container">
            <div class="welcome-text animate__animated animate__fadeInUp">
                <h1>Welcome back, <span class="doctor-name"><?php echo $my['name']; ?></span> 👋</h1>
                <p>Manage your patients, prescriptions, and community discussions all in one place.</p>
            </div>
        </div>
    </section>

    <!-- ============ STATISTICS CARDS ============ -->
    <div class="container stats-container">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card card-patients animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number"><?php echo $total_patients; ?></div>
                    <div class="stat-label">Total Patients</div>
                    <div class="stat-trend up"><i class="fas fa-arrow-up"></i> 12% this month</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card card-appointments animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-number">48</div>
                    <div class="stat-label">Appointments</div>
                    <div class="stat-trend up"><i class="fas fa-arrow-up"></i> 8% this week</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card card-prescriptions animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-prescription"></i>
                    </div>
                    <div class="stat-number"><?php echo $total_posts; ?></div>
                    <div class="stat-label">Forum Posts</div>
                    <div class="stat-trend up"><i class="fas fa-arrow-up"></i> Active discussions</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stat-card card-messages animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="stat-number"><?php echo $total_comments; ?></div>
                    <div class="stat-label">Total Responses</div>
                    <div class="stat-trend up"><i class="fas fa-arrow-up"></i> Community engaged</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="main-content-wrapper">
        <div class="container">
            <div class="content-grid">
                
                <!-- LEFT COLUMN - FORUM -->
                <div class="forum-column">
                    <div class="forum-card animate__animated animate__fadeIn">
                        <!-- Create Post -->
                        <div class="post-create-area">
                            <form method="post">
                                <div class="post-input-group">
                                    <img src="img/<?php echo $my['image'] ? $my['image'] : 'default-avatar.png'; ?>" 
                                         alt="Doctor" class="post-avatar-small">
                                    <div class="post-textarea-wrapper">
                                        <textarea class="post-textarea" name="post_content" rows="3" 
                                                  placeholder="Share your medical knowledge or ask a question..."></textarea>
                                    </div>
                                </div>
                                <div class="post-bottom-row">
                                    <div class="category-select-wrapper">
                                        <span class="category-badge"><i class="fas fa-tag"></i> Category:</span>
                                        <input class="category-select" list="browsers" name="browser" placeholder="Select category">
                                        <datalist id="browsers">
                                            <option value="General Health">
                                            <option value="Cardiology">
                                            <option value="Neurology">
                                            <option value="Pediatrics">
                                            <option value="Dermatology">
                                            <option value="Orthopedics">
                                            <option value="Covid19">
                                            <option value="Diabetes">
                                            <option value="Mental Health">
                                            <option value="Nutrition">
                                        </datalist>
                                    </div>
                                    <button class="btn-post-submit" name="post">
                                        <i class="fas fa-paper-plane"></i> Publish Post
                                    </button>
                                </div>
                            </form>
                            
                            <?php
                            if (isset($_POST['post'])){
                                $post_content = mysqli_real_escape_string($conn, $_POST['post_content']);
                                $category = mysqli_real_escape_string($conn, $_POST['browser']);
                                if($user_type=="Patient"){
                                    mysqli_query($conn,"INSERT INTO post (content,category,date,p_nid,time) VALUES ('$post_content','$category',CURRENT_DATE,'$user_id',CURRENT_TIME)");
                                }else{
                                    mysqli_query($conn,"INSERT INTO post (content,category,date,p_nid,d_nid,time) VALUES ('$post_content','$category',CURRENT_DATE,'$user_id','$user_id',CURRENT_TIME)");
                                }
                                echo "<script>window.location.href='doctorhome.php';</script>";
                            }
                            ?>
                        </div>

                        <!-- Posts List -->
                        <div class="forum-header">
                            <h5><i class="fas fa-newspaper"></i> Medical Community Forum</h5>
                            <span style="font-size:0.8rem;opacity:0.7;">Latest discussions</span>
                        </div>

                        <div class="postshow">
                            <?php
                            $query = "SELECT * FROM post ORDER BY post_id DESC";
                            $query_run = mysqli_query($conn, $query);
                            
                            if($query_run && mysqli_num_rows($query_run) > 0):
                                foreach($query_run as $row):
                                    $id = $row['post_id'];
                                    $nid = $row['p_nid'];
                                    $pcategory = $row['category'];
                                    $query2 = mysqli_query($conn,"SELECT * FROM person WHERE nid = $nid");
                                    $person_row = mysqli_fetch_array($query2);
                                    $name = $person_row['name'];
                            ?>
                            <div class="post-item" id="post<?php echo $row['post_id']; ?>">
                                <!-- Post Header -->
                                <div class="post-item-header">
                                    <img src="img/<?php echo $person_row['image'] ? $person_row['image'] : 'default-avatar.png'; ?>" 
                                         alt="User" class="post-user-avatar">
                                    <div class="post-meta">
                                        <div class="post-user-name">
                                            <?php if($person_row['occupation'] == "Doctor"): ?>
                                                <a href="doctorprofile.php?id=<?php echo $nid; ?>">
                                                    <?php echo $name; ?> 
                                                    <span style="color:#009B46;font-size:0.75rem;">👨‍⚕️ Doctor</span>
                                                </a>
                                            <?php else: ?>
                                                <a href="myprofile.php?id=<?php echo $nid; ?>"><?php echo $name; ?></a>
                                            <?php endif; ?>
                                        </div>
                                        <span class="post-category-tag">#<?php echo $pcategory; ?></span>
                                    </div>
                                </div>

                                <!-- Post Content -->
                                <div class="post-content-text">
                                    <?php echo nl2br(htmlspecialchars($row['content'])); ?>
                                </div>

                                <!-- Post Actions -->
                                <div class="post-actions-row">
                                    <?php if($user_id == $nid): ?>
                                    <button class="btn-action-sm btn-edit-sm editbtn">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <a href="deletepost.php?id=<?php echo $id; ?>" onclick="return confirm('Delete this post?')">
                                        <button class="btn-action-sm btn-delete-sm">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </a>
                                    <?php endif; ?>
                                    
                                    <!-- Hidden post data for edit modal -->
                                    <div style="display:none;" class="posid"><?php echo $row['post_id']; ?></div>
                                    <div style="display:none;" class="content"><?php echo $row['content']; ?></div>
                                </div>

                                <!-- Comment Section -->
                                <div class="comment-section">
                                    <div class="comment-label">
                                        <i class="fas fa-comment-dots"></i> Answers & Comments
                                    </div>
                                    
                                    <!-- Existing Comments -->
                                    <?php 
                                    $comment_query = mysqli_query($conn,"SELECT * FROM commentpost WHERE post_id = '$id'");
                                    while ($comment_row = mysqli_fetch_array($comment_query)):
                                        $comment_by = $comment_row['p_nid'];
                                        $comment_query2 = mysqli_query($conn,"SELECT * FROM person WHERE nid = '$comment_by'");
                                        $comment_row2 = mysqli_fetch_array($comment_query2);
                                        $c_name = $comment_row2['name'];
                                        $occupation = $comment_row2['occupation'];
                                    ?>
                                    <div class="comment-item">
                                        <div class="comment-author">
                                            <?php if($occupation == "Doctor"): ?>
                                                <a href="doctorprofile.php?id=<?php echo $comment_by; ?>">
                                                    <?php echo $c_name; ?>
                                                    <span class="doctor-badge">👨‍⚕️ Doctor</span>
                                                </a>
                                            <?php else: ?>
                                                <a href="myprofile.php?id=<?php echo $comment_by; ?>"><?php echo $c_name; ?></a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="comment-content">
                                            <?php echo nl2br(htmlspecialchars($comment_row['content'])); ?>
                                        </div>
                                        
                                        <!-- Comment Edit/Delete -->
                                        <?php if($comment_by == $user_id): ?>
                                        <div class="comment-actions">
                                            <button class="btn-action-sm btn-edit-sm ceditbtn">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <a href="deletecomment.php?id=<?php echo $id; ?>&comment_id=<?php echo $comment_row['comment_id']; ?>" 
                                               onclick="return confirm('Delete this comment?')">
                                                <button class="btn-action-sm btn-delete-sm">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </a>
                                        </div>
                                        <div style="display:none;" class="com-post-id"><?php echo $id; ?></div>
                                        <div style="display:none;" class="com-id"><?php echo $comment_row['comment_id']; ?></div>
                                        <div style="display:none;" class="com-content"><?php echo $comment_row['content']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <?php endwhile; ?>

                                    <!-- Add Comment Form -->
                                    <form action="comment.php" method="post" style="margin-top:15px;">
                                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                                        <div class="comment-input-group">
                                            <textarea name="comment_content" rows="2" class="comment-textarea" 
                                                      placeholder="Write your answer..."></textarea>
                                            <button type="submit" name="comment" class="btn-comment-submit">
                                                <i class="fas fa-reply"></i> Reply
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php 
                                endforeach;
                            else:
                            ?>
                            <div style="padding:40px;text-align:center;color:#999;">
                                <i class="fas fa-inbox" style="font-size:3rem;display:block;margin-bottom:15px;"></i>
                                <p>No posts yet. Start a discussion!</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN - SIDEBAR -->
                <div class="sidebar-column">
                    
                    <!-- Quick Actions -->
                    <div class="sidebar-card animate__animated animate__fadeInRight">
                        <div class="sidebar-header">
                            <i class="fas fa-bolt"></i> Quick Actions
                        </div>
                        <div class="sidebar-body">
                            <a href="patient.php" class="quick-action-item">
                                <div class="quick-action-icon patients"><i class="fas fa-user-plus"></i></div>
                                <div class="quick-action-text">Manage Patients</div>
                            </a>
                            <a href="hospital.php" class="quick-action-item">
                                <div class="quick-action-icon hospital"><i class="fas fa-hospital"></i></div>
                                <div class="quick-action-text">View Hospitals</div>
                            </a>
                            <a href="message.php?id=0" class="quick-action-item">
                                <div class="quick-action-icon messages"><i class="fas fa-envelope"></i></div>
                                <div class="quick-action-text">Messages</div>
                            </a>
                            <a href="mypost.php" class="quick-action-item">
                                <div class="quick-action-icon prescriptions"><i class="fas fa-clipboard-list"></i></div>
                                <div class="quick-action-text">My Posts</div>
                            </a>
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="sidebar-card animate__animated animate__fadeInRight" style="animation-delay:0.1s;">
                        <div class="sidebar-header">
                            <i class="fas fa-search"></i> Search Discussions
                        </div>
                        <div class="sidebar-body">
                            <form action="searchquestion.php" method="POST">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Search topics..." 
                                           style="border-radius:25px 0 0 25px;border:2px solid #e0e0e0;padding:10px 20px;">
                                    <div class="input-group-append">
                                        <button class="btn" type="submit" name="go" 
                                                style="background:var(--primary);color:#fff;border-radius:0 25px 25px 0;padding:10px 20px;">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="sidebar-card animate__animated animate__fadeInRight" style="animation-delay:0.2s;">
                        <div class="sidebar-header">
                            <i class="fas fa-folder"></i> Categories
                        </div>
                        <div class="sidebar-body">
                            <a href="mypost.php" class="category-list-item">
                                <span><i class="fas fa-user-edit mr-2"></i> My Posts</span>
                                <i class="fas fa-chevron-right" style="color:#ccc;"></i>
                            </a>
                            <?php
                            $cat_query = mysqli_query($conn,"SELECT category, COUNT(*) as c FROM post GROUP BY category ORDER BY COUNT(*) DESC LIMIT 8");
                            while ($category_row = mysqli_fetch_array($cat_query)):
                            ?>
                            <a href="categorypost.php?category=<?php echo urlencode($category_row['category']); ?>" 
                               class="category-list-item">
                                <span>#<?php echo htmlspecialchars($category_row['category']); ?></span>
                                <span class="category-count"><?php echo $category_row['c']; ?></span>
                            </a>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ EDIT POST MODAL ============ -->
    <div class="modal fade" id="editmodal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit mr-2"></i>Update Post</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="updatepost.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="updatequestion_id" id="updatequestion_id">
                        <div class="form-group">
                            <label>Edit your post content:</label>
                            <textarea name="updatequestion" id="updatequestion" class="form-control" 
                                      rows="4" style="border-radius:10px;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="updatedata" class="btn-modal-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============ EDIT COMMENT MODAL ============ -->
    <div class="modal fade" id="editmodalans" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit mr-2"></i>Update Comment</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="commentupdate.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="pos_id" id="pos_id">
                        <input type="hidden" name="com_id" id="com_id">
                        <div class="form-group">
                            <label>Edit your comment:</label>
                            <textarea name="updateans" id="updateans" class="form-control" 
                                      rows="3" style="border-radius:10px;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="updatecom" class="btn-modal-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============ FOOTER ============ -->
    <footer class="footer-area">
        <div class="container">
            <p>© <?php echo date('Y'); ?> All Rights Reserved by 
               <a href="#">Identity Based Healthcare System (IBHS)</a> 
               <i class="fa fa-heart" style="color:#e74c3c;"></i>
            </p>
        </div>
    </footer>

    <!-- ============ SCRIPTS ============ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Edit Post Modal
            $('.editbtn').on('click', function() {
                $('#editmodal').modal('show');
                var $tr = $(this).closest('.post-item');
                var data = $tr.find('.posid, .content').map(function() {
                    return $(this).text().trim();
                }).get();
                $('#updatequestion_id').val(data[0]);
                $('#updatequestion').val(data[1]);
            });

            // Edit Comment Modal
            $('.ceditbtn').on('click', function() {
                $('#editmodalans').modal('show');
                var $tr = $(this).closest('.comment-item');
                var data = $tr.find('.com-post-id, .com-id, .com-content').map(function() {
                    return $(this).text().trim();
                }).get();
                $('#pos_id').val(data[0]);
                $('#com_id').val(data[1]);
                $('#updateans').val(data[2]);
            });

            // Smooth scroll for quick actions
            $('.quick-action-item').on('click', function(e) {
                if ($(this).attr('href') === '#') {
                    e.preventDefault();
                }
            });

            // Auto-hide success messages
            setTimeout(function() {
                $('.alert-success').fadeOut('slow');
            }, 3000);
        });
    </script>
</body>
</html>