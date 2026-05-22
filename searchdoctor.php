<?php include('dbconn.php'); ?>
<?php include('session.php'); ?>
<?php
    // FIXED: Null-safe GET parameter
    $search = isset($_GET['search']) ? $_GET['search'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Search Doctors - IBHS">
    <meta name="author" content="">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title>Search: <?php echo htmlspecialchars($search); ?> | IBHS</title>

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
            padding: 28px 0;
            margin-bottom: 28px;
            border-radius: 0 0 30px 30px;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
            top: -30px;
            right: -30px;
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

        /* ============ SEARCH BOX INLINE ============ */
        .search-inline {
            display: flex;
            align-items: center;
            gap: 0;
        }

        .search-inline input {
            padding: 10px 18px;
            border: 2px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.1);
            color: #fff;
            border-radius: 25px 0 0 25px;
            font-size: 0.85rem;
            width: 250px;
            font-family: 'Poppins', sans-serif;
            outline: none;
        }

        .search-inline input::placeholder { color: rgba(255,255,255,0.6); }
        .search-inline input:focus { border-color: #fff; background: rgba(255,255,255,0.2); }

        .search-inline button {
            padding: 10px 20px;
            background: #fff;
            color: var(--primary);
            border: none;
            border-radius: 0 25px 25px 0;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .search-inline button:hover { background: #f0f0f0; }

        /* ============ DOCTOR CARDS ============ */
        .doctor-grid {
            display: grid;
            gap: 16px;
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
            align-items: center;
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

        .doctor-card:hover::before { opacity: 1; }

        .doctor-avatar {
            width: 65px;
            height: 65px;
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

        .doctor-info { flex: 1; min-width: 0; }

        .doctor-name {
            font-weight: 700;
            font-size: 1rem;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .doctor-name a {
            color: var(--text-dark);
            text-decoration: none;
            transition: var(--transition);
        }

        .doctor-name a:hover { color: var(--primary); }

        .doctor-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .specialist-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
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
            padding: 3px 10px;
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
            margin-bottom: 20px;
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

        .sidebar-body { padding: 12px; }

        .category-list { list-style: none; padding: 0; margin: 0; }

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

        .category-item:hover { background: var(--primary-light); text-decoration: none; color: var(--primary); }

        .category-count {
            background: var(--primary);
            color: #fff;
            padding: 2px 10px;
            border-radius: 15px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--primary);
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            margin-bottom: 15px;
        }

        .back-link:hover { text-decoration: underline; }

        /* ============ EMPTY STATE ============ */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
        }

        .empty-state i { font-size: 4rem; color: #e0e0e0; margin-bottom: 15px; }
        .empty-state h5 { color: #999; font-weight: 600; }

        /* ============ RESULTS COUNT ============ */
        .results-count {
            margin-bottom: 15px;
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .results-count strong { color: var(--primary); }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 992px) {
            .sidebar-card { position: static; }
        }

        @media (max-width: 768px) {
            body { padding-top: 75px; }
            .page-header { padding: 20px 0; border-radius: 0 0 20px 20px; }
            .header-title h4 { font-size: 1.1rem; }
            .header-icon-box { width: 42px; height: 42px; font-size: 20px; border-radius: 10px; }
            .search-inline input { width: 150px; }
            .doctor-card { flex-wrap: wrap; }
            .view-profile-btn { width: 100%; justify-content: center; margin-top: 10px; }
        }
    </style>
</head>

<body>

    <?php
    $my_query = mysqli_query($conn,"SELECT * from person inner join gender on person.gender = gender.gender_id where person.nid ='$user_id'");
    $my = ($my_query) ? mysqli_fetch_array($my_query) : null;
    
    // Count search results
    $count_query = mysqli_query($conn,"SELECT COUNT(DISTINCT d.d_nid) as total FROM doctor d INNER JOIN person p ON p.nid = d.d_nid INNER JOIN dmdc dm ON dm.dmdc_id = d.dmdc_id WHERE p.name LIKE '%$search%' AND d.d_nid != '$user_id'");
    $count_data = ($count_query) ? mysqli_fetch_assoc($count_query) : null;
    $total_results = ($count_data && isset($count_data['total'])) ? $count_data['total'] : 0;
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
                        <i class="fas fa-search"></i>
                    </div>
                    <div>
                        <h4>Search Results</h4>
                        <span>Showing results for "<?php echo htmlspecialchars($search); ?>"</span>
                    </div>
                </div>
                <!-- Inline Search -->
                <form action="searchdoc.php" method="GET" class="search-inline">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search doctors...">
                    <button type="submit"><i class="fas fa-search"></i> Search</button>
                </form>
            </div>
        </div>
    </div>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="container">
        
        <!-- Back Link -->
        <a href="doctor.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to All Doctors
        </a>
        
        <div class="row">
            
            <!-- Doctor Cards Column -->
            <div class="col-lg-8">
                
                <?php if($search): ?>
                <div class="results-count">
                    Found <strong><?php echo $total_results; ?></strong> doctor<?php echo $total_results != 1 ? 's' : ''; ?> matching "<strong><?php echo htmlspecialchars($search); ?></strong>"
                </div>
                <?php endif; ?>
                
                <?php
                    // FIXED: Added table aliases to avoid ambiguous column error
                    // 'name' is now p.name (from person table)
                    $search_escaped = mysqli_real_escape_string($conn, $search);
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
                        WHERE p.name LIKE '%$search_escaped%' 
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
                        </div>
                        
                        <a href="doctorprofile.php?id=<?php echo $doctor_row['nid']; ?>" class="view-profile-btn">
                            <i class="fas fa-eye"></i> View Profile
                        </a>
                    </div>
                    <?php endwhile; ?>
                </div>
                
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <h5>No doctors found</h5>
                    <p class="text-muted">Try searching with a different name</p>
                </div>
                <?php endif; ?>
                
            </div>

            <!-- Sidebar Column -->
            <div class="col-lg-4">
                
                <!-- Categories Card -->
                <div class="sidebar-card">
                    <div class="sidebar-header">
                        <i class="fas fa-folder"></i> Specializations
                    </div>
                    <div class="sidebar-body">
                        <?php
                            // FIXED: Added table aliases
                            $cat_query = mysqli_query($conn,"SELECT dm.specialist, COUNT(DISTINCT d.d_nid) as c FROM dmdc dm INNER JOIN doctor d ON dm.dmdc_id = d.dmdc_id WHERE d.d_nid != '$user_id' GROUP BY dm.specialist ORDER BY dm.specialist");
                            if($cat_query && mysqli_num_rows($cat_query) > 0):
                        ?>
                        <ul class="category-list">
                            <?php while ($category_row = mysqli_fetch_array($cat_query)): ?>
                            <a href="doctorcategory.php?category=<?php echo urlencode($category_row['specialist']); ?>" class="category-item">
                                <span><i class="fas fa-tag mr-2" style="color:var(--primary);font-size:0.7rem;"></i> <?php echo $category_row['specialist']; ?></span>
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