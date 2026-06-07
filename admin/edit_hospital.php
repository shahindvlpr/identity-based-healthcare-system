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

if (isset($_GET['hospital_id'])) {
    $hospital_id = $_GET['hospital_id'];

    $stmt = $conn->prepare("SELECT * FROM hospital WHERE hospital_id = ?");
    $stmt->bind_param("s", $hospital_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $error = "Hospital not found.";
    } else {
        $hospital = $result->fetch_assoc();
    }
} else {
    header("Location: manage_hospitals.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($hospital)) {
    $hospital_name = trim($_POST['hospital_name']);
    $numberof_ward = intval($_POST['numberof_ward']);
    $wardfee_perday = intval($_POST['wardfee_perday']);
    $numberof_cabin = intval($_POST['numberof_cabin']);
    $cabinfee_perday = intval($_POST['cabinfee_perday']);
    $docreg = trim($_POST['docreg']);
    $docregtext = trim($_POST['docregtext']);
    
    // Validation
    $errors = [];
    if (empty($hospital_name)) $errors[] = "Hospital name is required";
    if ($numberof_ward < 0) $errors[] = "Valid number of wards is required";
    if ($wardfee_perday < 0) $errors[] = "Valid ward fee is required";
    if ($numberof_cabin < 0) $errors[] = "Valid number of cabins is required";
    if ($cabinfee_perday < 0) $errors[] = "Valid cabin fee is required";
    
    if (empty($errors)) {
        $update_query = "UPDATE hospital 
                         SET hospital_name = ?, numberof_ward = ?, wardfee_perday = ?, 
                             numberof_cabin = ?, cabinfee_perday = ?, docreg = ?, docregtext = ? 
                         WHERE hospital_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("siiiiiss", $hospital_name, $numberof_ward, $wardfee_perday, 
                         $numberof_cabin, $cabinfee_perday, $docreg, $docregtext, $hospital_id);
        
        if ($stmt->execute()) {
            $success = "✅ Hospital information updated successfully!";
            // Refresh data
            $hospital['hospital_name'] = $hospital_name;
            $hospital['numberof_ward'] = $numberof_ward;
            $hospital['wardfee_perday'] = $wardfee_perday;
            $hospital['numberof_cabin'] = $numberof_cabin;
            $hospital['cabinfee_perday'] = $cabinfee_perday;
            $hospital['docreg'] = $docreg;
            $hospital['docregtext'] = $docregtext;
        } else {
            $error = "❌ Error updating hospital details: " . $conn->error;
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
    <title>Edit Hospital | IBHS Admin</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        /* Floating Icons */
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

        .main-container {
            max-width: 1100px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        /* Main Card */
        .main-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .main-card:hover {
            transform: translateY(-5px);
        }

        /* Header Section */
        .card-header-custom {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 30px 35px;
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
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
        }

        /* Form Section */
        .form-section {
            padding: 35px;
        }

        /* Form Groups */
        .form-group-custom {
            margin-bottom: 22px;
        }

        .form-label-custom {
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 8px;
            color: #2d3748;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-label-custom i {
            color: #2a5298;
        }

        .required-star {
            color: #e53e3e;
        }

        .input-icon-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1rem;
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

        /* Radio Toggle */
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
            padding: 12px 20px;
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
            font-size: 1rem;
            color: #2a5298;
        }

        /* Buttons */
        .btn-back {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            border: none;
            color: white;
            border-radius: 12px;
            padding: 10px 24px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(79,172,254,0.3);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-back:hover {
            background: linear-gradient(135deg, #3a8fd6, #00c4d4);
            transform: translateX(-6px);
            box-shadow: 0 8px 20px rgba(79,172,254,0.4);
            color: white;
            text-decoration: none;
            gap: 12px;
        }

        .btn-back i {
            transition: transform 0.3s ease;
        }

        .btn-back:hover i {
            transform: translateX(-4px);
        }

        .btn-update {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            border: none;
            color: white;
            border-radius: 14px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 60, 114, 0.4);
            color: white;
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

        /* Hospital Preview Card */
        .hospital-preview {
            background: linear-gradient(135deg, #f7fafc, #edf2f7);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            margin-bottom: 25px;
        }

        .hospital-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 36px;
            color: white;
        }

        .hospital-preview h4 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .hospital-preview .badge-id {
            background: #e2e8f0;
            color: #4a5568;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
        }

        /* Revenue Preview Box */
        .revenue-preview {
            background: linear-gradient(135deg, #e6fffa, #b2f5ea);
            border-radius: 16px;
            padding: 15px 20px;
            margin-top: 20px;
            border-left: 4px solid #2a5298;
        }

        .revenue-preview i {
            color: #2a5298;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-header-custom {
                padding: 20px;
            }
            .form-section {
                padding: 20px;
            }
            .header-icon {
                width: 45px;
                height: 45px;
                font-size: 22px;
            }
            .toggle-label {
                padding: 10px 15px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>

<!-- Floating Icons -->
<div class="floating-icons">
    <i class="fas fa-hospital floating-icon" style="top: 10%; left: 5%; animation-delay: 0s;"></i>
    <i class="fas fa-stethoscope floating-icon" style="top: 20%; right: 8%; animation-delay: 2s;"></i>
    <i class="fas fa-heartbeat floating-icon" style="bottom: 15%; left: 10%; animation-delay: 4s;"></i>
    <i class="fas fa-ambulance floating-icon" style="bottom: 25%; right: 15%; animation-delay: 1s;"></i>
</div>

<div class="main-container">
    
    <!-- Main Card -->
    <div class="main-card" data-aos="fade-up" data-aos-duration="800">
        <div class="card-header-custom">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="header-icon">
                    <i class="fas fa-hospital-user"></i>
                </div>
                <div class="flex-grow-1">
                    <h2 class="text-white mb-1 fw-bold" style="font-size: 1.6rem;">
                        Edit Hospital Information
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
            
            <!-- Alert Messages -->
            <?php if ($success): ?>
                <div class="alert-custom alert-success-custom" data-aos="fade-down">
                    <i class="fas fa-check-circle fa-lg"></i>
                    <div class="flex-grow-1"><?= $success ?></div>
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
            
            <!-- Hospital Preview Card -->
            <div class="hospital-preview" data-aos="fade-up">
                <div class="hospital-icon">
                    <i class="fas fa-hospital"></i>
                </div>
                <h4 id="previewName"><?php echo htmlspecialchars($hospital['hospital_name']); ?></h4>
                <span class="badge-id">
                    <i class="fas fa-id-card"></i> ID: <?php echo htmlspecialchars($hospital['hospital_id']); ?>
                </span>
            </div>
            
            <!-- Back Button -->
            <div class="mb-4">
                <a href="manage_hospitals.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Hospital Management
                </a>
            </div>
            
            <!-- Edit Form -->
            <form method="POST" action="" id="editForm">
                <div class="row">
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
                                       value="<?php echo htmlspecialchars($hospital['hospital_name']); ?>" required>
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
                                       value="<?php echo $hospital['numberof_ward']; ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group-custom" data-aos="fade-right" data-aos-delay="200">
                            <label class="form-label-custom">
                                <i class="fas fa-money-bill-wave"></i>
                                Ward Fee per Day (৳)
                            </label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-taka-sign input-icon"></i>
                                <input type="number" name="wardfee_perday" class="form-control-custom" 
                                       id="wardFee"
                                       value="<?php echo isset($hospital['wardfee_perday']) ? $hospital['wardfee_perday'] : '0'; ?>">
                            </div>
                        </div>
                    </div>
                    
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
                                       value="<?php echo $hospital['numberof_cabin']; ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group-custom" data-aos="fade-left" data-aos-delay="150">
                            <label class="form-label-custom">
                                <i class="fas fa-coins"></i>
                                Cabin Fee per Day (৳)
                            </label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-taka-sign input-icon"></i>
                                <input type="number" name="cabinfee_perday" class="form-control-custom" 
                                       id="cabinFee"
                                       value="<?php echo isset($hospital['cabinfee_perday']) ? $hospital['cabinfee_perday'] : '0'; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group-custom" data-aos="fade-left" data-aos-delay="200">
                            <label class="form-label-custom">
                                <i class="fas fa-user-md"></i>
                                Doctor Registration Status <span class="required-star">*</span>
                            </label>
                            <div class="toggle-group">
                                <label class="toggle-option">
                                    <input type="radio" name="docreg" value="Y" <?php echo $hospital['docreg'] == 'Y' ? 'checked' : ''; ?> required>
                                    <span class="toggle-label">
                                        <i class="fas fa-check-circle"></i>
                                        Yes, Available
                                    </span>
                                </label>
                                <label class="toggle-option">
                                    <input type="radio" name="docreg" value="N" <?php echo $hospital['docreg'] == 'N' ? 'checked' : ''; ?>>
                                    <span class="toggle-label">
                                        <i class="fas fa-times-circle"></i>
                                        Not Available
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Doctor Registration Info -->
                <div class="form-group-custom" data-aos="fade-up" data-aos-delay="250" id="docregInfoDiv" style="<?php echo $hospital['docreg'] == 'Y' ? 'display: block;' : 'display: none;'; ?>">
                    <label class="form-label-custom">
                        <i class="fas fa-info-circle"></i>
                        Doctor Registration Information
                    </label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-sticky-note input-icon"></i>
                        <textarea name="docregtext" class="form-control-custom" rows="3" 
                                  placeholder="Provide details about doctor registration process, requirements, or contact information..."><?php echo htmlspecialchars($hospital['docregtext'] ?? ''); ?></textarea>
                    </div>
                </div>
                
                <!-- Revenue Preview -->
                <div class="revenue-preview" data-aos="fade-up" data-aos-delay="300">
                    <i class="fas fa-chart-line"></i>
                    <strong class="ms-2">Revenue Summary Preview</strong>
                    <div class="row mt-2 small">
                        <div class="col-6" id="wardRevenuePreview">
                            🏥 Ward Revenue: ৳<?php echo number_format(($hospital['numberof_ward'] ?? 0) * ($hospital['wardfee_perday'] ?? 0)); ?>
                        </div>
                        <div class="col-6" id="cabinRevenuePreview">
                            🛏️ Cabin Revenue: ৳<?php echo number_format(($hospital['numberof_cabin'] ?? 0) * ($hospital['cabinfee_perday'] ?? 0)); ?>
                        </div>
                        <div class="col-12 mt-1 text-muted" id="totalRevenuePreview">
                            💰 Total Daily Revenue: ৳<?php echo number_format((($hospital['numberof_ward'] ?? 0) * ($hospital['wardfee_perday'] ?? 0)) + (($hospital['numberof_cabin'] ?? 0) * ($hospital['cabinfee_perday'] ?? 0))); ?>
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="d-flex justify-content-between align-items-center gap-3 mt-4 pt-3">
                    <a href="manage_hospitals.php" class="btn btn-secondary" style="border-radius: 12px; padding: 10px 24px;">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn-update" id="submitBtn">
                        <i class="fas fa-save"></i> Update Hospital
                    </button>
                </div>
            </form>
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
    
    // Live preview update
    $('#hospitalName').on('input', function() {
        const name = $(this).val();
        $('#previewName').text(name || '<?php echo $hospital['hospital_name']; ?>');
    });
    
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
    
    // Form submission animation
    $('#editForm').on('submit', function() {
        const btn = $('#submitBtn');
        btn.html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        btn.prop('disabled', true);
        
        setTimeout(function() {
            btn.prop('disabled', false);
        }, 3000);
    });
    
    // Validation on inputs
    $('#numWards, #numCabins').on('input', function() {
        if ($(this).val() < 0) $(this).val(0);
    });
</script>

</body>
</html>