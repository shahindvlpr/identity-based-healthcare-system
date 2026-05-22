<?php
    include ('dbconn.php');
    include('session.php');
    
    // FIXED: Null-safe GET parameter
    $id = isset($_GET['id']) ? $_GET['id'] : '0';
    
    // FIXED: Null-safe queries
    $sender_query = mysqli_query($conn,"SELECT * from person where person.nid ='$id'");
    $senderinfo_row = ($sender_query && mysqli_num_rows($sender_query) > 0) ? mysqli_fetch_array($sender_query) : null;
    
    $user_query = mysqli_query($conn,"SELECT * from person where person.nid ='$user_id'");
    $userinfo_row = ($user_query && mysqli_num_rows($user_query) > 0) ? mysqli_fetch_array($user_query) : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Messages - IBHS">
    <meta name="author" content="">

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/message.css">
    
    <title>Messages | IBHS</title>

    <link href="css/blog-home.css" rel="stylesheet">
    <link rel="stylesheet" href="css/net.css">

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
            --radius: 16px;
            --transition: all 0.3s ease;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #e8ecf1;
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 70px;
        }

        /* ============ NAVIGATION ============ */
        .navbar {
            background: #ffffff !important;
            padding: 10px 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
            height: 68px;
        }

        .logo_img img { height: 42px; }

        .navbar-nav .nav-link {
            color: #555 !important;
            font-size: 13px;
            font-weight: 500;
            margin: 0 2px;
            padding: 8px 14px !important;
            border-radius: 8px;
            transition: var(--transition);
            white-space: nowrap;
        }

        .navbar-nav .nav-link i { margin-right: 4px; font-size: 12px; }
        .navbar-nav .nav-link:hover { background: #f0fdf4; color: var(--primary) !important; }

        .nav-user-badge {
            background: var(--primary-light);
            border-radius: 8px;
            padding: 5px 12px !important;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-user-badge .divname {
            font-size: 11px !important;
            font-weight: 600 !important;
            color: var(--text-dark) !important;
            line-height: 1.2;
        }

        .nav-user-badge .divid {
            font-size: 8px !important;
            font-weight: 500 !important;
            color: var(--text-light) !important;
        }

        .btn-logout-nav {
            background: #fff !important;
            color: var(--danger) !important;
            border: 2px solid var(--danger) !important;
            padding: 7px 16px !important;
            border-radius: 25px !important;
            font-weight: 600 !important;
            font-size: 12px !important;
        }

        .btn-logout-nav:hover { background: var(--danger) !important; color: #fff !important; }

        /* ============ CHAT FRAME ============ */
        #frame {
            width: 95%;
            max-width: 1050px;
            height: 85vh;
            max-height: 700px;
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            display: flex;
            margin-bottom: 20px;
        }

        #sidepanel {
            width: 320px;
            min-width: 280px;
            height: 100%;
            background: #1e2a3a;
            color: #e0e0e0;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        #profile {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        #profile .wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        #profile .wrap p {
            font-weight: 600;
            font-size: 0.95rem;
            color: #fff;
            margin: 0;
        }

        #contacts {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        #contacts::-webkit-scrollbar { width: 4px; }
        #contacts::-webkit-scrollbar-track { background: transparent; }
        #contacts::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 10px; }

        #contacts ul { list-style: none; padding: 0; margin: 0; }

        .contact { transition: var(--transition); }

        .contact a { text-decoration: none !important; display: block; }

        .contact .wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            transition: var(--transition);
        }

        .contact:hover .wrap { background: #263545; }

        .contact-status {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #2ecc71;
            flex-shrink: 0;
        }

        .contact img {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.2);
            flex-shrink: 0;
        }

        .contact .meta .name {
            font-weight: 600;
            color: #e0e0e0;
            font-size: 0.9rem;
            margin: 0;
        }

        .contact:hover .meta .name { color: #fff; }

        /* ============ CHAT AREA ============ */
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
            background: #f5f6fa;
            min-width: 0;
        }

        .contact-profile {
            padding: 18px 25px;
            background: #fff;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .contact-profile p {
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-dark);
            margin: 0;
        }

        .messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .messages::-webkit-scrollbar { width: 5px; }
        .messages::-webkit-scrollbar-track { background: transparent; }
        .messages::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }

        .message-input {
            padding: 15px 20px;
            background: #fff;
            border-top: 1px solid #eee;
            flex-shrink: 0;
        }

        .message-input .wrap {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .message-input .input-field {
            flex: 1;
            border: 2px solid #e8e8e8;
            border-radius: 25px;
            padding: 12px 20px;
            font-size: 0.88rem;
            font-family: 'Poppins', sans-serif;
            outline: none;
            transition: var(--transition);
        }

        .message-input .input-field:focus { border-color: var(--primary); }

        .message-input .submit {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: var(--primary);
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 18px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .message-input .submit:hover { background: var(--primary-dark); }

        .empty-chat {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #bbb;
        }

        .empty-chat i { font-size: 4rem; display: block; margin-bottom: 15px; }

        @media (max-width: 768px) {
            #frame {
                width: 100%;
                height: calc(100vh - 70px);
                border-radius: 0;
                max-height: none;
            }
            #sidepanel { width: 70px; min-width: 70px; }
            .contact .meta, #profile .wrap p { display: none; }
            .contact .wrap { justify-content: center; padding: 14px 10px; }
        }
    </style>
</head>

<body>

    <?php
    $my_query = mysqli_query($conn,"SELECT * from person inner join gender on person.gender = gender.gender_id where person.nid ='$user_id'");
    $my = ($my_query) ? mysqli_fetch_array($my_query) : null;
    ?>

    <!-- ============ NAVIGATION ============ -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <div class="logo_img">
                <a href="index.html"><img src="img/bg_logo1.png" alt="IBHS Logo"></a>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto align-items-center">
                    <?php if($user_type=='Doctor'): ?>
                    <li class="nav-item"><a class="nav-link" href="patient.php"><i class="fas fa-users"></i> Patients</a></li>
                    <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="doctor.php"><i class="fas fa-user-md"></i> Doctors</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="hospital.php"><i class="fas fa-hospital"></i> Hospitals</a></li>
                    <?php if($user_type == "Patient"): ?>
                    <li class="nav-item"><a class="nav-link" href="myprescription.php"><i class="fas fa-prescription"></i> Prescriptions</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="message.php?id=0"><i class="fas fa-envelope"></i> Messages</a></li>
                    <li class="nav-item ml-2">
                        <?php if($user_type=='Doctor'): ?>
                        <a class="nav-link nav-user-badge" href="doctorprofile.php?id=<?php echo $user_id; ?>">
                            <i class="fas fa-user-circle" style="font-size:15px;color:var(--primary);"></i>
                            <div><div class="divname"><?php echo $my['name'] ?? 'User'; ?></div><div class="divid"><?php echo $my['nid'] ?? ''; ?></div></div>
                        </a>
                        <?php else: ?>
                        <a class="nav-link nav-user-badge" href="myprofile.php?id=<?php echo $user_id; ?>">
                            <i class="fas fa-user-circle" style="font-size:15px;color:var(--primary);"></i>
                            <div><div class="divname"><?php echo $my['name'] ?? 'User'; ?></div><div class="divid"><?php echo $my['nid'] ?? ''; ?></div></div>
                        </a>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item ml-2">
                        <a class="nav-link btn-logout-nav" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ============ CHAT FRAME ============ -->
    <div id="frame">
        
        <!-- Sidebar -->
        <div id="sidepanel">
            <div id="profile">
                <div class="wrap">
                    <i class="fas fa-user-circle" style="font-size:24px;color:#2ecc71;"></i>
                    <p><?php echo $userinfo_row['name'] ?? 'User'; ?></p>
                </div>
            </div>

            <div id="contacts">
                <?php if($user_type == "Patient"): ?>
                <ul>
                    <?php
                        $query = mysqli_query($conn,"SELECT d_nid, MAX(datetime) as datetime FROM message WHERE p_nid ='$user_id' GROUP BY d_nid ORDER BY datetime DESC");
                        if($query && mysqli_num_rows($query) > 0):
                            while($patient_row = mysqli_fetch_array($query)):
                                $d_nid = $patient_row['d_nid'];
                                $query2 = mysqli_query($conn,"SELECT * FROM person WHERE nid ='$d_nid'");
                                $dnid_row = ($query2 && mysqli_num_rows($query2) > 0) ? mysqli_fetch_array($query2) : null;
                                if(!$dnid_row) continue;
                    ?>
                    <li class="contact">
                        <a href="message.php?id=<?php echo $d_nid; ?>">
                            <div class="wrap">
                                <span class="contact-status"></span>
                                <img src="img/<?php echo $dnid_row['image'] ?? 'doctor1.jpg'; ?>" alt="" onerror="this.src='img/doctor1.jpg'">
                                <div class="meta">
                                    <p class="name"><?php echo $dnid_row['name'] ?? 'Unknown'; ?></p>
                                </div>
                            </div>
                        </a>
                    </li>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <li style="padding:30px;text-align:center;color:#888;">
                        <i class="fas fa-inbox" style="font-size:2rem;display:block;margin-bottom:10px;"></i>
                        <small>No conversations yet</small>
                    </li>
                    <?php endif; ?>
                </ul>
                <?php else: ?>
                <ul>
                    <?php
                        $query = mysqli_query($conn,"SELECT p_nid, MAX(datetime) as datetime FROM message WHERE d_nid ='$user_id' GROUP BY p_nid ORDER BY datetime DESC");
                        if($query && mysqli_num_rows($query) > 0):
                            while($doctor_row = mysqli_fetch_array($query)):
                                $p_nid = $doctor_row['p_nid'];
                                $query2 = mysqli_query($conn,"SELECT * FROM person WHERE nid ='$p_nid'");
                                $pnid_row = ($query2 && mysqli_num_rows($query2) > 0) ? mysqli_fetch_array($query2) : null;
                                if(!$pnid_row) continue;
                    ?>
                    <li class="contact">
                        <a href="message.php?id=<?php echo $p_nid; ?>">
                            <div class="wrap">
                                <span class="contact-status"></span>
                                <img src="img/<?php echo $pnid_row['image'] ?? 'patient1.png'; ?>" alt="" onerror="this.src='img/patient1.png'">
                                <div class="meta">
                                    <p class="name"><?php echo $pnid_row['name'] ?? 'Unknown'; ?></p>
                                </div>
                            </div>
                        </a>
                    </li>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <li style="padding:30px;text-align:center;color:#888;">
                        <i class="fas fa-inbox" style="font-size:2rem;display:block;margin-bottom:10px;"></i>
                        <small>No conversations yet</small>
                    </li>
                    <?php endif; ?>
                </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="content">
            <?php if($id != '0' && $senderinfo_row): ?>
            <div class="contact-profile">
                <i class="fas fa-user-circle" style="font-size:20px;color:var(--primary);"></i>
                <p><?php echo $senderinfo_row['name'] ?? 'Unknown'; ?></p>
            </div>
            <?php endif; ?>

            <div class="messages" id="chatMessages">
                <?php if($id == '0' || !$senderinfo_row): ?>
                <div class="empty-chat">
                    <div>
                        <i class="fas fa-comments"></i>
                        <p>Select a conversation to start messaging</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="message-input">
                <?php if($id != '0' && $senderinfo_row): ?>
                <div class="wrap">
                    <form action="#" class="typing-area" style="display:flex;width:100%;gap:10px;" onsubmit="return false;">
                        <input type="hidden" class="usertype" name="usertype" value="<?php echo $user_type; ?>">
                        <input type="hidden" class="incoming_id" name="incoming_id" value="<?php echo $user_id; ?>">
                        <input type="hidden" class="outgoing_id" name="outgoing_id" value="<?php echo $id; ?>">
                        <input type="text" name="message" class="input-field" placeholder="Type a message..." autocomplete="off">
                        <button type="submit" class="submit"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ============ SCRIPTS ============ -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js"></script>
    <script src="js/chat.js"></script>
</body>
</html>