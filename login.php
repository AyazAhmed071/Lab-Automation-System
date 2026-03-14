<?php
session_start();
require_once 'config/db.php';
// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password  FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $admin = $result->fetch_assoc();
            if ($password == '123456') {
                // if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Admin not found.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MTL Login | K-Electric</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            /* Aapki original HD picture jo folder mein hai */
            background-image: url('assets/images/background.png') !important;
            background-size: cover !important;
            background-position: center !important;
            background-attachment: fixed !important;
            background-repeat: no-repeat !important;

            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }


        /* Glassmorphism Effect - Card styling */
        .login-card {
            background: rgba(255, 255, 255, 0.1) !important;
            /* Transparent white */
            backdrop-filter: blur(15px) !important;
            /* Image piche se blur nazar aayegi */
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 20px !important;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5) !important;
            width: 100%;
            max-width: 400px;
            text-align: center;
            color: white !important;
        }

        .login-logo {
            display: block;
            margin: 0 auto 20px auto;
            height: 120px;
            /* Width ki bajaye Height fix karen taake form na faile */
            width: auto;
            /* Width khud ba khud adjust hogi */
            object-fit: contain;

        }

        .form-label {
            font-weight: 500;
            color: #FFD100 !important;
            /* Labels ko KE Yellow rang diya hai */
            text-align: left;
            display: block;
        }

        /* Input fields matching the background */
        .form-control {
            background: rgba(255, 255, 255, 0.15) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            color: white !important;
            border-radius: 10px !important;
            padding: 12px;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.2) !important;
            border-color: #FFD100 !important;
            box-shadow: 0 0 8px rgba(255, 209, 0, 0.4) !important;
            color: white !important;
        }

        /* Placeholder color light white */
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        /* KE Yellow Login Button */
        .btn-ke {
            background-color: white !important;
            /* Original KE Yellow */
            color: #003057 !important;
            /* Original KE Blue */
            font-weight: 700 !important;
            border: none !important;
            padding: 12px !important;
            border-radius: 10px !important;
            width: 100%;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s ease;
        }

        .btn-ke:hover {
            background-color: #e6bc00 !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 209, 0, 0.3);
        }

        .login-footer {
            margin-top: 20px;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
        }
    </style>
</head>

<body>


    <div class="login-card">
        <img src="assets/images/logo.png" alt="K-Electric Logo" class="login-logo">

        <form action="" method="POST">
            <div class="mb-3 text-start">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-white" style="border: 1px solid rgba(255,255,255,0.3); border-radius: 10px 0 0 10px;">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="text" name="username" id="username" class="form-control border-start-0" placeholder="Enter username" required style="border-radius: 0 10px 10px 0 !important;">
                </div>
            </div>

            <div class="mb-4 text-start">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-white" style="border: 1px solid rgba(255,255,255,0.3); border-radius: 10px 0 0 10px;">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" id="password" class="form-control border-start-0" placeholder="Enter password" required style="border-radius: 0 10px 10px 0 !important;">
                </div>
            </div>

            <button type="submit" name="login" class="btn-ke">
                <i class="fas fa-sign-in-alt me-2"></i> LOG IN
            </button>
        </form>

        <div class="login-footer">
            <p>&copy; 2026 K-Electric MTL Unit <br> All Rights Reserved</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>