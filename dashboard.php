<?php
include 'header.php';

// Fetch statistics
$total_products = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$total_tests = $conn->query("SELECT COUNT(*) as count FROM testing_records")->fetch_assoc()['count'];
$passed_tests = $conn->query("SELECT COUNT(*) as count FROM testing_records WHERE result = 'Pass'")->fetch_assoc()['count'];
$failed_tests = $conn->query("SELECT COUNT(*) as count FROM testing_records WHERE result = 'Fail'")->fetch_assoc()['count'];
?>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card card-stats bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-0">Total Products</h6>
                        <h2 class="display-4 fw-bold mb-0"><?php echo $total_products; ?></h2>
                    </div>
                    <div class="icon-box bg-white text-primary">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-0">Total Tests</h6>
                        <h2 class="display-4 fw-bold mb-0"><?php echo $total_tests; ?></h2>
                    </div>
                    <div class="icon-box bg-white text-info">
                        <i class="fas fa-vial"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-0">Passed Tests</h6>
                        <h2 class="display-4 fw-bold mb-0"><?php echo $passed_tests; ?></h2>
                    </div>
                    <div class="icon-box bg-white text-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stats bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-0">Failed Tests</h6>
                        <h2 class="display-4 fw-bold mb-0"><?php echo $failed_tests; ?></h2>
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
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Recent Products</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Revision</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $recent_products = $conn->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 5");
                            if ($recent_products->num_rows > 0) {
                                while ($row = $recent_products->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$row['product_id']}</td>
                                            <td>{$row['product_name']}</td>
                                            <td>{$row['product_code']}</td>
                                            <td>{$row['revision']}</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>No products found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Recent Tests</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Test ID</th>
                                <th>Product ID</th>
                                <th>Result</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $recent_tests = $conn->query("SELECT * FROM testing_records ORDER BY created_at DESC LIMIT 5");
                            if ($recent_tests->num_rows > 0) {
                                while ($row = $recent_tests->fetch_assoc()) {
                                    $result_class = ($row['result'] == 'Pass') ? 'bg-success' : 'bg-danger';
                                    echo "<tr>
                                            <td>{$row['test_id']}</td>
                                            <td>{$row['product_id']}</td>
                                            <td><span class='badge {$result_class}'>{$row['result']}</span></td>
                                            <td>{$row['status']}</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>No tests found</td></tr>";
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
