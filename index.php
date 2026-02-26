<?php
session_start();
require_once "koneksi.php";

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $data  = mysqli_fetch_assoc($query);

    if ($data && password_verify($password, $data['password'])) {

        $_SESSION['id'] = $data['id'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = $data['role'];

        header("Location: dashboard.php");
        exit;

    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SARPRAS - MAKN Ende</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1B5E20 0%, #2E7D32 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Background pattern with MAKN Ende identity */
        body::before {
            content: "MAKN ENDE";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            font-size: 10vw;
            font-weight: 900;
            color: rgba(255, 255, 255, 0.05);
            white-space: nowrap;
            pointer-events: none;
            z-index: 0;
            text-transform: uppercase;
            letter-spacing: 15px;
        }

        /* Islamic pattern decoration */
        body::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(45deg, 
                rgba(255, 215, 0, 0.15) 25%, 
                transparent 25%,
                transparent 50%,
                rgba(255, 215, 0, 0.15) 50%,
                rgba(255, 215, 0, 0.15) 75%,
                transparent 75%,
                transparent);
            background-size: 40px 40px;
            z-index: 0;
        }

        .container {
            width: 100%;
            max-width: 380px;
            padding: 15px;
            position: relative;
            z-index: 1;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 25px 25px 30px;
            box-shadow: 0 15px 40px rgba(0, 40, 0, 0.3);
            border: 1px solid rgba(46, 125, 50, 0.2);
            position: relative;
            overflow: hidden;
        }

        /* Islamic geometric pattern border */
        .login-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, 
                #2E7D32, 
                #FFD700, 
                #1B5E20, 
                #FFD700, 
                #2E7D32);
            background-size: 200% 100%;
            animation: gradientMove 3s linear infinite;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 0; }
            100% { background-position: 200% 0; }
        }

        /* Header section with MAKN Ende identity */
        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }

        .school-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #2E7D32, #1B5E20);
            border-radius: 50%;
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 30px;
            box-shadow: 0 8px 15px rgba(27, 94, 32, 0.25);
            border: 2px solid #FFD700;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 8px 15px rgba(27, 94, 32, 0.25);
            }
            50% {
                transform: scale(1.03);
                box-shadow: 0 12px 20px rgba(27, 94, 32, 0.35);
            }
        }

        .school-icon i {
            font-size: 35px;
        }

        .title {
            font-size: 24px;
            font-weight: 700;
            color: #1B5E20;
            margin-bottom: 3px;
            letter-spacing: 1.5px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .subtitle {
            font-size: 13px;
            color: #2E7D32;
            letter-spacing: 2px;
            text-transform: uppercase;
            position: relative;
            display: inline-block;
            padding: 0 20px;
            font-weight: 600;
        }

        .subtitle::before,
        .subtitle::after {
            content: "⚘";
            color: #FFD700;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
        }

        .subtitle::before {
            left: 0;
        }

        .subtitle::after {
            right: 0;
        }

        .region {
            font-size: 11px;
            color: #388E3C;
            margin-top: 5px;
            font-style: italic;
            background: rgba(255, 215, 0, 0.1);
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
        }

        .region i {
            color: #FFD700;
            margin-right: 3px;
            font-size: 10px;
        }

        .form-group {
            margin-bottom: 15px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #1B5E20;
            font-weight: 500;
            font-size: 12px;
            letter-spacing: 0.3px;
        }

        .form-group label i {
            margin-right: 5px;
            color: #2E7D32;
            font-size: 11px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #81C784;
            transition: color 0.3s;
            font-size: 14px;
        }

        .input-wrapper input {
            width: 100%;
            padding: 10px 10px 10px 35px;
            border: 1.5px solid #C8E6C9;
            border-radius: 8px;
            font-size: 13px;
            transition: all 0.3s;
            background: white;
        }

        .input-wrapper input:focus {
            border-color: #2E7D32;
            outline: none;
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
        }

        .input-wrapper input:focus + i {
            color: #2E7D32;
        }

        .input-wrapper input:hover {
            border-color: #81C784;
        }

        .btn-login {
            width: 100%;
            padding: 11px;
            background: linear-gradient(135deg, #2E7D32, #1B5E20);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            box-shadow: 0 3px 10px rgba(27, 94, 32, 0.3);
            margin-top: 5px;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(27, 94, 32, 0.4);
            background: linear-gradient(135deg, #1B5E20, #2E7D32);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login i {
            margin-right: 5px;
            font-size: 13px;
        }

        .error-message {
            background: linear-gradient(135deg, #C62828, #B71C1C);
            color: white;
            padding: 8px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 12px;
            text-align: center;
            animation: shake 0.5s ease-in-out;
            box-shadow: 0 3px 10px rgba(198, 40, 40, 0.2);
            position: relative;
            padding-left: 35px;
        }

        .error-message i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-3px); }
            20%, 40%, 60%, 80% { transform: translateX(3px); }
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #558B2F;
            border-top: 1px dashed #C8E6C9;
            padding-top: 15px;
        }

        .footer span {
            color: #FFD700;
            font-weight: bold;
        }

        .footer i {
            color: #C62828;
            animation: heartbeat 1.5s ease-in-out infinite;
            font-size: 9px;
        }

        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* Pattern dots */
        .pattern-dots {
            position: absolute;
            bottom: 15px;
            right: 15px;
            width: 70px;
            height: 70px;
            background-image: radial-gradient(#FFD700 1.5px, transparent 1.5px);
            background-size: 15px 15px;
            opacity: 0.15;
            pointer-events: none;
        }

        /* School pattern */
        .pattern-school {
            position: absolute;
            top: 15px;
            left: 15px;
            font-size: 30px;
            color: rgba(255, 215, 0, 0.1);
            transform: rotate(-15deg);
            pointer-events: none;
        }

        .pattern-school i {
            filter: drop-shadow(0 0 3px rgba(255, 215, 0, 0.2));
        }

        /* School motto */
        .school-motto {
            font-size: 10px;
            color: #558B2F;
            margin-top: 8px;
            font-style: italic;
            background: #E8F5E9;
            padding: 5px 12px;
            border-radius: 20px;
            display: inline-block;
            border: 1px solid #FFD700;
        }

        .school-motto i {
            color: #FFD700;
            margin: 0 3px;
            font-size: 8px;
        }

        /* Responsive design */
        @media (max-width: 480px) {
            .container {
                padding: 8px;
            }
            
            .login-card {
                padding: 20px 18px 22px;
            }
            
            .title {
                font-size: 22px;
            }
            
            .school-icon {
                width: 60px;
                height: 60px;
                font-size: 25px;
            }

            .school-icon i {
                font-size: 28px;
            }
        }
    </style>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- School pattern decoration -->
    <div class="pattern-school">
        <i class="fas fa-school"></i>
    </div>

    <div class="container">
        <div class="login-card">
            <div class="header">
                <div class="school-icon">
                    <i class="fas fa-school"></i>
                </div>
                <h1 class="title">SARPRAS</h1>
                <div class="subtitle">MAKN ENDE</div>
                <div class="region">
                    <i class="fas fa-map-marker-alt"></i> Flores, NTT
                </div>
                <div class="school-motto">
                    <i class="fas fa-quote-right"></i>
                    Berilmu, Beramal, Berakhlak
                    <i class="fas fa-quote-left"></i>
                </div>
            </div>

            <?php if(isset($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user-graduate"></i> Username
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-user-circle"></i>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               placeholder="Masukkan username" 
                               required 
                               autocomplete="off">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-key"></i>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               placeholder="Masukkan password" 
                               required>
                    </div>
                </div>

                <button type="submit" name="login" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>

            <div class="footer">
                <p>
                    <i class="fas fa-heart"></i> 
                    <span>MAKN Ende</span> | SARPRAS
                </p>
                <p style="margin-top: 3px; font-size: 8px; color: #81C784;">
                    <i class="fas fa-school"></i> MAK Negeri Ende
                </p>
                <p style="margin-top: 3px; font-size: 8px;">
                    © 2024
                </p>
            </div>
        </div>
    </div>

    <!-- Pattern dots decoration -->
    <div class="pattern-dots"></div>

    <!-- Add smooth scroll behavior -->
    <script>
        // Optional: Add smooth scroll and form enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.input-wrapper input');
            
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.querySelector('i').style.color = '#2E7D32';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.querySelector('i').style.color = '#81C784';
                });
            });

            // Add floating effect to school icon
            const schoolIcon = document.querySelector('.school-icon i');
            if (schoolIcon) {
                setInterval(() => {
                    schoolIcon.style.transform = 'scale(1.05)';
                    setTimeout(() => {
                        schoolIcon.style.transform = 'scale(1)';
                    }, 200);
                }, 3000);
            }
        });
    </script>
</body>
</html>