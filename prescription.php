<?php
    include('dbconn.php');
    include('session.php');

    // FIXED: Null-safe GET parameter
    $prescription_id = isset($_GET['preid']) ? $_GET['preid'] : '';

    if (!empty($prescription_id)) {
        $query = mysqli_query($conn, "SELECT * FROM prescription WHERE prescription_id = '$prescription_id'");

        if ($query && mysqli_num_rows($query) > 0) {
            $prescription = mysqli_fetch_array($query);
            $pid = $prescription['p_nid'];
            $did = $prescription['d_nid'];

            // Fetch doctor's name
            $doctor_query = mysqli_query($conn, "SELECT name FROM person WHERE nid = '$did'");
            $doctorname = ($doctor_query && mysqli_num_rows($doctor_query) > 0) ? mysqli_fetch_array($doctor_query) : ['name' => 'N/A'];

            // Fetch patient's name
            $patient_query = mysqli_query($conn, "SELECT name FROM person WHERE nid = '$pid'");
            $patientname = ($patient_query && mysqli_num_rows($patient_query) > 0) ? mysqli_fetch_array($patient_query) : ['name' => 'N/A'];

            // Fetch child name if applicable
            $childname = null;
            if ($prescription['childbirth_id'] != "N/A") {
                $childid = $prescription['childbirth_id'];
                $child_query = mysqli_query($conn, "SELECT name FROM patientbelow18 WHERE childbirth_id = '$childid'");
                $childname = ($child_query && mysqli_num_rows($child_query) > 0) ? mysqli_fetch_array($child_query) : null;
            }

            // Count drugs
            $drug_count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM drug WHERE prescription_id = '$prescription_id'");
            $drug_data = ($drug_count_query) ? mysqli_fetch_assoc($drug_count_query) : null;
            $total_drugs = ($drug_data && isset($drug_data['total'])) ? $drug_data['total'] : 0;

            // Count diseases
            $dis_count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM detectdisease WHERE prescription_id = '$prescription_id'");
            $dis_data = ($dis_count_query) ? mysqli_fetch_assoc($dis_count_query) : null;
            $total_diseases = ($dis_data && isset($dis_data['total'])) ? $dis_data['total'] : 0;

            // Count tests
            $test_count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM doctorgivetest WHERE prescription_id = '$prescription_id'");
            $test_data = ($test_count_query) ? mysqli_fetch_assoc($test_count_query) : null;
            $total_tests = ($test_data && isset($test_data['total'])) ? $test_data['total'] : 0;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Prescription Management - IBHS">
    <meta name="author" content="">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title>Prescription #<?php echo $prescription_id; ?> | IBHS</title>

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

        .nav-user-badge .divname { font-size: 12px !important; font-weight: 600 !important; color: var(--text-dark) !important; line-height: 1.2; }
        .nav-user-badge .divid { font-size: 9px !important; font-weight: 500 !important; color: var(--text-light) !important; }

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

        /* ============ MAIN CONTENT ============ */
        .main-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        /* ============ INFO CARDS ============ */
        .info-cards-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .info-mini-card {
            background: #fff;
            border-radius: var(--radius);
            padding: 16px 18px;
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: var(--transition);
        }

        .info-mini-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }

        .mini-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #fff;
            flex-shrink: 0;
        }

        .mini-icon.doctor { background: linear-gradient(135deg, #3498db, #2980b9); }
        .mini-icon.patient { background: linear-gradient(135deg, #27ae60, #2ecc71); }
        .mini-icon.child { background: linear-gradient(135deg, #9b59b6, #8e44ad); }

        .mini-info .mini-label { font-size: 0.68rem; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px; }
        .mini-info .mini-value { font-weight: 600; font-size: 0.85rem; color: var(--text-dark); }

        /* ============ SECTION CARDS ============ */
        .section-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            overflow: hidden;
            margin-bottom: 22px;
        }

        .section-header {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fafafa;
        }

        .section-header .section-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            color: #fff;
        }

        .section-icon.drugs { background: linear-gradient(135deg, #e74c3c, #c0392b); }
        .section-icon.diseases { background: linear-gradient(135deg, #f39c12, #e67e22); }
        .section-icon.tests { background: linear-gradient(135deg, #3498db, #2980b9); }
        .section-icon.refer { background: linear-gradient(135deg, #9b59b6, #8e44ad); }

        .section-header h5 { margin: 0; font-weight: 600; font-size: 0.9rem; }
        .section-header .badge-count {
            margin-left: auto;
            background: #eee;
            color: #666;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .custom-table { width: 100%; margin: 0; }
        
        .custom-table thead th {
            background: #f8f9fa;
            border: none;
            border-bottom: 2px solid #eee;
            padding: 12px 15px;
            font-weight: 600;
            color: #555;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .custom-table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f5f5f5;
            font-size: 0.84rem;
        }

        .custom-table tbody tr { transition: var(--transition); }
        .custom-table tbody tr:hover { background: #f8fdf9; }
        .custom-table tbody tr:last-child td { border-bottom: none; }

        .btn-delete-sm {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            background: #ffebee;
            color: #c62828;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-block;
        }

        .btn-delete-sm:hover { background: #c62828; color: #fff; }

        .btn-add-section {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.82rem;
            cursor: pointer;
            transition: var(--transition);
            margin: 15px 20px;
        }

        .btn-add-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 155, 70, 0.3);
        }

        .btn-save-section {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 12px 28px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.88rem;
            cursor: pointer;
            transition: var(--transition);
            margin: 20px;
        }

        .btn-save-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 155, 70, 0.3);
        }

        /* ============ REFER & REMARKS SECTION ============ */
        .refer-section {
            padding: 20px;
            background: #fafafa;
            border-top: 1px solid #eee;
        }

        .refer-section .form-group {
            margin-bottom: 15px;
        }

        .refer-section label {
            font-weight: 600;
            font-size: 0.82rem;
            color: #555;
            margin-bottom: 6px;
            display: block;
        }

        .refer-section label i { color: var(--primary); margin-right: 6px; }

        .refer-section .form-control {
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            padding: 10px 15px;
            font-size: 0.85rem;
            font-family: 'Poppins', sans-serif;
            transition: var(--transition);
        }

        .refer-section .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 155, 70, 0.1);
        }

        .empty-state {
            text-align: center;
            padding: 30px;
            color: #bbb;
        }

        .empty-state i { font-size: 2.5rem; display: block; margin-bottom: 10px; }

        /* ============ MODAL ============ */
        .modal-content { border: none; border-radius: var(--radius); overflow: hidden; }
        .modal-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            padding: 14px 20px;
        }
        .modal-header .close { color: #fff; opacity: 1; text-shadow: none; }
        .modal-title { font-weight: 600; font-size: 0.95rem; }
        .modal-body { padding: 22px; }
        .modal-body .form-control {
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            padding: 10px 15px;
            font-size: 0.85rem;
            font-family: 'Poppins', sans-serif;
        }
        .modal-body .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 0.2rem rgba(0,155,70,0.1); }
        .modal-body label { font-weight: 600; font-size: 0.82rem; color: #555; margin-bottom: 5px; }
        .modal-footer { border-top: 1px solid #f0f0f0; padding: 14px 20px; }
        .btn-modal-primary { background: var(--primary); color: #fff; border: none; padding: 10px 22px; border-radius: 10px; font-weight: 600; }
        .btn-modal-secondary { background: #e8e8e8; color: #555; border: none; padding: 10px 22px; border-radius: 10px; font-weight: 600; }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 768px) {
            body { padding-top: 75px; }
            .page-header { padding: 18px 0; border-radius: 0 0 20px 20px; }
            .info-cards-row { grid-template-columns: 1fr 1fr; }
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
                font-size: 0.75rem;
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
                <a href="patienthome.php"><img src="img/bg_logo1.png" alt="IBHS Logo"></a>
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
                <a href="patient.php"><i class="fas fa-users"></i> Patients</a>
                <i class="fas fa-chevron-right" style="font-size:0.7rem;"></i>
                <span>Prescription #<?php echo $prescription_id; ?></span>
            </div>
        </div>
    </div>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="container main-container">
        
        <?php if (!empty($prescription_id) && isset($prescription)): ?>

        <!-- Info Cards -->
        <div class="info-cards-row">
            <div class="info-mini-card">
                <div class="mini-icon doctor"><i class="fas fa-user-md"></i></div>
                <div class="mini-info">
                    <div class="mini-label">Doctor</div>
                    <div class="mini-value">Dr. <?php echo $doctorname['name'] ?? 'N/A'; ?></div>
                </div>
            </div>
            
            <?php if ($prescription['childbirth_id'] == "N/A"): ?>
            <div class="info-mini-card">
                <div class="mini-icon patient"><i class="fas fa-user"></i></div>
                <div class="mini-info">
                    <div class="mini-label">Patient</div>
                    <div class="mini-value"><?php echo $patientname['name'] ?? 'N/A'; ?></div>
                </div>
            </div>
            <?php else: ?>
            <div class="info-mini-card">
                <div class="mini-icon patient"><i class="fas fa-user-shield"></i></div>
                <div class="mini-info">
                    <div class="mini-label">Guardian</div>
                    <div class="mini-value"><?php echo $patientname['name'] ?? 'N/A'; ?></div>
                </div>
            </div>
            <div class="info-mini-card">
                <div class="mini-icon child"><i class="fas fa-child"></i></div>
                <div class="mini-info">
                    <div class="mini-label">Patient (Child)</div>
                    <div class="mini-value"><?php echo $childname['name'] ?? 'N/A'; ?></div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Drugs Section -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon drugs"><i class="fas fa-pills"></i></div>
                <h5>Prescribed Drugs</h5>
                <span class="badge-count"><?php echo $total_drugs; ?> Items</span>
            </div>
            <?php
                $drug_query = mysqli_query($conn,"SELECT * FROM drug WHERE prescription_id = '$prescription_id'");
                if($drug_query && mysqli_num_rows($drug_query) > 0):
            ?>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Drug Name</th>
                            <th>Power</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($pre = mysqli_fetch_array($drug_query)): ?>
                        <tr>
                            <td data-label="Drug Name"><strong><?php echo $pre['drug_name']; ?></strong></td>
                            <td data-label="Power"><?php echo $pre['power']; ?></td>
                            <td data-label="Type"><?php echo $pre['drug_type']; ?></td>
                            <td data-label="Description"><?php echo $pre['description'] ?: '—'; ?></td>
                            <td data-label="Action">
                                <a href="deletedrug.php?drug_name=<?php echo urlencode($pre['drug_name']); ?>&preid=<?php echo $pre['prescription_id']; ?>&power=<?php echo urlencode($pre['power']); ?>&drug_type=<?php echo urlencode($pre['drug_type']); ?>" class="btn-delete-sm" onclick="return confirm('Delete this drug?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state"><i class="fas fa-pills"></i><small>No drugs added yet</small></div>
            <?php endif; ?>
            <button type="button" class="btn-add-section" data-toggle="modal" data-target="#drugadd">
                <i class="fas fa-plus-circle"></i> Add Drug
            </button>
        </div>

        <!-- Diseases Section -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon diseases"><i class="fas fa-virus"></i></div>
                <h5>Detected Diseases</h5>
                <span class="badge-count"><?php echo $total_diseases; ?> Items</span>
            </div>
            <?php
                $dis_query = mysqli_query($conn,"SELECT * FROM detectdisease WHERE prescription_id = '$prescription_id'");
                if($dis_query && mysqli_num_rows($dis_query) > 0):
            ?>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr><th>Disease Name</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <?php while($pre = mysqli_fetch_array($dis_query)): ?>
                        <tr>
                            <td data-label="Disease Name"><strong><?php echo $pre['disease_name']; ?></strong></td>
                            <td data-label="Action">
                                <a href="diseasedelete.php?diseasename=<?php echo urlencode($pre['disease_name']); ?>&preid=<?php echo $pre['prescription_id']; ?>" class="btn-delete-sm" onclick="return confirm('Delete this disease?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state"><i class="fas fa-virus"></i><small>No diseases detected</small></div>
            <?php endif; ?>
            <button type="button" class="btn-add-section" data-toggle="modal" data-target="#finddisease">
                <i class="fas fa-plus-circle"></i> Add Disease
            </button>
        </div>

        <!-- Tests Section -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon tests"><i class="fas fa-flask"></i></div>
                <h5>Recommended Tests</h5>
                <span class="badge-count"><?php echo $total_tests; ?> Items</span>
            </div>
            <?php
                $test_query = mysqli_query($conn,"SELECT dgt.*, t.test_name FROM doctorgivetest dgt INNER JOIN test t ON dgt.test_id = t.test_id WHERE dgt.prescription_id = '$prescription_id'");
                if($test_query && mysqli_num_rows($test_query) > 0):
            ?>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Test ID</th>
                            <th>Test Name</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($pre = mysqli_fetch_array($test_query)): ?>
                        <tr>
                            <td data-label="Test ID"><?php echo $pre['test_id']; ?></td>
                            <td data-label="Test Name"><?php echo $pre['test_name']; ?></td>
                            <td data-label="Date"><?php echo $pre['giving']; ?></td>
                            <td data-label="Action">
                                <a href="deletetest.php?preid=<?php echo $prescription_id; ?>&test_id=<?php echo $pre['test_id']; ?>" class="btn-delete-sm" onclick="return confirm('Delete this test?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state"><i class="fas fa-flask"></i><small>No tests added yet</small></div>
            <?php endif; ?>
            <button type="button" class="btn-add-section" data-toggle="modal" data-target="#addtest">
                <i class="fas fa-plus-circle"></i> Add Test
            </button>
        </div>

        <!-- Refer & Remarks Section -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon refer"><i class="fas fa-sticky-note"></i></div>
                <h5>Refer & Remarks</h5>
            </div>
            <div class="refer-section">
                <form action="prescriptionupdate.php" method="POST">
                    <input type="hidden" name="prescription_id" value="<?php echo $prescription_id; ?>">
                    
                    <div class="form-group">
                        <label><i class="fas fa-share"></i> Refer to Doctor</label>
                        <input type="text" class="form-control" list="doctor_ref" name="doctor_ref" placeholder="Search doctor by name...">
                        <datalist id="doctor_ref">
                            <?php 
                                $ref_query = mysqli_query($conn,"SELECT d.*, p.name, dm.specialist FROM doctor d INNER JOIN person p ON d.d_nid = p.nid INNER JOIN dmdc dm ON d.dmdc_id = dm.dmdc_id");
                                while($docref = mysqli_fetch_array($ref_query)):
                            ?>
                            <option value="<?php echo $docref['dmdc_id']; ?>"><?php echo $docref['name']." (".$docref['specialist'].")"; ?></option>
                            <?php endwhile; ?>
                        </datalist>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-pen"></i> Remarks</label>
                        <input type="text" class="form-control" name="remarks" placeholder="Add any remarks...">
                    </div>
                    
                    <button type="submit" class="btn-save-section" name="submit">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>

        <?php else: ?>
        <div class="section-card">
            <div class="empty-state">
                <i class="fas fa-exclamation-circle"></i>
                <h5>No prescription found</h5>
                <p class="text-muted">Please provide a valid prescription ID.</p>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <!-- ============ MODALS ============ -->
    <!-- Add Drug Modal -->
    <div class="modal fade" id="drugadd" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-pills mr-2"></i> Add Drug</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="druginsert.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="prescription_id" value="<?php echo $prescription_id; ?>">
                        <div class="form-group">
                            <label>Drug Name</label>
                            <input type="text" name="drug_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Power</label>
                            <input type="text" name="drug_power" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Drug Type</label>
                            <select class="form-control" name="type" required>
                                <option value="Tablet">Tablet</option>
                                <option value="Capsule">Capsule</option>
                                <option value="Syrup">Syrup</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="description" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-modal-primary" name="add">Add Drug</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Disease Modal -->
    <div class="modal fade" id="finddisease" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-virus mr-2"></i> Detect Disease</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="detectdisease.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="prescription_id" value="<?php echo $prescription_id; ?>">
                        <div class="form-group">
                            <label>Disease Name</label>
                            <input type="text" name="disease" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-modal-primary" name="delect">Add Disease</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Test Modal -->
    <div class="modal fade" id="addtest" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-flask mr-2"></i> Add Test</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="doctestinsert.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="prescription_id" value="<?php echo $prescription_id; ?>">
                        <div class="form-group">
                            <label>Select Test</label>
                            <select class="form-control" name="test" required>
                                <?php
                                    $test_list_query = mysqli_query($conn,"SELECT * FROM test");
                                    while($test = mysqli_fetch_array($test_list_query)):
                                ?>
                                <option value="<?php echo $test['test_id']; ?>"><?php echo $test['test_name']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-modal-primary" name="testadd">Add Test</button>
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