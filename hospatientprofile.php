<?php include('session.php'); ?>
<?php
include ('dbconn.php');
$id=$_GET['id'];
$query = mysqli_query($conn,"SELECT * from person inner join patient on person.nid = patient.p_nid inner join gender on person.gender = gender.gender_id where person.nid ='$id'");
$patpro_row=mysqli_fetch_array($query);
$query = mysqli_query($conn,"SELECT name from person where nid = (select father_nid from person where person.nid ='$id')");
$father = mysqli_fetch_array($query);
$query = mysqli_query($conn,"SELECT name from person where nid = (select mother_nid from person where person.nid ='$id')");
$mother = mysqli_fetch_array($query);
$query = mysqli_query($conn,"SELECT name from person where nid = (select husband_nid from person where person.nid ='$id')");
$husband = mysqli_fetch_array($query);
$query = mysqli_query($conn,"SELECT COUNT(*) FROM person WHERE husband_nid = (SELECT nid FROM person WHERE nid = '$id') GROUP BY husband_nid");
$marital_status = mysqli_fetch_array($query);
$query = mysqli_query($conn,"SELECT * from person inner join gender on person.gender = gender.gender_id where person.nid ='$user_id'");
$my = mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Patient Profile - IBHS">
    <meta name="author" content="">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title>Patient Profile | IBHS</title>

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
            background: linear-gradient(135deg, #009B46 0%, #007a38 100%);
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

        /* ============ MAIN CONTENT ============ */
        .main-body {
            padding-bottom: 40px;
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
            background: linear-gradient(135deg, #009B46, #00d2ff);
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
            font-size: 0.8rem;
            margin-bottom: 12px;
            display: inline-block;
            background: var(--primary-light);
            padding: 4px 14px;
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
            margin-bottom: 10px;
        }

        .profile-blood i {
            font-size: 0.65rem;
        }

        .profile-divider {
            border: none;
            border-top: 1px solid #f0f0f0;
            margin: 15px 0;
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
            font-size: 0.8rem;
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
            padding: 20px 25px;
        }

        .info-row {
            display: flex;
            padding: 13px 0;
            border-bottom: 1px solid #f8f8f8;
            align-items: flex-start;
            transition: var(--transition);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row:hover {
            background: #fafdf9;
            margin: 0 -10px;
            padding: 13px 10px;
            border-radius: 8px;
        }

        .info-label {
            width: 160px;
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

        .info-value .badge-status {
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
            
            .info-row {
                flex-direction: column;
                gap: 4px;
            }
            
            .info-label {
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
                <a href="hospatient.php"><i class="fas fa-users"></i> Patients</a>
                <i class="fas fa-chevron-right" style="font-size:0.7rem;"></i>
                <span><i class="fas fa-user"></i> <?php echo $patpro_row['name']; ?></span>
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
                            <img src="img/<?php echo $patpro_row['image']; ?>" 
                                 alt="Patient Photo" class="profile-avatar"
                                 onerror="this.src='img/patient1.png'">
                        </div>
                        <div class="profile-info">
                            <h5 class="profile-name"><?php echo $patpro_row['name']; ?></h5>
                            <span class="profile-role">
                                <i class="fas fa-user-injured mr-1"></i> Patient
                            </span>
                            <br>
                            <span class="profile-blood">
                                <i class="fas fa-tint"></i> Blood: <?php echo $patpro_row['blood']; ?>
                            </span>
                        </div>
                    </div>

                    <!-- Children Card -->
                    <?php if($user_id == $id): ?>
                    <div class="children-card">
                        <div class="children-header">
                            <span><i class="fas fa-child mr-2"></i> My Children</span>
                            <span style="font-size:0.75rem;color:#999;">
                                <?php 
                                    $child_count = mysqli_num_rows(mysqli_query($conn,"SELECT * from patientbelow18 where guardian_nid = $user_id"));
                                    echo $child_count . ' child' . ($child_count != 1 ? 'ren' : '');
                                ?>
                            </span>
                        </div>
                        <ul class="children-list">
                            <?php 
                                $query = mysqli_query($conn,"SELECT * from patientbelow18 where guardian_nid = $user_id");
                                if(mysqli_num_rows($query) > 0):
                                    while($below18 = mysqli_fetch_array($query)):
                            ?>
                            <li class="child-item">
                                <a href="below18profile.php?gid=<?php echo $user_id; ?>&child_id=<?php echo $below18['childbirth_id']; ?>">
                                    <i class="fas fa-child"></i> <?php echo $below18['name']; ?>
                                </a>
                            </li>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <li class="child-item" style="color:#999;justify-content:center;">
                                <small>No children added yet</small>
                            </li>
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
                                <div class="info-value"><?php echo $patpro_row['name']; ?></div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-venus-mars"></i> Gender
                                </div>
                                <div class="info-value"><?php echo $patpro_row['gender_name']; ?></div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-calendar-alt"></i> Date of Birth
                                </div>
                                <div class="info-value"><?php echo $patpro_row['dob']; ?></div>
                            </div>

                            <!-- Marital Status -->
                            <?php if($husband!=null && $patpro_row['gender_name'] == 'female'): ?>
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-male"></i> Husband's Name
                                </div>
                                <div class="info-value"><?php echo $husband['name']; ?></div>
                            </div>
                            <?php else: ?>
                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-ring"></i> Marital Status
                                </div>
                                <div class="info-value">
                                    <?php if($marital_status!=NULL): ?>
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
                                <div class="info-value"><?php echo $father['name']; ?></div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-female"></i> Mother's Name
                                </div>
                                <div class="info-value"><?php echo $mother['name']; ?></div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-briefcase"></i> Occupation
                                </div>
                                <div class="info-value"><?php echo $patpro_row['occupation']; ?></div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-pray"></i> Religion
                                </div>
                                <div class="info-value"><?php echo $patpro_row['religion']; ?></div>
                            </div>

                            <div class="info-row">
                                <div class="info-label">
                                    <i class="fas fa-id-card"></i> NID Number
                                </div>
                                <div class="info-value"><?php echo $patpro_row['nid']; ?></div>
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