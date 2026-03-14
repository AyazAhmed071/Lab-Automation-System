<?php
include '../config/db.php';
include '../header.php';

$error = '';
$success = '';

if (!isset($_GET['id'])) {
    header("Location: view_products.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    header("Location: view_products.php");
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
            // Update product data for the form
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

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="card-title mb-0">Edit Product: <?php echo $product['product_id']; ?></h4>
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

                <form action="edit_product.php?id=<?php echo $id; ?>" method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" name="product_name" id="product_name" class="form-control" required value="<?php echo $product['product_name']; ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="product_code" class="form-label">Product Code</label>
                            <input type="text" name="product_code" id="product_code" class="form-control" required value="<?php echo $product['product_code']; ?>">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="revision" class="form-label">Revision</label>
                            <input type="text" name="revision" id="revision" class="form-control" required value="<?php echo $product['revision']; ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="manufacturing_number" class="form-label">Manufacturing Number</label>
                            <input type="text" name="manufacturing_number" id="manufacturing_number" class="form-control" required value="<?php echo $product['manufacturing_number']; ?>">
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="view_products.php" class="btn btn-light me-md-2">Back to List</a>
                        <button type="submit" class="btn btn-primary px-5">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
