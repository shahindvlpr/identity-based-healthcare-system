<?php include('dbconn.php'); ?>

<?php
  $message = "";
  session_start();
  if(isset($_POST['btn'])){
    $hosid = mysqli_real_escape_string($conn,$_POST['hosid']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);
    
    $query = mysqli_query($conn,"SELECT * FROM hospital WHERE hospital_id ='$hosid' AND PASSWORD ='$password'");
    
    if ($query && $query->num_rows > 0){
      $row = mysqli_fetch_array($query);
      $user_id = $row['hospital_id'];
      $_SESSION['id']=$user_id;
      $_SESSION['radio'] = "Hospital";
      header("Location:hospitalhome.php");
      exit();
    }else{ 
      $message = "Invalid Hospital ID or Password!";
    }	
  }    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Hospital Management System - Identity Based Healthcare">
    <meta name="author" content="Healthcare Team">

    <!-- Bootstrap & Dependencies -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <title>Hospital Login | Identity Based Healthcare</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e5799 0%, #207cca 50%, #2989d8 100%);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Medical Icons Background Pattern */
        .medical-pattern {
            position: fixed;
            width: 100%;
            height: 100%;
            opacity: 0.05;
            z-index: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='1' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M22 12h-4M6 12H2M12 2v4M12 18v4'%3E%3C/path%3E%3Ccircle cx='12' cy='12' r='2'%3E%3C/circle%3E%3C/svg%3E");
            background-repeat: repeat;
        }

        /* Animated Background Circles */
        .bg-circle {
            position: fixed;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            animation: float 20s infinite ease-in-out;
            z-index: 0;
        }

        .circle1 { width: 400px; height: 400px; top: -150px; left: -150px; animation-delay: 0s; }
        .circle2 { width: 600px; height: 600px; bottom: -250px; right: -200px; animation-delay: 5s; }
        .circle3 { width: 250px; height: 250px; top: 50%; left: 10%; animation-delay: 2s; }
        .circle4 { width: 350px; height: 350px; bottom: 20%; right: 10%; animation-delay: 7s; }
        .circle5 { width: 180px; height: 180px; top: 20%; right: 15%; animation-delay: 3s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) translateX(0px); }
            25% { transform: translateY(-40px) translateX(30px); }
            50% { transform: translateY(30px) translateX(-20px); }
            75% { transform: translateY(-20px) translateX(-30px); }
        }

        /* Navbar Styling */
        .navbar-modern {
            background: linear-gradient(135deg, #009B46, #007a38) !important;
            box-shadow: 0 2px 25px rgba(0,0,0,0.15);
            padding: 12px 0;
            transition: all 0.3s;
        }

        .logo_img img {
            height: 55px;
            transition: transform 0.3s;
        }

        .logo_img img:hover {
            transform: scale(1.05);
        }

        /* Main Container */
        .middlecolumn {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 100px 20px 60px;
            z-index: 1;
        }

        /* Left Side Styling */
        .leftside {
            position: relative;
            animation: slideInLeft 1s ease-out;
            text-align: center;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .leftside img {
            max-width: 280px;
            height: auto;
            filter: drop-shadow(20px 20px 40px rgba(0,0,0,0.2));
            transition: transform 0.3s;
            margin-bottom: 30px;
        }

        .leftside img:hover {
            transform: scale(1.02);
        }

        .hospital-info {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 20px;
            margin-top: 20px;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .hospital-info h3 {
            color: white;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .hospital-info p {
            color: rgba(255,255,255,0.9);
            font-size: 14px;
            line-height: 1.6;
        }

        .feature-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.2);
            padding: 8px 15px;
            border-radius: 50px;
            margin: 5px;
            font-size: 12px;
            color: white;
        }

        /* Right Side Card */
        .rightside {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 25px;
            padding: 45px 40px;
            width: 450px;
            position: relative;
            box-shadow: 0 25px 55px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
            animation: slideInRight 1s ease-out;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .rightside:hover {
            transform: translateY(-5px);
            box-shadow: 0 35px 65px rgba(0,0,0,0.35);
        }

        /* Form Header */
        .form-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .hospital-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #009B46, #007a38);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0,155,70,0.4); }
            50% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(0,155,70,0); }
        }

        .hospital-icon i {
            font-size: 40px;
            color: white;
        }

        .form-header h2 {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #009B46, #207cca);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 8px;
        }

        .form-header p {
            color: #666;
            font-size: 14px;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
            font-size: 14px;
        }

        .form-group label i {
            margin-right: 8px;
            color: #009B46;
        }

        .input-group {
            position: relative;
        }

        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: #009B46;
        }

        .form-control {
            border-radius: 12px;
            border: 2px solid #e0e0e0;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #009B46;
            box-shadow: 0 0 0 4px rgba(0, 155, 70, 0.1);
            outline: none;
        }

        /* Button Styling */
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #009B46, #007a38);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            font-size: 16px;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-login:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 155, 70, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login i {
            margin-right: 8px;
        }

        /* Registration Button */
        .btn-register {
            display: block;
            width: 100%;
            text-align: center;
            padding: 12px;
            margin-top: 15px;
            background: #fff;
            color: #009B46;
            border: 2px solid #009B46;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-register:hover {
            background: #009B46;
            color: #fff;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 155, 70, 0.2);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e0e0e0;
        }

        .divider span {
            padding: 0 15px;
            color: #999;
            font-size: 13px;
            font-weight: 500;
        }

        /* Alert Message */
        .alert-message {
            background: linear-gradient(135deg, #ffe6e6, #ffcccc);
            color: #d63031;
            padding: 12px 18px;
            border-radius: 12px;
            margin-bottom: 25px;
            border-left: 4px solid #d63031;
            animation: shake 0.5s ease-in-out;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-8px); }
            75% { transform: translateX(8px); }
        }

        /* Security Badge */
        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .security-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #666;
        }

        .security-item i {
            color: #009B46;
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .leftside {
                display: none;
            }
            
            .rightside {
                width: 450px;
                margin: 0 auto;
            }
        }

        @media (max-width: 768px) {
            .rightside {
                width: 90%;
                padding: 35px 25px;
            }
            
            .form-header h2 {
                font-size: 24px;
            }
            
            .hospital-icon {
                width: 65px;
                height: 65px;
            }
            
            .hospital-icon i {
                font-size: 32px;
            }
        }

        /* Remove number input arrows */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</head>

