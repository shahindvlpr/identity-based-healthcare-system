<?php include('dbconn.php'); ?>
<?php include('session.php'); ?>
<?php
    // FIXED: Null-safe GET parameters
    $search = isset($_GET['id']) ? $_GET['id'] : '';
    $a = isset($_GET['a']) ? $_GET['a'] : 'mo';
    
    // FIXED: Null-safe query
    $query = mysqli_query($conn,"SELECT * from person inner join patient on person.nid = patient.p_nid inner join gender on person.gender = gender.gender_id where nid = '$search'");
    $patient_row = ($query && mysqli_num_rows($query) > 0) ? mysqli_fetch_array($query) : null;
    
    if(!$patient_row) {
        header("location:patient.php");
        exit();
    }
    
    $nid = $patient_row['nid'];
    
    // Count prescriptions
    $presc_count_query = mysqli_query($conn,"SELECT COUNT(*) as total FROM prescription WHERE p_nid = '$nid' AND childbirth_id = 'N/A'");
    $presc_data = ($presc_count_query) ? mysqli_fetch_assoc($presc_count_query) : null;
    $prescription_count = ($presc_data && isset($presc_data['total'])) ? $presc_data['total'] : 0;
    
    // Count children
    $child_count_query = mysqli_query($conn,"SELECT COUNT(*) as total FROM patientbelow18 WHERE guardian_nid = '$nid'");
    $child_data = ($child_count_query) ? mysqli_fetch_assoc($child_count_query) : null;
    $child_count = ($child_data && isset($child_data['total'])) ? $child_data['total'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Patient Details - IBHS">
    <meta name="author" content="">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title><?php echo $patient_row['name'] ?? 'Patient'; ?> | IBHS</title>

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
            --purple: #9b59b6;
            --purple-light: #f3e5f5;
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
            background: linear-gradient(135deg, #3498db, #2980b9);
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

        .profile-info { padding: 15px 25px 22px; }

        .profile-name {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--text-dark);
            margin-bottom: 2px;
        }

        .profile-role {
            color: var(--info);
            font-weight: 600;
            font-size: 0.78rem;
            margin-bottom: 10px;
            display: inline-block;
            background: var(--info-light);
            padding: 4px 14px;
            border-radius: 20px;
        }

        .info-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            margin-bottom: 12px;
        }

        .info-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 500;
        }

        .tag-blood { background: #ffebee; color: #c62828; }
        .tag-gender { background: #f3e5f5; color: #7b1fa2; }
        .tag-religion { background: #fff8e1; color: #f57f17; }
        .tag-dob { background: #e3f2fd; color: #1565c0; }
        .tag-occupation { background: #e8f5e9; color: #2e7d32; }

        .btn-message {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none;
        }

        .btn-message:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 155, 70, 0.3);
            color: #fff;
            text-decoration: none;
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

        .children-header i { color: var(--purple); margin-right: 8px; }

        .children-list { list-style: none; padding: 0; margin: 0; }

        .child-item {
            padding: 12px 20px;
            border-bottom: 1px solid #f8f8f8;
            transition: var(--transition);
        }

        .child-item:hover { background: #fafdf9; }

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

        .child-item a:hover { color: var(--primary); }
        .child-item a i { color: var(--purple); }

        .no-children {
            text-align: center;
            padding: 20px;
            color: #bbb;
            font-size: 0.82rem;
        }

        /* ============ PRESCRIPTION TABLE ============ */
        .table-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            overflow: hidden;
            transition: var(--transition);
            margin-bottom: 25px;
        }

        .table-card:hover { box-shadow: var(--shadow-md); }

        .table-card-header {
            padding: 18px 25px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fafafa;
        }

        .table-card-header .header-icon {
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

        .table-card-header h5 { margin: 0; font-weight: 600; font-size: 1rem; }

        .custom-table { width: 100%; margin: 0; }
        
        .custom-table thead th {
            background: #f8f9fa;
            border: none;
            border-bottom: 2px solid #eee;
            padding: 14px 18px;
            font-weight: 600;
            color: #555;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .custom-table tbody td {
            padding: 14px 18px;
            vertical-align: middle;
            border-bottom: 1px solid #f5f5f5;
            font-size: 0.88rem;
        }

        .custom-table tbody tr { transition: var(--transition); }
        .custom-table tbody tr:hover { background: #f8fdf9; }
        .custom-table tbody tr:last-child td { border-bottom: none; }

        .serial-badge {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #f0fdf4;
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .btn-view-presc {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.78rem;
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none;
        }

        .btn-view-presc:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 155, 70, 0.3);
            color: #fff;
            text-decoration: none;
        }

        .btn-add-presc {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: var(--transition);
            cursor: pointer;
            margin: 15px 0 5px;
            text-decoration: none;
        }

        .btn-add-presc:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 155, 70, 0.3);
            color: #fff;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-state i { font-size: 3.5rem; color: #e0e0e0; display: block; margin-bottom: 15px; }
        .empty-state h5 { color: #999; font-weight: 600; }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 768px) {
            body { padding-top: 75px; }
            .page-header { padding: 18px 0; border-radius: 0 0 20px 20px; }
            .profile-avatar { width: 85px; height: 85px; }
            .profile-cover { height: 60px; }
            .profile-avatar-wrapper { margin-top: -40px; }
            
            .custom-table thead { display: none; }
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
            .custom-table tbody tr { display: block; border-bottom: 1px solid #eee; padding: 5px 0; }
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
                    <li class="nav-item"><a class="nav-link active" href="patient.php"><i class="fas fa-users"></i> Patients</a></li>
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
                <a href="patient.php"><i class="fas fa-users"></i> My Patients</a>
                <i class="fas fa-chevron-right" style="font-size:0.7rem;"></i>
                <span><i class="fas fa-user"></i> <?php echo $patient_row['name'] ?? 'Patient'; ?></span>
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
                            <img src="img/<?php echo $patient_row['image'] ?? 'patient1.png'; ?>" 
                                 alt="Patient" class="profile-avatar"
                                 onerror="this.src='img/patient1.png'">
                        </div>
                        <div class="profile-info">
                            <h5 class="profile-name"><?php echo $patient_row['name'] ?? 'Unknown'; ?></h5>
                            <span class="profile-role">
                                <i class="fas fa-user-injured mr-1"></i> Patient
                            </span>
                            
                            <div class="info-tags">
                                <span class="info-tag tag-blood">
                                    <i class="fas fa-tint"></i> <?php echo $patient_row['blood'] ?? 'N/A'; ?>
                                </span>
                                <span class="info-tag tag-gender">
                                    <i class="fas fa-venus-mars"></i> <?php echo $patient_row['gender_name'] ?? 'N/A'; ?>
                                </span>
                                <span class="info-tag tag-dob">
                                    <i class="fas fa-calendar-alt"></i> <?php echo $patient_row['dob'] ?? 'N/A'; ?>
                                </span>
                                <span class="info-tag tag-occupation">
                                    <i class="fas fa-briefcase"></i> <?php echo $patient_row['occupation'] ?? 'N/A'; ?>
                                </span>
                                <span class="info-tag tag-religion">
                                    <i class="fas fa-pray"></i> <?php echo $patient_row['religion'] ?? 'N/A'; ?>
                                </span>
                            </div>
                            
                            <?php if($a == 'm' || $a == 'mo'): ?>
                            <a href="message.php?id=<?php echo $search; ?>" class="btn-message">
                                <i class="fas fa-envelope"></i> Message Patient
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Children Card -->
                    <div class="children-card">
                        <div class="children-header">
                            <span><i class="fas fa-child"></i> Children</span>
                            <span style="font-size:0.75rem;color:#999;"><?php echo $child_count; ?> child<?php echo $child_count != 1 ? 'ren' : ''; ?></span>
                        </div>
                        <ul class="children-list">
                            <?php 
                                $child_query = mysqli_query($conn,"SELECT * FROM patientbelow18 WHERE guardian_nid = '$nid'");
                                if($child_query && mysqli_num_rows($child_query) > 0):
                                    while($below18 = mysqli_fetch_array($child_query)):
                            ?>
                            <li class="child-item">
                                <a href="below18profile.php?gid=<?php echo $nid; ?>&child_id=<?php echo $below18['childbirth_id']; ?>&a=<?php echo $a; ?>">
                                    <i class="fas fa-child"></i> <?php echo $below18['name']; ?>
                                </a>
                            </li>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <li class="no-children">No children registered</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <!-- RIGHT COLUMN - Prescriptions -->
                <div class="col-md-8">
                    <div class="table-card">
                        <div class="table-card-header">
                            <div class="header-icon">
                                <i class="fas fa-list-ul"></i>
                            </div>
                            <h5>Prescription History</h5>
                            <span style="margin-left:auto;font-size:0.78rem;color:#999;"><?php echo $prescription_count; ?> Records</span>
                        </div>
                        
                        <?php
                            $presc_query = mysqli_query($conn,"SELECT * FROM prescription WHERE p_nid = '$nid' AND childbirth_id = 'N/A' ORDER BY prescription_id DESC");
                            if($presc_query && mysqli_num_rows($presc_query) > 0):
                        ?>
                        <div class="table-responsive">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Prescription</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $c = 1;
                                        while($pre = mysqli_fetch_array($presc_query)):
                                    ?>
                                    <tr>
                                        <td data-label="#">
                                            <div class="serial-badge"><?php echo $c; ?></div>
                                        </td>
                                        <td data-label="Prescription">
                                            <span style="font-weight:500;">Prescription #<?php echo $pre['prescription_id']; ?></span>
                                        </td>
                                        <td data-label="Action">
                                            <a href="prescriptionview.php?id=<?php echo $pre['prescription_id']; ?>" class="btn-view-presc">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    <?php $c++; endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-prescription-bottle"></i>
                            <h5>No prescriptions yet</h5>
                            <p class="text-muted">Prescriptions will appear here.</p>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Add Prescription Button -->
                        <?php if($a == '0' || $a == 'm'): ?>
                        <div style="padding: 0 25px 20px;">
                            <form action="prescriptionidinsert.php" method="POST">
                                <input type="hidden" name="childbirth_id" value="N/A">
                                <input type="hidden" name="pid" value="<?php echo $patient_row['nid']; ?>">
                                <input type="hidden" name="did" value="<?php echo $user_id; ?>">
                                <button type="submit" class="btn-add-presc" name="add">
                                    <i class="fas fa-plus-circle"></i> Add Prescription
                                </button>
                            </form>
                        </div>
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