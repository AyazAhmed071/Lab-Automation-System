<?php
include 'header.php'; // Is mein hamari fix ki hui session_start() aur db connection maujood hai

// Initialize variables
$where_clauses = [];
$params = [];
$types = "";

// Agar search form submit hua hai
if ($_SERVER['REQUEST_METHOD'] == 'GET' && (isset($_GET['search']))) {

    // 1. Date Range Filter
    if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
        $where_clauses[] = "tr.created_at BETWEEN ? AND ?";
        $params[] = $_GET['from_date'] . " 00:00:00";
        $params[] = $_GET['to_date'] . " 23:59:59";
        $types .= "ss";
    }

    // 2. Product Code Filter
    if (!empty($_GET['product_code'])) {
        $where_clauses[] = "p.product_code LIKE ?";
        $params[] = "%" . $_GET['product_code'] . "%";
        $types .= "s";
    }

    // 3. Result Filter (Pass/Fail)
    if (!empty($_GET['result'])) {
        $where_clauses[] = "tr.result = ?";
        $params[] = $_GET['result'];
        $types .= "s";
    }
}

// Query Building
$query = "SELECT tr.*, p.product_name, p.product_code 
          FROM testing_records tr 
          JOIN products p ON tr.product_id = p.product_id";

if (!empty($where_clauses)) {
    $query .= " WHERE " . implode(" AND ", $where_clauses);
}
$query .= " ORDER BY tr.created_at DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$results = $stmt->get_result();
?>

<div class="container-fluid py-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-search me-2"></i>KE Lab - Advanced Search</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="search.php" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="<?php echo $_GET['from_date'] ?? ''; ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="<?php echo $_GET['to_date'] ?? ''; ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Product Code</label>
                    <input type="text" name="product_code" class="form-control" placeholder="e.g. KE-TRANS-01" value="<?php echo $_GET['product_code'] ?? ''; ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="result" class="form-select">
                        <option value="">All Results</option>
                        <option value="Pass" <?php echo (isset($_GET['result']) && $_GET['result'] == 'Pass') ? 'selected' : ''; ?>>Pass</option>
                        <option value="Fail" <?php echo (isset($_GET['result']) && $_GET['result'] == 'Fail') ? 'selected' : ''; ?>>Fail</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" name="search" class="btn btn-primary w-100">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Product Name</th>
                            <th>Code</th>
                            <th>Result</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($results->num_rows > 0): ?>
                            <?php while ($row = $results->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo date('d-M-Y', strtotime($row['created_at'])); ?></td>
                                    <td><?php echo $row['product_name']; ?></td>
                                    <td><code><?php echo $row['product_code']; ?></code></td>
                                    <td>
                                        <span class="badge <?php echo ($row['result'] == 'Pass') ? 'bg-success' : 'bg-danger'; ?>">
                                            <?php echo $row['result']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="generate_report.php?id=<?php echo $row['test_id']; ?>" class="btn btn-sm btn-outline-dark">
                                            <i class="fas fa-file-pdf me-1"></i> Report
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No records found matching your criteria.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>