<?php
include 'header.php';

// Initialize variables
$where_clauses = [];
$params = [];
$types = "";

// Search Logic (Wahi jo aapka pehle tha)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && (isset($_GET['search']))) {
    if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
        $where_clauses[] = "tr.created_at BETWEEN ? AND ?";
        $params[] = $_GET['from_date'] . " 00:00:00";
        $params[] = $_GET['to_date'] . " 23:59:59";
        $types .= "ss";
    }
    if (!empty($_GET['product_code'])) {
        $where_clauses[] = "p.product_code LIKE ?";
        $params[] = "%" . $_GET['product_code'] . "%";
        $types .= "s";
    }
    if (!empty($_GET['result'])) {
        $where_clauses[] = "tr.result = ?";
        $params[] = $_GET['result'];
        $types .= "s";
    }
}

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

<style>
    :root { --ke-blue: #004a99; --ke-yellow: #ffc20e; }
    .search-card { border-radius: 15px; border: none; background: #fff; }
    .ke-header { background: var(--ke-blue) !important; border-radius: 15px 15px 0 0 !important; }
    .btn-ke { background: var(--ke-yellow); color: #000; font-weight: bold; border: none; }
    .btn-ke:hover { background: #e5af0d; }
    .table-container { border-radius: 15px; overflow: hidden; background: white; }
    .pdf-link { color: #dc3545; font-weight: bold; text-decoration: none; transition: 0.3s; }
    .pdf-link:hover { color: #a71d2a; transform: scale(1.1); }
</style>

<div class="container-fluid py-4">
    <div class="card search-card shadow-sm mb-4">
        <div class="card-header ke-header text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-filter me-2"></i> K-Electric Lab - Report Finder</h5>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="search.php" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted small">FROM DATE</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-calendar-alt text-primary"></i></span>
                        <input type="date" name="from_date" class="form-control" value="<?php echo $_GET['from_date'] ?? ''; ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted small">TO DATE</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-calendar-check text-primary"></i></span>
                        <input type="date" name="to_date" class="form-control" value="<?php echo $_GET['to_date'] ?? ''; ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted small">PRODUCT CODE</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-barcode text-primary"></i></span>
                        <input type="text" name="product_code" class="form-control" placeholder="Search code..." value="<?php echo $_GET['product_code'] ?? ''; ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold text-muted small">TEST RESULT</label>
                    <select name="result" class="form-select border-start-0">
                        <option value="">All Results</option>
                        <option value="Pass" <?php echo (isset($_GET['result']) && $_GET['result'] == 'Pass') ? 'selected' : ''; ?>>Pass</option>
                        <option value="Fail" <?php echo (isset($_GET['result']) && $_GET['result'] == 'Fail') ? 'selected' : ''; ?>>Fail</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" name="search" class="btn btn-ke w-100 shadow-sm">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm table-container">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold">Date</th>
                            <th class="py-3 text-uppercase small fw-bold">Product Details</th>
                            <th class="py-3 text-uppercase small fw-bold">Product Code</th>
                            <th class="py-3 text-uppercase small fw-bold text-center">Result</th>
                            <th class="py-3 text-uppercase small fw-bold text-center">Report</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($results->num_rows > 0): ?>
                            <?php while ($row = $results->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold"><?php echo date('d M, Y', strtotime($row['created_at'])); ?></div>
                                        <small class="text-muted"><?php echo date('h:i A', strtotime($row['created_at'])); ?></small>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark"><?php echo $row['product_name']; ?></div>
                                        <small class="text-muted">Unit ID: #<?php echo $row['product_id']; ?></small>
                                    </td>
                                    <td><span class="badge bg-light text-primary border px-3"><?php echo $row['product_code']; ?></span></td>
                                    <td class="text-center">
                                        <?php $badge = ($row['result'] == 'Pass') ? 'bg-success' : 'bg-danger'; ?>
                                        <span class="badge <?php echo $badge; ?> rounded-pill px-3 py-2 shadow-sm">
                                            <i class="fas <?php echo ($row['result'] == 'Pass') ? 'fa-check-circle' : 'fa-times-circle'; ?> me-1"></i>
                                            <?php echo $row['result']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="generate_report.php?id=<?php echo $row['test_id']; ?>" target="_blank" class="btn btn-sm btn-outline-danger px-3 fw-bold">
                                            <i class="fas fa-file-pdf"></i> View PDF
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-50">
                                    <p class="text-muted fw-bold">Koe record nahi mila. Filters change kar ke check karein.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

