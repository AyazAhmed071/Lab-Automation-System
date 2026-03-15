<?php
include '../config/db.php'; // Path check kar lijiyega
include '../header.php';

$error = '';
$success = '';

// Generate 12-digit unique test ID (Aapka logic)
function generateTestID($conn)
{
    do {
        $id = str_pad(mt_rand(1, 999999999999), 12, '0', STR_PAD_LEFT);
        $check = $conn->query("SELECT id FROM testing_records WHERE test_id = '$id'");
    } while ($check->num_rows > 0);
    return $id;
}

// Fetch products for dropdown
$products = $conn->query("SELECT product_id, product_name FROM products ORDER BY product_id ASC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $test_id = generateTestID($conn);
    $product_id = trim($_POST['product_id']);
    $test_type = trim($_POST['test_type']);
    $testing_department = trim($_POST['testing_department']);
    $testing_criteria = trim($_POST['testing_criteria']);
    $result = trim($_POST['result']);
    $remarks = trim($_POST['remarks']);
    $tester_name = trim($_POST['tester_name']);
    $testing_date = trim($_POST['testing_date']);

    // Determine status (Aapka logic)
    $status = ($result == 'Pass') ? 'Marked for CPRI approval' : 'Marked for re-manufacturing';

    if (empty($product_id) || empty($test_type) || empty($testing_department) || empty($testing_criteria) || empty($result) || empty($tester_name) || empty($testing_date)) {
        $error = "Please fill in all required fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO testing_records (test_id, product_id, test_type, testing_department, testing_criteria, result, remarks, tester_name, testing_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $test_id, $product_id, $test_type, $testing_department, $testing_criteria, $result, $remarks, $tester_name, $testing_date, $status);

        if ($stmt->execute()) {
            $success = "Record added successfully! Test ID: <strong>$test_id</strong>";
        } else {
            $error = "Error adding record: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<style>
    :root {
        --ke-blue: #004a99;
        --ke-yellow: #ffc20e;
    }

    .test-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        overflow: hidden;
    }

    .test-header {
        background: var(--ke-blue);
        color: white;
        padding: 25px;
        border-bottom: 5px solid var(--ke-yellow);
    }

    .form-label {
        font-weight: 700;
        color: var(--ke-blue);
        font-size: 0.85rem;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        padding: 12px;
        border: 1px solid #ced4da;
        transition: 0.3s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--ke-yellow);
        box-shadow: 0 0 0 0.25rem rgba(255, 194, 14, 0.15);
    }

    .section-title {
        font-size: 1rem;
        font-weight: 800;
        color: #666;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-submit {
        background: var(--ke-blue);
        color: white;
        border-radius: 12px;
        padding: 15px 40px;
        font-weight: 700;
        border: none;
        transition: 0.3s;
    }

    .btn-submit:hover {
        background: #003366;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 74, 153, 0.3);
    }

    .input-group-text {
        background: #f8f9fa;
        border-radius: 10px 0 0 10px !important;
        color: var(--ke-blue);
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark mb-0">Testing <span class="text-primary">Console</span></h2>
                <a href="view_tests.php" class="btn btn-outline-secondary rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fas fa-list me-2"></i> View All Records
                </a>
            </div>

            <div class="card test-card">
                <div class="test-header">
                    <div class="d-flex align-items-center">
                        <div class="bg-white p-3 rounded-circle me-3 shadow-sm">
                            <i class="fas fa-microscope text-primary fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 fw-bold">New Laboratory Test Record</h4>
                            <p class="mb-0 opacity-75 small">System will automatically generate a unique 12-digit Tracking ID</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <?php if ($error): ?>
                        <div class="alert alert-danger border-0 shadow-sm mb-4 rounded-3"><i class="fas fa-times-circle me-2"></i> <?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success border-0 shadow-sm mb-4 rounded-3"><i class="fas fa-check-circle me-2"></i> <?php echo $success; ?></div>
                    <?php endif; ?>

                    <form action="add_test.php" method="POST">

                        <div class="section-title"><i class="fas fa-info-circle me-2 text-primary"></i> Primary Information</div>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Target Product</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-box"></i></span>
                                    <select name="product_id" class="form-select" required>
                                        <option value="" disabled selected>Choose Material...</option>
                                        <?php while ($row = $products->fetch_assoc()): ?>
                                            <option value="<?php echo $row['product_id']; ?>">
                                                <?php echo $row['product_id']; ?> - <?php echo $row['product_name']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Test Date</label>
                                <input type="date" name="testing_date" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Department</label>
                                <input type="text" name="testing_department" class="form-control" placeholder="e.g. QC Lab" required>
                            </div>
                        </div>

                        <div class="section-title"><i class="fas fa-vials me-2 text-primary"></i> Testing Parameters</div>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Test Type / Category</label>
                                <input type="text" name="test_type" class="form-control" placeholder="e.g. Dielectric Strength / Load Test" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tester / Engineer Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user-check"></i></span>
                                    <input type="text" name="tester_name" class="form-control" placeholder="Full Name" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Testing Criteria & Standards</label>
                                <textarea name="testing_criteria" class="form-control" rows="2" placeholder="Describe the standards (e.g. IEC 60076)" required></textarea>
                            </div>
                        </div>

                        <div class="section-title"><i class="fas fa-poll-h me-2 text-primary"></i> Final Verdict</div>
                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Test Result</label>
                                <select name="result" class="form-select fw-bold" required>
                                    <option value="" disabled selected>Select Result</option>
                                    <option value="Pass" style="color: green;">🟢 PASS</option>
                                    <option value="Fail" style="color: red;">🔴 FAIL</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Technical Remarks / Observations</label>
                                <textarea name="remarks" class="form-control" rows="2" placeholder="Mention any deviations or specific observations"></textarea>
                            </div>
                        </div>

                        <div class="text-end pt-4 mt-2 border-top">
                            <button type="reset" class="btn btn-light px-4 me-2 fw-bold text-muted">Clear Form</button>
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-save me-2"></i> Finalize & Generate Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>