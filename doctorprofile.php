<?php
include ('dbconn.php');
$c1 = 1;
$id = isset($_GET['id']) ? $_GET['id'] : $user_id;

// FIXED: Added null safety
$query = mysqli_query($conn,"SELECT * from person inner join doctor on person.nid = doctor.d_nid where person.nid ='$id'");
$docpro_row = ($query) ? mysqli_fetch_array($query) : null;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
    
    <title>Dr. <?php echo $docpro_row['name'] ?? 'Doctor'; ?> | IBHS</title>
    
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

        .navbar-nav .nav-link:hover {
            background: #f0fdf4;
            color: var(--primary) !important;
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

        .btn-logout-nav:hover { background: var(--danger) !important; color: #fff !important; }

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

        .breadcrumb-row a { color: rgba(255,255,255,0.9); text-decoration: none; }
        .breadcrumb-row a:hover { color: #fff; text-decoration: underline; }
        .breadcrumb-row span { color: #00d2ff; font-weight: 600; }

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

        .profile-card:hover { box-shadow: var(--shadow-md); }

        .profile-cover {
            background: linear-gradient(135deg, #1a1a2e, #2c3e50);
            height: 80px;
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

        .profile-card:hover .profile-avatar { transform: scale(1.03); }

        .profile-info { padding: 15px 25px 25px; }

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

        .rating-display {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .stars { color: #f39c12; font-size: 1rem; letter-spacing: 2px; }
        .rating-number { font-weight: 700; color: #f39c12; font-size: 0.95rem; }

        .profile-mobile {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: #555;
            font-size: 0.85rem;
            margin-bottom: 12px;
        }

        .profile-mobile i { color: var(--primary); }

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

        .degree-list { text-align: left; margin-top: 12px; }

        .degree-item {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 6px 0;
            font-size: 0.8rem;
            color: #555;
        }

        .degree-item i { color: var(--primary); margin-top: 2px; font-size: 0.7rem; }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-top: 15px;
        }

        .btn-action {
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.82rem;
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-rate { background: #f39c12; color: #fff; border: none; }
        .btn-rate:hover { background: #e67e22; color: #fff; transform: translateY(-2px); text-decoration: none; }
        .btn-message { background: var(--info); color: #fff; border: none; }
        .btn-message:hover { background: #2980b9; color: #fff; transform: translateY(-2px); text-decoration: none; }
        .btn-appoint { background: var(--primary); color: #fff; border: none; }
        .btn-appoint:hover { background: var(--primary-dark); color: #fff; transform: translateY(-2px); text-decoration: none; }
        .btn-appointed { background: var(--danger); color: #fff; border: none; }
        .btn-appointed:hover { background: #c0392b; color: #fff; transform: translateY(-2px); text-decoration: none; }

        /* ============ REVIEWS ============ */
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
            background: #fafafa;
        }

        .reviews-header i { color: #f39c12; margin-right: 8px; }

        .review-item { padding: 16px 20px; border-bottom: 1px solid #f8f8f8; }
        .review-item:last-child { border-bottom: none; }

        .review-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .review-author { font-weight: 600; font-size: 0.85rem; }
        .review-author.me { color: var(--primary); }
        .review-rating { font-size: 0.8rem; color: #f39c12; font-weight: 600; }
        .review-text { font-size: 0.82rem; color: #666; line-height: 1.5; }

        .no-reviews { text-align: center; padding: 25px; color: #bbb; }
        .no-reviews i { font-size: 2rem; display: block; margin-bottom: 10px; }

        /* ============ CHAMBER TABLE ============ */
        .chamber-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            overflow: hidden;
            margin-bottom: 25px;
            transition: var(--transition);
        }

        .chamber-card:hover { box-shadow: var(--shadow-md); }

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

        .chamber-header h5 { margin: 0; font-weight: 600; font-size: 1rem; }

        .btn-add-chamber {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.8rem;
            float: right;
            margin: 15px 20px 20px;
            transition: var(--transition);
        }

        .btn-add-chamber:hover { background: var(--primary-dark); transform: translateY(-2px); }

        .custom-table { width: 100%; margin: 0; }
        .custom-table thead th {
            background: #fafafa;
            border: none;
            border-bottom: 2px solid #eee;
            padding: 14px 20px;
            font-weight: 600;
            color: #555;
            font-size: 0.8rem;
            text-transform: uppercase;
        }
        .custom-table tbody td { padding: 14px 20px; border-bottom: 1px solid #f5f5f5; font-size: 0.85rem; }
        .custom-table tbody tr:hover { background: #f8fdf9; }

        .btn-edit-sm {
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
            background: #e3f2fd;
            color: #1565c0;
            border: none;
            transition: var(--transition);
        }

        .btn-edit-sm:hover { background: #1565c0; color: #fff; }

        .btn-delete-sm {
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
            background: #ffebee;
            color: #c62828;
            border: none;
            transition: var(--transition);
            text-decoration: none;
        }

        .btn-delete-sm:hover { background: #c62828; color: #fff; }

        /* ============ LIST CARDS ============ */
        .list-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            overflow: hidden;
            height: 100%;
            transition: var(--transition);
        }

        .list-card:hover { box-shadow: var(--shadow-md); }

        .list-header {
            padding: 18px 25px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .list-header .header-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
        }

        .icon-green { background: linear-gradient(135deg, #009B46, #007a38); }
        .icon-purple { background: linear-gradient(135deg, #9b59b6, #8e44ad); }

        .list-header h5 { margin: 0; font-weight: 600; font-size: 1rem; }

        .list-body { padding: 10px; max-height: 400px; overflow-y: auto; }

        .list-body::-webkit-scrollbar { width: 4px; }
        .list-body::-webkit-scrollbar-track { background: transparent; }
        .list-body::-webkit-scrollbar-thumb { background: #ddd; border-radius: 10px; }

        .list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 13px 16px;
            border-radius: 10px;
            transition: var(--transition);
            margin-bottom: 3px;
        }

        .list-item:hover { background: #f8fdf9; }

        .list-item-left { display: flex; align-items: center; gap: 14px; }

        .list-num {
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

        .list-name {
            font-weight: 500;
            color: var(--text-dark);
            font-size: 0.9rem;
            text-decoration: none;
            transition: var(--transition);
        }

        .list-name:hover { color: var(--primary); text-decoration: none; }

        .list-sub { font-size: 0.75rem; color: #999; }

        .list-arrow { color: #ccc; font-size: 0.8rem; transition: var(--transition); }
        .list-item:hover .list-arrow { color: var(--primary); transform: translateX(4px); }

        .no-data { text-align: center; padding: 30px; color: #bbb; }
        .no-data i { font-size: 2.5rem; display: block; margin-bottom: 10px; }

        /* ============ MODAL ============ */
        .modal-content { border: none; border-radius: var(--radius); overflow: hidden; }
        .modal-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            padding: 16px 22px;
        }
        .modal-header .close { color: #fff; opacity: 1; text-shadow: none; }
        .modal-title { font-weight: 600; font-size: 1rem; }
        .modal-body { padding: 25px; }
        .modal-body .form-control {
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            padding: 10px 15px;
            font-size: 0.85rem;
            font-family: 'Poppins', sans-serif;
        }
        .modal-body .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 0.2rem rgba(0,155,70,0.1); }
        .modal-footer { border-top: 1px solid #f0f0f0; padding: 16px 22px; }
        .btn-modal-primary {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
        }
        .btn-modal-secondary {
            background: #e8e8e8;
            color: #555;
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            body { padding-top: 75px; }
            .page-header { padding: 18px 0; border-radius: 0 0 20px 20px; }
            .profile-avatar { width: 85px; height: 85px; }
            .profile-cover { height: 60px; }
            .profile-avatar-wrapper { margin-top: -40px; }
            .custom-table thead { display: none; }
            .custom-table tbody td { display: block; text-align: right; padding: 10px 15px; }
            .custom-table tbody td::before { content: attr(data-label); float: left; font-weight: 600; color: #555; }
        }
    </style>
</head>

<body>

    <?php
    // FIXED: Null-safe user query
    $my_query = mysqli_query($conn,"SELECT * from person inner join gender on person.gender = gender.gender_id where person.nid ='$user_id'");
    $my = ($my_query) ? mysqli_fetch_array($my_query) : null;
    
    // FIXED: Null-safe rating query
    $rating_query = mysqli_query($conn,"SELECT ROUND(AVG(rating),1) as rating FROM reviewfordoctor where d_nid = '$id' group by d_nid");
    $docrat = ($rating_query) ? mysqli_fetch_array($rating_query) : null;
    $avg_rating = ($docrat && isset($docrat['rating'])) ? $docrat['rating'] : 0.0;
    $full_stars = floor($avg_rating);
    $half_star = ($avg_rating - $full_stars) >= 0.5 ? 1 : 0;
    $empty_stars = 5 - $full_stars - $half_star;
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
                        <a class="nav-link btn-logout-nav" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ============ PAGE HEADER ============ -->
    <div class="page-header">
        <div class="container">
            <div class="breadcrumb-row">
                <a href="doctor.php"><i class="fas fa-user-md"></i> Doctors</a>
                <i class="fas fa-chevron-right" style="font-size:0.7rem;"></i>
                <span><i class="fas fa-stethoscope"></i><?php echo $docpro_row['name'] ?? 'Unknown'; ?></span>
            </div>
        </div>
    </div>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="container">
        <div class="main-body">
            <div class="row gutters-sm">
                
                <!-- LEFT COLUMN -->
                <div class="col-md-4 mb-4">
                    <div class="profile-card">
                        <div class="profile-cover"></div>
                        <div class="profile-avatar-wrapper">
                            <img src="img/<?php echo $docpro_row['image'] ?? 'doctor1.jpg'; ?>" 
                                 alt="Doctor" class="profile-avatar"
                                 onerror="this.src='img/doctor1.jpg'">
                        </div>
                        <div class="profile-info">
                            <h5 class="profile-name"><?php echo $docpro_row['name'] ?? 'Unknown'; ?></h5>
                            <span class="profile-role"><i class="fas fa-user-md mr-1"></i> Doctor</span>
                            
                            <div class="rating-display">
                                <div class="stars">
                                    <?php for($i=0; $i<$full_stars; $i++): ?><i class="fas fa-star"></i><?php endfor; ?>
                                    <?php if($half_star): ?><i class="fas fa-star-half-alt"></i><?php endif; ?>
                                    <?php for($i=0; $i<$empty_stars; $i++): ?><i class="far fa-star"></i><?php endfor; ?>
                                </div>
                                <span class="rating-number"><?php echo $avg_rating; ?></span>
                            </div>
                            
                            <div class="profile-mobile">
                                <i class="fas fa-phone-alt"></i> <?php echo $docpro_row['mobile_no'] ?? 'N/A'; ?>
                            </div>
                            
                            <?php
                                $spec_query = mysqli_query($conn,"select * from doctor inner join dmdc on doctor.dmdc_id = dmdc.dmdc_id inner join doctordegree on doctor.dmdc_id = doctordegree.dmdc_id where d_nid = '$id'");
                                $specialists = [];
                                $degrees = [];
                                if($spec_query) {
                                    while($dmdc = mysqli_fetch_array($spec_query)){
                                        if(!in_array($dmdc['specialist'], $specialists)){
                                            $specialists[] = $dmdc['specialist'];
                                        }
                                        $degrees[] = $dmdc;
                                    }
                                }
                            ?>
                            
                            <div style="margin-bottom:10px;">
                                <?php foreach($specialists as $spec): ?>
                                    <span class="specialist-tag"><?php echo $spec; ?></span>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="degree-list">
                                <?php 
                                    $shown = [];
                                    foreach($degrees as $deg):
                                    $key = $deg['degree_name'].$deg['institute_name'];
                                    if(!in_array($key, $shown)):
                                        $shown[] = $key;
                                ?>
                                <div class="degree-item">
                                    <i class="fas fa-graduation-cap"></i>
                                    <span><?php echo $deg['degree_name']; ?> (<?php echo $deg['institute_name']; ?>)</span>
                                </div>
                                <?php endif; endforeach; ?>
                            </div>
                            
                            <?php if($user_id != $id && $user_type != 'Doctor'): ?>
                            <div class="action-buttons">
                                <?php 
                                    $rev_query = mysqli_query($conn,"SELECT * from reviewfordoctor where p_nid = '$user_id' and d_nid = '$id'");
                                    $rev = ($rev_query) ? mysqli_fetch_array($rev_query) : null;
                                    $pres_query = mysqli_query($conn,"SELECT * from prescription where p_nid = '$user_id' and d_nid = '$id'");
                                    $check = ($pres_query) ? mysqli_fetch_array($pres_query) : null;
                                    if(!$rev && $check):
                                ?>
                                <button type="button" class="btn-action btn-rate" data-toggle="modal" data-target="#ratingmodel">
                                    <i class="fas fa-star"></i> Rate
                                </button>
                                <?php endif; ?>
                                <a href="message.php?id=<?php echo $id; ?>" class="btn-action btn-message">
                                    <i class="fas fa-envelope"></i> Message
                                </a>
                                <?php 
                                    $app_query = mysqli_query($conn,"SELECT * from appointment where p_nid = '$user_id' and d_nid = '$id'");
                                    $check3 = ($app_query) ? mysqli_fetch_array($app_query) : null;
                                    if(!$check3 || ($check3 && $check3['appointment'] == 'N/A')):
                                ?>
                                <a href="appointment.php?dnid=<?php echo $id; ?>&pnid=<?php echo $user_id; ?>" class="btn-action btn-appoint">
                                    <i class="fas fa-calendar-check"></i> Appointment
                                </a>
                                <?php else: ?>
                                <a href="appointmentundo.php?dnid=<?php echo $id; ?>&pnid=<?php echo $user_id; ?>" class="btn-action btn-appointed">
                                    <i class="fas fa-calendar-times"></i> Appointmented
                                </a>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Reviews -->
                    <div class="reviews-card">
                        <div class="reviews-header"><i class="fas fa-star"></i> Patient Reviews</div>
                        <?php
                            $rev_list_query = mysqli_query($conn,"SELECT * FROM reviewfordoctor inner join person on reviewfordoctor.p_nid = person.nid where d_nid = '$id' order by FIELD(p_nid,'$user_id') DESC");
                            if($rev_list_query && mysqli_num_rows($rev_list_query) > 0):
                                while($review_row = mysqli_fetch_array($rev_list_query)):
                        ?>
                        <div class="review-item">
                            <div class="review-header">
                                <span class="review-author <?php echo ($review_row['p_nid']==$user_id) ? 'me' : ''; ?>">
                                    <?php echo ($review_row['p_nid']==$user_id) ? '🙋 Me' : $review_row['name']; ?>
                                </span>
                                <span class="review-rating"><?php echo $review_row['rating']; ?> <i class="fas fa-star"></i></span>
                            </div>
                            <p class="review-text"><?php echo $review_row['review']; ?></p>
                        </div>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <div class="no-reviews"><i class="far fa-comment-dots"></i><small>No reviews yet</small></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div class="col-md-8">
                    
                    <!-- Chamber Table -->
                    <div class="chamber-card">
                        <div class="chamber-header">
                            <div class="header-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <h5>Chamber Information</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>Location</th><th>Start Time</th><th>End Time</th><th>Day</th>
                                        <?php if($user_id == $id): ?><th>Edit</th><th>Delete</th><?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $chamber_query = mysqli_query($conn,"SELECT * from doctorchamber where d_nid = '$id' order by location_ DESC");
                                        if($chamber_query && mysqli_num_rows($chamber_query) > 0):
                                            while($docchamber_row = mysqli_fetch_array($chamber_query)):
                                    ?>
                                    <tr>
                                        <td data-label="Location"><?php echo $docchamber_row['location_']; ?></td>
                                        <td data-label="Start Time"><?php echo $docchamber_row['start_time']; ?></td>
                                        <td data-label="End Time"><?php echo $docchamber_row['end_time']; ?></td>
                                        <td data-label="Day"><?php echo $docchamber_row['day']; ?></td>
                                        <?php if($user_id == $id): ?>
                                        <td data-label="Edit">
                                            <button class="btn-edit-sm editbtn"><i class="fas fa-edit"></i> Edit</button>
                                        </td>
                                        <td data-label="Delete">
                                            <a href="chamberdelete.php?location=<?php echo urlencode($docchamber_row['location_']); ?>&start_time=<?php echo urlencode($docchamber_row['start_time']); ?>&end_time=<?php echo urlencode($docchamber_row['end_time']); ?>&day=<?php echo urlencode($docchamber_row['day']); ?>" 
                                               class="btn-delete-sm" onclick="return confirm('Delete this chamber?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endwhile; ?>
                                    <?php else: ?>
                                    <tr><td colspan="<?php echo ($user_id == $id) ? '6' : '4'; ?>" class="no-data"><i class="fas fa-info-circle"></i> No chamber info</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if($user_id == $id): ?>
                        <button type="button" class="btn-add-chamber" data-toggle="modal" data-target="#addChamberModal">
                            <i class="fas fa-plus-circle mr-1"></i> Add Chamber
                        </button>
                        <?php endif; ?>
                    </div>

                    <!-- Lists Row -->
                    <div class="row gutters-sm">
                        <!-- Appointments -->
                        <div class="col-sm-6 mb-3">
                            <div class="list-card">
                                <div class="list-header">
                                    <div class="header-icon icon-green"><i class="fas fa-calendar-check"></i></div>
                                    <h5>Appointments</h5>
                                </div>
                                <div class="list-body">
                                    <?php 
                                        $appt_query = mysqli_query($conn,"SELECT * from appointment where d_nid = '$id' order by date ASC");
                                        if($appt_query && mysqli_num_rows($appt_query) > 0):
                                            $c = 1;
                                            while($appointment = mysqli_fetch_array($appt_query)):
                                                $nid = $appointment['p_nid'];
                                                $pat_query = mysqli_query($conn,"SELECT name, TIMESTAMPDIFF(YEAR, dob, CURDATE()) AS age from person where nid = '$nid'");
                                                $patientapp_row = ($pat_query) ? mysqli_fetch_array($pat_query) : null;
                                    ?>
                                    <div class="list-item">
                                        <div class="list-item-left">
                                            <div class="list-num"><?php echo $c; ?></div>
                                            <div>
                                                <?php if($user_id == $id): ?>
                                                <a href="docseepatient.php?id=<?php echo $nid; ?>&a=m" class="list-name"><?php echo $patientapp_row['name'] ?? 'Unknown'; ?></a>
                                                <?php else: ?>
                                                <a href="myprofile.php?id=<?php echo $nid; ?>" class="list-name"><?php echo $patientapp_row['name'] ?? 'Unknown'; ?></a>
                                                <?php endif; ?>
                                                <div class="list-sub">Age: <?php echo $patientapp_row['age'] ?? 'N/A'; ?></div>
                                            </div>
                                        </div>
                                        <i class="fas fa-chevron-right list-arrow"></i>
                                    </div>
                                    <?php $c++; endwhile; ?>
                                    <?php else: ?>
                                    <div class="no-data"><i class="fas fa-calendar-xmark"></i><small>No appointments</small></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Working Hospitals -->
                        <div class="col-sm-6 mb-3">
                            <div class="list-card">
                                <div class="list-header">
                                    <div class="header-icon icon-purple"><i class="fas fa-hospital"></i></div>
                                    <h5>Working Hospitals</h5>
                                </div>
                                <div class="list-body">
                                    <?php 
                                        $hos_query = mysqli_query($conn,"SELECT * from doctorworking where d_nid = '$id' order by joined_date ASC");
                                        if($hos_query && mysqli_num_rows($hos_query) > 0):
                                            $c = 1;
                                            while($doclist = mysqli_fetch_array($hos_query)):
                                                $hosid = $doclist['hospital_id'];
                                                $hos_name_query = mysqli_query($conn,"SELECT hospital_name from hospital where hospital_id = '$hosid'");
                                                $name = ($hos_name_query) ? mysqli_fetch_array($hos_name_query) : null;
                                    ?>
                                    <div class="list-item">
                                        <div class="list-item-left">
                                            <div class="list-num"><?php echo $c; ?></div>
                                            <a href="hospitalprofile.php?id=<?php echo $hosid; ?>" class="list-name"><?php echo $name['hospital_name'] ?? 'Unknown'; ?></a>
                                        </div>
                                        <i class="fas fa-chevron-right list-arrow"></i>
                                    </div>
                                    <?php $c++; endwhile; ?>
                                    <?php else: ?>
                                    <div class="no-data"><i class="fas fa-hospital"></i><small>No hospitals</small></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ MODALS ============ -->
    <!-- Add Chamber Modal -->
    <div class="modal fade" id="addChamberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i> Add Chamber</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="chamberinsert.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group"><label>Location</label><input type="text" name="location" class="form-control" required></div>
                        <div class="form-group"><label>Start Time</label><input type="text" name="start" placeholder="hh:mm:ss" class="form-control" required></div>
                        <div class="form-group"><label>End Time</label><input type="text" name="end" placeholder="hh:mm:ss" class="form-control" required></div>
                        <div class="form-group"><label>Day</label><input type="text" name="day" class="form-control" required></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="insertchamber" class="btn-modal-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Chamber Modal -->
    <div class="modal fade" id="editChamberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit mr-2"></i> Update Chamber</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="chamberupdate.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="location" id="editLocationOld">
                        <input type="hidden" name="start" id="editStartOld">
                        <input type="hidden" name="end" id="editEndOld">
                        <input type="hidden" name="day" id="editDayOld">
                        <div class="form-group"><label>Location</label><input type="text" name="updatelocation" id="editLocation" class="form-control"></div>
                        <div class="form-group"><label>Start Time</label><input type="text" name="updatestart" id="editStart" class="form-control"></div>
                        <div class="form-group"><label>End Time</label><input type="text" name="updateend" id="editEnd" class="form-control"></div>
                        <div class="form-group"><label>Day</label><input type="text" name="updatedate" id="editDay" class="form-control"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="updatecham" class="btn-modal-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rating Modal -->
    <div class="modal fade" id="ratingmodel" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-star mr-2"></i> Rate Doctor</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="rating.php" method="POST">
                    <input type="hidden" name="dnid" value="<?php echo $id; ?>">
                    <div class="modal-body text-center">
                        <div class="rateyo mb-3" id="rating" data-rateyo-rating="4" data-rateyo-num-stars="5" data-rateyo-score="3"></div>
                        <input type="hidden" name="rating" id="ratingValue">
                        <div class="form-group">
                            <label>Your Review</label>
                            <textarea class="form-control" name="review" rows="4" required placeholder="Write your review..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="sendrate" class="btn-modal-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============ SCRIPTS ============ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>

    <script>
        $(document).ready(function () {
            // Edit Chamber Modal
            $('.editbtn').on('click', function () {
                $('#editChamberModal').modal('show');
                var $tr = $(this).closest('tr');
                var data = $tr.children("td").map(function () {
                    return $(this).text().trim();
                }).get();
                
                $('#editLocation').val(data[0]);
                $('#editStart').val(data[1]);
                $('#editEnd').val(data[2]);
                $('#editDay').val(data[3]);
                $('#editLocationOld').val(data[0]);
                $('#editStartOld').val(data[1]);
                $('#editEndOld').val(data[2]);
                $('#editDayOld').val(data[3]);
            });

            // Rating Star
            $("#rating").rateYo({
                rating: 4,
                numStars: 5,
                fullStar: true,
                onSet: function (rating) {
                    $('#ratingValue').val(rating);
                }
            });
        });
    </script>
</body>
</html>