<?php
require_once 'config/db.php';

// Check if products exist
$check = $conn->query("SELECT * FROM products");
if ($check->num_rows == 0) {
    // Sample Products
    $products = [
        ['1234567890', 'Industrial Sensor X1', 'IS-X1-2023', 'v1.2', 'MFG-98765'],
        ['2345678901', 'High Voltage Relay', 'HV-R-100', 'v2.0', 'MFG-11223'],
        ['3456789012', 'Digital Multimeter', 'DM-2024', 'v1.0', 'MFG-55667'],
        ['4567890123', 'Lab Power Supply', 'LPS-30V', 'v3.1', 'MFG-88990'],
        ['5678901234', 'Smart Thermostat', 'ST-400', 'v1.5', 'MFG-33445'],
    ];

    $stmt = $conn->prepare("INSERT INTO products (product_id, product_name, product_code, revision, manufacturing_number) VALUES (?, ?, ?, ?, ?)");
    foreach ($products as $p) {
        $stmt->bind_param("sssss", $p[0], $p[1], $p[2], $p[3], $p[4]);
        $stmt->execute();
    }
    $stmt->close();
    echo "Sample products inserted.<br>";

    // Sample Tests
    $tests = [
        ['123456789012', '1234567890', 'Performance Test', 'Quality Control', 'Accuracy within +/- 0.5%', 'Pass', 'Device performed well within specified ranges.', 'John Doe', date('Y-m-d'), 'Marked for CPRI approval'],
        ['234567890123', '2345678901', 'Load Test', 'Engineering', 'Withstand 1000V for 5 minutes', 'Fail', 'Device failed at 850V. Insulation breakdown observed.', 'Jane Smith', date('Y-m-d'), 'Marked for re-manufacturing'],
        ['345678901234', '3456789012', 'Calibration Test', 'QC', 'Reading accuracy < 0.1%', 'Pass', 'All readings are accurate.', 'Bob Johnson', date('Y-m-d'), 'Marked for CPRI approval'],
        ['456789012345', '4567890123', 'Durability Test', 'Engineering', '10,000 cycles without failure', 'Pass', 'Completed 10,000 cycles successfully.', 'Alice Brown', date('Y-m-d'), 'Marked for CPRI approval'],
        ['567890123456', '5678901234', 'Connectivity Test', 'QC', 'Signal strength > -70dBm', 'Fail', 'Signal strength drops in metallic enclosure.', 'Charlie Davis', date('Y-m-d'), 'Marked for re-manufacturing'],
    ];

    $stmt = $conn->prepare("INSERT INTO testing_records (test_id, product_id, test_type, testing_department, testing_criteria, result, remarks, tester_name, testing_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($tests as $t) {
        $stmt->bind_param("ssssssssss", $t[0], $t[1], $t[2], $t[3], $t[4], $t[5], $t[6], $t[7], $t[8], $t[9]);
        $stmt->execute();
    }
    $stmt->close();
    echo "Sample testing records inserted.<br>";
} else {
    echo "Database already has data.<br>";
}
?>
