<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Identity Based Healthcare System">
    <meta name="author" content="">

    <!-- Bootstrap & Dependencies -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <title>Identity Based Healthcare | Secure Login</title>
    
    <?php include('dbconn.php'); ?>

    <?php
      $message = "";
      session_start();
      if(isset($_POST['btn'])){
        $nid = mysqli_real_escape_string($conn,$_POST['nid']);
        $password = mysqli_real_escape_string($conn,$_POST['password']);
        $radio = mysqli_real_escape_string($conn,$_POST['radio']);
        if($radio =='Patient'){
          $query = mysqli_query($conn,"SELECT * FROM patient WHERE p_nid ='$nid' AND PASSWORD ='$password'");
          $row = mysqli_fetch_array($query);
          $user_id = $row['p_nid'];
        }else{
          $query = mysqli_query($conn,"SELECT * FROM doctor WHERE d_nid ='$nid' AND PASSWORD ='$password'");
          $row = mysqli_fetch_array($query);
          $user_id = $row['d_nid'];
        }
        
        if ($query->num_rows > 0){
          $_SESSION['id'] = $user_id;
          $_SESSION['radio'] = $radio;
          if($radio == 'Patient'){
              header("Location: patienthome.php");
              exit();
          }else if($radio == 'Doctor'){
              header("Location: doctorhome.php");
              exit();
          }
        }else{
          $message = "Invalid NID or Password!";
        }	
      }
    ?>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Animated background circles */
        .bg-circle {
            position: fixed;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 20s infinite ease-in-out;
            z-index: 0;
        }

        .circle1 {
            width: 300px;
            height: 300px;
            top: -100px;
            left: -100px;
            animation-delay: 0s;
        }

        .circle2 {
            width: 500px;
            height: 500px;
            bottom: -200px;
            right: -150px;
            animation-delay: 5s;
        }

        .circle3 {
            width: 200px;
            height: 200px;
            top: 40%;
            left: 10%;
            animation-delay: 2s;
        }

        .circle4 {
            width: 400px;
            height: 400px;
            bottom: 20%;
            right: 15%;
            animation-delay: 7s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) translateX(0px); }
            25% { transform: translateY(-30px) translateX(20px); }
            50% { transform: translateY(20px) translateX(-15px); }
            75% { transform: translateY(-20px) translateX(-20px); }
        }

        /* Modern Navbar */
        .navbar-modern {
            background: linear-gradient(135deg, #009B46, #007a38) !important;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            padding: 15px 0;
            transition: all 0.3s;
        }

        .logo_img img {
            height: 55px;
            transition: transform 0.3s;
        }

        .logo_img img:hover {
            transform: scale(1.05);
        }

        .navbar-nav .nav-link {
            color: white !important;
            font-size: 16px;
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s;
            position: relative;
        }

        .navbar-nav .nav-link:hover {
            color: #ffd700 !important;
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: #ffd700;
            transition: width 0.3s;
        }

        .navbar-nav .nav-link:hover::after {
            width: 80%;
        }

        /* Main Container */
        .middlecolumn {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 100px 20px 60px;
        }

        /* Left Side Styling */
        .leftside {
            position: relative;
            animation: slideInLeft 1s ease-out;
        }

        .leftside img {
            max-width: 100%;
            height: auto;
            filter: drop-shadow(20px 20px 40px rgba(0,0,0,0.2));
            transition: transform 0.3s;
        }

        .leftside img:hover {
            transform: scale(1.02);
        }

        /* Right Side Card */
        .rightside {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            padding: 40px;
            width: 420px;
            position: relative;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
            animation: slideInRight 1s ease-out;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .rightside:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 70px rgba(0,0,0,0.4);
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

        /* Form Styling */
        .form-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-title h3 {
            color: #333;
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .form-title p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            display: block;
            font-size: 14px;
        }

        .form-group label i {
            margin-right: 8px;
            color: #009B46;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #009B46;
            box-shadow: 0 0 0 0.2rem rgba(0, 155, 70, 0.25);
            outline: none;
        }

        /* Radio Buttons Styling */
        .radio-group {
            display: flex;
            gap: 30px;
            margin-top: 5px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .radio-option input[type="radio"] {
            display: none;
        }

        .radio-option .radio-custom {
            width: 20px;
            height: 20px;
            border: 2px solid #ddd;
            border-radius: 50%;
            margin-right: 10px;
            position: relative;
            transition: all 0.3s;
        }

        .radio-option input[type="radio"]:checked + .radio-custom {
            border-color: #009B46;
            background: #009B46;
        }

        .radio-option input[type="radio"]:checked + .radio-custom::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
        }

        .radio-option .radio-label {
            color: #555;
            font-weight: 500;
        }

        /* Button Styling */
        .btn-primary {
            background: linear-gradient(135deg, #009B46, #007a38);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s;
            margin-bottom: 20px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 155, 70, 0.4);
            background: linear-gradient(135deg, #007a38, #005c2a);
        }

        .btn-secondary {
            background: white;
            border: 2px solid #009B46;
            color: #009B46;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-secondary:hover {
            background: #009B46;
            color: white;
            transform: translateY(-2px);
        }

        .btn-group-custom {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 15px;
        }

        .btn-group-custom a {
            flex: 1;
            text-decoration: none;
        }

        .btn-group-custom button {
            width: 100%;
        }

        hr {
            border-top: 2px solid #f0f0f0;
            margin: 20px 0;
        }

        /* Alert Message */
        .alert-message {
            background: linear-gradient(135deg, #ffe6e6, #ffcccc);
            color: #d63031;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
            border-left: 4px solid #d63031;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
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
                padding: 30px 25px;
            }
            
            .radio-group {
                flex-direction: column;
                gap: 15px;
            }
            
            .btn-group-custom {
                flex-direction: column;
            }
        }

        /* Floating Labels Effect */
        .form-group {
            position: relative;
        }

        /* Loading Effect for Button */
        .btn-primary:active {
            transform: scale(0.98);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #009B46;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #007a38;
        }
    </style>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</head>

<body>

<!-- Animated Background Circles -->
<div class="bg-circle circle1"></div>
<div class="bg-circle circle2"></div>
<div class="bg-circle circle3"></div>
<div class="bg-circle circle4"></div>

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
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-info-circle"></i> About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-phone"></i> Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="middlecolumn">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <!-- Left Side - Branding -->
            <div class="col-md-6 leftside text-center">
                <img src="img/logo.png" alt="Healthcare Logo" class="img-fluid">
                <div class="mt-4 animate__animated animate__fadeInUp">
                    <h3 class="text-white">Welcome to Identity Based Healthcare</h3>
                    <p class="text-white-50">Secure • Reliable • Efficient</p>
                </div>
            </div>
            
            <!-- Right Side - Login Form -->
            <div class="col-md-6">
                <div class="rightside">
                    <div class="form-title">
                        <h3><i class="fas fa-lock" style="color: #009B46;"></i> Secure Login</h3>
                        <p>Access your healthcare dashboard</p>
                    </div>
                    
                    <!-- Error Message Display -->
                    <?php if($message): ?>
                    <div class="alert-message">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $message; ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="post">
                        <!-- NID Field -->
                        <div class="form-group">
                            <label><i class="fas fa-id-card"></i> National ID (NID)</label>
                            <input type="number" name="nid" class="form-control" id="nid" placeholder="Enter your NID number" required>
                        </div>
                        
                        <!-- Password Field -->
                        <div class="form-group">
                            <label><i class="fas fa-key"></i> Password</label>
                            <input type="password" name="password" class="form-control" id="Password" placeholder="Enter your password" required>
                        </div>
                        
                        <!-- Login As Radio Buttons -->
                        <div class="form-group">
                            <label><i class="fas fa-user-tag"></i> Login As</label>
                            <div class="radio-group">
                                <div class="radio-option">
                                    <input type="radio" name="radio" id="patient" value="Patient" checked>
                                    <span class="radio-custom"></span>
                                    <span class="radio-label">Patient</span>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" name="radio" id="doctor" value="Doctor">
                                    <span class="radio-custom"></span>
                                    <span class="radio-label">Doctor</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sign In Button -->
                        <button type="submit" name="btn" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Sign In
                        </button>
                        
                        <hr>
                        
                        <!-- Registration Buttons -->
                        <div class="btn-group-custom">
                            <a href="patientreg.php">
                                <button type="button" class="btn btn-secondary">
                                    <i class="fas fa-user-plus"></i> Register as Patient
                                </button>
                            </a>
                            <a href="doctorreg.php"> 
                                <button type="button" class="btn btn-secondary">
                                    <i class="fas fa-user-md"></i> Register as Doctor
                                </button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Smooth animations and form interactions
    $(document).ready(function() {
        // Add floating effect to inputs
        $('.form-control').on('focus', function() {
            $(this).parent().addClass('focused');
        }).on('blur', function() {
            $(this).parent().removeClass('focused');
        });
        
        // Radio button click animation
        $('.radio-option').on('click', function() {
            $(this).find('input[type="radio"]').prop('checked', true);
        });
    });
</script>

</body>
</html>