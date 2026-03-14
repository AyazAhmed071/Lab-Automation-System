<?php
include 'config/db.php'; // Sirf DB chahiye, header nahi chahiye kyunke ye printable page hai

if (!isset($_GET['id'])) {
    die("Error: Test ID missing.");
}

$test_id = $_GET['id'];

// Database se test aur product ka sara data nikalna
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
    <title>Test Report - <?php echo $data['product_code']; ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .report-container {
            width: 800px;
            margin: 20px auto;
            border: 2px solid #333;
            padding: 30px;
            position: relative;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #333;
            margin-bottom: 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #0056b3;
        }

        .report-title {
            text-align: center;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 20px 0;
        }

        .info-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .label {
            font-weight: bold;
            background-color: #f9f9f9;
            width: 30%;
        }

        .result-box {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            border: 2px dashed #333;
        }

        .pass {
            color: green;
            font-weight: bold;
            font-size: 20px;
        }

        .fail {
            color: red;
            font-weight: bold;
            font-size: 20px;
        }

        .footer-sigs {
            margin-top: 80px;
            display: flex;
            justify-content: space-between;
        }

        .sig-box {
            border-top: 1px solid #333;
            width: 200px;
            text-align: center;
            padding-top: 5px;
        }

        @media print {
            .no-print {
                display: none;
            }

            .report-container {
                border: none;
                width: 100%;
                margin: 0;
            }
        }
    </style>
</head>

<body>

    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: #0056b3; color: white; border: none; border-radius: 5px;">
            Click to Print Report
        </button>
    </div>

    <div class="report-container">
        <table class="header-table">
            <tr>
                <td class="logo">K-ELECTRIC <br> <span style="font-size: 12px; color: #666;">Material Testing Laboratory</span></td>
                <td style="text-align: right;">
                    <strong>Report No:</strong> #KE-MTL-<?php echo $data['test_id']; ?><br>
                    <strong>Date:</strong> <?php echo date('d-M-Y', strtotime($data['created_at'])); ?>
                </td>
            </tr>
        </table>

        <h2 class="report-title">Material Test Certificate</h2>

        <table class="info-table">
            <tr>
                <td class="label">Product Name:</td>
                <td><?php echo $data['product_name']; ?></td>
            </tr>
            <tr>
                <td class="label">Product Code / ID:</td>
                <td><?php echo $data['product_code']; ?></td>
            </tr>
            <tr>
                <td class="label">Revision No:</td>
                <td><?php echo $data['revision']; ?></td>
            </tr>
            <tr>
                <td class="label">Status during Test:</td>
                <td><?php echo $data['status']; ?></td>
            </tr>
        </table>

        <h3>Test Observations:</h3>
        <table class="info-table">
            <tr style="background: #eee;">
                <th>Parameter</th>
                <th>Standard Requirement</th>
                <th>Observed Value</th>
            </tr>
            <tr>
                <td>Visual Inspection</td>
                <td>As per Drawing</td>
                <td>Satisfactory</td>
            </tr>
            <tr>
                <td>Operational Test</td>
                <td>Standard KE-STP-01</td>
                <td>Compliant</td>
            </tr>
        </table>

        <div class="result-box">
            TEST RESULT:
            <span class="<?php echo ($data['result'] == 'Pass') ? 'pass' : 'fail'; ?>">
                <?php echo strtoupper($data['result']); ?>
            </span>
        </div>

        <p><strong>Remarks:</strong> The material has been tested as per KE quality standards and found <?php echo ($data['result'] == 'Pass') ? 'fit' : 'unfit'; ?> for use.</p>

        <div class="footer-sigs">
            <div class="sig-box">Lab Analyst Signature</div>
            <div class="sig-box">Quality Manager Signature</div>
        </div>
    </div>

</body>

</html>