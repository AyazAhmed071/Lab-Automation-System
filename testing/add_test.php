<?php
include '../header.php';

$error = '';
$success = '';

// Generate 12-digit unique test ID
function generateTestID($conn) {
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

    // Determine status
    $status = ($result == 'Pass') ? 'Marked for CPRI approval' : 'Marked for re-manufacturing';

    if (empty($product_id) || empty($test_type) || empty($testing_department) || empty($testing_criteria) || empty($result) || empty($tester_name) || empty($testing_date)) {
        $error = "Please fill in all required fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO testing_records (test_id, product_id, test_type, testing_department, testing_criteria, result, remarks, tester_name, testing_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $test_id, $product_id, $test_type, $testing_department, $testing_criteria, $result, $remarks, $tester_name, $testing_date, $status);
        
        if ($stmt->execute()) {
            $success = "Testing record added successfully. ID: " . $test_id;
        } else {
            $error = "Error adding testing record: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm mb-5">
            <div class="card-header bg-white">
                <h4 class="card-title mb-0">Add Testing Record</h4>
            </div>
            <div class="card-body p-4">
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="add_test.php" method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="product_id" class="form-label">Product ID</label>
                            <select name="product_id" id="product_id" class="form-select" required>
                                <option value="" disabled selected>Select Product</option>
                                <?php while ($row = $products->fetch_assoc()): ?>
                                    <option value="<?php echo $row['product_id']; ?>">
                                        <?php echo $row['product_id']; ?> - <?php echo $row['product_name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="test_type" class="form-label">Test Type</label>
                            <input type="text" name="test_type" id="test_type" class="form-control" required placeholder="e.g., Performance, Durability">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="testing_department" class="form-label">Testing Department</label>
                            <input type="text" name="testing_department" id="testing_department" class="form-control" required placeholder="e.g., QC, Engineering">
                        </div>
                        <div class="col-md-6">
                            <label for="testing_date" class="form-label">Testing Date</label>
                            <input type="date" name="testing_date" id="testing_date" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="testing_criteria" class="form-label">Testing Criteria</label>
                            <textarea name="testing_criteria" id="testing_criteria" class="form-control" rows="3" required placeholder="Enter testing criteria and parameters"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="result" class="form-label">Result</label>
                            <select name="result" id="result" class="form-select" required>
                                <option value="" disabled selected>Select Result</option>
                                <option value="Pass">Pass</option>
                                <option value="Fail">Fail</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tester_name" class="form-label">Tester Name</label>
                            <input type="text" name="tester_name" id="tester_name" class="form-control" required placeholder="Enter tester name">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="remarks" class="form-label">Detailed Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Enter additional remarks or failure details"></textarea>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="view_tests.php" class="btn btn-light me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5">Submit Testing Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
