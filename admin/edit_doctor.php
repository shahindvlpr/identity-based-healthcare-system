<?php
include_once('../dbconn.php');
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: index.php");
    exit();
}

$success_msg = '';
$error_msg = '';

if (isset($_GET['nid'])) {
    $nid = $_GET['nid'];

    $stmt = $conn->prepare("SELECT person.nid, person.name, doctor.specialist, 
                            person.mobile_no AS phone, person.gender, person.blood,
                            doctor.dmdc_id, doctor.visiting_fee
                            FROM doctor 
                            INNER JOIN person ON doctor.d_nid = person.nid
                            WHERE person.nid = ?");
    $stmt->bind_param("s", $nid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Convert gender from numeric to text for display
        $gender_display = '';
        switch($row['gender']) {
            case 1: $gender_display = 'Male'; break;
            case 2: $gender_display = 'Female'; break;
            case 3: $gender_display = 'Other'; break;
            default: $gender_display = 'Male';
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $specialist = trim($_POST['specialist']);
            $phone = trim($_POST['phone']);
            $gender_text = $_POST['gender'];
            $blood = $_POST['blood'];
            $visiting_fee = isset($_POST['visiting_fee']) ? (int)$_POST['visiting_fee'] : 0;
            
            // Validation
            $errors = [];
            if (empty($name)) $errors[] = "Doctor name is required";
            if (empty($specialist)) $errors[] = "Specialist field is required";
            if (empty($phone) || !preg_match('/^01[3-9]\d{8}$/', $phone)) $errors[] = "Valid mobile number is required";
            if (empty($blood)) $errors[] = "Blood group is required";
            
            switch ($gender_text) {
                case 'Male': $gender = 1; break;
                case 'Female': $gender = 2; break;
                case 'Other': $gender = 3; break;
                default: $gender = 1;
            }
            
            if (empty($errors)) {
                $conn->begin_transaction();
                try {
                    $updatePerson = $conn->prepare("UPDATE person SET name = ?, mobile_no = ?, gender = ?, blood = ? WHERE nid = ?");
                    $updatePerson->bind_param("ssiss", $name, $phone, $gender, $blood, $nid);
                    $updatePerson->execute();
                    
                    $updateDoctor = $conn->prepare("UPDATE doctor SET specialist = ?, visiting_fee = ? WHERE d_nid = ?");
                    $updateDoctor->bind_param("sis", $specialist, $visiting_fee, $nid);
                    $updateDoctor->execute();
                    
                    $conn->commit();
                    $success_msg = "Doctor information updated successfully!";
                    
                    // Refresh row data
                    $row['name'] = $name;
                    $row['specialist'] = $specialist;
                    $row['phone'] = $phone;
                    $row['blood'] = $blood;
                    $row['visiting_fee'] = $visiting_fee;
                    $gender_display = $gender_text;
                    
                } catch (Exception $e) {
                    $conn->rollback();
                    $error_msg = "Error updating doctor: " . $e->getMessage();
                }
            } else {
                $error_msg = implode("<br>", $errors);
            }
        }
    } else {
        $error_msg = "Doctor not found.";
    }
} else {
    header("Location: manage_doctors.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Doctor | IBHS Admin</title>
    
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .main-container {
            max-width: 1000px;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            color: #667eea;
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
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
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
            background: linear-gradient(135deg, #667eea, #764ba2);
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
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
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

        /* Doctor Preview Card */
        .doctor-preview {
            background: linear-gradient(135deg, #f7fafc, #edf2f7);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            margin-bottom: 25px;
        }

        .doctor-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 36px;
            color: white;
        }

        .doctor-preview h4 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .doctor-preview .badge-id {
            background: #e2e8f0;
            color: #4a5568;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
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
        }
    </style>
</head>
<body>

<div class="main-container">
    
    <!-- Main Card -->
    <div class="main-card" data-aos="fade-up" data-aos-duration="800">
        <div class="card-header-custom">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="header-icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="flex-grow-1">
                    <h2 class="text-white mb-1 fw-bold" style="font-size: 1.6rem;">
                        Edit Doctor Information
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
            
            <!-- Success/Error Messages -->
            <?php if ($success_msg): ?>
                <div class="alert-custom alert-success-custom" data-aos="fade-down">
                    <i class="fas fa-check-circle fa-lg"></i>
                    <div class="flex-grow-1"><?php echo $success_msg; ?></div>
                    <button type="button" class="btn-close btn-close-white" onclick="this.parentElement.remove()"></button>
                </div>
            <?php endif; ?>
            
            <?php if ($error_msg): ?>
                <div class="alert-custom alert-danger-custom" data-aos="fade-down">
                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                    <div class="flex-grow-1"><?php echo $error_msg; ?></div>
                    <button type="button" class="btn-close btn-close-white" onclick="this.parentElement.remove()"></button>
                </div>
            <?php endif; ?>
            
            <!-- Doctor Preview Card -->
            <div class="doctor-preview" data-aos="fade-up">
                <div class="doctor-avatar">
                    <i class="fas fa-user-md"></i>
                </div>
                <h4 id="previewName">Dr. <?php echo htmlspecialchars($row['name']); ?></h4>
                <span class="badge-id">
                    <i class="fas fa-id-card"></i> NID: <?php echo htmlspecialchars($row['nid']); ?>
                </span>
                <?php if (!empty($row['dmdc_id'])): ?>
                <span class="badge-id ms-2">
                    <i class="fas fa-qrcode"></i> DMDC: <?php echo htmlspecialchars($row['dmdc_id']); ?>
                </span>
                <?php endif; ?>
            </div>
            
            <!-- Back Button -->
            <div class="mb-4">
                <a href="manage_doctors.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Doctor Management
                </a>
            </div>
            
            <!-- Edit Form -->
            <form method="POST" action="" id="editForm">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group-custom" data-aos="fade-right" data-aos-delay="100">
                            <label class="form-label-custom">
                                <i class="fas fa-user"></i>
                                Full Name <span class="required-star">*</span>
                            </label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-signature input-icon"></i>
                                <input type="text" name="name" class="form-control-custom" 
                                       id="doctorName"
                                       value="<?php echo htmlspecialchars($row['name']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group-custom" data-aos="fade-right" data-aos-delay="150">
                            <label class="form-label-custom">
                                <i class="fas fa-stethoscope"></i>
                                Specialist Field <span class="required-star">*</span>
                            </label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-brain input-icon"></i>
                                <input type="text" name="specialist" class="form-control-custom" 
                                       id="specialist"
                                       value="<?php echo htmlspecialchars($row['specialist']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group-custom" data-aos="fade-right" data-aos-delay="200">
                            <label class="form-label-custom">
                                <i class="fas fa-phone-alt"></i>
                                Mobile Number <span class="required-star">*</span>
                            </label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-mobile-alt input-icon"></i>
                                <input type="tel" name="phone" class="form-control-custom" 
                                       id="mobileNo"
                                       value="<?php echo htmlspecialchars($row['phone']); ?>" required>
                            </div>
                            <div class="small text-muted mt-1">
                                <i class="fas fa-info-circle"></i> Format: 01XXXXXXXXX
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="form-group-custom" data-aos="fade-left" data-aos-delay="100">
                            <label class="form-label-custom">
                                <i class="fas fa-money-bill-wave"></i>
                                Visiting Fee (৳)
                            </label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-taka-sign input-icon"></i>
                                <input type="number" name="visiting_fee" class="form-control-custom" 
                                       value="<?php echo isset($row['visiting_fee']) ? $row['visiting_fee'] : '0'; ?>"
                                       placeholder="Enter visiting fee">
                            </div>
                        </div>
                        
                        <div class="form-group-custom" data-aos="fade-left" data-aos-delay="150">
                            <label class="form-label-custom">
                                <i class="fas fa-venus-mars"></i>
                                Gender <span class="required-star">*</span>
                            </label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-genderless input-icon"></i>
                                <select name="gender" class="form-select-custom" id="genderSelect" required>
                                    <option value="Male" <?php echo $gender_display == 'Male' ? 'selected' : ''; ?>>👨 Male</option>
                                    <option value="Female" <?php echo $gender_display == 'Female' ? 'selected' : ''; ?>>👩 Female</option>
                                    <option value="Other" <?php echo $gender_display == 'Other' ? 'selected' : ''; ?>>👤 Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group-custom" data-aos="fade-left" data-aos-delay="200">
                            <label class="form-label-custom">
                                <i class="fas fa-tint"></i>
                                Blood Group <span class="required-star">*</span>
                            </label>
                            <div class="input-icon-wrapper">
                                <i class="fas fa-droplet input-icon"></i>
                                <select name="blood" class="form-select-custom" id="bloodSelect" required>
                                    <option value="">Select Blood Group</option>
                                    <?php
                                    $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
                                    foreach ($bloodGroups as $group) {
                                        $selected = ($row['blood'] == $group) ? 'selected' : '';
                                        echo "<option value='$group' $selected>$group 🩸</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="d-flex justify-content-between align-items-center gap-3 mt-4 pt-3">
                    <a href="manage_doctors.php" class="btn btn-secondary" style="border-radius: 12px; padding: 10px 24px;">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn-update" id="submitBtn">
                        <i class="fas fa-save"></i> Update Doctor
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
    
    // Live preview update
    $('#doctorName').on('input', function() {
        const name = $(this).val();
        $('#previewName').text('Dr. ' + (name || '<?php echo $row['name']; ?>'));
    });
    
    // Mobile number validation
    $('#mobileNo').on('input', function() {
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
    
    // Blood group change effect
    $('#bloodSelect').on('change', function() {
        if ($(this).val()) {
            $(this).css('border-color', '#48bb78');
        }
    });
    
    // Form submission animation
    $('#editForm').on('submit', function() {
        const btn = $('#submitBtn');
        btn.html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        btn.prop('disabled', true);
        
        setTimeout(function() {
            btn.prop('disabled', false);
        }, 3000);
    });
    
    // Gender icon update
    function updateGenderIcon() {
        const gender = $('#genderSelect').val();
        const icon = $('.input-icon-wrapper i').first();
        if (gender === 'Male') {
            icon.removeClass('fa-genderless').addClass('fa-mars');
        } else if (gender === 'Female') {
            icon.removeClass('fa-genderless').addClass('fa-venus');
        } else {
            icon.removeClass('fa-mars fa-venus').addClass('fa-genderless');
        }
    }
    
    $('#genderSelect').on('change', updateGenderIcon);
    updateGenderIcon();
</script>

</body>
</html>