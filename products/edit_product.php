<?php
include '../config/db.php';
include '../header.php';

$error = '';
$success = '';

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='view_products.php';</script>";
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    echo "<script>window.location.href='view_products.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = trim($_POST['product_name']);
    $product_code = trim($_POST['product_code']);
    $revision = trim($_POST['revision']);
    $manufacturing_number = trim($_POST['manufacturing_number']);

    if (empty($product_name) || empty($product_code) || empty($revision) || empty($manufacturing_number)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("UPDATE products SET product_name = ?, product_code = ?, revision = ?, manufacturing_number = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $product_name, $product_code, $revision, $manufacturing_number, $id);

        if ($stmt->execute()) {
            $success = "Product updated successfully.";
            $product['product_name'] = $product_name;
            $product['product_code'] = $product_code;
            $product['revision'] = $revision;
            $product['manufacturing_number'] = $manufacturing_number;
        } else {
            $error = "Error updating product: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<style>
    .edit-container {
        max-width: 900px;
        margin-top: 30px;
    }

    .card-modern {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .card-header-ke {
        background-color: #004a99;
        color: white;
        border-radius: 15px 15px 0 0 !important;
        padding: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #004a99;
    }

    .btn-ke-update {
        background-color: #004a99;
        color: white;
        font-weight: bold;
        border-radius: 8px;
        padding: 10px 25px;
        transition: 0.3s;
    }

    .btn-ke-update:hover {
        background-color: #003366;
        color: white;
        box-shadow: 0 5px 15px rgba(0, 74, 153, 0.3);
    }
</style>

<div class="container edit-container">
    <div class="card card-modern">
        <div class="card-header-ke d-flex align-items-center justify-content-between">
            <h4 class="mb-0"><i class="fas fa-edit me-2"></i> Edit Product: <?php echo htmlspecialchars($product['product_id']); ?></h4>
            <a href="view_products.php" class="btn btn-sm btn-light text-primary fw-bold">Back to List</a>
        </div>

        <div class="card-body p-4 p-md-5">
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?php echo $success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="edit_product.php?id=<?php echo $id; ?>" method="POST">
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" name="product_name" id="product_name" class="form-control" required
                            value="<?php echo htmlspecialchars($product['product_name']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="product_code" class="form-label">Product Code</label>
                        <input type="text" name="product_code" id="product_code" class="form-control" required
                            value="<?php echo htmlspecialchars($product['product_code']); ?>">
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="revision" class="form-label">Revision</label>
                        <input type="text" name="revision" id="revision" class="form-control" required
                            value="<?php echo htmlspecialchars($product['revision']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="manufacturing_number" class="form-label">Manufacturing Number</label>
                        <input type="text" name="manufacturing_number" id="manufacturing_number" class="form-control" required
                            value="<?php echo htmlspecialchars($product['manufacturing_number']); ?>">
                    </div>
                </div>

                <div class="text-end pt-3 border-top mt-4">
                    <button type="submit" class="btn btn-ke-update">
                        <i class="fas fa-save me-2"></i> Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include '../footer.php'; ?>