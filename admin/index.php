<?php
session_start();
include_once('../dbconn.php');

$error = "";

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password']) || $password === $row['password']) {
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            $_SESSION['admin_id'] = $row['id'] ?? $username;
            $_SESSION['login_time'] = time();
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Incorrect password. Please try again.";
        }
    } else {
        $error = "Admin user not found. Invalid credentials.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | IBHS - Identity Based Healthcare System</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            background: #0f0c29;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Gradient Background */
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(125deg, #0f0c29, #302b63, #24243e);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            z-index: -2;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Animated Grid Pattern */
        .grid-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: -1;
            animation: gridMove 20s linear infinite;
        }

        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        /* Floating Orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            z-index: -1;
            animation: floatOrb 20s infinite ease-in-out;
        }

        .orb-1 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, #667eea, #764ba2);
            top: -150px;
            left: -150px;
        }

        .orb-2 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, #f093fb, #f5576c);
            bottom: -200px;
            right: -200px;
            animation-delay: -5s;
        }

        .orb-3 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, #4facfe, #00f2fe);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: -10s;
        }

        @keyframes floatOrb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.1); }
        }

        /* Main Container */
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        /* Glass Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(15px);
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            max-width: 480px;
            width: 100%;
        }

        .glass-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.6);
            border-color: rgba(255, 255, 255, 0.25);
        }

        /* Card Header */
        .card-header-glass {
            padding: 40px 35px 20px;
            text-align: center;
            position: relative;
        }

        .logo-wrapper {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            position: relative;
            animation: logoGlow 3s ease-in-out infinite;
        }

        @keyframes logoGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(102, 126, 234, 0.5); }
            50% { box-shadow: 0 0 40px rgba(102, 126, 234, 0.8); }
        }

        .logo-wrapper i {
            font-size: 45px;
            color: white;
            animation: iconSpin 10s linear infinite;
        }

        @keyframes iconSpin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .typing-title {
            font-size: 28px;
            font-weight: 800;
            color: white;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .typing-subtitle {
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
            letter-spacing: 1px;
        }

        /* Typing Animation */
        .typed-cursor {
            font-size: 28px;
            color: #667eea;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }

        /* Card Body */
        .card-body-glass {
            padding: 0 35px 40px;
        }

        /* Form Groups */
        .input-group-glass {
            margin-bottom: 25px;
            position: relative;
        }

        .input-group-glass label {
            display: block;
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .input-group-glass label i {
            margin-right: 8px;
            font-size: 12px;
        }

        .input-field {
            position: relative;
        }

        .input-field input {
            width: 100%;
            padding: 14px 16px 14px 45px;
            background: rgba(255, 255, 255, 0.08);
            border: 1.5px solid rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            color: white;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .input-field input:focus {
            outline: none;
            border-color: #667eea;
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        }

        .input-field input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.5);
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .input-field input:focus + .input-icon {
            color: #667eea;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        /* Login Button */
        .btn-login-glass {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 16px;
            color: white;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-top: 10px;
        }

        .btn-login-glass::before {
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

        .btn-login-glass:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-login-glass:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.5);
        }

        .btn-login-glass i {
            margin-right: 8px;
        }

        /* Alert */
        .alert-glass {
            background: rgba(220, 38, 38, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(220, 38, 38, 0.3);
            border-radius: 16px;
            padding: 14px 18px;
            margin-bottom: 25px;
            color: #fca5a5;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .alert-glass i {
            font-size: 16px;
        }

        /* Footer */
        .footer-glass {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .footer-glass a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 12px;
            transition: color 0.3s ease;
            margin: 0 10px;
        }

        .footer-glass a:hover {
            color: #667eea;
        }

        .footer-glass .separator {
            color: rgba(255, 255, 255, 0.3);
            font-size: 12px;
        }

        /* Loading State */
        .btn-loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 576px) {
            .card-header-glass {
                padding: 30px 25px 15px;
            }
            .card-body-glass {
                padding: 0 25px 30px;
            }
            .logo-wrapper {
                width: 70px;
                height: 70px;
                border-radius: 22px;
            }
            .logo-wrapper i {
                font-size: 35px;
            }
            .typing-title {
                font-size: 24px;
            }
        }

        /* Stats Badge */
        .stats-badge {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 25px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            backdrop-filter: blur(5px);
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 18px;
            font-weight: 800;
            color: white;
        }

        .stat-label {
            font-size: 9px;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>

<!-- Animated Background -->
<div class="animated-bg"></div>
<div class="grid-pattern"></div>

<!-- Floating Orbs -->
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<div class="login-wrapper">
    <div class="glass-card" data-aos="zoom-in" data-aos-duration="800">
        <div class="card-header-glass">
            <div class="logo-wrapper">
                <i class="fas fa-shield-heart"></i>
            </div>
            <h1 class="typing-title">
                <span id="typed-text"></span><span class="typed-cursor">|</span>
            </h1>
            <p class="typing-subtitle">SECURE ADMIN ACCESS PORTAL</p>
        </div>
        
        <div class="card-body-glass">
            
            <!-- Stats Badge -->
            <div class="stats-badge" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-item">
                    <div class="stat-number" id="systemStatus">--</div>
                    <div class="stat-label">System Status</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="activeAdmins">--</div>
                    <div class="stat-label">Active Admins</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="totalUsers">--</div>
                    <div class="stat-label">Total Users</div>
                </div>
            </div>
            
            <!-- Error Message -->
            <?php if ($error): ?>
                <div class="alert-glass" data-aos="fade-down">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div><?= htmlspecialchars($error) ?></div>
                </div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form method="POST" action="" id="loginForm">
                <div class="input-group-glass" data-aos="fade-right" data-aos-delay="200">
                    <label>
                        <i class="fas fa-user"></i> USERNAME
                    </label>
                    <div class="input-field">
                        <input type="text" name="username" id="username" 
                               placeholder="Enter your username" required autofocus>
                        <i class="fas fa-user-circle input-icon"></i>
                    </div>
                </div>
                
                <div class="input-group-glass" data-aos="fade-right" data-aos-delay="250">
                    <label>
                        <i class="fas fa-lock"></i> PASSWORD
                    </label>
                    <div class="input-field">
                        <input type="password" name="password" id="password" 
                               placeholder="Enter your password" required>
                        <i class="fas fa-key input-icon"></i>
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="btn-login-glass" id="loginBtn" data-aos="fade-up" data-aos-delay="300">
                    <i class="fas fa-arrow-right-to-bracket"></i> ACCESS DASHBOARD
                </button>
            </form>
            
            <!-- Footer -->
            <div class="footer-glass" data-aos="fade-up" data-aos-delay="350">
                <a href="../index.html">
                    <i class="fas fa-home"></i> Homepage
                </a>
                <span class="separator">|</span>
                <a href="#">
                    <i class="fas fa-question-circle"></i> Support
                </a>
                <span class="separator">|</span>
                <a href="#">
                    <i class="fas fa-shield-alt"></i> Privacy
                </a>
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
    
    // Typing Animation
    const words = ['Welcome Back!', 'Admin Access', 'IBHS Portal'];
    let wordIndex = 0;
    let charIndex = 0;
    let isDeleting = false;
    const typedTextElement = document.getElementById('typed-text');
    
    function typeEffect() {
        const currentWord = words[wordIndex];
        
        if (isDeleting) {
            typedTextElement.textContent = currentWord.substring(0, charIndex - 1);
            charIndex--;
        } else {
            typedTextElement.textContent = currentWord.substring(0, charIndex + 1);
            charIndex++;
        }
        
        if (!isDeleting && charIndex === currentWord.length) {
            isDeleting = true;
            setTimeout(typeEffect, 2000);
        } else if (isDeleting && charIndex === 0) {
            isDeleting = false;
            wordIndex = (wordIndex + 1) % words.length;
            setTimeout(typeEffect, 500);
        } else {
            setTimeout(typeEffect, isDeleting ? 100 : 150);
        }
    }
    
    typeEffect();
    
    // Fetch dynamic stats
    function fetchStats() {
        $.ajax({
            url: 'ajax_login_stats.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#systemStatus').text(data.status || 'Active');
                $('#activeAdmins').text(data.active_admins || '3');
                $('#totalUsers').text(data.total_users || '1.2k');
            },
            error: function() {
                $('#systemStatus').text('Online');
                $('#activeAdmins').text('3');
                $('#totalUsers').text('1.2k');
            }
        });
    }
    
    // Password visibility toggle
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
    
    // Form submission animation
    $('#loginForm').on('submit', function() {
        const btn = $('#loginBtn');
        btn.addClass('btn-loading');
        btn.html('<i class="fas fa-spinner"></i> AUTHENTICATING...');
    });
    
    // Input validation effects
    $('#username, #password').on('input', function() {
        const field = $(this);
        if (field.val().length > 0) {
            field.css({
                'border-color': '#48bb78',
                'background': 'rgba(255,255,255,0.12)'
            });
        } else {
            field.css({
                'border-color': 'rgba(255,255,255,0.15)',
                'background': 'rgba(255,255,255,0.08)'
            });
        }
    });
    
    // Add ripple effect to button
    $('.btn-login-glass').on('click', function(e) {
        const x = e.clientX - e.target.offsetLeft;
        const y = e.clientY - e.target.offsetTop;
        
        const ripple = document.createElement('span');
        ripple.style.position = 'absolute';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.style.width = '0';
        ripple.style.height = '0';
        ripple.style.borderRadius = '50%';
        ripple.style.background = 'rgba(255,255,255,0.5)';
        ripple.style.transform = 'translate(-50%, -50%)';
        ripple.style.transition = 'width 0.6s, height 0.6s';
        
        $(this).append(ripple);
        
        setTimeout(() => {
            ripple.style.width = '300px';
            ripple.style.height = '300px';
        }, 10);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    });
    
    // Initialize stats
    fetchStats();
    setInterval(fetchStats, 30000);
    
    // Add animated border effect on card
    setInterval(() => {
        $('.glass-card').css('border-color', 'rgba(255,255,255,0.2)');
        setTimeout(() => {
            $('.glass-card').css('border-color', 'rgba(255,255,255,0.15)');
        }, 200);
    }, 3000);
</script>

</body>
</html>