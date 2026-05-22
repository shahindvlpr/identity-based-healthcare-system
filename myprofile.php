<?php include('session.php'); ?>
<?php
include ('dbconn.php');
$id = $_GET['id'] ?? $user_id;

// Get patient profile
$query = mysqli_query($conn,"SELECT * from person inner join patient on person.nid = patient.p_nid inner join gender on person.gender = gender.gender_id where person.nid ='$id'");
$patpro_row = mysqli_fetch_array($query);

// If not a patient, try without patient join
if(!$patpro_row) {
    $query = mysqli_query($conn,"SELECT * from person inner join gender on person.gender = gender.gender_id where person.nid ='$id'");
    $patpro_row = mysqli_fetch_array($query);
}

// Get father's name - with null check
$father_result = mysqli_query($conn,"SELECT name from person where nid = (select father_nid from person where person.nid ='$id')");
$father = ($father_result) ? mysqli_fetch_array($father_result) : null;
$father_name = ($father && isset($father['name'])) ? $father['name'] : 'N/A';

// Get mother's name - with null check
$mother_result = mysqli_query($conn,"SELECT name from person where nid = (select mother_nid from person where person.nid ='$id')");
$mother = ($mother_result) ? mysqli_fetch_array($mother_result) : null;
$mother_name = ($mother && isset($mother['name'])) ? $mother['name'] : 'N/A';

// Get husband's name - with null check
$husband_result = mysqli_query($conn,"SELECT name from person where nid = (select husband_nid from person where person.nid ='$id')");
$husband = ($husband_result) ? mysqli_fetch_array($husband_result) : null;
$husband_name = ($husband && isset($husband['name'])) ? $husband['name'] : null;

// Get marital status - with null check
$marital_result = mysqli_query($conn,"SELECT COUNT(*) as total FROM person WHERE husband_nid = (SELECT nid FROM person WHERE nid = '$id') GROUP BY husband_nid");
$marital_status = ($marital_result) ? mysqli_fetch_array($marital_result) : null;
$is_married = ($marital_status && isset($marital_status['total']) && $marital_status['total'] > 0);

// Get current user info
$my_query = mysqli_query($conn,"SELECT * from person inner join gender on person.gender = gender.gender_id where person.nid ='$user_id'");
$my = mysqli_fetch_array($my_query);

