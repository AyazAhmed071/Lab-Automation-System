<?php
include '../config/db.php';
include '../header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = trim($_POST['product_name']);
    $product_code = trim($_POST['product_code']);
    $revision = trim($_POST['revision']);
    $manufacturing_number = trim($_POST['manufacturing_number']);
    $product_id = trim($_POST['product_id']); // Agar aap manual ID de rahe hain

    if (empty($product_name) || empty($product_code) || empty($revision) || empty($manufacturing_number)) {
        $error = "Please fill in all required fields.";
    } else {
        // Database Insert Logic
        $stmt = $conn->prepare("INSERT INTO products (product_id, product_name, product_code, revision, manufacturing_number) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $product_id, $product_name, $product_code, $revision, $manufacturing_number);
        
        if ($stmt->execute()) {
            $success = "New product registered successfully!";
            // Optional: Form clear karne ke liye variables empty kar den
            $product_name = $product_code = $revision = $manufacturing_number = $product_id = "";
        } else {
            $error = "Error adding product: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<style>
    :root { --ke-blue: #004a99; --ke-yellow: #ffc20e; }
    
    .add-container { max-width: 850px; margin-top: 30px; }
    
    .card-modern {
        border: none;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    .card-header-ke {
        background: var(--ke-blue);
        color: white;
        border-radius: 20px 20px 0 0 !important;
        padding: 25px;
        border-bottom: 5px solid var(--ke-yellow);
    }

    .form-label {
        font-weight: 600;
        color: var(--ke-blue);
        font-size: 0.9rem;
        text-transform: uppercase;
    }

    .form-control {
        border-radius: 10px;
        padding: 12px;
        border: 1px solid #dee2e6;
    }

    .form-control:focus {
        border-color: var(--ke-blue);
        box-shadow: 0 0 0 0.25rem rgba(0, 74, 153, 0.1);
    }

    .btn-ke-save {
        background-color: var(--ke-blue);
        color: white;
        font-weight: 700;
        border-radius: 10px;
        padding: 12px 40px;
        transition: 0.3s;
        border: none;
    }

    .btn-ke-save:hover {
        background-color: #003366;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 74, 153, 0.3);
    }
</style>

<div class="container add-container mb-5">
    <div class="mb-4">
        <a href="view_products.php" class="text-decoration-none text-muted fw-bold small">
            <i class="fas fa-chevron-left me-1"></i> BACK TO PRODUCT LIST
        </a>
    </div>

    <div class="card card-modern">
        <div class="card-header-ke">
            <div class="d-flex align-items-center">
                <div class="bg-white p-2 rounded-circle me-3" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-plus text-primary fa-lg"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold">Add New Material</h4>
                    <p class="mb-0 small opacity-75">Register a new product in the MTL database</p>
                </div>
            </div>
        </div>

        <div class="card-body p-4 p-md-5">
            <?php if ($error): ?>
                <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
                    <i class="fas fa-check-circle me-2"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form action="add_product.php" method="POST">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Internal Product ID</label>
                        <input type="text" name="product_id" class="form-control" placeholder="e.g. 5001" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Product Description</label>
                        <input type="text" name="product_name" class="form-control" placeholder="e.g. Copper Cable" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Product Code</label>
                        <input type="text" name="product_code" class="form-control" placeholder="e.g. KE-CBL-09" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Revision</label>
                        <input type="text" name="revision" class="form-control" placeholder="Rev-01" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Manufacturing No.</label>
                        <input type="text" name="manufacturing_number" class="form-control" placeholder="MN-456" required>
                    </div>
                </div>

                <div class="mt-5 pt-3 border-top d-flex justify-content-between align-items-center">
                    <p class="text-muted small mb-0"><i class="fas fa-info-circle me-1"></i> All fields are mandatory for lab reports.</p>
                    <button type="submit" class="btn btn-ke-save">
                        <i class="fas fa-cloud-upload-alt me-2"></i> REGISTER PRODUCT
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>


