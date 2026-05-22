<?php
    include('dbconn.php');
    include('session.php');
    
    // FIXED: Null-safe GET parameter
    $prescription_id = isset($_GET['id']) ? $_GET['id'] : '';
    
    // FIXED: Null-safe queries
    $query = mysqli_query($conn,"SELECT * FROM prescription WHERE prescription_id = '$prescription_id'");
    $prescription = ($query && mysqli_num_rows($query) > 0) ? mysqli_fetch_array($query) : null;
    
    if(!$prescription) {
        echo "<script>alert('Prescription not found!'); window.location.href='patienthome.php';</script>";
        exit();
    }
    
    $pid = $prescription['p_nid'];
    $did = $prescription['d_nid'];
    
    $p_query = mysqli_query($conn,"SELECT name FROM person WHERE nid = '$pid'");
    $patientname = ($p_query) ? mysqli_fetch_array($p_query) : null;
    
    $d_query = mysqli_query($conn,"SELECT name FROM person WHERE nid = '$did'");
    $doctorname = ($d_query) ? mysqli_fetch_array($d_query) : null;
    
    $childname = null;
    if($prescription['childbirth_id'] != "N/A"){
        $childid = $prescription['childbirth_id'];
        $c_query = mysqli_query($conn,"SELECT name FROM patientbelow18 WHERE childbirth_id = '$childid'");
        $childname = ($c_query) ? mysqli_fetch_array($c_query) : null;
    }
    
    // Count drugs
    $drug_count = mysqli_query($conn,"SELECT COUNT(*) as total FROM drug WHERE prescription_id = '$prescription_id'");
    $drug_data = ($drug_count) ? mysqli_fetch_assoc($drug_count) : null;
    $total_drugs = ($drug_data) ? $drug_data['total'] : 0;
    
    // Count diseases
    $dis_count = mysqli_query($conn,"SELECT COUNT(*) as total FROM detectdisease WHERE prescription_id = '$prescription_id'");
    $dis_data = ($dis_count) ? mysqli_fetch_assoc($dis_count) : null;
    $total_diseases = ($dis_data) ? $dis_data['total'] : 0;
    
    // Count tests
    $test_count = mysqli_query($conn,"SELECT COUNT(*) as total FROM doctorgivetest WHERE prescription_id = '$prescription_id'");
    $test_data = ($test_count) ? mysqli_fetch_assoc($test_count) : null;
    $total_tests = ($test_data) ? $test_data['total'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Prescription View - IBHS">
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

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 1;
            flex-wrap: wrap;
            gap: 15px;
        }

        .breadcrumb-row {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.9);
            font-size: 0.85rem;
        }

        .breadcrumb-row a { color: rgba(255,255,255,0.9); text-decoration: none; }
        .breadcrumb-row a:hover { color: #fff; text-decoration: underline; }
        .breadcrumb-row span { color: #00d2ff; font-weight: 600; }

        /* Download Button */
        .btn-download-presc {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            background: #fff;
            color: var(--primary);
            border: 2px solid #fff;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
            font-family: 'Poppins', sans-serif;
        }

        .btn-download-presc:hover {
            background: rgba(255,255,255,0.9);
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.25);
        }

        /* ============ INFO CARDS ROW ============ */
        .info-cards-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .info-mini-card {
            background: #fff;
            border-radius: var(--radius);
            padding: 18px 20px;
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: var(--transition);
        }

        .info-mini-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }

        .mini-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #fff;
            flex-shrink: 0;
        }

        .mini-icon.doctor { background: linear-gradient(135deg, #3498db, #2980b9); }
        .mini-icon.patient { background: linear-gradient(135deg, #27ae60, #2ecc71); }
        .mini-icon.child { background: linear-gradient(135deg, #9b59b6, #8e44ad); }
        .mini-icon.refer { background: linear-gradient(135deg, #e67e22, #d35400); }

        .mini-info .mini-label {
            font-size: 0.7rem;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .mini-info .mini-value {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-dark);
        }

        .mini-info .mini-value a {
            color: var(--primary);
            text-decoration: none;
        }

        .mini-info .mini-value a:hover { text-decoration: underline; }

        /* ============ SECTION CARDS ============ */
        .section-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            overflow: hidden;
            margin-bottom: 25px;
        }

        .section-header {
            padding: 16px 22px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fafafa;
        }

        .section-header .section-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #fff;
        }

        .section-icon.drugs { background: linear-gradient(135deg, #e74c3c, #c0392b); }
        .section-icon.diseases { background: linear-gradient(135deg, #f39c12, #e67e22); }
        .section-icon.tests { background: linear-gradient(135deg, #3498db, #2980b9); }
        .section-icon.remarks { background: linear-gradient(135deg, #9b59b6, #8e44ad); }

        .section-header h5 { margin: 0; font-weight: 600; font-size: 0.95rem; }
        .section-header .badge-count {
            margin-left: auto;
            background: #eee;
            color: #666;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .custom-table { width: 100%; margin: 0; }
        
        .custom-table thead th {
            background: #f8f9fa;
            border: none;
            border-bottom: 2px solid #eee;
            padding: 12px 18px;
            font-weight: 600;
            color: #555;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .custom-table tbody td {
            padding: 12px 18px;
            vertical-align: middle;
            border-bottom: 1px solid #f5f5f5;
            font-size: 0.85rem;
        }

        .custom-table tbody tr { transition: var(--transition); }
        .custom-table tbody tr:hover { background: #f8fdf9; }
        .custom-table tbody tr:last-child td { border-bottom: none; }

        .btn-view-sm {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 15px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none;
        }

        .btn-view-sm:hover {
            background: var(--primary-dark);
            color: #fff;
            text-decoration: none;
            transform: translateY(-1px);
        }

        .result-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .result-available { background: #e8f5e9; color: #27ae60; }
        .result-pending { background: #fff3e0; color: #e67e22; }

        .remarks-box {
            padding: 20px 22px;
            font-size: 0.9rem;
            color: #555;
            line-height: 1.6;
        }

        .empty-state {
            text-align: center;
            padding: 30px;
            color: #bbb;
        }

        .empty-state i { font-size: 2.5rem; display: block; margin-bottom: 10px; }

        /* ============ PRINT TITLE ============ */
        .print-title {
            display: none;
        }

        /* ============ PRINT STYLES ============ */
        @media print {
            body {
                background: #fff !important;
                padding-top: 0 !important;
                font-size: 11px;
                color: #000 !important;
            }
            
            .navbar, 
            .page-header, 
            .btn-download-presc, 
            .btn-logout-nav,
            .nav-user-badge,
            footer, 
            .no-print,
            .breadcrumb-row,
            .header-actions,
            .btn-view-sm {
                display: none !important;
            }
            
            .container {
                max-width: 100% !important;
                width: 100% !important;
                padding: 0 10px !important;
                margin: 0 !important;
            }
            
            .section-card {
                box-shadow: none !important;
                border: 1px solid #ccc !important;
                page-break-inside: avoid;
                margin-bottom: 12px !important;
            }
            
            .section-header {
                background: #f5f5f5 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .info-cards-row {
                display: grid !important;
                grid-template-columns: repeat(4, 1fr) !important;
                gap: 8px !important;
            }
            
            .info-mini-card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                padding: 8px !important;
            }
            
            .mini-icon {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                width: 35px !important;
                height: 35px !important;
                font-size: 16px !important;
            }
            
            .custom-table thead th {
                background: #eee !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .custom-table tbody td {
                padding: 8px 12px !important;
                font-size: 0.8rem !important;
            }
            
            .empty-state { display: none !important; }
            
            .result-badge {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .section-icon {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .print-title {
                display: block !important;
                text-align: center;
                margin-bottom: 15px;
                padding-top: 10px;
            }
            
            .print-title h3 {
                font-size: 1.3rem;
                font-weight: 700;
                margin: 0;
                color: #000;
            }
            
            .print-title p {
                font-size: 0.9rem;
                color: #555;
                margin: 5px 0;
            }
            
            .print-title hr {
                border: 1px solid #000;
            }
            
            @page {
                margin: 0.8cm;
                size: A4;
            }
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 768px) {
            body { padding-top: 75px; }
            .page-header { padding: 18px 0; border-radius: 0 0 20px 20px; }
            .info-cards-row { grid-template-columns: 1fr 1fr; }
            .header-content { flex-direction: column; align-items: flex-start; }
            
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
                font-size: 0.78rem;
            }
            .custom-table tbody tr { display: block; border-bottom: 1px solid #eee; padding: 5px 0; }
        }

        @media (max-width: 480px) {
            .info-cards-row { grid-template-columns: 1fr; }
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
            <div class="header-content">
                <div class="breadcrumb-row">
                    <a href="myprescription.php"><i class="fas fa-prescription"></i> Prescriptions</a>
                    <i class="fas fa-chevron-right" style="font-size:0.7rem;"></i>
                    <span>Prescription #<?php echo $prescription_id; ?></span>
                </div>
                <!-- PRINT / PDF DOWNLOAD BUTTON -->
                <div class="header-actions">
                    <button onclick="printPrescription()" class="btn-download-presc">
                        <i class="fas fa-print"></i> Print / Download PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ MAIN CONTENT ============ -->
    <div class="container">
        
        <!-- Print Title (visible only when printing) -->
        <div class="print-title">
            <h3>Prescription #<?php echo $prescription_id; ?></h3>
            <p>
                <strong>Doctor:</strong> Dr. <?php echo $doctorname['name'] ?? 'N/A'; ?> | 
                <strong>Patient:</strong> <?php echo $patientname['name'] ?? 'N/A'; ?> | 
                <strong>Date:</strong> <?php echo date('d M Y'); ?>
            </p>
            <hr>
        </div>

        <!-- Info Cards Row -->
        <div class="info-cards-row">
            <div class="info-mini-card">
                <div class="mini-icon doctor"><i class="fas fa-user-md"></i></div>
                <div class="mini-info">
                    <div class="mini-label">Doctor</div>
                    <div class="mini-value">
                        <a href="doctorprofile.php?id=<?php echo $did; ?>">Dr. <?php echo $doctorname['name'] ?? 'N/A'; ?></a>
                    </div>
                </div>
            </div>
            
            <?php if($prescription['childbirth_id'] == "N/A"): ?>
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
            
            <div class="info-mini-card">
                <div class="mini-icon refer"><i class="fas fa-share"></i></div>
                <div class="mini-info">
                    <div class="mini-label">Refer Doctor</div>
                    <div class="mini-value">
                        <?php 
                            $refnid = $prescription['doctor_ref'];
                            if($refnid){
                                $ref_query = mysqli_query($conn,"SELECT p.name, dm.specialist FROM doctor d INNER JOIN person p ON p.nid = d.d_nid INNER JOIN dmdc dm ON d.dmdc_id = dm.dmdc_id WHERE d.d_nid = '$refnid'");
                                $doc = ($ref_query) ? mysqli_fetch_array($ref_query) : null;
                                if($doc):
                        ?>
                            <a href="doctorprofile.php?id=<?php echo $refnid; ?>">Dr. <?php echo $doc['name']; ?></a>
                            <small style="color:#999;">(<?php echo $doc['specialist']; ?>)</small>
                        <?php else: ?>
                            <span style="color:#999;">NILL</span>
                        <?php endif; ?>
                        <?php } else { ?>
                            <span style="color:#999;">NILL</span>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Remarks Section -->
        <?php if(!empty($prescription['remarks'])): ?>
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon remarks"><i class="fas fa-sticky-note"></i></div>
                <h5>Remarks</h5>
            </div>
            <div class="remarks-box">
                <?php echo nl2br(htmlspecialchars($prescription['remarks'])); ?>
            </div>
        </div>
        <?php endif; ?>

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
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($pre = mysqli_fetch_array($drug_query)): ?>
                        <tr>
                            <td data-label="Drug Name"><strong><?php echo $pre['drug_name']; ?></strong></td>
                            <td data-label="Power"><?php echo $pre['power']; ?></td>
                            <td data-label="Type"><?php echo $pre['drug_type']; ?></td>
                            <td data-label="Description"><?php echo $pre['description'] ?: '—'; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state"><i class="fas fa-pills"></i><small>No drugs prescribed</small></div>
            <?php endif; ?>
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
                        <tr><th>Disease Name</th></tr>
                    </thead>
                    <tbody>
                        <?php while($pre = mysqli_fetch_array($dis_query)): ?>
                        <tr><td data-label="Disease Name"><strong><?php echo $pre['disease_name']; ?></strong></td></tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state"><i class="fas fa-virus"></i><small>No diseases detected</small></div>
            <?php endif; ?>
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
                            <th>Given Date</th>
                            <th>Result</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($pre = mysqli_fetch_array($test_query)):
                            $preid = $pre['prescription_id'];
                            $testid = $pre['test_id'];
                            $tr_query = mysqli_query($conn,"SELECT * FROM testreport WHERE prescription_id = '$preid' AND test_id = '$testid'");
                            $has_report = ($tr_query && mysqli_num_rows($tr_query) > 0);
                            if($has_report):
                                $testr = mysqli_fetch_array($tr_query);
                        ?>
                        <tr>
                            <td data-label="Test ID"><?php echo $pre['test_id']; ?></td>
                            <td data-label="Test Name"><?php echo $pre['test_name']; ?></td>
                            <td data-label="Given Date"><?php echo $pre['giving']; ?></td>
                            <td data-label="Result">
                                <span class="result-badge result-available"><?php echo $testr['result']; ?></span>
                            </td>
                            <td data-label="Action">
                                <a href="viewtestreport.php?id=<?php echo $preid; ?>&test_id=<?php echo $testid; ?>&serial=<?php echo $testr['serial']; ?>" class="btn-view-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        <?php else: ?>
                        <tr>
                            <td data-label="Test ID"><?php echo $pre['test_id']; ?></td>
                            <td data-label="Test Name"><?php echo $pre['test_name']; ?></td>
                            <td data-label="Given Date"><?php echo $pre['giving']; ?></td>
                            <td data-label="Result">
                                <span class="result-badge result-pending">Pending</span>
                            </td>
                            <td data-label="Action">—</td>
                        </tr>
                        <?php endif; endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state"><i class="fas fa-flask"></i><small>No tests recommended</small></div>
            <?php endif; ?>
        </div>

    </div>

    <!-- ============ SCRIPTS ============ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js"></script>
    
    <script>
        // Print / Download PDF function
        function printPrescription() {
            window.print();
        }
        
        // Keyboard shortcut Ctrl+P
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                // Default browser print will work
            }
        });
    </script>
</body>
</html>