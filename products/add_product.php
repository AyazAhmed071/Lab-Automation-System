<?php
include '../header.php';

$error = '';
$success = '';

// Generate 10-digit unique product ID
function generateProductID($conn) {
    do {
        $id = str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);
        $check = $conn->query("SELECT id FROM products WHERE product_id = '$id'");
    } while ($check->num_rows > 0);
    return $id;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = generateProductID($conn);
    $product_name = trim($_POST['product_name']);
    $product_code = trim($_POST['product_code']);
    $revision = trim($_POST['revision']);
    $manufacturing_number = trim($_POST['manufacturing_number']);

    if (empty($product_name) || empty($product_code) || empty($revision) || empty($manufacturing_number)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO products (product_id, product_name, product_code, revision, manufacturing_number) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $product_id, $product_name, $product_code, $revision, $manufacturing_number);
        
        if ($stmt->execute()) {
            $success = "Product added successfully. ID: " . $product_id;
        } else {
            $error = "Error adding product: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="card-title mb-0">Add New Product</h4>
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

                <form action="add_product.php" method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" name="product_name" id="product_name" class="form-control" required placeholder="Enter product name">
                        </div>
                        <div class="col-md-6">
                            <label for="product_code" class="form-label">Product Code</label>
                            <input type="text" name="product_code" id="product_code" class="form-control" required placeholder="Enter product code">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="revision" class="form-label">Revision</label>
                            <input type="text" name="revision" id="revision" class="form-control" required placeholder="Enter revision">
                        </div>
                        <div class="col-md-6">
                            <label for="manufacturing_number" class="form-label">Manufacturing Number</label>
                            <input type="text" name="manufacturing_number" id="manufacturing_number" class="form-control" required placeholder="Enter manufacturing number">
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="view_products.php" class="btn btn-light me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
