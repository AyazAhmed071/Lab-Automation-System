<?php
require_once __DIR__ . '/config/db.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_name = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'Admin';
$admin_username = isset($_SESSION['admin_username']) ? $_SESSION['admin_username'] : 'admin';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Automation System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="wrapper d-flex align-items-stretch">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header text-center">
                <h3><i class="fas fa-microscope me-2"></i>Lab Automation</h3>
            </div>
            <ul class="list-unstyled components mb-5">
                <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                </li>
                <li class="<?php echo (strpos($_SERVER['PHP_SELF'], 'products/') !== false) ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>products/view_products.php"><i class="fas fa-box"></i> Products</a>
                </li>
                <li class="<?php echo (strpos($_SERVER['PHP_SELF'], 'testing/') !== false) ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>testing/view_tests.php"><i class="fas fa-flask"></i> Testing Records</a>
                </li>
                <li class="<?php echo (strpos($_SERVER['PHP_SELF'], 'search/') !== false) ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>search.php"><i class="fas fa-search"></i> Advanced Search</a>
                </li>
                <li>
                    <a href="search.php" class="nav-link text-white">
                        <i class="fas fa-file-pdf me-2"></i> Reports
                    </a>
                </li>
                <!-- <li class="<?php echo (strpos($_SERVER['PHP_SELF'], 'reports.php') !== false) ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>reports.php"><i class="fas fa-chart-line"></i> Reports</a>
                </li> -->
                <li>
                    <a href="<?php echo BASE_URL; ?>logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>

            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-primary">
                        <i class="fas fa-align-left"></i>
                    </button>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i> <?php echo $admin_name; ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="container-fluid">