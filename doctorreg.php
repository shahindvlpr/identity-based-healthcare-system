<?php include('dbconn.php'); ?> 
<?php
    // print_r($_POST);
    $message=""; 
    if(isset($_POST['submit'])){
   
        $nid = $_POST['nid'];
        $finger_print = $_POST['finger_print'];
        $retina_print = $_POST['retina_print'];
        $dmdc_id = $_POST['dmdc_id'];
        $query1 = mysqli_query($conn,"SELECT * FROM person where nid = '$nid' and finger_print = '$finger_print' and retina_print = '$retina_print' and isalive = 'yes'");
        $person_row = mysqli_fetch_array($query1);
        $query3 = mysqli_query($conn,"SELECT * FROM dmdc where dmdc_id = '$dmdc_id'");
        $dmdc_row = mysqli_fetch_array($query3);
        $query4 = mysqli_query($conn,"SELECT * FROM doctor where dmdc_id = '$dmdc_id' ");
        $docdmdc = mysqli_fetch_array($query4);
        $query2 = mysqli_query($conn,"SELECT * FROM doctor where d_nid = '$nid'");
        $doctor_row = mysqli_fetch_array($query2);


        if($person_row!=NULL && $doctor_row==NULL && $dmdc_row!=NULL && $docdmdc == NULL){
            header("location:previewdoc.php?id=".$nid."&dmdc_id=".$dmdc_id);
        }else{
            $message = "Wrong Information!";
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Doctor Registration - Identity Based Healthcare System">
    <meta name="author" content="Healthcare Team">
    
    <title>Doctor Registration | Identity Based Healthcare</title>
    
    <!-- Bootstrap CSS -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #009B46;
            --primary-dark: #007a38;
            --primary-light: #e8f5e9;
            --secondary: #1a237e;
            --accent: #00bcd4;
            --danger: #d63031;
            --text-dark: #2c3e50;
            --text-gray: #7f8c8d;
            --bg-light: #f8f9fa;
            --shadow-sm: 0 2px 10px rgba(0,0,0,0.08);
            --shadow-md: 0 5px 20px rgba(0,0,0,0.12);
            --shadow-lg: 0 10px 30px rgba(0,0,0,0.15);
            --shadow-xl: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
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
            background: rgba(255, 255, 255, 0.08);
            animation: floatBubble 20s infinite ease-in-out;
        }
        
        .bubble-1 { width: 400px; height: 400px; top: -150px; left: -150px; animation-delay: 0s; }
        .bubble-2 { width: 600px; height: 600px; bottom: -250px; right: -200px; animation-delay: 5s; }
        .bubble-3 { width: 250px; height: 250px; top: 40%; left: 10%; animation-delay: 2s; }
        .bubble-4 { width: 350px; height: 350px; bottom: 20%; right: 15%; animation-delay: 7s; }
        .bubble-5 { width: 180px; height: 180px; top: 60%; left: 85%; animation-delay: 3s; }
        
        @keyframes floatBubble {
            0%, 100% { transform: translateY(0px) translateX(0px) rotate(0deg); }
            25% { transform: translateY(-50px) translateX(40px) rotate(5deg); }
            50% { transform: translateY(40px) translateX(-30px) rotate(-5deg); }
            75% { transform: translateY(-30px) translateX(-40px) rotate(3deg); }
        }
        
        /* Navbar */
        .navbar-modern {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark)) !important;
            box-shadow: var(--shadow-md);
            padding: 1rem 0;
            z-index: 1000;
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
        
        .form-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 32px;
            padding: 50px 45px;
            width: 550px;
            max-width: 100%;
            box-shadow: var(--shadow-xl);
            backdrop-filter: blur(10px);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        
        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--primary), var(--accent), var(--primary));
            background-size: 200% 100%;
            animation: gradientMove 3s ease infinite;
        }
        
        @keyframes gradientMove {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .form-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }
        
        /* Header */
        .form-header {
            text-align: center;
            margin-bottom: 35px;
        }
        
        .doctor-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: pulse 2s infinite;
            box-shadow: 0 10px 25px rgba(0,155,70,0.3);
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0,155,70,0.4); }
            50% { transform: scale(1.05); box-shadow: 0 0 0 20px rgba(0,155,70,0); }
        }
        
        .doctor-icon i {
            font-size: 45px;
            color: white;
        }
        
        .form-header h1 {
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 8px;
        }
        
        .form-header p {
            color: var(--text-gray);
            font-size: 14px;
        }
        
        /* Form Groups */
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 10px;
            display: block;
            font-size: 14px;
        }
        
        .form-group label i {
            margin-right: 8px;
            color: var(--primary);
        }
        
        .input-icon-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .input-icon {
            position: absolute;
            left: 16px;
            color: var(--primary);
            font-size: 18px;
            z-index: 1;
        }
        
        .form-control {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #e0e0e0;
            border-radius: 16px;
            font-size: 15px;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
            background: white;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(0, 155, 70, 0.1);
            transform: translateY(-2px);
        }
        
        .form-control:hover {
            border-color: var(--primary);
        }
        
        /* Number input remove arrows */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        input[type=number] {
            -moz-appearance: textfield;
        }
        
        /* Submit Button */
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 16px;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            font-family: 'Inter', sans-serif;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
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
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 155, 70, 0.3);
        }
        
        .btn-submit:active {
            transform: translateY(0);
        }
        
        /* Alert Message */
        .alert-custom {
            background: linear-gradient(135deg, #ffe6e6, #ffcccc);
            color: var(--danger);
            padding: 14px 20px;
            border-radius: 16px;
            margin-bottom: 25px;
            border-left: 4px solid var(--danger);
            animation: shake 0.5s ease-in-out;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        /* Info Box */
        .info-box {
            background: linear-gradient(135deg, var(--primary-light), white);
            border-radius: 16px;
            padding: 18px;
            margin-top: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            border: 1px solid rgba(0,155,70,0.2);
        }
        
        .info-box i {
            font-size: 28px;
            color: var(--primary);
        }
        
        .info-box p {
            margin: 0;
            font-size: 12px;
            color: var(--text-gray);
            line-height: 1.5;
        }
        
        .info-box strong {
            color: var(--primary);
        }
        
        /* Footer */
        .footer-text {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 12px;
            color: var(--text-gray);
        }
        
        .footer-text i {
            color: var(--primary);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .form-card {
                padding: 35px 25px;
            }
            
            .form-header h1 {
                font-size: 26px;
            }
            
            .doctor-icon {
                width: 70px;
                height: 70px;
            }
            
            .doctor-icon i {
                font-size: 35px;
            }
            
            .form-control {
                padding: 12px 16px 12px 45px;
            }
        }
        
        /* Loading State */
        .btn-submit.loading {
            pointer-events: none;
            opacity: 0.7;
        }
        
        /* Floating Labels Effect */
        .form-group {
            position: relative;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--bg-light);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
    </style>
</head>

<body>
    <!-- Animated Background -->
    <div class="bg-animation">
        <div class="bubble bubble-1"></div>
        <div class="bubble bubble-2"></div>
        <div class="bubble bubble-3"></div>
        <div class="bubble bubble-4"></div>
        <div class="bubble bubble-5"></div>
    </div>
    
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
    
    <!-- Form Container -->
    <div class="form-container">
        <div class="form-card" data-aos="fade-up" data-aos-duration="800">
            <div class="form-header">
                <div class="doctor-icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <h1>Doctor Registration</h1>
                <p>Verify your credentials to become a registered doctor</p>
            </div>
            
            <!-- Error Message Display -->
            <?php if($message): ?>
            <div class="alert-custom" data-aos="fade-in">
                <i class="fas fa-exclamation-triangle"></i>
                <span><?php echo htmlspecialchars($message); ?></span>
            </div>
            <?php endif; ?>
            
            <form action="" method="POST" id="doctorRegForm">
                <div class="form-group">
                    <label><i class="fas fa-id-card"></i> National ID (NID)</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-id-card input-icon"></i>
                        <input type="number" class="form-control" placeholder="Enter your NID number" name="nid" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-hospital"></i> DMDC ID</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-id-badge input-icon"></i>
                        <input type="number" class="form-control" placeholder="Enter your DMDC ID" name="dmdc_id" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-fingerprint"></i> Finger Print Code</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-fingerprint input-icon"></i>
                        <input type="number" class="form-control" placeholder="Enter finger print code" name="finger_print" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-eye"></i> Retina Print Code</label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-eye input-icon"></i>
                        <input type="number" class="form-control" placeholder="Enter retina print code" name="retina_print" required>
                    </div>
                </div>
                
                <button type="submit" name="submit" class="btn-submit">
                    <i class="fas fa-arrow-right"></i>
                    Verify & Continue
                </button>
                
                <!-- <div class="info-box">
                    <i class="fas fa-shield-alt"></i>
                    <p>
                        <strong>Secure Verification Process</strong><br>
                        Your biometric data is encrypted and verified against government records. 
                        All information is protected with military-grade encryption.
                    </p>
                </div>
                
                <div class="footer-text">
                    <i class="fas fa-lock"></i> Secure Registration | <i class="fas fa-clock"></i> Identity Verified
                </div> -->
            </form>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
        });
        
        // Form submission loading effect
        document.getElementById('doctorRegForm')?.addEventListener('submit', function(e) {
            const submitBtn = document.querySelector('.btn-submit');
            if(submitBtn && !submitBtn.classList.contains('loading')) {
                submitBtn.classList.add('loading');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying Credentials...';
            }
        });
        
        // Input field animations
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateX(5px)';
                this.style.transition = 'all 0.3s';
            });
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateX(0)';
            });
        });
        
        // Add hover effect to form card
        const formCard = document.querySelector('.form-card');
        if(formCard) {
            formCard.addEventListener('mouseenter', function() {
                this.style.transition = 'all 0.3s';
            });
        }
        
        // Animate doctor icon on load
        setTimeout(() => {
            const doctorIcon = document.querySelector('.doctor-icon');
            if(doctorIcon) {
                doctorIcon.style.animation = 'none';
                setTimeout(() => {
                    doctorIcon.style.animation = 'pulse 2s infinite';
                }, 10);
            }
        }, 500);
    </script>
</body>
</html>