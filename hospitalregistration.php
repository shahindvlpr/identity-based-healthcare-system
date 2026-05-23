<?php include('dbconn.php'); ?>
<?php include('session.php'); ?>

<?php
    $success_message = "";
    $error_message = "";

    if(isset($_POST['register'])){
        $hospital_name = mysqli_real_escape_string($conn, $_POST['hospital_name']);
        $trade_license = mysqli_real_escape_string($conn, $_POST['trade_license']);
        $govt_reg_no = mysqli_real_escape_string($conn, $_POST['govt_reg_no']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $owner_name = mysqli_real_escape_string($conn, $_POST['owner_name']);
        $owner_nid = mysqli_real_escape_string($conn, $_POST['owner_nid']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $numberof_ward = mysqli_real_escape_string($conn, $_POST['numberof_ward']);
        $wardfee_perday = mysqli_real_escape_string($conn, $_POST['wardfee_perday']);
        $numberof_cabin = mysqli_real_escape_string($conn, $_POST['numberof_cabin']);
        $cabinfee_perday = mysqli_real_escape_string($conn, $_POST['cabinfee_perday']);
        
        // Handle file uploads
        $trade_license_file = '';
        $govt_approval_file = '';
        $hospital_certificate_file = '';
        
        $upload_dir = 'uploads/hospital_docs/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Upload Trade License
        if(isset($_FILES['trade_license_file']) && $_FILES['trade_license_file']['error'] == 0){
            $ext = pathinfo($_FILES['trade_license_file']['name'], PATHINFO_EXTENSION);
            $trade_license_file = $upload_dir . 'trade_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['trade_license_file']['tmp_name'], $trade_license_file);
        }
        
        // Upload Government Approval
        if(isset($_FILES['govt_approval_file']) && $_FILES['govt_approval_file']['error'] == 0){
            $ext = pathinfo($_FILES['govt_approval_file']['name'], PATHINFO_EXTENSION);
            $govt_approval_file = $upload_dir . 'govt_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['govt_approval_file']['tmp_name'], $govt_approval_file);
        }
        
        // Upload Hospital Certificate
        if(isset($_FILES['hospital_certificate_file']) && $_FILES['hospital_certificate_file']['error'] == 0){
            $ext = pathinfo($_FILES['hospital_certificate_file']['name'], PATHINFO_EXTENSION);
            $hospital_certificate_file = $upload_dir . 'cert_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['hospital_certificate_file']['tmp_name'], $hospital_certificate_file);
        }
        
        // Check if hospital already exists
        $check_query = mysqli_query($conn, "SELECT * FROM hospital WHERE trade_license = '$trade_license' OR govt_reg_no = '$govt_reg_no'");
        if($check_query && mysqli_num_rows($check_query) > 0){
            $error_message = "A hospital with this Trade License or Government Registration Number already exists!";
        } else {
            // Insert into hospital table
            $insert_query = mysqli_query($conn, "INSERT INTO hospital (
                hospital_name, 
                trade_license, 
                govt_reg_no, 
                address, 
                city,
                contact_number, 
                email, 
                owner_name, 
                owner_nid, 
                PASSWORD,
                numberof_ward,
                wardfee_perday,
                numberof_cabin,
                cabinfee_perday,
                trade_license_file,
                govt_approval_file,
                hospital_certificate_file,
                docreg,
                docregtext
            ) VALUES (
                '$hospital_name',
                '$trade_license',
                '$govt_reg_no',
                '$address',
                '$city',
                '$contact_number',
                '$email',
                '$owner_name',
                '$owner_nid',
                '$password',
                '$numberof_ward',
                '$wardfee_perday',
                '$numberof_cabin',
                '$cabinfee_perday',
                '$trade_license_file',
                '$govt_approval_file',
                '$hospital_certificate_file',
                '1',
                'We are hiring doctors. Apply now!'
            )");
            
            if($insert_query){
                $hospital_id = mysqli_insert_id($conn);
                $success_message = "Hospital registered successfully! Your Hospital ID is: <strong>" . $hospital_id . "</strong>. Please save this ID for login.";
            } else {
                $error_message = "Registration failed: " . mysqli_error($conn);
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Hospital Registration - IBHS">
    <meta name="author" content="">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <title>Hospital Registration | IBHS</title>

    <style>
        :root {
            --primary: #009B46;
            --primary-dark: #007a38;
            --primary-light: #e8f5e9;
            --danger: #e74c3c;
            --dark: #1a1a2e;
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
            --shadow-md: 0 5px 20px rgba(0,0,0,0.08);
            --shadow-lg: 0 15px 40px rgba(0,0,0,0.15);
            --radius: 16px;
            --radius-sm: 12px;
            --transition: all 0.3s ease;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: var(--text-dark);
            min-height: 100vh;
            padding-top: 85px;
        }

        .navbar {
            background: #ffffff !important;
            padding: 12px 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
            height: 72px;
        }

        .logo_img img { height: 44px; }

        .page-header {
            background: linear-gradient(135deg, #1a1a2e, #2c3e50);
            padding: 28px 0;
            margin-bottom: 30px;
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
            gap: 15px;
            position: relative;
            z-index: 1;
        }

        .header-icon-box {
            width: 55px;
            height: 55px;
            background: rgba(255,255,255,0.15);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #fff;
            flex-shrink: 0;
        }

        .header-content h4 {
            color: #fff;
            font-weight: 700;
            font-size: 1.4rem;
            margin: 0;
        }

        .header-content span {
            color: rgba(255,255,255,0.8);
            font-size: 0.82rem;
        }

        .form-container {
            max-width: 900px;
            margin: 0 auto 50px;
        }

        .section-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid #f0f0f0;
            padding: 30px;
            margin-bottom: 25px;
        }

        .section-title {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title .section-num {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .section-title i { color: var(--primary); }

        .form-group { margin-bottom: 20px; }

        .form-group label {
            font-weight: 600;
            color: #444;
            font-size: 0.85rem;
            margin-bottom: 6px;
            display: block;
        }

        .form-group label i { color: var(--primary); margin-right: 6px; width: 16px; }

        .form-group .form-control {
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            padding: 11px 15px;
            font-size: 0.88rem;
            font-family: 'Poppins', sans-serif;
            transition: var(--transition);
        }

        .form-group .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(0, 155, 70, 0.1);
        }

        .form-group textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .file-upload-area {
            border: 2px dashed #ddd;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            transition: var(--transition);
            cursor: pointer;
            background: #fafafa;
        }

        .file-upload-area:hover {
            border-color: var(--primary);
            background: #f0fdf4;
        }

        .file-upload-area i {
            font-size: 2.5rem;
            color: #ccc;
            display: block;
            margin-bottom: 10px;
        }

        .file-upload-area.has-file i { color: var(--primary); }
        .file-upload-area.has-file { border-color: var(--primary); background: var(--primary-light); }

        .file-upload-area input[type="file"] { display: none; }

        .file-upload-area .file-name {
            font-size: 0.8rem;
            color: #666;
            margin-top: 5px;
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            letter-spacing: 0.5px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 155, 70, 0.3);
        }

        .alert-success-custom {
            background: #e8f5e9;
            color: #27ae60;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #27ae60;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .alert-error-custom {
            background: #ffebee;
            color: #c62828;
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid #c62828;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--primary);
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .back-link:hover { text-decoration: underline; }

        @media (max-width: 768px) {
            body { padding-top: 75px; }
            .page-header { padding: 20px 0; border-radius: 0 0 20px 20px; }
            .form-row { grid-template-columns: 1fr; }
            .section-card { padding: 20px; }
        }
    </style>
</head>

<body>

    <!-- ============ NAVIGATION ============ -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <div class="logo_img">
                <a href="index.html"><img src="img/bg_logo1.png" alt="IBHS Logo"></a>
            </div>
        </div>
    </nav>

    <!-- ============ PAGE HEADER ============ -->
    <div class="page-header">
        <div class="container">
            <div class="header-content">
                <div class="header-icon-box"><i class="fas fa-hospital-user"></i></div>
                <div>
                    <h4>Hospital Registration</h4>
                    <span>Register your hospital to join our network</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ REGISTRATION FORM ============ -->
    <div class="container form-container">
        
        <a href="hospitallogin.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Login
        </a>

        <?php if($success_message): ?>
        <div class="alert-success-custom">
            <i class="fas fa-check-circle mr-2"></i> <?php echo $success_message; ?>
            <br>
            <a href="hospitallogin.php" class="btn-submit" style="display:inline-block;width:auto;padding:10px 25px;margin-top:15px;">
                <i class="fas fa-sign-in-alt mr-2"></i> Go to Login
            </a>
        </div>
        <?php endif; ?>

        <?php if($error_message): ?>
        <div class="alert-error-custom">
            <i class="fas fa-exclamation-circle mr-2"></i> <?php echo $error_message; ?>
        </div>
        <?php endif; ?>

        <?php if(!$success_message): ?>
        <form method="post" enctype="multipart/form-data">
            
            <!-- Section 1: Hospital Information -->
            <div class="section-card">
                <div class="section-title">
                    <span class="section-num">1</span>
                    <i class="fas fa-hospital"></i> Hospital Information
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-building"></i> Hospital Name *</label>
                        <input type="text" name="hospital_name" class="form-control" placeholder="Enter hospital name" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-file-contract"></i> Trade License Number *</label>
                        <input type="text" name="trade_license" class="form-control" placeholder="Enter trade license number" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-certificate"></i> Government Registration Number *</label>
                        <input type="text" name="govt_reg_no" class="form-control" placeholder="Enter government registration number" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-phone"></i> Contact Number *</label>
                        <input type="text" name="contact_number" class="form-control" placeholder="Enter contact number" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter email address">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-map-marker-alt"></i> Address *</label>
                    <textarea name="address" class="form-control" placeholder="Enter full address" required></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-city"></i> City *</label>
                        <input type="text" name="city" class="form-control" placeholder="Enter city" required>
                    </div>
                </div>
            </div>

            <!-- Section 2: Hospital Facilities -->
            <div class="section-card">
                <div class="section-title">
                    <span class="section-num">2</span>
                    <i class="fas fa-procedures"></i> Hospital Facilities
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-bed"></i> Number of Wards *</label>
                        <input type="number" name="numberof_ward" class="form-control" placeholder="Enter number of wards" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-money-bill-wave"></i> Ward Fee (Per Day) *</label>
                        <input type="number" name="wardfee_perday" class="form-control" placeholder="Enter fee per day" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-door-open"></i> Number of Cabins *</label>
                        <input type="number" name="numberof_cabin" class="form-control" placeholder="Enter number of cabins" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-coins"></i> Cabin Fee (Per Day) *</label>
                        <input type="number" name="cabinfee_perday" class="form-control" placeholder="Enter fee per day" required>
                    </div>
                </div>
            </div>

            <!-- Section 3: Owner/Admin Information -->
            <div class="section-card">
                <div class="section-title">
                    <span class="section-num">3</span>
                    <i class="fas fa-user-tie"></i> Owner / Admin Information
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Owner Name *</label>
                        <input type="text" name="owner_name" class="form-control" placeholder="Enter owner name" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-id-card"></i> Owner NID *</label>
                        <input type="text" name="owner_nid" class="form-control" placeholder="Enter owner NID" required>
                    </div>
                </div>
            </div>

            <!-- Section 4: Document Upload -->
            <div class="section-card">
                <div class="section-title">
                    <span class="section-num">4</span>
                    <i class="fas fa-file-upload"></i> Document Upload
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-file-alt"></i> Trade License (PDF/JPG/PNG)</label>
                        <div class="file-upload-area" id="tradeLicenseArea" onclick="document.getElementById('trade_license_file').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p style="margin:0;font-weight:500;">Click to upload Trade License</p>
                            <span class="file-name" id="tradeLicenseName">No file chosen</span>
                            <input type="file" name="trade_license_file" id="trade_license_file" accept=".pdf,.jpg,.jpeg,.png" onchange="showFileName(this, 'tradeLicenseName', 'tradeLicenseArea')">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-file-alt"></i> Government Approval Document</label>
                        <div class="file-upload-area" id="govtApprovalArea" onclick="document.getElementById('govt_approval_file').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p style="margin:0;font-weight:500;">Click to upload Government Approval</p>
                            <span class="file-name" id="govtApprovalName">No file chosen</span>
                            <input type="file" name="govt_approval_file" id="govt_approval_file" accept=".pdf,.jpg,.jpeg,.png" onchange="showFileName(this, 'govtApprovalName', 'govtApprovalArea')">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-certificate"></i> Hospital Certificate / License</label>
                    <div class="file-upload-area" id="hospitalCertArea" onclick="document.getElementById('hospital_certificate_file').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p style="margin:0;font-weight:500;">Click to upload Hospital Certificate</p>
                        <span class="file-name" id="hospitalCertName">No file chosen</span>
                        <input type="file" name="hospital_certificate_file" id="hospital_certificate_file" accept=".pdf,.jpg,.jpeg,.png" onchange="showFileName(this, 'hospitalCertName', 'hospitalCertArea')">
                    </div>
                </div>
            </div>

            <!-- Section 5: Login Credentials -->
            <div class="section-card">
                <div class="section-title">
                    <span class="section-num">5</span>
                    <i class="fas fa-lock"></i> Login Credentials
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label><i class="fas fa-key"></i> Password *</label>
                        <input type="password" name="password" class="form-control" placeholder="Create a password" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-key"></i> Confirm Password *</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm your password" required minlength="6">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" name="register" class="btn-submit">
                <i class="fas fa-check-circle mr-2"></i> Submit Registration
            </button>
            <p style="text-align:center;margin-top:15px;color:#888;font-size:0.82rem;">
                <i class="fas fa-info-circle"></i> Your Hospital ID will be generated after successful registration.
            </p>
        </form>
        <?php endif; ?>

    </div>

    <!-- ============ SCRIPTS ============ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js"></script>
    
    <script>
        // Show uploaded file name
        function showFileName(input, nameId, areaId) {
            const fileName = document.getElementById(nameId);
            const uploadArea = document.getElementById(areaId);
            if (input.files && input.files.length > 0) {
                fileName.textContent = input.files[0].name;
                uploadArea.classList.add('has-file');
            } else {
                fileName.textContent = 'No file chosen';
                uploadArea.classList.remove('has-file');
            }
        }

        // Password confirmation check
        document.querySelector('form')?.addEventListener('submit', function(e) {
            const password = document.querySelector('input[name="password"]');
            const confirm = document.querySelector('input[name="confirm_password"]');
            if(password && confirm && password.value !== confirm.value) {
                e.preventDefault();
                alert('Passwords do not match!');
                confirm.focus();
            }
        });

        // Drag and drop for file upload areas
        document.querySelectorAll('.file-upload-area').forEach(area => {
            area.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.style.borderColor = '#009B46';
                this.style.background = '#f0fdf4';
            });
            area.addEventListener('dragleave', function(e) {
                e.preventDefault();
                if(!this.classList.contains('has-file')) {
                    this.style.borderColor = '#ddd';
                    this.style.background = '#fafafa';
                }
            });
            area.addEventListener('drop', function(e) {
                e.preventDefault();
                const input = this.querySelector('input[type="file"]');
                if(input && e.dataTransfer.files.length > 0) {
                    input.files = e.dataTransfer.files;
                    const event = new Event('change');
                    input.dispatchEvent(event);
                }
            });
        });
    </script>
</body>
</html>