<?php
include 'header.php';

// Fetch statistics
$total_products = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$total_tests = $conn->query("SELECT COUNT(*) as count FROM testing_records")->fetch_assoc()['count'];
$passed_tests = $conn->query("SELECT COUNT(*) as count FROM testing_records WHERE result = 'Pass'")->fetch_assoc()['count'];
$failed_tests = $conn->query("SELECT COUNT(*) as count FROM testing_records WHERE result = 'Fail'")->fetch_assoc()['count'];
?>

<style>
    :root {
        --ke-blue: #004a99;
        --ke-yellow: #ffc20e;
    }

    .card-stats {
        border: none;
        border-radius: 15px;
        transition: transform 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .card-stats:hover {
        transform: translateY(-5px);
    }

    .icon-box {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.5rem;
    }

    /* KE Theme Overrides */
    .bg-ke-blue {
        background-color: var(--ke-blue) !important;
        color: white;
    }

    .bg-ke-yellow {
        background-color: var(--ke-yellow) !important;
        color: #333;
    }

    .table thead th {
        background-color: #f8f9fa;
        color: var(--ke-blue);
        border-top: none;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }
</style>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold text-dark mb-1">MTL Lab Dashboard</h3>
            <p class="text-muted">Welcome back, Administrator. Here's what's happening today.</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-stats shadow-sm bg-ke-blue">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1" style="opacity: 0.8;">Total Products</h6>
                            <h2 class="fw-bold mb-0"><?php echo $total_products; ?></h2>
                        </div>
                        <div class="icon-box bg-white text-primary">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats shadow-sm bg-ke-yellow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Total Tests</h6>
                            <h2 class="fw-bold mb-0"><?php echo $total_tests; ?></h2>
                        </div>
                        <div class="icon-box bg-white text-warning">
                            <i class="fas fa-vial"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1" style="opacity: 0.9;">Passed Tests</h6>
                            <h2 class="fw-bold mb-0"><?php echo $passed_tests; ?></h2>
                        </div>
                        <div class="icon-box bg-white text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats shadow-sm bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1" style="opacity: 0.9;">Failed Tests</h6>
                            <h2 class="fw-bold mb-0"><?php echo $failed_tests; ?></h2>
                        </div>
                        <div class="icon-box bg-white text-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="card-title fw-bold mb-0 text-dark" style="color: var(--ke-blue) !important;">
                        <i class="fas fa-list-ul me-2 text-primary"></i>Recent Products
                    </h5>
                    <a href="products/view_products.php" class="btn btn-sm btn-light border text-primary fw-bold">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="bg-light">
                                    <th class="ps-3" style="width: 80px;">ID</th>
                                    <th>Product Name</th>
                                    <th>Code</th>
                                    <th>Rev.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $recent_products = $conn->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 5");
                                if ($recent_products->num_rows > 0) {
                                    while ($row = $recent_products->fetch_assoc()) {
                                        echo "<tr>
                                                <td class='ps-3 text-muted fw-bold'>#{$row['product_id']}</td>
                                                <td>
                                                    <div class='fw-bold text-dark'>{$row['product_name']}</div>
                                                    <small class='text-muted'>Added on: " . date('d M', strtotime($row['created_at'])) . "</small>
                                                </td>
                                                <td><span class='badge bg-light text-dark border px-2 py-1'>{$row['product_code']}</span></td>
                                                <td><span class='text-primary fw-bold'>{$row['revision']}</span></td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center py-4'>No products found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="card-title fw-bold mb-0 text-dark" style="color: var(--ke-blue) !important;">
                        <i class="fas fa-microscope me-2 text-primary"></i>Recent Test Logs
                    </h5>
                    <a href="testing/view_tests.php" class="btn btn-sm btn-light border text-primary fw-bold">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="bg-light">
                                    <th class="ps-3">Test ID</th>
                                    <th>Product</th>
                                    <th>Result</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $recent_tests = $conn->query("SELECT * FROM testing_records ORDER BY created_at DESC LIMIT 5");
                                if ($recent_tests->num_rows > 0) {
                                    while ($row = $recent_tests->fetch_assoc()) {
                                        // Result Styling
                                        $result_badge = ($row['result'] == 'Pass') ? 'bg-success' : 'bg-danger';

                                        // Jugar: Status logic
                                        $status_val = (!empty($row['status'])) ? $row['status'] : 'Completed';
                                        $status_color = ($status_val == 'Pending') ? 'text-warning' : 'text-success';
                                        $status_icon = ($status_val == 'Pending') ? 'fa-clock' : 'fa-check-double';

                                        // --- DATE JUGAR START ---
                                        // Hum date ko "15 Mar, 02:30 PM" format mein dikhayenge
                                        $test_date = date('d M, h:i A', strtotime($row['created_at']));
                                        // --- DATE JUGAR END ---

                                        echo "<tr>
                    <td class='ps-3 text-muted'>T-{$row['test_id']}</td>
                    <td>
                        <div class='fw-bold text-dark'>Prod #{$row['product_id']}</div>
                        <small class='text-muted'><i class='far fa-calendar-alt me-1'></i>{$test_date}</small>
                    </td>
                    <td><span class='badge {$result_badge} shadow-sm px-3'>{$row['result']}</span></td>
                    <td>
                        <span class='{$status_color} fw-bold' style='font-size: 0.85rem;'>
                            <i class='fas {$status_icon} me-1'></i> {$status_val}
                        </span>
                    </td>
                  </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center py-4'>No tests found</td></tr>";
                                }
                                ?>
                            </tbody>


                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <?php include 'footer.php'; ?>