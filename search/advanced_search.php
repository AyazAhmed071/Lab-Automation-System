<?php
include '../header.php';

$search_product_id = isset($_GET['product_id']) ? trim($_GET['product_id']) : '';
$search_test_id = isset($_GET['test_id']) ? trim($_GET['test_id']) : '';
$search_test_type = isset($_GET['test_type']) ? trim($_GET['test_type']) : '';
$search_result = isset($_GET['result']) ? trim($_GET['result']) : '';

$where_clauses = [];
$params = [];
$types = "";

if (!empty($search_product_id)) {
    $where_clauses[] = "product_id LIKE ?";
    $params[] = "%$search_product_id%";
    $types .= "s";
}
if (!empty($search_test_id)) {
    $where_clauses[] = "test_id LIKE ?";
    $params[] = "%$search_test_id%";
    $types .= "s";
}
if (!empty($search_test_type)) {
    $where_clauses[] = "test_type LIKE ?";
    $params[] = "%$search_test_type%";
    $types .= "s";
}
if (!empty($search_result)) {
    $where_clauses[] = "result = ?";
    $params[] = $search_result;
    $types .= "s";
}

$query = "SELECT * FROM testing_records";
if (!empty($where_clauses)) {
    $query .= " WHERE " . implode(" AND ", $where_clauses);
}
$query .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$results = $stmt->get_result();
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="card-title mb-0">Advanced Search</h4>
            </div>
            <div class="card-body p-4">
                <form action="advanced_search.php" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="product_id" class="form-label">Product ID</label>
                        <input type="text" name="product_id" id="product_id" class="form-control" value="<?php echo $search_product_id; ?>" placeholder="Search by Product ID">
                    </div>
                    <div class="col-md-3">
                        <label for="test_id" class="form-label">Test ID</label>
                        <input type="text" name="test_id" id="test_id" class="form-control" value="<?php echo $search_test_id; ?>" placeholder="Search by Test ID">
                    </div>
                    <div class="col-md-3">
                        <label for="test_type" class="form-label">Test Type</label>
                        <input type="text" name="test_type" id="test_type" class="form-control" value="<?php echo $search_test_type; ?>" placeholder="Search by Test Type">
                    </div>
                    <div class="col-md-2">
                        <label for="result" class="form-label">Result</label>
                        <select name="result" id="result" class="form-select">
                            <option value="">All Results</option>
                            <option value="Pass" <?php echo ($search_result == 'Pass') ? 'selected' : ''; ?>>Pass</option>
                            <option value="Fail" <?php echo ($search_result == 'Fail') ? 'selected' : ''; ?>>Fail</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="table-container">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Test ID</th>
                    <th>Product ID</th>
                    <th>Test Type</th>
                    <th>Result</th>
                    <th>Status</th>
                    <th>Tester</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($results->num_rows > 0): ?>
                    <?php while ($row = $results->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo $row['test_id']; ?></strong></td>
                            <td><?php echo $row['product_id']; ?></td>
                            <td><?php echo $row['test_type']; ?></td>
                            <td>
                                <span class="badge <?php echo ($row['result'] == 'Pass') ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo $row['result']; ?>
                                </span>
                            </td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['tester_name']; ?></td>
                            <td><?php echo date('d M, Y', strtotime($row['testing_date'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">No matching records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../footer.php'; ?>
