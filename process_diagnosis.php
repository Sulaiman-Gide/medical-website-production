<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit;
}

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_POST['patient_id'];
    $diagnosis = $_POST['diagnosis'];
    $medicines = $_POST['medicines'];

    // Convert the medicines array to a comma-separated string
    $medicines_str = implode(',', $medicines);

    // Prepare the SQL statement
    $stmt = $pdo->prepare("INSERT INTO diagnostic (patient_id, diagnosis, medicines) VALUES (:patient_id, :diagnosis, :medicines)");

    // Bind parameters
    $stmt->bindParam(':patient_id', $patient_id);
    $stmt->bindParam(':diagnosis', $diagnosis);
    $stmt->bindParam(':medicines', $medicines_str);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: doctor_dashboard.php?success=1");
    } else {
        echo "Error: Could not save the diagnosis.";
    }
} else {
    echo "Invalid request method.";
}
?>
