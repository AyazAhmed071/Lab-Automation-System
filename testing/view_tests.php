<?php
include '../config/db.php';
include '../header.php';

// Fetch all tests with latest on top
$tests = $conn->query("SELECT * FROM testing_records ORDER BY created_at DESC");
?>

<style>
    :root {
        --ke-blue: #004a99;
        --ke-yellow: #ffc20e;
    }

    body {
        background-color: #f4f7f9;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .test-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        background: white;
    }

    .test-header {
        background: var(--ke-blue);
        color: white;
        padding: 20px 30px;
        border-bottom: 5px solid var(--ke-yellow);
    }

    .table thead th {
        background-color: #f8f9fa;
        color: var(--ke-blue);
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #eee;
        padding: 15px;
    }

    .table tbody td {
        padding: 15px;
        border-bottom: 1px solid #f1f1f1;
        vertical-align: middle;
        color: #444;
    }

    /* Status Tags to match add_test logic */
    .status-badge {
        font-weight: 700;
        font-size: 0.7rem;
        padding: 5px 12px;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .status-approved { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
    .status-rejected { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
    .status-review { background: #fff3e0; color: #ef6c00; border: 1px solid #ffe0b2; }

    .test-id-tag {
        font-family: 'Consolas', monospace;
        color: var(--ke-blue);
        font-weight: 700;
        background: #f0f4f8;
        padding: 4px 8px;
        border-radius: 5px;
    }

    .btn-action {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: 0.3s;
        border: 1px solid #eee;
        background: white;
        color: var(--ke-blue);
    }

    .btn-action:hover {
        background: var(--ke-blue);
        color: white;
        transform: translateY(-2px);
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--ke-blue);
    }
</style>

<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">

            <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                <div>
                    <h2 class="fw-bold text-dark mb-0">Testing <span class="text-primary">Logbook</span></h2>
                    <p class="text-muted small">Manage and audit all laboratory inspection history</p>
                </div>
                <a href="add_test.php" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" style="background: var(--ke-blue); border:none;">
                    <i class="fas fa-plus-circle me-2"></i> New Test Record
                </a>
            </div>

            <div class="card test-card">
                <div class="test-header">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-microscope fa-lg me-3 text-white"></i>
                        <h5 class="mb-0 fw-bold">All Material Inspection Records</h5>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Tracking ID</th>
                                    <th>Material & Date</th>
                                    <th>Test Category</th>
                                    <th>Verdict</th>
                                    <th>Process Status</th>
                                    <th>Engineer</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($tests->num_rows > 0): ?>
                                    <?php while ($row = $tests->fetch_assoc()): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <span class="test-id-tag"><?php echo $row['test_id']; ?></span>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark"><?php echo $row['product_id']; ?></div>
                                                <div class="text-muted small"><i class="far fa-calendar-alt me-1"></i><?php echo date('d M, Y', strtotime($row['testing_date'])); ?></div>
                                            </td>
                                            <td>
                                                <div class="small fw-bold text-primary text-uppercase"><?php echo $row['testing_department']; ?></div>
                                                <div class="text-truncate" style="max-width: 150px;"><?php echo $row['test_type']; ?></div>
                                            </td>
                                            <td>
                                                <?php if ($row['result'] == 'Pass'): ?>
                                                    <span class="badge bg-success shadow-sm" style="border-radius: 4px;">PASS</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger shadow-sm" style="border-radius: 4px;">FAIL</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($row['result'] == 'Pass'): ?>
                                                    <div class="status-badge status-approved">
                                                        <i class="fas fa-check-circle"></i> APPROVED
                                                    </div>
                                                    <div class="text-muted mt-1" style="font-size: 0.65rem; font-weight: 600;"><?php echo $row['status']; ?></div>
                                                <?php elseif ($row['result'] == 'Fail'): ?>
                                                    <div class="status-badge status-rejected">
                                                        <i class="fas fa-times-circle"></i> REJECTED
                                                    </div>
                                                    <div class="text-muted mt-1" style="font-size: 0.65rem; font-weight: 600; color: #c62828;"><?php echo $row['status']; ?></div>
                                                <?php else: ?>
                                                    <div class="status-badge status-review">
                                                        <i class="fas fa-clock"></i> IN REVIEW
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="small fw-bold text-secondary"><?php echo $row['tester_name']; ?></span>
                                            </td>
                                            <td class="text-center">
                                                <button class="btn-action" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $row['id']; ?>">
                                                    <i class="fas fa-search-plus"></i>
                                                </button>

                                                <div class="modal fade" id="viewModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content" style="border-radius: 20px; border: none;">
                                                            <div class="modal-header p-4" style="background: var(--ke-blue); color: white; border-bottom: 4px solid var(--ke-yellow); border-radius: 20px 20px 0 0;">
                                                                <h5 class="modal-title fw-bold"><i class="fas fa-file-contract me-2"></i> Report Details</h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body p-4 text-start">
                                                                <div class="mb-3 p-3 bg-light rounded-3 border-start border-4 border-primary">
                                                                    <label class="small fw-bold text-muted text-uppercase mb-1 d-block">Testing Criteria</label>
                                                                    <p class="mb-0 text-dark"><?php echo nl2br($row['testing_criteria']); ?></p>
                                                                </div>
                                                                <div class="mb-0 p-3 bg-light rounded-3 border-start border-4 border-warning">
                                                                    <label class="small fw-bold text-muted text-uppercase mb-1 d-block">Technical Remarks</label>
                                                                    <p class="mb-0 text-dark"><?php echo $row['remarks'] ? nl2br($row['remarks']) : 'No specific observations recorded.'; ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                                                <p>No testing records available.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
