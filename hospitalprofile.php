<?php include('dbconn.php'); ?>
<?php include('session.php'); ?>
<?php
    $c1 = 1;
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    
    // FIXED: Null-safe queries
    $query1 = mysqli_query($conn,"SELECT * FROM hospital where hospital_id = '$id'");
    $hospital_row = ($query1) ? mysqli_fetch_array($query1) : null;
    
    $query2 = mysqli_query($conn,"SELECT * from hospital where docreg = '0' and hospital_id = '$id'");
    $check = ($query2) ? mysqli_fetch_array($query2) : null;
    
    $query3 = mysqli_query($conn,"SELECT dmdc_id from doctor where d_nid ='$user_id'");
    $dmdc = ($query3) ? mysqli_fetch_array($query3) : null;
    
    $query4 = mysqli_query($conn,"SELECT * from docregistry where d_nid ='$user_id' and hospital_id = '$id'");
    $hoscheck = ($query4) ? mysqli_fetch_array($query4) : null;
    
    $query5 = mysqli_query($conn,"SELECT * from doctorworking where d_nid ='$user_id' and hospital_id = '$id'");
    $workingcheck = ($query5) ? mysqli_fetch_array($query5) : null;
    
    // Count doctors in this hospital
    $doc_count_query = mysqli_query($conn,"SELECT COUNT(*) as total FROM doctorworking where hospital_id = '$id'");
    $doc_count_data = ($doc_count_query) ? mysqli_fetch_assoc($doc_count_query) : null;
    $total_doctors = ($doc_count_data && isset($doc_count_data['total'])) ? $doc_count_data['total'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Hospital Profile - IBHS">
    <meta name="author" content="">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title><?php echo $hospital_row['hospital_name'] ?? 'Hospital'; ?> | IBHS</title>
    
    <?php include('dbconn.php'); ?>

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
            --danger-light: #ffebee;
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

        /* ============ RECRUITMENT BANNER ============ */
        .recruitment-banner {
            background: linear-gradient(135deg, #fff8e1, #fff3cd);
            border: 1px solid #ffc107;
            border-radius: var(--radius);
            padding: 14px 20px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .recruitment-banner marquee {
            flex: 1;
            font-weight: 500;
            color: #856404;
            font-size: 0.9rem;
        }

        .btn-apply {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: var(--transition);
            text-decoration: none;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.4);
            color: #fff;
            text-decoration: none;
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

        .profile-card:hover { box-shadow: var(--shadow-md); }

        .profile-cover {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            height: 70px;
        }

        .profile-avatar-wrapper {
            margin-top: -45px;
            position: relative;
            z-index: 2;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 5px solid #fff;
            object-fit: cover;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            background: #fff;
            padding: 8px;
        }

        .profile-card:hover .profile-avatar { transform: scale(1.03); }

        .profile-info { padding: 12px 25px 22px; }

        .profile-name {
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--text-dark);
            margin-bottom: 2px;
        }

        .profile-id {
            color: var(--text-light);
            font-size: 0.78rem;
            margin-bottom: 8px;
        }

        .stat-row {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 12px;
        }

        .stat-mini {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 10px 15px;
            text-align: center;
            flex: 1;
        }

        .stat-mini .val {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary);
        }

        .stat-mini .lbl {
            font-size: 0.65rem;
            color: var(--text-light);
        }

        /* ============ DETAILS CARD ============ */
        .details-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            overflow: hidden;
            transition: var(--transition);
            margin-bottom: 25px;
        }

        .details-card:hover { box-shadow: var(--shadow-md); }

        .details-header {
            padding: 18px 25px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .details-header .header-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
        }

        .details-header h5 { margin: 0; font-weight: 600; font-size: 1rem; }

        .details-body { padding: 10px 25px 20px; }

        .info-row {
            display: flex;
            padding: 13px 0;
            border-bottom: 1px solid #f8f8f8;
            align-items: center;
            transition: var(--transition);
        }

        .info-row:last-child { border-bottom: none; }

        .info-row:hover {
            background: #fafdf9;
            margin: 0 -10px;
            padding: 13px 10px;
            border-radius: 8px;
        }

        .info-label {
            width: 170px;
            flex-shrink: 0;
            font-weight: 600;
            color: #555;
            font-size: 0.82rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-label i { width: 18px; color: #e74c3c; text-align: center; font-size: 0.8rem; }

        .info-value {
            flex: 1;
            color: var(--text-dark);
            font-size: 0.88rem;
            font-weight: 500;
        }

        .info-highlight {
            display: inline-block;
            background: #e8f5e9;
            color: var(--primary-dark);
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.82rem;
        }

        /* ============ DOCTOR LIST CARD ============ */
        .doclist-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            overflow: hidden;
        }

        .doclist-header {
            padding: 18px 25px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .doclist-header .header-icon {
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

        .doclist-header h5 { margin: 0; font-weight: 600; font-size: 1rem; }

        .doclist-body { padding: 10px; max-height: 400px; overflow-y: auto; }
        .doclist-body::-webkit-scrollbar { width: 4px; }
        .doclist-body::-webkit-scrollbar-track { background: transparent; }
        .doclist-body::-webkit-scrollbar-thumb { background: #ddd; border-radius: 10px; }

        .doc-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 13px 16px;
            border-radius: 10px;
            transition: var(--transition);
            margin-bottom: 3px;
            text-decoration: none;
        }

        .doc-item:hover { background: #f8fdf9; text-decoration: none; }

        .doc-item-left { display: flex; align-items: center; gap: 14px; }

        .doc-num {
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

        .doc-name {
            font-weight: 500;
            color: var(--text-dark);
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .doc-item:hover .doc-name { color: var(--primary); }

        .doc-arrow { color: #ccc; font-size: 0.8rem; transition: var(--transition); }
        .doc-item:hover .doc-arrow { color: var(--primary); transform: translateX(4px); }

        .no-doctors { text-align: center; padding: 30px; color: #bbb; }
        .no-doctors i { font-size: 2.5rem; display: block; margin-bottom: 10px; }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 768px) {
            body { padding-top: 75px; }
            .page-header { padding: 18px 0; border-radius: 0 0 20px 20px; }
            .profile-avatar { width: 80px; height: 80px; }
            .profile-cover { height: 55px; }
            .profile-avatar-wrapper { margin-top: -38px; }
            .info-row { flex-direction: column; align-items: flex-start; gap: 4px; }
            .info-label { width: 100%; }
            .recruitment-banner { flex-direction: column; text-align: center; }
            .recruitment-banner marquee { width: 100%; }
        }
    </style>
</head>

<body>

    <?php
    $my_query = mysqli_query($conn,"SELECT * from person inner join gender on person.gender = gender.gender_id where person.nid ='$user_id'");
    $my = ($my_query) ? mysqli_fetch_array($my_query) : null;
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
            <div class="breadcrumb-row">
                <a href="hospital.php"><i class="fas fa-hospital"></i> Hospitals</a>
                <i class="fas fa-chevron-right" style="font-size:0.7rem;"></i>
                <span><?php echo $hospital_row['hospital_name'] ?? 'Hospital'; ?></span>
            </div>
        </div>
    </div>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="container">
        
        <!-- Recruitment Banner -->
        <?php if(!$check && $user_type == 'Doctor' && !$hoscheck && !$workingcheck): ?>
        <div class="recruitment-banner">
            <marquee behavior="scroll" direction="left" scrollamount="5">
                <i class="fas fa-bullhorn mr-2" style="color:#f39c12;"></i>
                <?php echo $hospital_row['docregtext'] ?? 'We are hiring doctors!'; ?>
            </marquee>
            <a href="docregister.php?id=<?php echo $user_id; ?>&hosid=<?php echo $id; ?>&dmdc=<?php echo $dmdc['dmdc_id'] ?? ''; ?>" class="btn-apply">
                <i class="fas fa-paper-plane"></i> Apply Now
            </a>
        </div>
        <?php endif; ?>

        <div class="main-body">
            <div class="row gutters-sm">
                
                <!-- LEFT COLUMN - Profile Card -->
                <div class="col-md-4 mb-4">
                    <div class="profile-card">
                        <div class="profile-cover"></div>
                        <div class="profile-avatar-wrapper">
                            <img src="img/hospital1.png" alt="Hospital" class="profile-avatar"
                                 onerror="this.src='img/hospital1.png'">
                        </div>
                        <div class="profile-info">
                            <h5 class="profile-name"><?php echo $hospital_row['hospital_name'] ?? 'Unknown'; ?></h5>
                            <div class="profile-id">
                                <i class="fas fa-id-card mr-1"></i> ID: <?php echo $hospital_row['hospital_id'] ?? 'N/A'; ?>
                            </div>
                            
                            <div class="stat-row">
                                <div class="stat-mini">
                                    <div class="val"><?php echo $hospital_row['numberof_ward'] ?? '0'; ?></div>
                                    <div class="lbl">Wards</div>
                                </div>
                                <div class="stat-mini">
                                    <div class="val"><?php echo $hospital_row['numberof_cabin'] ?? '0'; ?></div>
                                    <div class="lbl">Cabins</div>
                                </div>
                                <div class="stat-mini">
                                    <div class="val"><?php echo $total_doctors; ?></div>
                                    <div class="lbl">Doctors</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN - Details -->
                <div class="col-md-8">
                    
                    <!-- Hospital Details -->
                    <div class="details-card">
                        <div class="details-header">
                            <div class="header-icon"><i class="fas fa-info-circle"></i></div>
                            <h5>Hospital Information</h5>
                        </div>
                        <div class="details-body">
                            
                            <div class="info-row">
                                <div class="info-label"><i class="fas fa-hospital"></i> Hospital Name</div>
                                <div class="info-value"><?php echo $hospital_row['hospital_name'] ?? 'N/A'; ?></div>
                            </div>

                            <div class="info-row">
                                <div class="info-label"><i class="fas fa-fingerprint"></i> Hospital ID</div>
                                <div class="info-value"><?php echo $hospital_row['hospital_id'] ?? 'N/A'; ?></div>
                            </div>

                            <div class="info-row">
                                <div class="info-label"><i class="fas fa-bed"></i> Number of Wards</div>
                                <div class="info-value">
                                    <span class="info-highlight"><?php echo $hospital_row['numberof_ward'] ?? '0'; ?> Wards</span>
                                </div>
                            </div>

                            <div class="info-row">
                                <div class="info-label"><i class="fas fa-money-bill-wave"></i> Ward Fee (Per Day)</div>
                                <div class="info-value">৳ <?php echo $hospital_row['wardfee_perday'] ?? '0'; ?></div>
                            </div>

                            <div class="info-row">
                                <div class="info-label"><i class="fas fa-door-open"></i> Number of Cabins</div>
                                <div class="info-value">
                                    <span class="info-highlight"><?php echo $hospital_row['numberof_cabin'] ?? '0'; ?> Cabins</span>
                                </div>
                            </div>

                            <div class="info-row">
                                <div class="info-label"><i class="fas fa-coins"></i> Cabin Fee (Per Day)</div>
                                <div class="info-value">৳ <?php echo $hospital_row['cabinfee_perday'] ?? '0'; ?></div>
                            </div>

                        </div>
                    </div>

                    <!-- Doctor List -->
                    <div class="doclist-card">
                        <div class="doclist-header">
                            <div class="header-icon"><i class="fas fa-user-md"></i></div>
                            <h5>Medical Team</h5>
                            <span style="margin-left:auto;font-size:0.78rem;color:#999;"><?php echo $total_doctors; ?> Members</span>
                        </div>
                        <div class="doclist-body">
                            <?php 
                                $queryp = mysqli_query($conn,"SELECT * from doctorworking where hospital_id = '$id' order by joined_date ASC");
                                if($queryp && mysqli_num_rows($queryp) > 0):
                                    while($doclist = mysqli_fetch_array($queryp)):
                                        $nid = $doclist['d_nid'];
                                        $doc = mysqli_query($conn,"SELECT name from person where nid = '$nid'");
                                        $name = ($doc) ? mysqli_fetch_array($doc) : null;
                            ?>
                            <a href="doctorprofile.php?id=<?php echo $nid; ?>" class="doc-item">
                                <div class="doc-item-left">
                                    <div class="doc-num"><?php echo $c1; ?></div>
                                    <span class="doc-name">Dr. <?php echo $name['name'] ?? 'Unknown'; ?></span>
                                </div>
                                <i class="fas fa-chevron-right doc-arrow"></i>
                            </a>
                            <?php $c1++; endwhile; ?>
                            <?php else: ?>
                            <div class="no-doctors">
                                <i class="fas fa-user-md"></i>
                                <small>No doctors assigned yet</small>
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