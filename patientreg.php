<?php include('dbconn.php'); ?> 
<?php
    $message=""; 
    if(isset($_POST['submit'])){
        $nid = $_POST['nid'];
        $finger_print = $_POST['finger_print'];
        $retina_print = $_POST['retina_print'];
        $query1 = mysqli_query($conn,"SELECT * FROM person where nid = '$nid' and finger_print = '$finger_print' and retina_print = '$retina_print' and isalive = 'yes'");
        $person_row = mysqli_fetch_array($query1);
        $query2 = mysqli_query($conn,"SELECT * FROM patient where p_nid = '$nid'");
        $patient_row = mysqli_fetch_array($query2);
        if($person_row!=NULL && $patient_row==NULL){
            header("location:preview.php?id=".$nid);
        }else{
            $message = "Wrong Information!";
        }
    }

?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Patient Registration | Identity Based Healthcare</title>
    
    <!-- Bootstrap CSS -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
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
        
        /* Animated Background */
        .bg-animation {
            position: fixed;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        
        .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: floatBubble 15s infinite ease-in-out;
        }
        
        .bubble-1 { width: 300px; height: 300px; top: -100px; left: -100px; animation-delay: 0s; }
        .bubble-2 { width: 500px; height: 500px; bottom: -200px; right: -150px; animation-delay: 5s; }
        .bubble-3 { width: 200px; height: 200px; top: 40%; left: 10%; animation-delay: 2s; }
        .bubble-4 { width: 400px; height: 400px; bottom: 20%; right: 15%; animation-delay: 7s; }
        .bubble-5 { width: 150px; height: 150px; top: 60%; left: 80%; animation-delay: 3s; }
        
        @keyframes floatBubble {
            0%, 100% { transform: translateY(0px) translateX(0px) rotate(0deg); }
            25% { transform: translateY(-40px) translateX(30px) rotate(5deg); }
            50% { transform: translateY(30px) translateX(-20px) rotate(-5deg); }
            75% { transform: translateY(-20px) translateX(-30px) rotate(3deg); }
        }
        
        /* Navbar Styling */
        .navbar-modern {
            background: linear-gradient(135deg, #009B46, #007a38) !important;
            box-shadow: 0 2px 20px rgba(0,0,0,0.15);
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
        
        /* Form Container */
        .form-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 100px 20px 60px;
            z-index: 1;
        }
        
        .form-wrap {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            padding: 50px 40px;
            width: 500px;
            max-width: 100%;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
            animation: slideUp 0.8s ease-out;
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .form-wrap::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #009B46, #00d4ff, #009B46);
            background-size: 200% 100%;
            animation: gradientMove 3s ease infinite;
        }
        
        @keyframes gradientMove {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .form-wrap:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.4);
        }
        
        /* Header Styling */
        .form-header {
            text-align: center;
            margin-bottom: 35px;
        }
        
        .form-header h1 {
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(135deg, #009B46, #00d4ff);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 10px;
        }
        
        .form-header p {
            color: #666;
            font-size: 14px;
        }
        
        .icon-circle {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #009B46, #00d4ff);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .icon-circle i {
            font-size: 35px;
            color: white;
        }
        
        /* Input Fields */
        .input-group-custom {
            margin-bottom: 25px;
            position: relative;
        }
        
        .input-group-custom label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        .input-group-custom label i {
            margin-right: 8px;
            color: #009B46;
        }
        
        .input-group-custom input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s;
            background: white;
            font-family: 'Poppins', sans-serif;
        }
        
        .input-group-custom input:focus {
            outline: none;
            border-color: #009B46;
            box-shadow: 0 0 0 4px rgba(0, 155, 70, 0.1);
            transform: translateY(-2px);
        }
        
        .input-group-custom input:hover {
            border-color: #009B46;
        }
        
        /* Submit Button */
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #009B46, #007a38);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            font-family: 'Poppins', sans-serif;
            position: relative;
            overflow: hidden;
        }
        
        .btn-submit::before {
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
        
        .btn-submit:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 155, 70, 0.3);
        }
        
        .btn-submit:active {
            transform: translateY(0);
        }
        
        .btn-submit i {
            margin-right: 8px;
        }
        
        /* Alert Message */
        .alert-custom {
            background: linear-gradient(135deg, #ffe6e6, #ffcccc);
            color: #d63031;
            padding: 12px 18px;
            border-radius: 12px;
            margin-bottom: 25px;
            border-left: 4px solid #d63031;
            animation: shake 0.5s ease-in-out;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        /* Info Box */
        .info-box {
            background: #f0f9ff;
            border-radius: 12px;
            padding: 15px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid #cde5f5;
        }
        
        .info-box i {
            font-size: 24px;
            color: #009B46;
        }
        
        .info-box p {
            margin: 0;
            font-size: 12px;
            color: #666;
            line-height: 1.4;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .form-wrap {
                padding: 35px 25px;
            }
            
            .form-header h1 {
                font-size: 26px;
            }
            
            .input-group-custom input {
                padding: 12px 15px;
            }
        }
        
        /* Loading Animation for Button */
        .btn-submit.loading {
            pointer-events: none;
            opacity: 0.7;
        }
        
        /* Number Input Remove Arrows */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        input[type=number] {
            -moz-appearance: textfield;
        }
        
        /* Floating Label Effect */
        .input-group-custom {
            position: relative;
        }
        
        /* Footer */
        .footer-text {
            text-align: center;
            margin-top: 25px;
            font-size: 12px;
            color: #999;
        }
        
        .footer-text a {
            color: #009B46;
            text-decoration: none;
        }
        
        .footer-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <!-- Animated Background Bubbles -->
    <div class="bg-animation">
        <div class="bubble bubble-1"></div>
        <div class="bubble bubble-2"></div>
        <div class="bubble bubble-3"></div>
        <div class="bubble bubble-4"></div>
        <div class="bubble bubble-5"></div>
    </div>
    
    <!-- Navigation Bar -->
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
    
    <!-- Form Container -->
    <div class="form-container">
        <div class="form-wrap animate__animated animate__fadeInUp">
            <div class="form-header">
                <div class="icon-circle">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1>Patient Registration</h1>
                <p>Verify your identity to continue</p>
            </div>
            
            <!-- Error Message Display -->
            <?php if($message): ?>
            <div class="alert-custom">
                <i class="fas fa-exclamation-triangle"></i>
                <span><?php echo htmlspecialchars($message); ?></span>
            </div>
            <?php endif; ?>
            
            <form action="" method="POST">
                <div class="input-group-custom">
                    <label><i class="fas fa-id-card"></i> National ID (NID)</label>
                    <input type="number" placeholder="Enter your NID number" name="nid" required>
                </div>
                
                <div class="input-group-custom">
                    <label><i class="fas fa-fingerprint"></i> Finger Print Code</label>
                    <input type="number" placeholder="Enter finger print code" name="finger_print" required>
                </div>
                
                <div class="input-group-custom">
                    <label><i class="fas fa-eye"></i> Retina Print Code</label>
                    <input type="number" placeholder="Enter retina print code" name="retina_print" required>
                </div>
                
                <button type="submit" name="submit" class="btn-submit">
                    <i class="fas fa-arrow-right"></i> Verify & Continue
                </button>
                
                <!-- <div class="info-box">
                    <i class="fas fa-shield-alt"></i>
                    <p>Your biometric data is encrypted and secure. We use advanced security protocols to protect your information.</p>
                </div>
                
                <div class="footer-text">
                    <i class="fas fa-lock"></i> Secure Registration Process
                </div> -->
            </form>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Add loading effect on form submit
        document.querySelector('form')?.addEventListener('submit', function(e) {
            const submitBtn = document.querySelector('.btn-submit');
            if(submitBtn) {
                submitBtn.classList.add('loading');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';
            }
        });
        
        // Input field animations
        document.querySelectorAll('.input-group-custom input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateX(5px)';
            });
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateX(0)';
            });
        });
    </script>
</body>
</html>