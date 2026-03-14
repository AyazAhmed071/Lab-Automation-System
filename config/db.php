<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = mysqli_connect("localhost", "root", "", "ayaz_ahmed");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

define('BASE_URL', 'http://localhost/lab_automation_system/');
