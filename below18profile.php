<?php
    include ('dbconn.php');
    include('session.php');
    
    // FIXED: Null-safe GET parameters
    $id = isset($_GET['gid']) ? $_GET['gid'] : '';
    $child_id = isset($_GET['child_id']) ? $_GET['child_id'] : '';
    $a = isset($_GET['a']) ? $_GET['a'] : '1';
    
    // FIXED: Null-safe query
    $query = mysqli_query($conn,"SELECT person.name as gname, patientbelow18.name as cname, patientbelow18.dob as dob FROM patientbelow18 INNER JOIN person ON patientbelow18.guardian_nid = person.nid WHERE patientbelow18.guardian_nid = '$id' AND patientbelow18.childbirth_id = '$child_id'");
    $child = ($query && mysqli_num_rows($query) > 0) ? mysqli_fetch_array($query) : null;
    
    // Count prescriptions for this child
    $presc_count_query = mysqli_query($conn,"SELECT COUNT(*) as total FROM prescription WHERE p_nid = '$id' AND childbirth_id = '$child_id'");
    $presc_data = ($presc_count_query) ? mysqli_fetch_assoc($presc_count_query) : null;
    $prescription_count = ($presc_data && isset($presc_data['total'])) ? $presc_data['total'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Child Profile - IBHS">
    <meta name="author" content="">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title><?php echo $child['cname'] ?? 'Child'; ?> Profile | IBHS</title>
    
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
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
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

        .breadcrumb-row a { color: rgba(255,255,255,0.9); text-decoration: none; }
        .breadcrumb-row a:hover { color: #fff; text-decoration: underline; }
        .breadcrumb-row span { color: #fff; font-weight: 600; }

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
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
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
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #fff;
            margin: 0 auto;
        }

        .profile-card:hover .profile-avatar { transform: scale(1.03); }

        .profile-info { padding: 12px 25px 22px; }

        .profile-name {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--text-dark);
            margin-bottom: 2px;
        }

        .profile-badge {
            color: var(--purple);
            font-weight: 600;
            font-size: 0.75rem;
            margin-bottom: 10px;
            display: inline-block;
            background: var(--purple-light);
            padding: 4px 12px;
            border-radius: 20px;
        }

        .info-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.8rem;
            margin: 4px;
        }

        .info-tag.guardian {
            background: #e3f2fd;
            color: #1565c0;
        }

        .info-tag.dob {
            background: #fff3e0;
            color: #e65100;
        }

        .info-tag.prescriptions {
            background: #e8f5e9;
            color: #2e7d32;
        }

        /* ============ TABLE CARD ============ */
        .table-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            overflow: hidden;
            transition: var(--transition);
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

        .doctor-link {
            color: var(--text-dark);
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .doctor-link:hover { color: var(--primary); text-decoration: none; }
        .doctor-link i { color: var(--primary); font-size: 0.8rem; }

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
            margin-top: 15px;
        }

        .btn-add-presc:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 155, 70, 0.3);
        }

        /* ============ EMPTY STATE ============ */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-state i {
            font-size: 3.5rem;
            color: #e0e0e0;
            display: block;
            margin-bottom: 15px;
        }

        .empty-state h5 { color: #999; font-weight: 600; }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 768px) {
            body { padding-top: 75px; }
            .page-header { padding: 18px 0; border-radius: 0 0 20px 20px; }
            .profile-avatar { width: 80px; height: 80px; font-size: 30px; }
            .profile-cover { height: 55px; }
            .profile-avatar-wrapper { margin-top: -38px; }
            
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
            .custom-table tbody tr {
                display: block;
                border-bottom: 1px solid #eee;
                padding: 5px 0;
            }
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
                <a href="myprescription.php"><i class="fas fa-prescription"></i> Prescriptions</a>
                <i class="fas fa-chevron-right" style="font-size:0.7rem;"></i>
                <span><i class="fas fa-child"></i> <?php echo $child['cname'] ?? 'Child'; ?></span>
            </div>
        </div>
    </div>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="container">
        <div class="main-body">
            <div class="row gutters-sm">
                
                <!-- LEFT COLUMN - Child Profile Card -->
                <div class="col-md-4 mb-4">
                    <div class="profile-card">
                        <div class="profile-cover"></div>
                        <div class="profile-avatar-wrapper">
                            <div class="profile-avatar">
                                <?php echo strtoupper(substr($child['cname'] ?? 'C', 0, 1)); ?>
                            </div>
                        </div>
                        <div class="profile-info">
                            <h5 class="profile-name"><?php echo $child['cname'] ?? 'Unknown'; ?></h5>
                            <span class="profile-badge">
                                <i class="fas fa-child mr-1"></i> Child (Below 18)
                            </span>
                            
                            <div style="margin-top:12px;">
                                <span class="info-tag guardian">
                                    <i class="fas fa-user-shield"></i> Guardian: <?php echo $child['gname'] ?? 'N/A'; ?>
                                </span>
                                <span class="info-tag dob">
                                    <i class="fas fa-calendar-alt"></i> DOB: <?php echo $child['dob'] ?? 'N/A'; ?>
                                </span>
                                <span class="info-tag prescriptions">
                                    <i class="fas fa-prescription"></i> <?php echo $prescription_count; ?> Prescriptions
                                </span>
                            </div>
                        </div>
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
                        
                        <?php if ($user_type=='Doctor' || $user_id == $id): ?>
                        
                        <?php
                            $query = mysqli_query($conn,"SELECT * FROM prescription WHERE p_nid = '$id' AND childbirth_id = '$child_id' ORDER BY prescription_id DESC");
                            if($query && mysqli_num_rows($query) > 0):
                        ?>
                        <div class="table-responsive">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Prescription</th>
                                        <th>Doctor Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $c = 1;
                                        while($pre = mysqli_fetch_array($query)):
                                            $did = $pre['d_nid'];
                                            $doc_query = mysqli_query($conn,"SELECT name FROM person WHERE nid = '$did'");
                                            $docname = ($doc_query) ? mysqli_fetch_array($doc_query) : null;
                                    ?>
                                    <tr>
                                        <td data-label="#">
                                            <div class="serial-badge"><?php echo $c; ?></div>
                                        </td>
                                        <td data-label="Prescription">
                                            <span style="font-weight:500;">Prescription #<?php echo $pre['prescription_id']; ?></span>
                                        </td>
                                        <td data-label="Doctor Name">
                                            <a href="doctorprofile.php?id=<?php echo $pre['d_nid']; ?>" class="doctor-link">
                                                <i class="fas fa-user-md"></i>
                                                Dr. <?php echo $docname['name'] ?? 'Unknown'; ?>
                                            </a>
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
                            <p class="text-muted">Prescriptions for this child will appear here.</p>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Add Prescription Button (Doctor only) -->
                        <?php if($user_type == "Doctor" && $a == '0'): ?>
                        <div style="padding: 0 25px 20px;">
                            <form action="prescriptionidinsert.php" method="POST">
                                <input type="hidden" name="childbirth_id" value="<?php echo $child_id; ?>">
                                <input type="hidden" name="pid" value="<?php echo $id; ?>">
                                <input type="hidden" name="did" value="<?php echo $user_id; ?>">
                                <button type="submit" class="btn-add-presc" name="add">
                                    <i class="fas fa-plus-circle"></i> Add Prescription
                                </button>
                            </form>
                        </div>
                        <?php endif; ?>
                        
                        <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-lock"></i>
                            <h5>Access Restricted</h5>
                            <p class="text-muted">Only the guardian or a doctor can view prescriptions.</p>
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