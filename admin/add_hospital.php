<?php
include_once('../dbconn.php');
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: index.php");
    exit();
}

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $hospital_name     = trim($_POST['hospital_name']);
    $numberof_ward     = filter_var($_POST['numberof_ward'], FILTER_VALIDATE_INT);
    $wardfee_perday    = filter_var($_POST['wardfee_perday'], FILTER_VALIDATE_INT);
    $numberof_cabin    = filter_var($_POST['numberof_cabin'], FILTER_VALIDATE_INT);
    $cabinfee_perday   = filter_var($_POST['cabinfee_perday'], FILTER_VALIDATE_INT);
    $password          = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $docreg            = $_POST['docreg'];
    $docregtext        = trim($_POST['docregtext']);
    
    // Validation
    $errors = [];
    
    if (empty($hospital_name)) {
        $errors[] = "Hospital name is required";
    }
    
    if (!$numberof_ward || $numberof_ward < 1) {
        $errors[] = "Valid number of wards is required";
    }
    
    if (!$wardfee_perday || $wardfee_perday < 0) {
        $errors[] = "Valid ward fee is required";
    }
    
    if (!$numberof_cabin || $numberof_cabin < 0) {
        $errors[] = "Valid number of cabins is required";
    }
    
    if (!$cabinfee_perday || $cabinfee_perday < 0) {
        $errors[] = "Valid cabin fee is required";
    }
    
    if (empty($_POST['password']) || strlen($_POST['password']) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    if (empty($docreg)) {
        $errors[] = "Please specify if doctor registration is available";
    }
    
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO hospital 
            (hospital_id, hospital_name, numberof_ward, wardfee_perday, numberof_cabin, cabinfee_perday, password, docreg, docregtext)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $hospital_id = 'HOS-' . strtoupper(uniqid());
        
        $stmt->bind_param("ssiiiisss", $hospital_id, $hospital_name, $numberof_ward, $wardfee_perday, $numberof_cabin, $cabinfee_perday, $password, $docreg, $docregtext);
        
        if ($stmt->execute()) {
            $success = "Hospital added successfully!";
            // Clear form data
            $_POST = array();
        } else {
            $error = "Error adding hospital: " . $conn->error;
        }
        $stmt->close();
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
    <title>Add Hospital | IBHS Admin</title>
    
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
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #1e3c72 100%);
            min-height: 100vh;
            padding: 40px 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background Pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.03)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') repeat-x bottom;
            background-size: cover;
            opacity: 0.5;
            pointer-events: none;
            z-index: 0;
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
            color: rgba(255, 255, 255, 0.05);
            font-size: 60px;
            animation: floatIcon 15s infinite ease-in-out;
        }

        @keyframes floatIcon {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(10deg); }
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
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #1e3c72 100%);
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
            color: #2a5298;
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

        textarea ~ .input-icon {
            top: 20px;
            transform: none;
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

        textarea.form-control-custom {
            padding-top: 12px;
        }

        .form-control-custom:focus, .form-select-custom:focus {
            outline: none;
            border-color: #2a5298;
            box-shadow: 0 0 0 4px rgba(42, 82, 152, 0.1);
        }

        .form-control-custom:hover, .form-select-custom:hover {
            border-color: #c3dafe;
        }

        /* Radio Toggle Switch */
        .toggle-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .toggle-option {
            flex: 1;
            position: relative;
            cursor: pointer;
        }

        .toggle-option input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .toggle-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 20px;
            background: #f7fafc;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .toggle-option input:checked + .toggle-label {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            border-color: #2a5298;
            color: white;
        }

        .toggle-option input:checked + .toggle-label i {
            color: white;
        }

        .toggle-label i {
            font-size: 1.1rem;
            color: #2a5298;
            transition: color 0.3s ease;
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
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.4);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 60, 114, 0.5);
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

        /* Info Box */
        .info-box {
            background: linear-gradient(135deg, #ebf8ff, #e1efff);
            border-radius: 14px;
            padding: 15px 20px;
            margin-top: 20px;
            border-left: 4px solid #2a5298;
        }

        .info-box i {
            color: #2a5298;
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
            .toggle-label {
                padding: 10px 15px;
                font-size: 0.85rem;
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

        /* Preview Card */
        .preview-card {
            background: #f7fafc;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            border: 2px dashed #e2e8f0;
            transition: all 0.3s ease;
        }

        .preview-card:hover {
            border-color: #2a5298;
            background: #f0f5ff;
        }
    </style>
</head>
<body>

<!-- Floating Medical Icons -->
<div class="floating-icons">
    <i class="fas fa-hospital floating-icon" style="top: 10%; left: 5%; animation-delay: 0s; font-size: 80px;"></i>
    <i class="fas fa-stethoscope floating-icon" style="top: 20%; right: 8%; animation-delay: 2s; font-size: 60px;"></i>
    <i class="fas fa-heartbeat floating-icon" style="bottom: 15%; left: 10%; animation-delay: 4s; font-size: 70px;"></i>
    <i class="fas fa-ambulance floating-icon" style="bottom: 25%; right: 15%; animation-delay: 1s; font-size: 65px;"></i>
    <i class="fas fa-thermometer floating-icon" style="top: 50%; left: 2%; animation-delay: 3s; font-size: 50px;"></i>
    <i class="fas fa-pills floating-icon" style="top: 70%; right: 3%; animation-delay: 5s; font-size: 55px;"></i>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10" data-aos="fade-up" data-aos-duration="800">
            
            <!-- Main Card -->
            <div class="main-card">
                <div class="card-header-custom">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="header-icon">
                            <i class="fas fa-hospital"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h2 class="text-white mb-1 fw-bold" style="font-size: 1.8rem;">
                                Register New Hospital
                            </h2>
                            <p class="text-white-50 mb-0">
                                <i class="fas fa-calendar-alt me-1"></i> 
                                <?php echo date('l, F j, Y'); ?>
                            </p>
                        </div>
                        <div class="text-end">
                            <div class="text-white-50 small">
                                <i class="fas fa-shield-alt me-1"></i> Healthcare Partner
                            </div>
                            <div class="text-white fw-bold">
                                <i class="fas fa-user-shield me-1"></i>
                                <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    
                    <!-- Alerts -->
                    <?php if (!empty($success)): ?>
                        <div class="alert-custom alert-success-custom" data-aos="fade-down">
                            <i class="fas fa-check-circle fa-lg"></i>
                            <div class="flex-grow-1"><?= htmlspecialchars($success) ?></div>
                            <button type="button" class="btn-close btn-close-white" onclick="this.parentElement.remove()"></button>
                        </div>
                    <?php elseif (!empty($error)): ?>
                        <div class="alert-custom alert-danger-custom" data-aos="fade-down">
                            <i class="fas fa-exclamation-triangle fa-lg"></i>
                            <div class="flex-grow-1"><?= $error ?></div>
                            <button type="button" class="btn-close btn-close-white" onclick="this.parentElement.remove()"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Live Preview Info -->
                    <div class="preview-card" data-aos="fade-up" data-aos-delay="50">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-info-circle fa-2x" style="color: #2a5298;"></i>
                            <div>
                                <strong class="text-dark">Hospital Registration Information</strong>
                                <p class="mb-0 small text-muted">Fill in the details below to add a new hospital to the system. All fields marked with <span class="text-danger">*</span> are required.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form -->
                    <form method="POST" action="" id="hospitalForm">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-lg-6">
                                <div class="form-group-custom" data-aos="fade-right" data-aos-delay="100">
                                    <label class="form-label-custom">
                                        <i class="fas fa-building"></i>
                                        Hospital Name <span class="required-star">*</span>
                                    </label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-hospital-user input-icon"></i>
                                        <input type="text" name="hospital_name" class="form-control-custom" 
                                               id="hospitalName"
                                               value="<?= htmlspecialchars($_POST['hospital_name'] ?? '') ?>"
                                               placeholder="e.g., City General Hospital" required>
                                    </div>
                                </div>
                                
                                <div class="form-group-custom" data-aos="fade-right" data-aos-delay="150">
                                    <label class="form-label-custom">
                                        <i class="fas fa-bed"></i>
                                        Number of Wards <span class="required-star">*</span>
                                    </label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-layer-group input-icon"></i>
                                        <input type="number" name="numberof_ward" class="form-control-custom" 
                                               id="numWards"
                                               value="<?= htmlspecialchars($_POST['numberof_ward'] ?? '') ?>"
                                               placeholder="e.g., 10" required>
                                    </div>
                                </div>
                                
                                <div class="form-group-custom" data-aos="fade-right" data-aos-delay="200">
                                    <label class="form-label-custom">
                                        <i class="fas fa-money-bill-wave"></i>
                                        Ward Fee per Day (৳) <span class="required-star">*</span>
                                    </label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-taka-sign input-icon"></i>
                                        <input type="number" name="wardfee_perday" class="form-control-custom" 
                                               id="wardFee"
                                               value="<?= htmlspecialchars($_POST['wardfee_perday'] ?? '') ?>"
                                               placeholder="e.g., 1500" required>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right Column -->
                            <div class="col-lg-6">
                                <div class="form-group-custom" data-aos="fade-left" data-aos-delay="100">
                                    <label class="form-label-custom">
                                        <i class="fas fa-door-open"></i>
                                        Number of Cabins <span class="required-star">*</span>
                                    </label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-hotel input-icon"></i>
                                        <input type="number" name="numberof_cabin" class="form-control-custom" 
                                               id="numCabins"
                                               value="<?= htmlspecialchars($_POST['numberof_cabin'] ?? '') ?>"
                                               placeholder="e.g., 25" required>
                                    </div>
                                </div>
                                
                                <div class="form-group-custom" data-aos="fade-left" data-aos-delay="150">
                                    <label class="form-label-custom">
                                        <i class="fas fa-coins"></i>
                                        Cabin Fee per Day (৳) <span class="required-star">*</span>
                                    </label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-taka-sign input-icon"></i>
                                        <input type="number" name="cabinfee_perday" class="form-control-custom" 
                                               id="cabinFee"
                                               value="<?= htmlspecialchars($_POST['cabinfee_perday'] ?? '') ?>"
                                               placeholder="e.g., 3500" required>
                                    </div>
                                </div>
                                
                                <div class="form-group-custom" data-aos="fade-left" data-aos-delay="200">
                                    <label class="form-label-custom">
                                        <i class="fas fa-lock"></i>
                                        Hospital Login Password <span class="required-star">*</span>
                                    </label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-key input-icon"></i>
                                        <input type="password" name="password" class="form-control-custom" 
                                               id="password"
                                               placeholder="Create a secure password (min 6 characters)" required>
                                    </div>
                                    <div class="small text-muted mt-1">
                                        <i class="fas fa-shield-alt"></i> Minimum 6 characters
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Doctor Registration Toggle -->
                        <div class="form-group-custom" data-aos="fade-up" data-aos-delay="250">
                            <label class="form-label-custom">
                                <i class="fas fa-user-md"></i>
                                Doctor Registration Available? <span class="required-star">*</span>
                            </label>
                            <div class="toggle-group">
                                <label class="toggle-option">
                                    <input type="radio" name="docreg" value="Y" <?= (isset($_POST['docreg']) && $_POST['docreg'] == 'Y') ? 'checked' : '' ?> required>
                                    <span class="toggle-label">
                                        <i class="fas fa-check-circle"></i>
                                        Yes, Available
                                    </span>
                                </label>
                                <label class="toggle-option">
                                    <input type="radio" name="docreg" value="N" <?= (isset($_POST['docreg']) && $_POST['docreg'] == 'N') ? 'checked' : '' ?>>
                                    <span class="toggle-label">
                                        <i class="fas fa-times-circle"></i>
                                        Not Available
                                    </span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Doctor Registration Info -->
                        <div class="form-group-custom" data-aos="fade-up" data-aos-delay="300" id="docregInfoDiv" style="display: none;">
                            <label class="form-label-custom">
                                <i class="fas fa-info-circle"></i>
                                Doctor Registration Information
                            </label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-sticky-note input-icon"></i>
                                <textarea name="docregtext" class="form-control-custom" rows="3" 
                                          placeholder="Provide details about doctor registration process, requirements, or contact information..."><?= htmlspecialchars($_POST['docregtext'] ?? '') ?></textarea>
                            </div>
                        </div>
                        
                        <!-- Summary Card -->
                        <div class="info-box" data-aos="fade-up" data-aos-delay="350">
                            <i class="fas fa-chart-line"></i>
                            <strong>Revenue Summary Preview</strong>
                            <div class="row mt-2 small">
                                <div class="col-6" id="wardRevenuePreview">🏥 Ward Revenue: --</div>
                                <div class="col-6" id="cabinRevenuePreview">🛏️ Cabin Revenue: --</div>
                                <div class="col-12 mt-1 text-muted" id="totalRevenuePreview">💰 Total Daily Revenue: --</div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center gap-3 mt-4 pt-3">
                            <a href="manage_hospitals.php" class="btn-custom btn-secondary-custom">
                                <i class="fas fa-arrow-left"></i> Back to Hospitals
                            </a>
                            <button type="submit" class="btn-custom btn-primary-custom" id="submitBtn">
                                <i class="fas fa-plus-circle"></i> Register Hospital
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
    
    // Show/hide doctor registration info based on selection
    $('input[name="docreg"]').on('change', function() {
        if ($(this).val() === 'Y') {
            $('#docregInfoDiv').slideDown();
        } else {
            $('#docregInfoDiv').slideUp();
        }
    });
    
    // Check initial state
    if ($('input[name="docreg"]:checked').val() === 'Y') {
        $('#docregInfoDiv').show();
    }
    
    // Live revenue preview
    function updateRevenuePreview() {
        const numWards = parseInt($('#numWards').val()) || 0;
        const wardFee = parseInt($('#wardFee').val()) || 0;
        const numCabins = parseInt($('#numCabins').val()) || 0;
        const cabinFee = parseInt($('#cabinFee').val()) || 0;
        
        const wardRevenue = numWards * wardFee;
        const cabinRevenue = numCabins * cabinFee;
        const totalRevenue = wardRevenue + cabinRevenue;
        
        $('#wardRevenuePreview').html(`🏥 Ward Revenue: ৳${wardRevenue.toLocaleString()}`);
        $('#cabinRevenuePreview').html(`🛏️ Cabin Revenue: ৳${cabinRevenue.toLocaleString()}`);
        $('#totalRevenuePreview').html(`💰 Total Daily Revenue: ৳${totalRevenue.toLocaleString()}`);
    }
    
    $('#numWards, #wardFee, #numCabins, #cabinFee').on('input', updateRevenuePreview);
    updateRevenuePreview();
    
    // Form submission animation
    $('#hospitalForm').on('submit', function() {
        const btn = $('#submitBtn');
        btn.addClass('btn-loading');
        btn.html('<i class="fas fa-spinner"></i> Registering Hospital...');
        
        setTimeout(function() {
            btn.removeClass('btn-loading');
        }, 3000);
    });
    
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
    
    // Hospital name live preview
    $('#hospitalName').on('input', function() {
        const name = $(this).val();
        if (name) {
            $(this).css('border-color', '#48bb78');
        }
    });
</script>

</body>
</html>