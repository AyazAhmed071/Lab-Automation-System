<?php
include '../config/db.php';
include '../header.php';

// Delete Logic (Aapka original)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $success = "Product deleted successfully.";
    } else {
        $error = "Error deleting product.";
    }
    $stmt->close();
}

// Fetch all products
$products = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
?>

<style>
    /* Dashboard Theme Match */
    :root { --ke-blue: #004a99; --ke-yellow: #ffc20e; }
    
    .page-header {
        border-left: 5px solid var(--ke-yellow);
        padding-left: 15px;
        margin-bottom: 30px;
    }
    
    .page-title {
        color: var(--ke-blue);
        font-weight: 700;
        margin: 0;
    }

    .table-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        overflow: hidden;
        border: none;
    }

    .custom-table thead {
        background-color: var(--ke-blue);
        color: white;
    }

    .custom-table th {
        font-weight: 600;
        padding: 15px;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .custom-table td {
        padding: 15px;
        font-size: 0.95rem;
    }

    .product-code-badge {
        background: rgba(0, 74, 153, 0.1);
        color: var(--ke-blue);
        padding: 4px 10px;
        border-radius: 6px;
        font-family: 'Courier New', Courier, monospace;
        font-weight: bold;
    }

    .btn-add-new {
        background-color: var(--ke-blue);
        color: white;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-add-new:hover {
        background-color: #003366;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 74, 153, 0.3);
    }

    .action-btn {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: 0.2s;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center page-header">
        <div>
            <h2 class="page-title text-uppercase">Product Inventory</h2>
            <p class="text-muted small mb-0">Manage and track all lab-tested materials</p>
        </div>
        <a href="add_product.php" class="btn btn-add-new shadow-sm">
            <i class="fas fa-plus-circle me-2"></i> Add New Product
        </a>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
            <i class="fas fa-check-circle me-2"></i> <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="table-container mt-2">
        <div class="table-responsive">
            <table class="table table-hover align-middle custom-table mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Product ID</th>
                        <th>Product Name</th>
                        <th>Product Code</th>
                        <th>Revision</th>
                        <th>Mfg. Number</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($products->num_rows > 0): ?>
                        <?php while ($row = $products->fetch_assoc()): ?>
                            <tr>
                                <td class="ps-4 text-muted">#<?php echo $row['product_id']; ?></td>
                                <td>
                                    <span class="fw-bold text-dark"><?php echo $row['product_name']; ?></span>
                                </td>
                                <td>
                                    <span class="product-code-badge"><?php echo $row['product_code']; ?></span>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?php echo $row['revision']; ?></span></td>
                                <td><small class="text-muted"><?php echo $row['manufacturing_number']; ?></small></td>
                                <td class="text-center">
                                    <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-primary action-btn me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="view_products.php?delete=<?php echo $row['id']; ?>" class="btn btn-outline-danger action-btn" 
                                       onclick="return confirm('Are you sure you want to delete this product?')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-box-open fa-3x text-light mb-3"></i>
                                <p class="text-muted">No products found in the database.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>


