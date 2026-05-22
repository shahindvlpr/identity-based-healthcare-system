<?php include('dbconn.php'); ?>
<?php include('session.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Find Hospitals - IBHS">
    <meta name="author" content="">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title>Find Hospitals | IBHS</title>

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

        /* ============ HOSPITAL CARDS ============ */
        .hospital-grid {
            display: grid;
            gap: 18px;
        }

        .hospital-card {
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

        .hospital-card::before {
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

        .hospital-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            border-color: #e0e0e0;
        }

        .hospital-card:hover::before { opacity: 1; }

        .hospital-avatar {
            width: 70px;
            height: 70px;
            border-radius: 14px;
            object-fit: cover;
            border: 2px solid #f0f0f0;
            flex-shrink: 0;
            transition: var(--transition);
            background: #f8f9fa;
            padding: 8px;
        }

        .hospital-card:hover .hospital-avatar {
            border-color: var(--primary);
            transform: scale(1.05);
        }

        .hospital-info {
            flex: 1;
            min-width: 0;
        }

        .hospital-name {
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .hospital-name a {
            color: var(--text-dark);
            text-decoration: none;
            transition: var(--transition);
        }

        .hospital-name a:hover { color: var(--primary); }

        .hospital-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 8px;
        }

        .meta-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 500;
        }

        .meta-tag.wards {
            background: #e8f5e9;
            color: var(--primary-dark);
        }

        .meta-tag.cabins {
            background: #e3f2fd;
            color: #1565c0;
        }

        .meta-tag.id-tag {
            background: #fff3e0;
            color: #e65100;
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
            margin-top: 4px;
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

        /* Quick Info */
        .quick-info {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #f0f0f0;
        }

        .quick-info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            font-size: 0.82rem;
            color: #666;
        }

        .quick-info-item i {
            width: 20px;
            color: var(--primary);
            text-align: center;
        }

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
            .hospital-card { flex-direction: column; align-items: center; text-align: center; }
            .hospital-meta { justify-content: center; }
            .hospital-avatar { width: 55px; height: 55px; }
        }
    </style>
</head>

<body>

    <?php
    // FIXED: Null-safe user query
    $my_query = mysqli_query($conn,"SELECT * from person inner join gender on person.gender = gender.gender_id where person.nid ='$user_id'");
    $my = ($my_query) ? mysqli_fetch_array($my_query) : null;
    
    // Count total hospitals - FIXED: null-safe
    $count_query = mysqli_query($conn,"SELECT COUNT(*) as total FROM hospital");
    $count_data = ($count_query) ? mysqli_fetch_assoc($count_query) : null;
    $total_hospitals = ($count_data && isset($count_data['total'])) ? $count_data['total'] : 0;
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
                    <li class="nav-item"><a class="nav-link active" href="hospital.php"><i class="fas fa-hospital"></i> Hospitals</a></li>
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
    <div class="page-header">
        <div class="container">
            <div class="header-content">
                <div class="header-title">
                    <div class="header-icon-box">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <div>
                        <h4>Find Hospitals</h4>
                        <span>Browse healthcare facilities near you</span>
                    </div>
                </div>
                <div class="header-badge">
                    <div class="count"><?php echo $total_hospitals; ?></div>
                    <div class="label">Registered Hospitals</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="container">
        <div class="row">
            
            <!-- Hospital Cards Column -->
            <div class="col-lg-8">
                
                <?php
                    $query = mysqli_query($conn,"SELECT * FROM hospital ORDER BY hospital_name ASC");
                    if($query && mysqli_num_rows($query) > 0):
                ?>
                
                <div class="hospital-grid">
                    <?php while($hos_row = mysqli_fetch_array($query)): ?>
                    <div class="hospital-card animate__animated animate__fadeInUp">
                        <img src="img/hospital1.png" alt="Hospital" class="hospital-avatar"
                             onerror="this.src='img/hospital1.png'">
                        
                        <div class="hospital-info">
                            <div class="hospital-name">
                                <a href="hospitalprofile.php?id=<?php echo $hos_row['hospital_id']; ?>">
                                    <?php echo $hos_row['hospital_name']; ?>
                                </a>
                            </div>
                            
                            <div class="hospital-meta">
                                <span class="meta-tag wards">
                                    <i class="fas fa-bed"></i> <?php echo $hos_row['numberof_ward'] ?? '0'; ?> Wards
                                </span>
                                <span class="meta-tag cabins">
                                    <i class="fas fa-door-open"></i> <?php echo $hos_row['numberof_cabin'] ?? '0'; ?> Cabins
                                </span>
                                <span class="meta-tag id-tag">
                                    <i class="fas fa-id-card"></i> ID: <?php echo $hos_row['hospital_id']; ?>
                                </span>
                            </div>
                            
                            <a href="hospitalprofile.php?id=<?php echo $hos_row['hospital_id']; ?>" class="view-profile-btn">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-hospital"></i>
                    <h5>No hospitals registered yet</h5>
                    <p class="text-muted">Check back later for available hospitals</p>
                </div>
                <?php endif; ?>
                
            </div>

            <!-- Sidebar Column -->
            <div class="col-lg-4">
                
                <!-- Search Card -->
                <div class="sidebar-card">
                    <div class="sidebar-header">
                        <i class="fas fa-search"></i> Search Hospitals
                    </div>
                    <div class="sidebar-body">
                        <form action="hossearch.php" method="POST">
                            <div class="search-input-group">
                                <input type="text" name="search" placeholder="Search by hospital name...">
                                <button type="submit" name="go">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                        
                        <!-- Quick Info -->
                        <div class="quick-info">
                            <div class="quick-info-item">
                                <i class="fas fa-hospital"></i>
                                <span>Total Hospitals: <strong><?php echo $total_hospitals; ?></strong></span>
                            </div>
                            <div class="quick-info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Find healthcare near you</span>
                            </div>
                            <div class="quick-info-item">
                                <i class="fas fa-info-circle"></i>
                                <span>Click on a hospital to view details</span>
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
</body>
</html>