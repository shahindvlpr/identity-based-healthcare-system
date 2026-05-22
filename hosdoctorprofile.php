<?php
include ('dbconn.php');
$c1 = 1;
$id=$_GET['id'];
$query = mysqli_query($conn,"SELECT * from person inner join doctor on person.nid = doctor.d_nid where person.nid ='$id'");
$docpro_row=mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Doctor Profile - IBHS">
    <meta name="author" content="">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title>Doctor Profile | IBHS</title>
    
    <?php include('dbconn.php'); ?>
    <?php include('session.php'); ?>

    <link href="css/blog-home.css" rel="stylesheet">
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
            padding-top: 80px;
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
            background: linear-gradient(135deg, #1a1a2e, #2c3e50);
            padding: 25px 0;
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

        .breadcrumb-row {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.9);
            font-size: 0.85rem;
            position: relative;
            z-index: 1;
        }

        .breadcrumb-row a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
        }

        .breadcrumb-row a:hover {
            color: #fff;
            text-decoration: underline;
        }

        .breadcrumb-row span {
            color: #00d2ff;
            font-weight: 600;
        }

        /* ============ PROFILE CARD ============ */
        .profile-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            overflow: hidden;
            text-align: center;
            transition: var(--transition);
        }

        .profile-card:hover {
            box-shadow: var(--shadow-md);
        }

        .profile-cover {
            background: linear-gradient(135deg, #1a1a2e, #2c3e50);
            height: 80px;
            position: relative;
        }

        .profile-avatar-wrapper {
            margin-top: -50px;
            position: relative;
            z-index: 2;
        }

        .profile-avatar {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            border: 5px solid #fff;
            object-fit: cover;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
        }

        .profile-card:hover .profile-avatar {
            transform: scale(1.03);
        }

        .profile-info {
            padding: 15px 25px 25px;
        }

        .profile-name {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--text-dark);
            margin-bottom: 2px;
        }

        .profile-role {
            color: var(--primary);
            font-weight: 600;
            font-size: 0.78rem;
            margin-bottom: 8px;
            display: inline-block;
            background: var(--primary-light);
            padding: 4px 14px;
            border-radius: 20px;
        }

        /* Rating Stars */
        .rating-display {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .stars {
            color: #f39c12;
            font-size: 1rem;
            letter-spacing: 2px;
        }

        .rating-number {
            font-weight: 700;
            color: #f39c12;
            font-size: 0.95rem;
        }

        .rating-count {
            font-size: 0.75rem;
            color: #999;
        }

        .profile-mobile {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: #555;
            font-size: 0.85rem;
            margin-bottom: 12px;
        }

        .profile-mobile i {
            color: var(--primary);
        }

        /* Specialization Tags */
        .specialist-tag {
            display: inline-block;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
            margin: 3px;
        }

        .degree-list {
            text-align: left;
            margin-top: 12px;
        }

        .degree-item {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 6px 0;
            font-size: 0.8rem;
            color: #555;
        }

        .degree-item i {
            color: var(--primary);
            margin-top: 2px;
            font-size: 0.7rem;
        }

        .profile-divider {
            border: none;
            border-top: 1px solid #f0f0f0;
            margin: 15px 0;
        }

        /* ============ REVIEWS CARD ============ */
        .reviews-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            margin-top: 20px;
            overflow: hidden;
        }

        .reviews-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            background: #fafafa;
        }

        .reviews-header i {
            color: #f39c12;
        }

        .review-item {
            padding: 16px 20px;
            border-bottom: 1px solid #f8f8f8;
        }

        .review-item:last-child {
            border-bottom: none;
        }

        .review-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .review-author {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-dark);
        }

        .review-author.me {
            color: var(--primary);
        }

        .review-rating {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.8rem;
            color: #f39c12;
            font-weight: 600;
        }

        .review-text {
            font-size: 0.82rem;
            color: #666;
            line-height: 1.5;
        }

        .no-reviews {
            text-align: center;
            padding: 25px;
            color: #bbb;
        }

        .no-reviews i {
            font-size: 2rem;
            display: block;
            margin-bottom: 10px;
        }

        /* ============ CHAMBER TABLE CARD ============ */
        .chamber-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            overflow: hidden;
            margin-bottom: 25px;
            transition: var(--transition);
        }

        .chamber-card:hover {
            box-shadow: var(--shadow-md);
        }

        .chamber-header {
            padding: 18px 25px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chamber-header .header-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
        }

        .chamber-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1rem;
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
            font-size: 0.85rem;
        }

        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }

        .custom-table tbody tr:hover {
            background: #f8fdf9;
        }

        .location-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e3f2fd;
            color: #1565c0;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.8rem;
        }

        .time-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0fdf4;
            color: #009B46;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.8rem;
        }

        .day-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #fff8e1;
            color: #f39c12;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.8rem;
        }

        /* ============ APPOINTMENTS CARD ============ */
        .appointments-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            overflow: hidden;
            height: 100%;
            transition: var(--transition);
        }

        .appointments-card:hover {
            box-shadow: var(--shadow-md);
        }

        .appointments-header {
            padding: 18px 25px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .appointments-header .header-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #009B46, #007a38);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
        }

        .appointments-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1rem;
        }

        .appointments-body {
            padding: 10px;
            max-height: 400px;
            overflow-y: auto;
        }

        .appointments-body::-webkit-scrollbar {
            width: 4px;
        }

        .appointments-body::-webkit-scrollbar-track {
            background: transparent;
        }

        .appointments-body::-webkit-scrollbar-thumb {
            background: #ddd;
            border-radius: 10px;
        }

        .appointment-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 13px 16px;
            border-radius: 10px;
            transition: var(--transition);
            margin-bottom: 3px;
        }

        .appointment-item:hover {
            background: #f8fdf9;
        }

        .appointment-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .appointment-num {
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

        .appointment-name {
            font-weight: 500;
            color: var(--text-dark);
            font-size: 0.9rem;
            text-decoration: none;
            transition: var(--transition);
        }

        .appointment-name:hover {
            color: var(--primary);
            text-decoration: none;
        }

        .appointment-age {
            font-size: 0.75rem;
            color: #999;
        }

        .appointment-arrow {
            color: #ccc;
            font-size: 0.8rem;
            transition: var(--transition);
        }

        .appointment-item:hover .appointment-arrow {
            color: var(--primary);
            transform: translateX(4px);
        }

        .no-appointments {
            text-align: center;
            padding: 30px;
            color: #bbb;
        }

        .no-appointments i {
            font-size: 2.5rem;
            display: block;
            margin-bottom: 10px;
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }
            
            .page-header {
                padding: 18px 0;
                border-radius: 0 0 20px 20px;
            }
            
            .profile-avatar {
                width: 85px;
                height: 85px;
            }
            
            .profile-cover {
                height: 60px;
            }
            
            .profile-avatar-wrapper {
                margin-top: -40px;
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
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>

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
            <div class="breadcrumb-row">
                <a href="hospitaldoctor.php"><i class="fas fa-user-md"></i> Doctors</a>
                <i class="fas fa-chevron-right" style="font-size:0.7rem;"></i>
                <span><i class="fas fa-stethoscope"></i> Dr. <?php echo $docpro_row['name']; ?></span>
            </div>
        </div>
    </div>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="container">
        <div class="main-body">
            <div class="row gutters-sm">
                
                <!-- LEFT COLUMN - Profile & Reviews -->
                <div class="col-md-4 mb-4">
                    
                    <!-- Profile Card -->
                    <div class="profile-card">
                        <div class="profile-cover"></div>
                        <div class="profile-avatar-wrapper">
                            <img src="img/<?php echo $docpro_row['image']; ?>" 
                                 alt="Doctor Photo" class="profile-avatar"
                                 onerror="this.src='img/doctor1.jpg'">
                        </div>
                        <div class="profile-info">
                            <h5 class="profile-name">Dr. <?php echo $docpro_row['name']; ?></h5>
                            <span class="profile-role">
                                <i class="fas fa-user-md mr-1"></i> Doctor
                            </span>
                            
                            <!-- Rating -->
                            <?php
                                $query = mysqli_query($conn,"SELECT ROUND(AVG(rating),1) as rating FROM reviewfordoctor where d_nid = '$id' group by d_nid");
                                $docrat = mysqli_fetch_array($query);
                                $avg_rating = ($docrat != null) ? $docrat['rating'] : 0.0;
                                $full_stars = floor($avg_rating);
                                $half_star = ($avg_rating - $full_stars) >= 0.5 ? 1 : 0;
                                $empty_stars = 5 - $full_stars - $half_star;
                            ?>
                            <div class="rating-display">
                                <div class="stars">
                                    <?php for($i=0; $i<$full_stars; $i++): ?><i class="fas fa-star"></i><?php endfor; ?>
                                    <?php if($half_star): ?><i class="fas fa-star-half-alt"></i><?php endif; ?>
                                    <?php for($i=0; $i<$empty_stars; $i++): ?><i class="far fa-star"></i><?php endfor; ?>
                                </div>
                                <span class="rating-number"><?php echo $avg_rating; ?></span>
                            </div>
                            
                            <!-- Mobile -->
                            <div class="profile-mobile">
                                <i class="fas fa-phone-alt"></i> <?php echo $docpro_row['mobile_no']; ?>
                            </div>
                            
                            <hr class="profile-divider">
                            
                            <!-- Specialization & Degrees -->
                            <?php
                                $query = mysqli_query($conn,"select * from doctor inner join dmdc on doctor.dmdc_id = dmdc.dmdc_id inner join doctordegree on doctor.dmdc_id = doctordegree.dmdc_id where d_nid = '$id'");
                                $specialist = "";
                                $specialists = [];
                                $degrees = [];
                                while($dmdc = mysqli_fetch_array($query)){
                                    if(!in_array($dmdc['specialist'], $specialists)){
                                        $specialists[] = $dmdc['specialist'];
                                    }
                                    $degrees[] = $dmdc;
                                }
                            ?>
                            
                            <div class="mb-2">
                                <?php foreach($specialists as $spec): ?>
                                    <span class="specialist-tag"><?php echo $spec; ?></span>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="degree-list">
                                <?php 
                                    $shown_degrees = [];
                                    foreach($degrees as $deg):
                                    $degree_key = $deg['degree_name'].$deg['institute_name'];
                                    if(!in_array($degree_key, $shown_degrees)):
                                        $shown_degrees[] = $degree_key;
                                ?>
                                <div class="degree-item">
                                    <i class="fas fa-graduation-cap"></i>
                                    <span><?php echo $deg['degree_name']; ?> (<?php echo $deg['institute_name']; ?>)</span>
                                </div>
                                <?php endif; endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews Card -->
                    <div class="reviews-card">
                        <div class="reviews-header">
                            <i class="fas fa-star"></i> Patient Reviews
                        </div>
                        <?php
                            $query = mysqli_query($conn,"SELECT * FROM reviewfordoctor inner join person on reviewfordoctor.p_nid = person.nid where d_nid = '$id' order by FIELD(p_nid,'$user_id') DESC");
                            if(mysqli_num_rows($query) > 0):
                                while($review_row = mysqli_fetch_array($query)):
                        ?>
                        <div class="review-item">
                            <div class="review-header">
                                <span class="review-author <?php echo ($review_row['p_nid']==$user_id) ? 'me' : ''; ?>">
                                    <?php echo ($review_row['p_nid']==$user_id) ? '🙋 Me' : $review_row['name']; ?>
                                </span>
                                <span class="review-rating">
                                    <?php echo $review_row['rating']; ?> <i class="fas fa-star"></i>
                                </span>
                            </div>
                            <p class="review-text"><?php echo $review_row['review']; ?></p>
                        </div>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <div class="no-reviews">
                            <i class="far fa-comment-dots"></i>
                            <small>No reviews yet</small>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div class="col-md-8">
                    
                    <!-- Chamber Information -->
                    <div class="chamber-card">
                        <div class="chamber-header">
                            <div class="header-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h5>Chamber Information</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-location-dot mr-1"></i> Location</th>
                                        <th><i class="fas fa-clock mr-1"></i> Start Time</th>
                                        <th><i class="fas fa-clock mr-1"></i> End Time</th>
                                        <th><i class="fas fa-calendar-day mr-1"></i> Day</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $query = mysqli_query($conn,"SELECT * from doctorchamber where d_nid = '$id' order by location_ DESC");
                                        if(mysqli_num_rows($query) > 0):
                                            while($docchamber_row = mysqli_fetch_array($query)):
                                    ?>
                                    <tr>
                                        <td data-label="Location">
                                            <span class="location-badge">
                                                <i class="fas fa-map-pin"></i> <?php echo $docchamber_row['location_']; ?>
                                            </span>
                                        </td>
                                        <td data-label="Start Time">
                                            <span class="time-badge">
                                                <i class="far fa-clock"></i> <?php echo $docchamber_row['start_time']; ?>
                                            </span>
                                        </td>
                                        <td data-label="End Time">
                                            <span class="time-badge">
                                                <i class="far fa-clock"></i> <?php echo $docchamber_row['end_time']; ?>
                                            </span>
                                        </td>
                                        <td data-label="Day">
                                            <span class="day-badge">
                                                <i class="fas fa-calendar-check"></i> <?php echo $docchamber_row['day']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="4" style="text-align:center;padding:30px;color:#bbb;">
                                            <i class="fas fa-info-circle"></i> No chamber information available
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Appointments List -->
                    <div class="appointments-card">
                        <div class="appointments-header">
                            <div class="header-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h5>Appointed Patients</h5>
                        </div>
                        <div class="appointments-body">
                            <?php 
                                $queryp = mysqli_query($conn,"SELECT * from appointment where d_nid = '$id' order by date ASC");
                                if(mysqli_num_rows($queryp) > 0):
                                    while($appointment = mysqli_fetch_array($queryp)):
                                        $nid = $appointment['p_nid'];
                                        $patientapp = mysqli_query($conn,"SELECT name, TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age from person where nid = '$nid'");
                                        $patientapp_row = mysqli_fetch_array($patientapp);
                            ?>
                            <div class="appointment-item">
                                <div class="appointment-left">
                                    <div class="appointment-num"><?php echo $c1; ?></div>
                                    <div>
                                        <?php if($user_id == $id): ?>
                                        <a href="docseepatient.php?id=<?php echo $nid; ?>&a=0" class="appointment-name">
                                            <?php echo $patientapp_row['name']; ?>
                                        </a>
                                        <?php else: ?>
                                        <a href="myprofile.php?id=<?php echo $nid; ?>" class="appointment-name">
                                            <?php echo $patientapp_row['name']; ?>
                                        </a>
                                        <?php endif; ?>
                                        <div class="appointment-age">Age: <?php echo $patientapp_row['age']; ?></div>
                                    </div>
                                </div>
                                <i class="fas fa-chevron-right appointment-arrow"></i>
                            </div>
                            <?php $c1++; endwhile; ?>
                            <?php else: ?>
                            <div class="no-appointments">
                                <i class="fas fa-calendar-xmark"></i>
                                <small>No appointments yet</small>
                            </div>
                            <?php endif; ?>
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