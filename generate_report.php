<?php
include 'config/db.php';

if (!isset($_GET['id'])) {
    die("Error: Test ID missing.");
}

$test_id = $_GET['id'];

$query = "SELECT tr.*, p.* FROM testing_records tr 
          JOIN products p ON tr.product_id = p.product_id 
          WHERE tr.test_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $test_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    die("Error: Record not found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>KE Report - <?php echo $data['product_code']; ?></title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            /* Professional report font */
            background-color: #f5f5f5;
            padding: 20px;
        }

        .report-wrapper {
            width: 210mm;
            /* A4 Width */
            min-height: 297mm;
            margin: 0 auto;
            background: white;
            padding: 50px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            border-top: 10px solid #004a99;
            /* KE Blue top border */
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(0, 74, 153, 0.05);
            font-weight: bold;
            z-index: 0;
            pointer-events: none;
            white-space: nowrap;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #004a99;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .ke-logo {
            font-size: 32px;
            font-weight: 900;
            color: #004a99;
            letter-spacing: -1px;
        }

        .ke-tagline {
            font-size: 14px;
            color: #ffc20e;
            /* KE Gold */
            font-weight: bold;
            text-transform: uppercase;
        }

        .report-info {
            text-align: right;
            font-size: 13px;
        }

        .main-title {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin: 30px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        .info-grid td {
            padding: 12px;
            border: 1px solid #eee;
            font-size: 14px;
        }

        .bg-light {
            background-color: #f8f9fa;
            font-weight: bold;
            width: 25%;
            color: #555;
        }

        .observation-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .observation-table th {
            background: #004a99;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }

        .observation-table td {
            padding: 10px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        .result-container {
            margin: 40px 0;
            text-align: center;
        }

        .status-badge {
            display: inline-block;
            padding: 10px 40px;
            font-size: 22px;
            font-weight: bold;
            border: 3px solid;
            text-transform: uppercase;
            border-radius: 8px;
        }

        .pass-badge {
            color: #198754;
            border-color: #198754;
        }

        .fail-badge {
            color: #dc3545;
            border-color: #dc3545;
        }

        .footer-signatures {
            margin-top: 100px;
            display: flex;
            justify-content: space-between;
        }

        .sig-block {
            text-align: center;
            width: 250px;
        }

        .sig-line {
            border-top: 1px solid #333;
            margin-bottom: 5px;
        }

        .no-print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #004a99;
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            font-weight: bold;
            z-index: 100;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .no-print-btn {
                display: none;
            }

            .report-wrapper {
                box-shadow: none;
                width: 100%;
                margin: 0;
                padding: 30px;
            }
        }
    </style>
</head>

<body>

    <button class="no-print-btn" onclick="window.print()">
        <i class="fas fa-print"></i> PRINT OFFICIAL REPORT
    </button>

    <div class="report-wrapper">
        <div class="watermark">K-ELECTRIC MTL</div>

        <div class="header-section">
            <div>
                <div class="ke-logo">K-ELECTRIC</div>
                <div class="ke-tagline">Material Testing Laboratory</div>
                <div style="font-size: 12px; margin-top: 5px;">Head Office: 1st Floor, Block A, Elander Road, Karachi.</div>
            </div>
            <div class="report-info">
                <strong>Certificate No:</strong> KE/MTL/<?php echo date('Y'); ?>/<?php echo $data['test_id']; ?><br>
                <strong>Issue Date:</strong> <?php echo date('d F, Y', strtotime($data['created_at'])); ?><br>
                <strong>Valid Till:</strong> <?php echo date('d F, Y', strtotime($data['created_at'] . ' + 1 year')); ?>
            </div>
        </div>

        <h2 class="main-title">Material Test Certificate</h2>

        <table class="info-grid">
            <tr>
                <td class="bg-light">Manufacturer Name</td>
                <td>K-Electric Authorized Supplier</td>
                <td class="bg-light">Product Code</td>
                <td class="fw-bold"><?php echo $data['product_code']; ?></td>
            </tr>
            <tr>
                <td class="bg-light">Material Description</td>
                <td><?php echo $data['product_name']; ?></td>
                <td class="bg-light">Revision No.</td>
                <td><?php echo $data['revision']; ?></td>
            </tr>
            <tr>
                <td class="bg-light">Sample Condition</td>
                <td>New / Satisfactory</td>
                <td class="bg-light">Laboratory Status</td>
                <td><?php echo $data['status']; ?></td>
            </tr>
        </table>

        <h3>Standardized Test Observations</h3>
        <table class="observation-table">
            <thead>
                <tr>
                    <th>Sr.</th>
                    <th>Testing Parameter</th>
                    <th>Standard Method</th>
                    <th>Obtained Value</th>
                    <th>Verdict</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>01</td>
                    <td>Visual & Dimensional Check</td>
                    <td>KE-SPEC-04</td>
                    <td>As per drawing</td>
                    <td>Compliant</td>
                </tr>
                <tr>
                    <td>02</td>
                    <td>Performance/Load Test</td>
                    <td>IEC 60076</td>
                    <td>Stable</td>
                    <td>Satisfactory</td>
                </tr>
                <tr>
                    <td>03</td>
                    <td>Material Endurance</td>
                    <td>ASTM-E8</td>
                    <td>Within Limits</td>
                    <td>Pass</td>
                </tr>
            </tbody>
        </table>

        <div class="result-container">
            <p style="margin-bottom: 10px; font-weight: bold;">FINAL INSPECTION VERDICT:</p>
            <div class="status-badge <?php echo ($data['result'] == 'Pass') ? 'pass-badge' : 'fail-badge'; ?>">
                <?php echo ($data['result'] == 'Pass') ? 'ACCEPTED / FIT' : 'REJECTED / UNFIT'; ?>
            </div>
        </div>

        <p style="font-size: 13px; margin-top: 50px;">
            <strong>Disclaimer:</strong> This certificate is issued based on the testing performed on the sample provided to the MTL Laboratory. Any tempering with this certificate is a legal offense.
        </p>

        <div class="footer-signatures">
            <div class="sig-block">
                <div class="sig-line"></div>
                <strong>Lab In-Charge</strong>
                <div style="font-size: 12px; color: #777;">Material Testing Lab</div>
            </div>

            <div style="text-align: center;">
                <div style="width: 80px; height: 80px; border: 1px solid #ccc; margin: 0 auto 5px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #ccc;">QR CODE</div>
                <small>Verify Online</small>
            </div>

            <div class="sig-block">
                <div class="sig-line"></div>
                <strong>Quality Assurance Manager</strong>
                <div style="font-size: 12px; color: #777;">K-Electric Sindh Region</div>
            </div>
        </div>
    </div>

</body>

</html>