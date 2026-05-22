<?php include('session.php'); ?>
<?php include ('dbconn.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="My Prescriptions - IBHS">
    <meta name="author" content="">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title>My Prescriptions | IBHS</title>

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
            background: rgba(255,255,255,0.2);
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
            color: rgba(255,255,255,0.85);
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

        /* ============ MAIN LAYOUT ============ */
        .main-content {
            padding-bottom: 40px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 25px;
        }

        /* ============ PRESCRIPTION TABLE CARD ============ */
        .table-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            overflow: hidden;
        }

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

        .custom-table tbody tr {
            transition: var(--transition);
        }

        .custom-table tbody tr:hover {
            background: #f8fdf9;
        }

        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }

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

        .doctor-link:hover {
            color: var(--primary);
            text-decoration: none;
        }

        .doctor-link i {
            color: var(--primary);
            font-size: 0.8rem;
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

        /* ============ CHILDREN CARD ============ */
        .children-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            overflow: hidden;
            position: sticky;
            top: 90px;
        }

        .children-header {
            padding: 18px 20px;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fafafa;
        }

        .children-header i { color: var(--info); margin-right: 8px; }

        .children-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .child-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 13px 20px;
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
        .child-item a i { color: var(--info); font-size: 0.8rem; }

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

        .child-count-badge {
            background: var(--info-light);
            color: #1565c0;
            padding: 2px 10px;
            border-radius: 15px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        /* ============ EMPTY STATE ============ */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }

        .empty-state i {
            font-size: 4rem;
            color: #e0e0e0;
            display: block;
            margin-bottom: 15px;
        }

        .empty-state h5 { color: #999; font-weight: 600; }

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
        @media (max-width: 992px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
            .children-card {
                position: static;
            }
        }

        @media (max-width: 768px) {
            body { padding-top: 75px; }
            .page-header { padding: 20px 0; border-radius: 0 0 20px 20px; }
            .header-title h4 { font-size: 1.1rem; }
            .header-icon-box { width: 42px; height: 42px; font-size: 20px; border-radius: 10px; }
            
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
    // FIXED: Null-safe user query
    $my_query = mysqli_query($conn,"SELECT * from person inner join gender on person.gender = gender.gender_id where person.nid ='$user_id'");
    $my = ($my_query) ? mysqli_fetch_array($my_query) : null;
    
    // Count prescriptions - FIXED: null-safe
    $presc_count_query = mysqli_query($conn,"SELECT COUNT(*) as total FROM prescription WHERE p_nid = '$user_id' AND childbirth_id = 'N/A'");
    $presc_data = ($presc_count_query) ? mysqli_fetch_assoc($presc_count_query) : null;
    $prescription_count = ($presc_data && isset($presc_data['total'])) ? $presc_data['total'] : 0;
    
    // Count children - FIXED: null-safe
    $child_count_query = mysqli_query($conn,"SELECT COUNT(*) as total FROM patientbelow18 WHERE guardian_nid = '$user_id'");
    $child_data = ($child_count_query) ? mysqli_fetch_assoc($child_count_query) : null;
    $child_count = ($child_data && isset($child_data['total'])) ? $child_data['total'] : 0;
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
                    <li class="nav-item"><a class="nav-link active" href="myprescription.php"><i class="fas fa-prescription"></i> Prescriptions</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="message.php?id=0"><i class="fas fa-envelope"></i> Messages</a></li>
                    <li class="nav-item ml-2">
                        <a class="nav-link nav-user-badge" href="myprofile.php?id=<?php echo $user_id; ?>">
                            <i class="fas fa-user-circle" style="font-size:15px;color:var(--primary);"></i>
                            <div><div class="divname"><?php echo $my['name'] ?? 'User'; ?></div><div class="divid"><?php echo $my['nid'] ?? ''; ?></div></div>
                        </a>
                    </li>
                    <li class="nav-item ml-2">
                        <a class="nav-link btn-logout-nav" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ============ PAGE HEADER ============ -->
    <section class="page-header">
        <div class="container">
            <div class="header-content">
                <div class="header-title">
                    <div class="header-icon-box">
                        <i class="fas fa-prescription"></i>
                    </div>
                    <div>
                        <h4>My Prescriptions</h4>
                        <span>View and manage your prescriptions</span>
                    </div>
                </div>
                <div class="header-badge">
                    <div class="count"><?php echo $prescription_count; ?></div>
                    <div class="label">Total Prescriptions</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="container main-content">
        <div class="content-grid">
            
            <!-- Prescription Table -->
            <div class="table-card">
                <div class="table-card-header">
                    <div class="header-icon">
                        <i class="fas fa-list-ul"></i>
                    </div>
                    <h5>Prescription History</h5>
                </div>
                
                <?php
                    $query = mysqli_query($conn,"SELECT * FROM prescription WHERE p_nid = '$user_id' AND childbirth_id = 'N/A' ORDER BY prescription_id DESC");
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
                    <p class="text-muted">Your prescriptions will appear here once a doctor prescribes medication.</p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Children Sidebar -->
            <div class="children-card">
                <div class="children-header">
                    <span><i class="fas fa-child"></i> My Children</span>
                    <span class="child-count-badge"><?php echo $child_count; ?></span>
                </div>
                <ul class="children-list">
                    <?php 
                        $child_query = mysqli_query($conn,"SELECT * FROM patientbelow18 WHERE guardian_nid = '$user_id'");
                        if($child_query && mysqli_num_rows($child_query) > 0):
                            while($below18 = mysqli_fetch_array($child_query)):
                    ?>
                    <li class="child-item">
                        <a href="below18profile.php?gid=<?php echo $user_id; ?>&child_id=<?php echo $below18['childbirth_id']; ?>&a=1">
                            <i class="fas fa-child"></i> <?php echo $below18['name']; ?>
                        </a>
                    </li>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <li class="no-children">
                        <i class="fas fa-child" style="font-size:1.5rem;display:block;margin-bottom:8px;color:#ddd;"></i>
                        No children added
                    </li>
                    <?php endif; ?>
                </ul>
                <button type="button" class="btn-add-child" data-toggle="modal" data-target="#addChildModal">
                    <i class="fas fa-plus-circle mr-2"></i> Add Child
                </button>
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
</body>
</html>