<?php
include 'header.php';

$filter_result = isset($_GET['result']) ? trim($_GET['result']) : '';

$query = "SELECT * FROM testing_records";
if ($filter_result) {
    $query .= " WHERE result = '$filter_result'";
}
$query .= " ORDER BY created_at DESC";

$results = $conn->query($query);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Testing Reports</h2>
    <div class="btn-group" role="group">
        <a href="reports.php" class="btn btn-outline-secondary <?php echo ($filter_result == '') ? 'active' : ''; ?>">All</a>
        <a href="reports.php?result=Pass" class="btn btn-outline-success <?php echo ($filter_result == 'Pass') ? 'active' : ''; ?>">Passed</a>
        <a href="reports.php?result=Fail" class="btn btn-outline-danger <?php echo ($filter_result == 'Fail') ? 'active' : ''; ?>">Failed</a>
    </div>
</div>

<div class="table-container shadow-sm border-0">
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
                    <th>Remarks</th>
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
                            <td>
                                <span class="badge <?php echo ($row['result'] == 'Pass') ? 'bg-info' : 'bg-warning text-dark'; ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                            <td><?php echo $row['tester_name']; ?></td>
                            <td><small class="text-muted"><?php echo $row['remarks'] ? substr($row['remarks'], 0, 50) . '...' : 'No remarks'; ?></small></td>
                            <td><?php echo date('d M, Y', strtotime($row['testing_date'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No reports found for this filter.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
