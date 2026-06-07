<?php
include_once('../dbconn.php');
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: index.php");
    exit();
}

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $d_nid        = trim($_POST['d_nid']);
    $dmdc_id      = trim($_POST['dmdc_id']);
    $visiting_fee = filter_var($_POST['visiting_fee'], FILTER_VALIDATE_INT);
    $password     = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $specialist   = trim($_POST['specialist']);
    $name         = trim($_POST['name']);
    $mobile_no    = trim($_POST['mobile_no']);
    $gender       = (int)$_POST['gender'];
    $blood        = $_POST['blood'];
    
    // Validate required fields
    $errors = [];
    
    if (empty($d_nid)) {
        $errors[] = "NID is required";
    } elseif (!preg_match('/^\d{10}$|^\d{17}$/', $d_nid)) {
        $errors[] = "NID must be 10 or 17 digits";
    }
    
    if (empty($dmdc_id)) {
        $errors[] = "DMDC ID is required";
    }
    
    if (!$visiting_fee || $visiting_fee < 0) {
        $errors[] = "Valid visiting fee is required";
    }
    
    if (empty($name)) {
        $errors[] = "Doctor name is required";
    }
    
    if (empty($mobile_no) || !preg_match('/^01[3-9]\d{8}$/', $mobile_no)) {
        $errors[] = "Valid Bangladeshi mobile number is required (01XXXXXXXXX)";
    }
    
    if ($gender < 1 || $gender > 3) {
        $errors[] = "Valid gender is required";
    }
    
    if (empty($blood)) {
        $errors[] = "Blood group is required";
    }
    
    $finger_print = isset($_POST['finger_print']) ? (int)$_POST['finger_print'] : null;
    $retina_print = isset($_POST['retina_print']) ? (int)$_POST['retina_print'] : null;
    
    // Check if NID already exists
    $check_stmt = $conn->prepare("SELECT nid FROM person WHERE nid = ?");
    $check_stmt->bind_param("s", $d_nid);
    $check_stmt->execute();
    $check_stmt->store_result();
    
    if ($check_stmt->num_rows > 0) {
        $errors[] = "A person with this NID already exists.";
    }
    $check_stmt->close();
    
    // Check if DMDC ID already exists
    $check_dmdc = $conn->prepare("SELECT dmdc_id FROM doctor WHERE dmdc_id = ?");
    $check_dmdc->bind_param("s", $dmdc_id);
    $check_dmdc->execute();
    $check_dmdc->store_result();
    
    if ($check_dmdc->num_rows > 0) {
        $errors[] = "A doctor with this DMDC ID already exists.";
    }
    $check_dmdc->close();
    
    if (empty($errors)) {
        $conn->begin_transaction();
        
        try {
            $stmt1 = $conn->prepare("INSERT INTO person (nid, name, mobile_no, gender, blood, finger_print, retina_print) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt1->bind_param("sssissi", $d_nid, $name, $mobile_no, $gender, $blood, $finger_print, $retina_print);
            $stmt1->execute();
            
            $stmt2 = $conn->prepare("INSERT INTO doctor (d_nid, dmdc_id, visiting_fee, password, specialist) VALUES (?, ?, ?, ?, ?)");
            $stmt2->bind_param("ssiss", $d_nid, $dmdc_id, $visiting_fee, $password, $specialist);
            $stmt2->execute();
            
            $conn->commit();
            $message = "Doctor added successfully!";
            $_POST = array();
            
        } catch (mysqli_sql_exception $e) {
            $conn->rollback();
            $error = "Database Error: " . $e->getMessage();
        }
    } else {
        $error = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Doctor | IBHS Admin</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.05)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
            pointer-events: none;
            z-index: 0;
        }

        /* Floating particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s infinite ease-in-out;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Main Card */
        .main-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .main-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.3);
        }

        /* Header Section */
        .card-header-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 35px 40px;
            position: relative;
            overflow: hidden;
        }

        .card-header-custom::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .header-icon {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }

        .header-icon:hover {
            transform: rotate(10deg) scale(1.1);
        }

        /* Form Section */
        .form-section {
            padding: 40px;
        }

        /* Form Groups */
        .form-group-custom {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label-custom {
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 8px;
            color: #4a5568;
            display: flex;
            align-items: center;
            gap: 8px;
            letter-spacing: 0.5px;
        }

        .form-label-custom i {
            color: #667eea;
            font-size: 0.9rem;
        }

        .required-star {
            color: #e53e3e;
            margin-left: 4px;
        }

        .form-control-custom, .form-select-custom {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control-custom:focus, .form-select-custom:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-control-custom:hover, .form-select-custom:hover {
            border-color: #c3dafe;
        }

        /* Input with icon */
        .input-icon-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            transition: color 0.3s ease;
        }

        .input-icon-wrapper .form-control-custom {
            padding-left: 45px;
        }

        .input-icon-wrapper .form-control-custom:focus + .input-icon {
            color: #667eea;
        }

        /* Button Styles */
        .btn-custom {
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .btn-secondary-custom {
            background: #f7fafc;
            color: #4a5568;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary-custom:hover {
            background: #edf2f7;
            transform: translateY(-2px);
        }

        /* Alert Styles */
        .alert-custom {
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 25px;
            border: none;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success-custom {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
        }

        .alert-danger-custom {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            color: white;
        }

        /* Blood Group Badge Preview */
        .blood-preview {
            margin-top: 5px;
            font-size: 0.75rem;
            color: #718096;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-header-custom {
                padding: 25px 20px;
            }
            .form-section {
                padding: 25px 20px;
            }
            .header-icon {
                width: 50px;
                height: 50px;
                font-size: 24px;
            }
        }

        /* Loading animation for submit button */
        .btn-loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Stats Cards */
        .stats-mini {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card-mini {
            background: white;
            padding: 15px;
            border-radius: 16px;
            text-align: center;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .stat-card-mini:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 800;
            color: #2d3748;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.7rem;
            color: #718096;
            margin-top: 5px;
        }

        /* Tooltip */
        .tooltip-custom {
            position: absolute;
            background: #2d3748;
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.7rem;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-bottom: 5px;
            white-space: nowrap;
            display: none;
            z-index: 10;
        }

        .form-group-custom:hover .tooltip-custom {
            display: block;
        }
    </style>
</head>
<body>

<!-- Animated Particles -->
<div class="particles" id="particles"></div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10" data-aos="fade-up" data-aos-duration="800">
            
            <!-- Stats Mini Cards -->
            <div class="stats-mini">
                <div class="stat-card-mini" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-number" id="totalDoctors">--</div>
                    <div class="stat-label">Total Doctors</div>
                </div>
                <div class="stat-card-mini" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-number" id="totalHospitals">--</div>
                    <div class="stat-label">Hospitals</div>
                </div>
                <div class="stat-card-mini" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-number" id="todayAppointments">--</div>
                    <div class="stat-label">Today's Appointments</div>
                </div>
                <div class="stat-card-mini" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-number" id="activeDoctors">--</div>
                    <div class="stat-label">Active Doctors</div>
                </div>
            </div>
            
            <!-- Main Card -->
            <div class="main-card">
                <div class="card-header-custom">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="header-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h2 class="text-white mb-1 fw-bold" style="font-size: 1.8rem;">
                                Add New Doctor
                            </h2>
                            <p class="text-white-50 mb-0">
                                <i class="fas fa-calendar-alt me-1"></i> 
                                <?php echo date('l, F j, Y'); ?>
                            </p>
                        </div>
                        <div class="text-end">
                            <div class="text-white-50 small">
                                <i class="fas fa-shield-alt me-1"></i> Admin Panel
                            </div>
                            <div class="text-white fw-bold">
                                <i class="fas fa-user-circle me-1"></i>
                                <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    
                    <!-- Alerts -->
                    <?php if ($message): ?>
                        <div class="alert-custom alert-success-custom" data-aos="fade-down">
                            <i class="fas fa-check-circle fa-lg"></i>
                            <div class="flex-grow-1"><?= htmlspecialchars($message) ?></div>
                            <button type="button" class="btn-close btn-close-white" onclick="this.parentElement.remove()"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert-custom alert-danger-custom" data-aos="fade-down">
                            <i class="fas fa-exclamation-triangle fa-lg"></i>
                            <div class="flex-grow-1"><?= $error ?></div>
                            <button type="button" class="btn-close btn-close-white" onclick="this.parentElement.remove()"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Form -->
                    <form method="POST" action="" id="doctorForm">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-lg-6">
                                <div class="form-group-custom" data-aos="fade-right" data-aos-delay="100">
                                    <label class="form-label-custom">
                                        <i class="fas fa-id-card"></i>
                                        NID Number <span class="required-star">*</span>
                                    </label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-credit-card input-icon"></i>
                                        <input type="text" name="d_nid" class="form-control-custom" 
                                               value="<?= htmlspecialchars($_POST['d_nid'] ?? '') ?>"
                                               placeholder="Enter 10 or 17 digit NID" required>
                                    </div>
                                    <div class="blood-preview">
                                        <i class="fas fa-info-circle"></i> Must be 10 or 17 digits
                                    </div>
                                </div>
                                
                                <div class="form-group-custom" data-aos="fade-right" data-aos-delay="150">
                                    <label class="form-label-custom">
                                        <i class="fas fa-stethoscope"></i>
                                        DMDC ID <span class="required-star">*</span>
                                    </label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-qrcode input-icon"></i>
                                        <input type="text" name="dmdc_id" class="form-control-custom" 
                                               value="<?= htmlspecialchars($_POST['dmdc_id'] ?? '') ?>"
                                               placeholder="Enter DMDC registration ID" required>
                                    </div>
                                </div>
                                
                                <div class="form-group-custom" data-aos="fade-right" data-aos-delay="200">
                                    <label class="form-label-custom">
                                        <i class="fas fa-money-bill-wave"></i>
                                        Visiting Fee (৳) <span class="required-star">*</span>
                                    </label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-taka-sign input-icon"></i>
                                        <input type="number" name="visiting_fee" class="form-control-custom" 
                                               value="<?= htmlspecialchars($_POST['visiting_fee'] ?? '') ?>"
                                               placeholder="e.g., 800" required>
                                    </div>
                                </div>
                                
                                <div class="form-group-custom" data-aos="fade-right" data-aos-delay="250">
                                    <label class="form-label-custom">
                                        <i class="fas fa-lock"></i>
                                        Password <span class="required-star">*</span>
                                    </label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-key input-icon"></i>
                                        <input type="password" name="password" class="form-control-custom" 
                                               id="password" placeholder="Create a secure password" required>
                                    </div>
                                    <div class="blood-preview">
                                        <i class="fas fa-shield-alt"></i> Minimum 6 characters
                                    </div>
                                </div>
                                
                                <div class="form-group-custom" data-aos="fade-right" data-aos-delay="300">
                                    <label class="form-label-custom">
                                        <i class="fas fa-brain"></i>
                                        Specialist <span class="required-star">*</span>
                                    </label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-graduation-cap input-icon"></i>
                                        <input type="text" name="specialist" class="form-control-custom" 
                                               value="<?= htmlspecialchars($_POST['specialist'] ?? '') ?>"
                                               placeholder="e.g., Cardiologist, Neurologist" required>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Column -->
                            <div class="col-lg-6">
                                <div class="form-group-custom" data-aos="fade-left" data-aos-delay="100">
                                    <label class="form-label-custom">
                                        <i class="fas fa-user"></i>
                                        Full Name <span class="required-star">*</span>
                                    </label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-signature input-icon"></i>
                                        <input type="text" name="name" class="form-control-custom" 
                                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                               placeholder="Doctor's full name" required>
                                    </div>
                                </div>
                                
                                <div class="form-group-custom" data-aos="fade-left" data-aos-delay="150">
                                    <label class="form-label-custom">
                                        <i class="fas fa-phone-alt"></i>
                                        Mobile Number <span class="required-star">*</span>
                                    </label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-mobile-alt input-icon"></i>
                                        <input type="tel" name="mobile_no" class="form-control-custom" 
                                               value="<?= htmlspecialchars($_POST['mobile_no'] ?? '') ?>"
                                               placeholder="01XXXXXXXXX" required>
                                    </div>
                                    <div class="blood-preview">
                                        <i class="fas fa-check-circle"></i> Bangladeshi mobile number
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom" data-aos="fade-left" data-aos-delay="200">
                                            <label class="form-label-custom">
                                                <i class="fas fa-venus-mars"></i>
                                                Gender <span class="required-star">*</span>
                                            </label>
                                            <select name="gender" class="form-select-custom" required>
                                                <option value="">Select Gender</option>
                                                <option value="1" <?= (isset($_POST['gender']) && $_POST['gender'] == '1') ? 'selected' : '' ?>>👨 Male</option>
                                                <option value="2" <?= (isset($_POST['gender']) && $_POST['gender'] == '2') ? 'selected' : '' ?>>👩 Female</option>
                                                <option value="3" <?= (isset($_POST['gender']) && $_POST['gender'] == '3') ? 'selected' : '' ?>>👤 Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-custom" data-aos="fade-left" data-aos-delay="250">
                                            <label class="form-label-custom">
                                                <i class="fas fa-tint"></i>
                                                Blood Group <span class="required-star">*</span>
                                            </label>
                                            <select name="blood" class="form-select-custom" id="bloodGroup" required>
                                                <option value="">Select Blood Group</option>
                                                <option value="A+" <?= (isset($_POST['blood']) && $_POST['blood'] == 'A+') ? 'selected' : '' ?>>A+ 🩸</option>
                                                <option value="A-" <?= (isset($_POST['blood']) && $_POST['blood'] == 'A-') ? 'selected' : '' ?>>A- 🩸</option>
                                                <option value="B+" <?= (isset($_POST['blood']) && $_POST['blood'] == 'B+') ? 'selected' : '' ?>>B+ 🩸</option>
                                                <option value="B-" <?= (isset($_POST['blood']) && $_POST['blood'] == 'B-') ? 'selected' : '' ?>>B- 🩸</option>
                                                <option value="O+" <?= (isset($_POST['blood']) && $_POST['blood'] == 'O+') ? 'selected' : '' ?>>O+ 🩸</option>
                                                <option value="O-" <?= (isset($_POST['blood']) && $_POST['blood'] == 'O-') ? 'selected' : '' ?>>O- 🩸</option>
                                                <option value="AB+" <?= (isset($_POST['blood']) && $_POST['blood'] == 'AB+') ? 'selected' : '' ?>>AB+ 🩸</option>
                                                <option value="AB-" <?= (isset($_POST['blood']) && $_POST['blood'] == 'AB-') ? 'selected' : '' ?>>AB- 🩸</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom" data-aos="fade-left" data-aos-delay="300">
                                            <label class="form-label-custom">
                                                <i class="fas fa-fingerprint"></i>
                                                Finger Print <span class="required-star">*</span>
                                            </label>
                                            <div class="input-icon-wrapper">
                                                <i class="fas fa-hand-peace input-icon"></i>
                                                <input type="number" name="finger_print" class="form-control-custom" 
                                                       value="<?= htmlspecialchars($_POST['finger_print'] ?? '') ?>"
                                                       placeholder="Enter fingerprint code" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-custom" data-aos="fade-left" data-aos-delay="350">
                                            <label class="form-label-custom">
                                                <i class="fas fa-eye"></i>
                                                Retina Print <span class="required-star">*</span>
                                            </label>
                                            <div class="input-icon-wrapper">
                                                <i class="fas fa-eye input-icon"></i>
                                                <input type="number" name="retina_print" class="form-control-custom" 
                                                       value="<?= htmlspecialchars($_POST['retina_print'] ?? '') ?>"
                                                       placeholder="Enter retina scan code" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center gap-3 mt-4 pt-3">
                            <a href="manage_doctors.php" class="btn-custom btn-secondary-custom">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                            <button type="submit" class="btn-custom btn-primary-custom" id="submitBtn">
                                <i class="fas fa-plus-circle"></i> Register Doctor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    // Initialize AOS
    AOS.init({
        duration: 800,
        once: true,
        offset: 50
    });
    
    // Generate floating particles
    function createParticles() {
        const particlesContainer = document.getElementById('particles');
        const particleCount = 50;
        
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            const size = Math.random() * 5 + 2;
            particle.style.width = size + 'px';
            particle.style.height = size + 'px';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 5 + 's';
            particle.style.animationDuration = Math.random() * 4 + 3 + 's';
            particlesContainer.appendChild(particle);
        }
    }
    
    // Fetch dashboard stats
    function fetchStats() {
        $.ajax({
            url: 'ajax_get_stats.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#totalDoctors').text(data.total_doctors || '0');
                $('#totalHospitals').text(data.total_hospitals || '0');
                $('#todayAppointments').text(data.today_appointments || '0');
                $('#activeDoctors').text(data.active_doctors || '0');
            },
            error: function() {
                $('#totalDoctors').text('0');
                $('#totalHospitals').text('0');
                $('#todayAppointments').text('0');
                $('#activeDoctors').text('0');
            }
        });
    }
    
    // Form submission animation
    $('#doctorForm').on('submit', function() {
        const btn = $('#submitBtn');
        btn.addClass('btn-loading');
        btn.html('<i class="fas fa-spinner"></i> Registering...');
        
        setTimeout(function() {
            btn.removeClass('btn-loading');
        }, 3000);
    });
    
    // Blood group preview color
    $('#bloodGroup').on('change', function() {
        const selected = $(this).val();
        if (selected) {
            $(this).css('border-color', '#48bb78');
        }
    });
    
    // Initialize
    createParticles();
    fetchStats();
    
    // Auto-refresh stats every 30 seconds
    setInterval(fetchStats, 30000);
    
    // Password strength indicator
    $('#password').on('input', function() {
        const password = $(this).val();
        const strength = getPasswordStrength(password);
        $(this).css('border-color', strength.color);
    });
    
    function getPasswordStrength(password) {
        if (password.length < 6) {
            return { color: '#e53e3e' };
        } else if (password.length < 8) {
            return { color: '#ed8936' };
        } else {
            return { color: '#48bb78' };
        }
    }
</script>

</body>
</html>