// Count children
$child_query = mysqli_query($conn,"SELECT COUNT(*) as total FROM patientbelow18 where guardian_nid = '$user_id'");
$child_count_data = ($child_query) ? mysqli_fetch_assoc($child_query) : null;
$child_count = ($child_count_data && isset($child_count_data['total'])) ? $child_count_data['total'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="My Profile - IBHS">
    <meta name="author" content="">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title>My Profile | IBHS</title>
    
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

        .nav-user-badge {
            background: var(--primary-light);
            border-radius: 10px;
            padding: 6px 14px !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-user-badge .divname {
            font-size: 13px !important;
            font-weight: 600 !important;
            color: var(--text-dark) !important;
            line-height: 1.2;
        }

        .nav-user-badge .divid {
            font-size: 10px !important;
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
            background: linear-gradient(135deg, #009B46 0%, #007a38 100%);
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
            background: rgba(255,255,255,0.05);
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
            color: #fff;
            font-weight: 600;
        }

        /* ============ PROFILE LEFT CARD ============ */
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
            height: 75px;
            position: relative;
        }

        .profile-avatar-wrapper {
            margin-top: -48px;
            position: relative;
            z-index: 2;
        }

        .profile-avatar {
            width: 105px;
            height: 105px;
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
            padding: 12px 25px 22px;
        }

        .profile-name {
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--text-dark);
            margin-bottom: 2px;
        }

        .profile-role {
            color: var(--primary);
            font-weight: 600;
            font-size: 0.75rem;
            margin-bottom: 10px;
            display: inline-block;
            background: var(--primary-light);
            padding: 4px 12px;
            border-radius: 20px;
        }

        .profile-blood {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ffebee;
            color: #c62828;
            padding: 5px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.78rem;
        }

        .profile-blood i {
            font-size: 0.65rem;
        }

        /* ============ CHILDREN CARD ============ */
        .children-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            margin-top: 20px;
            overflow: hidden;
        }

        .children-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fafafa;
        }

        .children-header i {
            color: var(--info);
            margin-right: 8px;
        }

        .children-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .child-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            border-bottom: 1px solid #f8f8f8;
            transition: var(--transition);
        }

        .child-item:hover {
            background: #fafdf9;
        }

        .child-item a {
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .child-item a:hover {
            color: var(--primary);
        }

        .child-item a i {
            color: var(--info);
        }

        .btn-add-child {
            display: block;
            width: calc(100% - 40px);
            margin: 15px 20px;
            padding: 10px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.82rem;
            transition: var(--transition);
            cursor: pointer;
        }

        .btn-add-child:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 155, 70, 0.3);
        }

        .no-children {
            text-align: center;
            padding: 20px;
            color: #bbb;
            font-size: 0.82rem;
        }

        /* ============ DETAILS CARD ============ */
        .details-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            overflow: hidden;
            transition: var(--transition);
        }

        .details-card:hover {
            box-shadow: var(--shadow-md);
        }

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
            background: linear-gradient(135deg, #3498db, #2980b9);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 18px;
        }

        .details-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-dark);
        }

        .details-body {
            padding: 10px 25px 20px;
        }

        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f8f8f8;
            align-items: center;
            transition: var(--transition);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row:hover {
            background: #fafdf9;
            margin: 0 -10px;
            padding: 12px 10px;
            border-radius: 8px;
        }

        .info-label {
            width: 155px;
            flex-shrink: 0;
            font-weight: 600;
            color: #555;
            font-size: 0.82rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-label i {
            width: 18px;
            color: var(--primary);
            text-align: center;
            font-size: 0.8rem;
        }

        .info-value {
            flex: 1;
            color: var(--text-dark);
            font-size: 0.88rem;
            font-weight: 500;
        }

        .badge-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge-married {
            background: #e3f2fd;
            color: #1565c0;
        }

        .badge-unmarried {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        /* ============ MODAL ============ */
        .modal-content {
            border: none;
            border-radius: var(--radius);
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            padding: 16px 22px;
        }

        .modal-header .close {
            color: #fff;
            opacity: 1;
            text-shadow: none;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1rem;
        }

        .modal-body {
            padding: 25px;
        }

        .modal-body .form-group label {
            font-weight: 600;
            color: #555;
            font-size: 0.82rem;
            margin-bottom: 6px;
        }

        .modal-body .form-control {
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            padding: 10px 15px;
            font-size: 0.85rem;
            transition: var(--transition);
            font-family: 'Poppins', sans-serif;
        }

        .modal-body .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 155, 70, 0.1);
        }

        .modal-footer {
            border-top: 1px solid #f0f0f0;
            padding: 16px 22px;
        }

        .btn-modal-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: var(--transition);
        }

        .btn-modal-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 155, 70, 0.3);
        }

        .btn-modal-secondary {
            background: #e8e8e8;
            color: #555;
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: var(--transition);
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 768px) {
            body {
                padding-top: 75px;
            }
            
            .page-header {
                padding: 18px 0;
                border-radius: 0 0 20px 20px;
            }
            
            .profile-avatar {
                width: 80px;
                height: 80px;
            }
            
            .profile-cover {
                height: 55px;
            }
            
            .profile-avatar-wrapper {
                margin-top: -38px;
            }
            
            .info-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }
            
            .info-label {
                width: 100%;
            }
            
            .navbar-nav .nav-link {
                font-size: 12px;
                padding: 7px 12px !important;
            }
            
            .nav-user-badge {
                padding: 5px 10px !important;
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
                    
                    <?php if($user_type=='Doctor'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="patient.php"><i class="fas fa-users"></i> Patients</a>
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
                    
                    <!-- User Badge -->
                    <li class="nav-item ml-2">
                        <?php if($user_type=='Doctor'): ?>
                        <a class="nav-link nav-user-badge" href="doctorprofile.php?id=<?php echo $user_id; ?>">
                            <i class="fas fa-user-circle" style="font-size:18px;color:var(--primary);"></i>
                            <div>
                                <div class="divname"><?php echo $my['name'] ?? 'User'; ?></div>
                                <div class="divid"><?php echo $my['nid'] ?? ''; ?></div>
                            </div>
                        </a>
                        <?php else: ?>
                        <a class="nav-link nav-user-badge" href="myprofile.php?id=<?php echo $user_id; ?>">
                            <i class="fas fa-user-circle" style="font-size:18px;color:var(--primary);"></i>
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
            <div class="breadcrumb-row">
                <a href="#"><i class="fas fa-home"></i> Home</a>
                <i class="fas fa-chevron-right" style="font-size:0.7rem;"></i>
                <span><i class="fas fa-user"></i> <?php echo $patpro_row['name'] ?? 'Profile'; ?></span>
            </div>
        </div>
    </div>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="container">
        <div class="main-body">
            <div class="row gutters-sm">
                
                <!-- LEFT COLUMN - Profile Card -->
                <div class="col-md-4 mb-4">
                    <div class="profile-card">
                        <div class="profile-cover"></div>
                        <div class="profile-avatar-wrapper">
                            <img src="img/<?php echo $patpro_row['image'] ?? 'patient1.png'; ?>" 
                                 alt="Profile Photo" class="profile-avatar"
                                 onerror="this.src='img/patient1.png'">
                        </div>
                        <div class="profile-info">
                            <h5 class="profile-name"><?php echo $patpro_row['name'] ?? 'Unknown'; ?></h5>
                            <span class="profile-role">
                                <i class="fas fa-user-injured mr-1"></i> 
                                <?php echo $patpro_row['occupation'] ?? 'Patient'; ?>
                            </span>
                            <br>
                            <span class="profile-blood">
                                <i class="fas fa-tint"></i> Blood: <?php echo $patpro_row['blood'] ?? 'N/A'; ?>
                            </span>
                        </div>
                    </div>

                    <!-- Children Card -->
                    <?php if($user_id == $id): ?>
                    <div class="children-card">
                        <div class="children-header">
                            <span><i class="fas fa-child"></i> My Children</span>
                            <span style="font-size:0.75rem;color:#999;"><?php echo $child_count; ?> child<?php echo $child_count != 1 ? 'ren' : ''; ?></span>
                        </div>
                        <ul class="children-list">
                            <?php 
                                $query = mysqli_query($conn,"SELECT * from patientbelow18 where guardian_nid = '$user_id'");
                                if($query && mysqli_num_rows($query) > 0):
                                    while($below18 = mysqli_fetch_array($query)):
                            ?>
                            <li class="child-item">
                                <a href="below18profile.php?gid=<?php echo $user_id; ?>&child_id=<?php echo $below18['childbirth_id']; ?>&a=1">
                                    <i class="fas fa-child"></i> <?php echo $below18['name']; ?>
                                </a>
                            </li>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <li class="no-children">No children added yet</li>
                            <?php endif; ?>
                        </ul>
                        <button type="button" class="btn-add-child" data-toggle="modal" data-target="#addChildModal">
                            <i class="fas fa-plus-circle mr-2"></i> Add Child
                        </button>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- RIGHT COLUMN - Details -->
                <div class="col-md-8">
                    <div class="details-card">
                        <div class="details-header">
                            <div class="header-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <h5>Personal Information</h5>
                        </div>
                        <div class="details-body">
                            
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-user"></i> Full Name
                                </div>
                                <div class="info-value"><?php echo $patpro_row['name'] ?? 'N/A'; ?></div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-venus-mars"></i> Gender
                                </div>
                                <div class="info-value"><?php echo $patpro_row['gender_name'] ?? 'N/A'; ?></div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-calendar-alt"></i> Date of Birth
                                </div>
                                <div class="info-value"><?php echo $patpro_row['dob'] ?? 'N/A'; ?></div>
                            </div>

                            <!-- Marital Status - FIXED -->
                            <?php if($husband_name != null && ($patpro_row['gender_name'] ?? '') == 'female'): ?>
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-male"></i> Husband's Name
                                </div>
                                <div class="info-value"><?php echo $husband_name; ?></div>
                            </div>
                            <?php else: ?>
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-ring"></i> Marital Status
                                </div>
                                <div class="info-value">
                                    <?php if($is_married): ?>
                                        <span class="badge-status badge-married">
                                            <i class="fas fa-check-circle mr-1"></i> Married
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-status badge-unmarried">
                                            <i class="fas fa-circle mr-1"></i> Unmarried
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-male"></i> Father's Name
                                </div>
                                <div class="info-value"><?php echo $father_name; ?></div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-female"></i> Mother's Name
                                </div>
                                <div class="info-value"><?php echo $mother_name; ?></div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-briefcase"></i> Occupation
                                </div>
                                <div class="info-value"><?php echo $patpro_row['occupation'] ?? 'N/A'; ?></div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-pray"></i> Religion
                                </div>
                                <div class="info-value"><?php echo $patpro_row['religion'] ?? 'N/A'; ?></div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-id-card"></i> NID Number
                                </div>
                                <div class="info-value"><?php echo $patpro_row['nid'] ?? 'N/A'; ?></div>
                            </div>

                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <!-- ============ ADD CHILD MODAL ============ -->
    <div class="modal fade" id="addChildModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-child mr-2"></i> Add Child (Below 18)
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="below18insert.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="prescription_id">
                        
                        <div class="form-group">
                            <label><i class="fas fa-id-card mr-1"></i> Birth ID</label>
                            <input type="number" name="birth_id" class="form-control" placeholder="Enter birth ID" required>
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-user mr-1"></i> Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter child's name" required>
                        </div>
                        
                        <div class="form-group">
                            <label><i class="fas fa-calendar mr-1"></i> Date of Birth</label>
                            <input type="date" name="dob" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </button>
                        <button type="submit" name="insertbelow18" class="btn-modal-primary">
                            <i class="fas fa-save mr-1"></i> Save Child
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============ SCRIPTS ============ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>