<body>
    <!-- Medical Pattern Background -->
    <div class="medical-pattern"></div>
    
    <!-- Animated Background Circles -->
    <div class="bg-circle circle1"></div>
    <div class="bg-circle circle2"></div>
    <div class="bg-circle circle3"></div>
    <div class="bg-circle circle4"></div>
    <div class="bg-circle circle5"></div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-modern fixed-top">
        <div class="container">
            <div class="logo_img">
                <a href="index.html">
                    <img src="img/bg_logo1.png" alt="Healthcare Logo">
                </a>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="middlecolumn">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <!-- Left Side - Hospital Information -->
                <div class="col-md-6 leftside">
                    <img src="img/logo.png" alt="Hospital Logo">
                    <div class="hospital-info animate__animated animate__fadeInUp">
                        <h3><i class="fas fa-hospital-user"></i> Hospital Management Portal</h3>
                        <p>Secure access to patient records, appointment management, and healthcare analytics.</p>
                        <div>
                            <span class="feature-badge"><i class="fas fa-chart-line"></i> Analytics</span>
                            <span class="feature-badge"><i class="fas fa-ambulance"></i> Emergency</span>
                            <span class="feature-badge"><i class="fas fa-calendar-check"></i> Appointments</span>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - Login Form -->
                <div class="col-md-6">
                    <div class="rightside">
                        <div class="form-header">
                            <div class="hospital-icon">
                                <i class="fas fa-hospital"></i>
                            </div>
                            <h2>Hospital Login</h2>
                            <p>Access your hospital dashboard securely</p>
                        </div>
                        
                        <!-- Error Message Display -->
                        <?php if($message): ?>
                        <div class="alert-message">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span><?php echo htmlspecialchars($message); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <form method="post">
                            <div class="form-group">
                                <label><i class="fas fa-id-badge"></i> Hospital ID</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-building"></i>
                                        </span>
                                    </div>
                                    <input type="number" name="hosid" class="form-control" id="hosid" placeholder="Enter your Hospital ID" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-lock"></i> Password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-key"></i>
                                        </span>
                                    </div>
                                    <input type="password" name="password" class="form-control" id="Password" placeholder="Enter your password" required>
                                </div>
                            </div>
                            
                            <button type="submit" name="btn" class="btn-login">
                                <i class="fas fa-sign-in-alt"></i> Sign In
                            </button>
                        </form>
                        
                        <!-- REGISTRATION BUTTON -->
                        <div class="divider">
                            <span>New Hospital?</span>
                        </div>
                        <a href="hospitalregistration.php" class="btn-register">
                            <i class="fas fa-hospital-user mr-2"></i> Register Your Hospital
                        </a>
                        
                        <div class="security-badge">
                            <div class="security-item">
                                <i class="fas fa-shield-alt"></i>
                                <span>SSL Secure</span>
                            </div>
                            <div class="security-item">
                                <i class="fas fa-lock"></i>
                                <span>Encrypted</span>
                            </div>
                            <div class="security-item">
                                <i class="fas fa-clock"></i>
                                <span>24/7 Support</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add loading effect on form submit
        document.querySelector('form')?.addEventListener('submit', function(e) {
            const submitBtn = document.querySelector('.btn-login');
            if(submitBtn && !submitBtn.classList.contains('loading')) {
                submitBtn.classList.add('loading');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
            }
        });
        
        // Input field animations
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateX(3px)';
            });
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateX(0)';
            });
        });
        
        // Add hover effect to feature badges
        document.querySelectorAll('.feature-badge').forEach(badge => {
            badge.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
                this.style.transition = '0.3s';
            });
            badge.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>