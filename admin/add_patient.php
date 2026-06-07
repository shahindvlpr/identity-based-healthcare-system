<?php
include_once('../dbconn.php');
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $nid           = trim($_POST['nid']);
    $name          = trim($_POST['name']);
    $mobile_no     = trim($_POST['mobile_no']);
    $gender        = (int)$_POST['gender'];
    $blood         = $_POST['blood'];
    $finger_print  = trim($_POST['finger_print']);
    $retina_print  = trim($_POST['retina_print']);
    $password      = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Validation
    $errors = [];
    
    if (empty($nid)) {
        $errors[] = "NID is required";
    } elseif (!preg_match('/^\d{10}$|^\d{17}$/', $nid)) {
        $errors[] = "NID must be 10 or 17 digits";
    }
    
    if (empty($name)) {
        $errors[] = "Patient name is required";
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
    
    if (empty($_POST['password']) || strlen($_POST['password']) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    if (empty($finger_print)) {
        $errors[] = "Finger print code is required";
    }
    
    if (empty($retina_print)) {
        $errors[] = "Retina print code is required";
    }
    
    if (empty($errors)) {
        $conn->begin_transaction();
        
        try {
            // Check if nid already exists in person
            $stmt_check = $conn->prepare("SELECT nid FROM person WHERE nid = ?");
            $stmt_check->bind_param("s", $nid);
            $stmt_check->execute();
            $stmt_check->store_result();
            
            // If not exists, insert into person
            if ($stmt_check->num_rows === 0) {
                $stmt1 = $conn->prepare("INSERT INTO person (nid, name, mobile_no, gender, blood, finger_print, retina_print) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt1->bind_param("sssisss", $nid, $name, $mobile_no, $gender, $blood, $finger_print, $retina_print);
                $stmt1->execute();
            } else {
                // Person exists, check if already a patient
                $check_patient = $conn->prepare("SELECT p_nid FROM patient WHERE p_nid = ?");
                $check_patient->bind_param("s", $nid);
                $check_patient->execute();
                $check_patient->store_result();
                
                if ($check_patient->num_rows > 0) {
                    throw new Exception("This person is already registered as a patient!");
                }
            }
            
            // Insert into patient table
            $stmt2 = $conn->prepare("INSERT INTO patient (p_nid, password) VALUES (?, ?)");
            $stmt2->bind_param("ss", $nid, $password);
            $stmt2->execute();
            
            $conn->commit();
            $message = "Patient added successfully!";
            $_POST = array(); // Clear form
            
        } catch (mysqli_sql_exception $e) {
            $conn->rollback();
            $error = "Database Error: " . $e->getMessage();
        } catch (Exception $e) {
            $conn->rollback();
            $error = $e->getMessage();
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
    <title>Add Patient | IBHS Admin</title>
    
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
            background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.03)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') repeat-x bottom;
            background-size: cover;
            opacity: 0.4;
            pointer-events: none;
        }

        /* Floating Medical Icons */
        .floating-icons {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
            pointer-events: none;
        }

        .floating-icon {
            position: absolute;
            color: rgba(255, 255, 255, 0.06);
            font-size: 70px;
            animation: floatIcon 12s infinite ease-in-out;
        }

        @keyframes floatIcon {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-25px) rotate(5deg); }
        }

        /* Main Card */
        .main-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            transition: transform 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .main-card:hover {
            transform: translateY(-5px);
        }

        /* Header Section */
        .card-header-custom {
            background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
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
            animation: pulseGlow 4s ease-in-out infinite;
        }

        @keyframes pulseGlow {
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
            transition: all 0.3s ease;
        }

        .header-icon:hover {
            transform: rotate(10deg) scale(1.1);
            background: rgba(255, 255, 255, 0.3);
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
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 8px;
            letter-spacing: 0.3px;
        }

        .form-label-custom i {
            color: #0083b0;
            font-size: 0.9rem;
        }

        .required-star {
            color: #e53e3e;
            margin-left: 4px;
        }

        .input-icon-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            transition: all 0.3s ease;
            font-size: 1rem;
            z-index: 1;
        }

        .form-control-custom, .form-select-custom {
            width: 100%;
            padding: 12px 16px 12px 45px;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control-custom:focus, .form-select-custom:focus {
            outline: none;
            border-color: #00b4db;
            box-shadow: 0 0 0 4px rgba(0, 180, 219, 0.1);
        }

        .form-control-custom:hover, .form-select-custom:hover {
            border-color: #c3dafe;
        }

        /* Button Styles */
        .btn-custom {
            padding: 14px 28px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 180, 219, 0.4);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 180, 219, 0.5);
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

        /* Patient Avatar Preview */
        .avatar-preview {
            text-align: center;
            margin-bottom: 25px;
            padding: 20px;
            background: linear-gradient(135deg, #f7fafc, #edf2f7);
            border-radius: 20px;
        }

        .avatar-circle {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #00b4db, #0083b0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 48px;
            color: white;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .avatar-circle:hover {
            transform: scale(1.05);
        }

        /* Info Box */
        .info-box {
            background: linear-gradient(135deg, #e6fffa, #b2f5ea);
            border-radius: 14px;
            padding: 15px 20px;
            margin-top: 20px;
            border-left: 4px solid #00b4db;
        }

        .info-box i {
            color: #0083b0;
            margin-right: 10px;
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
            .avatar-circle {
                width: 70px;
                height: 70px;
                font-size: 32px;
            }
        }

        /* Loading State */
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

        /* Blood Group Badge */
        .blood-badge-preview {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 5px;
        }
    </style>
</head>
<body>

<!-- Floating Medical Icons -->
<div class="floating-icons">
    <i class="fas fa-user-injured floating-icon" style="top: 15%; left: 5%; animation-delay: 0s; font-size: 80px;"></i>
    <i class="fas fa-heartbeat floating-icon" style="top: 25%; right: 8%; animation-delay: 2s; font-size: 60px;"></i>
    <i class="fas fa-stethoscope floating-icon" style="bottom: 20%; left: 10%; animation-delay: 4s; font-size: 70px;"></i>
    <i class="fas fa-thermometer-half floating-icon" style="bottom: 30%; right: 12%; animation-delay: 1s; font-size: 55px;"></i>
    <i class="fas fa-prescription-bottle floating-icon" style="top: 60%; left: 3%; animation-delay: 3s; font-size: 65px;"></i>
    <i class="fas fa-wheelchair floating-icon" style="top: 75%; right: 5%; animation-delay: 5s; font-size: 60px;"></i>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10" data-aos="fade-up" data-aos-duration="800">
            
            <!-- Main Card -->
            <div class="main-card">
                <div class="card-header-custom">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="header-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h2 class="text-white mb-1 fw-bold" style="font-size: 1.8rem;">
                                Register New Patient
                            </h2>
                            <p class="text-white-50 mb-0">
                                <i class="fas fa-calendar-alt me-1"></i> 
                                <?php echo date('l, F j, Y'); ?>
                            </p>
                        </div>
                        <div class="text-end">
                            <div class="text-white-50 small">
                                <i class="fas fa-shield-alt me-1"></i> Patient Registration
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
                    <?php elseif ($error): ?>
                        <div class="alert-custom alert-danger-custom" data-aos="fade-down">
                            <i class="fas fa-exclamation-triangle fa-lg"></i>
                            <div class="flex-grow-1"><?= $error ?></div>
                            <button type="button" class="btn-close btn-close-white" onclick="this.parentElement.remove()"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Patient Avatar Preview -->
                    <div class="avatar-preview" data-aos="fade-up" data-aos-delay="50">
                        <div class="avatar-circle" id="avatarPreview">
                            <i class="fas fa-user"></i>
                        </div>
                        <h5 class="mb-0" id="patientNamePreview">New Patient</h5>
                        <small class="text-muted" id="patientIdPreview">NID: Not entered</small>
                    </div>
                    
                    <!-- Info Box -->
                    <div class="info-box" data-aos="fade-up" data-aos-delay="50">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-info-circle fa-2x"></i>
                            <div>
                                <strong class="text-dark">Patient Registration Information</strong>
                                <p class="mb-0 small text-muted">Fill in the details below to register a new patient. All fields marked with <span class="text-danger">*</span> are required.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form -->
                    <form method="POST" action="" id="patientForm">
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
                                        <input type="text" name="nid" class="form-control-custom" 
                                               id="nidInput"
                                               value="<?= htmlspecialchars($_POST['nid'] ?? '') ?>"
                                               placeholder="Enter 10 or 17 digit NID" required>
                                    </div>
                                    <div class="small text-muted mt-1">
                                        <i class="fas fa-info-circle"></i> Must be 10 or 17 digits
                                    </div>
                                </div>
                                
                                <div class="form-group-custom" data-aos="fade-right" data-aos-delay="150">
                                    <label class="form-label-custom">
                                        <i class="fas fa-user"></i>
                                        Full Name <span class="required-star">*</span>
                                    </label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-signature input-icon"></i>
                                        <input type="text" name="name" class="form-control-custom" 
                                               id="nameInput"
                                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                               placeholder="Patient's full name" required>
                                    </div>
                                </div>
                                
                                <div class="form-group-custom" data-aos="fade-right" data-aos-delay="200">
                                    <label class="form-label-custom">
                                        <i class="fas fa-phone-alt"></i>
                                        Mobile Number <span class="required-star">*</span>
                                    </label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-mobile-alt input-icon"></i>
                                        <input type="tel" name="mobile_no" class="form-control-custom" 
                                               id="mobileInput"
                                               value="<?= htmlspecialchars($_POST['mobile_no'] ?? '') ?>"
                                               placeholder="01XXXXXXXXX" required>
                                    </div>
                                    <div class="small text-muted mt-1">
                                        <i class="fas fa-check-circle"></i> Bangladeshi mobile number
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Column -->
                            <div class="col-lg-6">
                                <div class="form-group-custom" data-aos="fade-left" data-aos-delay="100">
                                    <label class="form-label-custom">
                                        <i class="fas fa-lock"></i>
                                        Password <span class="required-star">*</span>
                                    </label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-key input-icon"></i>
                                        <input type="password" name="password" class="form-control-custom" 
                                               id="passwordInput"
                                               placeholder="Create a secure password (min 6 characters)" required>
                                    </div>
                                    <div class="small text-muted mt-1">
                                        <i class="fas fa-shield-alt"></i> Minimum 6 characters
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-custom" data-aos="fade-left" data-aos-delay="150">
                                            <label class="form-label-custom">
                                                <i class="fas fa-venus-mars"></i>
                                                Gender <span class="required-star">*</span>
                                            </label>
                                            <select name="gender" class="form-select-custom" id="genderSelect" required>
                                                <option value="">Select Gender</option>
                                                <option value="1" <?= (isset($_POST['gender']) && $_POST['gender'] == '1') ? 'selected' : '' ?>>👨 Male</option>
                                                <option value="2" <?= (isset($_POST['gender']) && $_POST['gender'] == '2') ? 'selected' : '' ?>>👩 Female</option>
                                                <option value="3" <?= (isset($_POST['gender']) && $_POST['gender'] == '3') ? 'selected' : '' ?>>👤 Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group-custom" data-aos="fade-left" data-aos-delay="200">
                                            <label class="form-label-custom">
                                                <i class="fas fa-tint"></i>
                                                Blood Group <span class="required-star">*</span>
                                            </label>
                                            <select name="blood" class="form-select-custom" id="bloodSelect" required>
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
                                        <div class="form-group-custom" data-aos="fade-left" data-aos-delay="250">
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
                                        <div class="form-group-custom" data-aos="fade-left" data-aos-delay="300">
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
                        
                        <!-- Summary Card -->
                        <div class="info-box mt-3" data-aos="fade-up" data-aos-delay="350">
                            <i class="fas fa-chart-simple"></i>
                            <strong>Registration Summary</strong>
                            <div class="row mt-2 small">
                                <div class="col-md-4">
                                    <i class="fas fa-fingerprint"></i> Biometric ID: <span id="bioPreview">Pending</span>
                                </div>
                                <div class="col-md-4">
                                    <i class="fas fa-tint"></i> Blood Group: <span id="bloodPreview">Not selected</span>
                                </div>
                                <div class="col-md-4">
                                    <i class="fas fa-shield-alt"></i> Status: <span class="text-success">Active</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center gap-3 mt-4 pt-3">
                            <a href="manage_patients.php" class="btn-custom btn-secondary-custom">
                                <i class="fas fa-arrow-left"></i> Back to Patients
                            </a>
                            <button type="submit" class="btn-custom btn-primary-custom" id="submitBtn">
                                <i class="fas fa-user-plus"></i> Register Patient
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
    
    // Live preview updates
    $('#nameInput').on('input', function() {
        const name = $(this).val();
        $('#patientNamePreview').text(name || 'New Patient');
        updateAvatarIcon();
    });
    
    $('#nidInput').on('input', function() {
        const nid = $(this).val();
        $('#patientIdPreview').text(nid ? `NID: ${nid}` : 'NID: Not entered');
        $('#bioPreview').text(nid ? `${nid.slice(-4)}****` : 'Pending');
    });
    
    $('#bloodSelect').on('change', function() {
        const blood = $(this).val();
        $('#bloodPreview').text(blood || 'Not selected');
        if (blood) {
            $(this).css('border-color', '#48bb78');
        }
    });
    
    $('#genderSelect').on('change', function() {
        updateAvatarIcon();
    });
    
    function updateAvatarIcon() {
        const gender = $('#genderSelect').val();
        const avatar = $('.avatar-circle');
        
        if (gender == '1') {
            avatar.html('<i class="fas fa-mars"></i>');
            avatar.css('background', 'linear-gradient(135deg, #3498db, #2980b9)');
        } else if (gender == '2') {
            avatar.html('<i class="fas fa-venus"></i>');
            avatar.css('background', 'linear-gradient(135deg, #e91e63, #c2185b)');
        } else {
            avatar.html('<i class="fas fa-user"></i>');
            avatar.css('background', 'linear-gradient(135deg, #00b4db, #0083b0)');
        }
    }
    
    // Password strength indicator
    $('#passwordInput').on('input', function() {
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
    
    // Mobile number validation
    $('#mobileInput').on('input', function() {
        const mobile = $(this).val();
        const pattern = /^01[3-9]\d{8}$/;
        if (pattern.test(mobile)) {
            $(this).css('border-color', '#48bb78');
        } else if (mobile.length > 0) {
            $(this).css('border-color', '#e53e3e');
        } else {
            $(this).css('border-color', '#e2e8f0');
        }
    });
    
    // NID validation
    $('#nidInput').on('input', function() {
        const nid = $(this).val();
        const pattern = /^\d{10}$|^\d{17}$/;
        if (pattern.test(nid)) {
            $(this).css('border-color', '#48bb78');
        } else if (nid.length > 0) {
            $(this).css('border-color', '#e53e3e');
        } else {
            $(this).css('border-color', '#e2e8f0');
        }
    });
    
    // Form submission animation
    $('#patientForm').on('submit', function() {
        const btn = $('#submitBtn');
        btn.addClass('btn-loading');
        btn.html('<i class="fas fa-spinner"></i> Registering Patient...');
        
        setTimeout(function() {
            btn.removeClass('btn-loading');
        }, 3000);
    });
    
    // Initial avatar update
    updateAvatarIcon();
</script>

</body>
</html>