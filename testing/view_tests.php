<?php
include '../header.php';

// Fetch all tests
$tests = $conn->query("SELECT * FROM testing_records ORDER BY created_at DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Testing Records</h2>
    <a href="add_test.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Add Testing Record</a>
</div>

<div class="table-container">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Test ID</th>
                    <th>Product ID</th>
                    <th>Test Type</th>
                    <th>Department</th>
                    <th>Result</th>
                    <th>Status</th>
                    <th>Tester</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($tests->num_rows > 0): ?>
                    <?php while ($row = $tests->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo $row['test_id']; ?></strong></td>
                            <td><?php echo $row['product_id']; ?></td>
                            <td><?php echo $row['test_type']; ?></td>
                            <td><?php echo $row['testing_department']; ?></td>
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
                            <td><?php echo date('d M, Y', strtotime($row['testing_date'])); ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $row['id']; ?>">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                <!-- View Modal -->
                                <div class="modal fade" id="viewModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Test Details: <?php echo $row['test_id']; ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Product ID:</strong> <?php echo $row['product_id']; ?>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Test Type:</strong> <?php echo $row['test_type']; ?>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Department:</strong> <?php echo $row['testing_department']; ?>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Testing Date:</strong> <?php echo date('d M, Y', strtotime($row['testing_date'])); ?>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <strong>Testing Criteria:</strong><br>
                                                        <div class="p-2 bg-light rounded mt-1"><?php echo nl2br($row['testing_criteria']); ?></div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <strong>Result:</strong> 
                                                        <span class="badge <?php echo ($row['result'] == 'Pass') ? 'bg-success' : 'bg-danger'; ?>"><?php echo $row['result']; ?></span>
                                                        - <?php echo $row['status']; ?>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <strong>Remarks:</strong><br>
                                                        <div class="p-2 bg-light rounded mt-1"><?php echo nl2br($row['remarks']); ?></div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <strong>Tester Name:</strong> <?php echo $row['tester_name']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center py-4">No testing records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../footer.php'; ?>
