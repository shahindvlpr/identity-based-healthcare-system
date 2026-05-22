<?php
    $cat = isset($_GET['category']) ? $_GET['category'] : '';
?>

<?php include('dbconn.php'); ?>
<?php include('session.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Doctors by Category - IBHS">
    <meta name="author" content="">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title><?php echo htmlspecialchars($cat); ?> Doctors | IBHS</title>

    <link href="css/blog-home.css" rel="stylesheet">
    <link rel="stylesheet" href="css/net.css"/>
    <link rel="stylesheet" href="css/myprofile.css"/>

    <style>
        :root {
            --primary: #009B46;
            --primary-dark: #007a38;
            --primary-light: #e8f5e9;
            --info: #3498db;
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

        .logo_img img {
            height: 44px;
        }

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

        .navbar-nav .nav-link i {
            margin-right: 5px;
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

        .btn-logout-nav:hover {
            background: var(--danger) !important;
            color: #fff !important;
        }

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

        .header-title .breadcrumb-sub {
            color: rgba(255,255,255,0.8);
            font-size: 0.82rem;
        }

        .header-title .breadcrumb-sub a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
        }

        .header-title .breadcrumb-sub a:hover {
            color: #fff;
            text-decoration: underline;
        }

        /* ============ DOCTOR CARDS ============ */
        .doctor-grid {
            display: grid;
            gap: 18px;
        }

        .doctor-card {
            background: #fff;
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            transition: var(--transition);
            display: flex;
            gap: 16px;
            align-items: flex-start;
            position: relative;
            overflow: hidden;
        }

        .doctor-card::before {
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

        .doctor-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            border-color: #e0e0e0;
        }

        .doctor-card:hover::before {
            opacity: 1;
        }

        .doctor-avatar {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #f0f0f0;
            flex-shrink: 0;
            transition: var(--transition);
        }

        .doctor-card:hover .doctor-avatar {
            border-color: var(--primary);
            transform: scale(1.05);
        }

        .doctor-info {
            flex: 1;
            min-width: 0;
        }

        .doctor-name {
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .doctor-name a {
            color: var(--text-dark);
            text-decoration: none;
            transition: var(--transition);
        }

        .doctor-name a:hover {
            color: var(--primary);
        }

        .doctor-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 8px;
        }

        .specialist-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 500;
            background: #e8f5e9;
            color: var(--primary-dark);
        }

        .rating-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
            background: #fff8e1;
            color: #f39c12;
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

        .sidebar-header i {
            color: var(--primary);
        }

        .sidebar-body {
            padding: 12px;
        }

        .category-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .category-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 14px;
            border-radius: 8px;
            transition: var(--transition);
            text-decoration: none;
            color: var(--text-dark);
            margin-bottom: 3px;
        }

        .category-item:hover {
            background: var(--primary-light);
            text-decoration: none;
            color: var(--primary);
        }

        .category-item.active-cat {
            background: var(--primary);
            color: #fff;
        }

        .category-item.active-cat:hover {
            background: var(--primary-dark);
            color: #fff;
        }

        .category-count {
            background: var(--primary);
            color: #fff;
            padding: 2px 10px;
            border-radius: 15px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .category-item.active-cat .category-count {
            background: rgba(255,255,255,0.3);
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

        .empty-state h5 {
            color: #999;
            font-weight: 600;
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 992px) {
            .sidebar-card {
                position: static;
                margin-top: 20px;
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
            
            .header-title h4 {
                font-size: 1.1rem;
            }
            
            .header-icon-box {
                width: 42px;
                height: 42px;
                font-size: 20px;
                border-radius: 10px;
            }
            
            .doctor-card {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .doctor-tags {
                justify-content: center;
            }
            
            .doctor-avatar {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>

<body>

    <?php
    // FIXED: Null-safe user query
    $my_query = mysqli_query($conn,"SELECT * from person inner join gender on person.gender = gender.gender_id where person.nid ='$user_id'");
    $my = ($my_query) ? mysqli_fetch_array($my_query) : null;
    
    // FIXED: Null-safe category count for this specific category
    $count_query = mysqli_query($conn,"SELECT COUNT(DISTINCT d.d_nid) as total FROM doctor d INNER JOIN dmdc dm ON dm.dmdc_id = d.dmdc_id WHERE dm.specialist = '".mysqli_real_escape_string($conn, $cat)."' AND d.d_nid != '$user_id'");
    $count_data = ($count_query) ? mysqli_fetch_assoc($count_query) : null;
    $total_in_category = ($count_data && isset($count_data['total'])) ? $count_data['total'] : 0;
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
                    <li class="nav-item">
                        <a class="nav-link" href="patient.php"><i class="fas fa-users"></i> Patients</a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="doctor.php"><i class="fas fa-user-md"></i> Doctors</a>
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
                    
                    <!-- User Badge -->
                    <li class="nav-item ml-2">
                        <?php if($user_type=='Doctor'): ?>
                        <a class="nav-link nav-user-badge" href="doctorprofile.php?id=<?php echo $user_id; ?>">
                            <i class="fas fa-user-circle" style="font-size:16px;color:var(--primary);"></i>
                            <div>
                                <div class="divname"><?php echo $my['name'] ?? 'User'; ?></div>
                                <div class="divid"><?php echo $my['nid'] ?? ''; ?></div>
                            </div>
                        </a>
                        <?php else: ?>
                        <a class="nav-link nav-user-badge" href="myprofile.php?id=<?php echo $user_id; ?>">
                            <i class="fas fa-user-circle" style="font-size:16px;color:var(--primary);"></i>
                            <div>
                                <div class="divname"><?php echo $my['name'] ?? 'User'; ?></div>
                                <div class="divid"><?php echo $my['nid'] ?? ''; ?></div>
                            </div>
                        </a>
                        <?php endif; ?>
                    </li>
                    
                    <li class="nav-item ml-2">
                        <a class="nav-link btn-logout-nav" href="logout.php">
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
                <div class="header-title">
                    <div class="header-icon-box">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <div>
                        <h4><?php echo htmlspecialchars($cat); ?> Specialists</h4>
                        <div class="breadcrumb-sub">
                            <a href="doctor.php"><i class="fas fa-user-md"></i> All Doctors</a>
                            <i class="fas fa-chevron-right mx-1" style="font-size:0.6rem;"></i>
                            <span><?php echo htmlspecialchars($cat); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="container">
        <div class="row">
            
            <!-- Doctor Cards Column -->
            <div class="col-lg-8">
                
                <?php
                    // FIXED: Added table aliases to avoid ambiguous column errors
                    $cat_escaped = mysqli_real_escape_string($conn, $cat);
                    $query = mysqli_query($conn,"
                        SELECT 
                            d.d_nid AS nid, 
                            p.name, 
                            dm.specialist, 
                            ROUND(AVG(r.rating), 1) AS rating, 
                            p.image 
                        FROM doctor d
                        INNER JOIN person p ON p.nid = d.d_nid 
                        INNER JOIN dmdc dm ON dm.dmdc_id = d.dmdc_id  
                        LEFT JOIN reviewfordoctor r ON r.d_nid = d.d_nid 
                        WHERE dm.specialist = '$cat_escaped' 
                        AND d.d_nid != '$user_id' 
                        GROUP BY d.d_nid, p.name, dm.specialist, p.image 
                        ORDER BY rating DESC
                    ");
                    
                    if($query && mysqli_num_rows($query) > 0):
                ?>
                
                <div class="doctor-grid">
                    <?php while ($doctor_row = mysqli_fetch_array($query)): 
                        $doctor_image = !empty($doctor_row['image']) ? $doctor_row['image'] : 'doctor1.jpg';
                    ?>
                    <div class="doctor-card animate__animated animate__fadeInUp">
                        <img src="img/<?php echo $doctor_image; ?>" 
                             alt="Doctor" class="doctor-avatar"
                             onerror="this.src='img/doctor1.jpg'">
                        
                        <div class="doctor-info">
                            <div class="doctor-name">
                                <a href="doctorprofile.php?id=<?php echo $doctor_row['nid']; ?>">
                                    Dr. <?php echo $doctor_row['name']; ?>
                                </a>
                            </div>
                            
                            <div class="doctor-tags">
                                <span class="specialist-tag">
                                    <i class="fas fa-stethoscope"></i> <?php echo $doctor_row['specialist']; ?>
                                </span>
                                <span class="rating-tag">
                                    <i class="fas fa-star"></i> 
                                    <?php echo ($doctor_row['rating'] && $doctor_row['rating'] > 0) ? $doctor_row['rating'] : '0.0'; ?>
                                </span>
                            </div>
                            
                            <a href="doctorprofile.php?id=<?php echo $doctor_row['nid']; ?>" class="view-profile-btn">
                                <i class="fas fa-eye"></i> View Full Profile
                            </a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
                
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-user-md"></i>
                    <h5>No doctors found in <?php echo htmlspecialchars($cat); ?></h5>
                    <p class="text-muted">Try browsing other specializations</p>
                </div>
                <?php endif; ?>
                
            </div>

            <!-- Sidebar Column -->
            <div class="col-lg-4">
                
                <!-- Categories Card -->
                <div class="sidebar-card">
                    <div class="sidebar-header">
                        <i class="fas fa-folder"></i> All Specializations
                    </div>
                    <div class="sidebar-body">
                        <?php
                            // FIXED: Added table aliases
                            $cat_query = mysqli_query($conn,"SELECT dm.specialist, COUNT(DISTINCT d.d_nid) as c FROM dmdc dm INNER JOIN doctor d ON dm.dmdc_id = d.dmdc_id WHERE d.d_nid != '$user_id' GROUP BY dm.specialist ORDER BY dm.specialist");
                            if($cat_query && mysqli_num_rows($cat_query) > 0):
                        ?>
                        <ul class="category-list">
                            <?php while ($category_row = mysqli_fetch_array($cat_query)): 
                                $is_active = ($category_row['specialist'] == $cat);
                            ?>
                            <a href="doctorcategory.php?category=<?php echo urlencode($category_row['specialist']); ?>" 
                               class="category-item <?php echo $is_active ? 'active-cat' : ''; ?>">
                                <span><i class="fas fa-tag mr-2" style="font-size:0.7rem;"></i> <?php echo $category_row['specialist']; ?></span>
                                <span class="category-count"><?php echo $category_row['c']; ?></span>
                            </a>
                            <?php endwhile; ?>
                        </ul>
                        <?php else: ?>
                        <p class="text-muted text-center" style="font-size:0.85rem;padding:15px;">No specializations found</p>
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