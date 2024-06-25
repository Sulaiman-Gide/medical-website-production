<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

require 'config.php';

// Delete patient record if delete request is made
if (isset($_POST['delete'])) {
    $patient_id = $_POST['patient_id'];
    $stmt = $pdo->prepare("DELETE FROM patients WHERE patient_id = :patient_id");
    $stmt->execute(['patient_id' => $patient_id]);
    $success = "Patient record deleted successfully!";
}

// Fetch all patient records
$stmt = $pdo->query("SELECT * FROM patients");
$patients = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Records</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            height: 100vh;
            overflow: hidden;
            font-family: sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .container {
            display: flex;
            overflow-y: hidden;
            background-color: #f9fafb;
            
            height: 100%;
        }

        .sidebar {
            display: none;
            flex-direction: column;
            width: 13rem;
            height: 100%;
            margin-left: 0.3rem;
            margin-top: 0.3rem;
            margin-bottom: 0.3rem;
            border-radius: 0.375rem;
            background-color: white;
            overflow-y: scroll;
            padding: 0.5rem 0.7rem;
            border: 1px solid #e5e7eb;
        }

        .sidebar::-webkit-scrollbar {
            display: none;
        }
        .sidebar h2 {
            font-weight: 800;
            font-size: 1.52rem;
            text-align: center;
            margin-top: 0.75rem;
            margin-bottom: 0.5rem;
        }
        .sidebar h1 {
            font-weight: 800;
            font-size: 1rem;
            text-align: center;
            margin-top: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .sidebar span {
            display: block;
            border: 1px solid #d1d5db;
            width: 100%;
            margin: 1rem 0;
        }

        .sidebar a,
        .sidebar div {
            display: flex;
            justify-content: flex-start;
            align-items: center;
           
            cursor: pointer;
            padding: 0.4rem 0.5rem;
            border-radius: 0.375rem;
            margin-top: 1rem;
            border: 1px solid #e5e7eb;
            color: #4b5563;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar div:hover {
            background-color: #1f2937;
            color: white;
        }

        .sidebar a.active {
            background-color: #1f2937;
            color: white;
        }

        .sidebar a:hover .icon,
        .sidebar div:hover .icon {
            color: white;
        }

        .sidebar a .icon,
        .sidebar div .icon {
            font-size: 1.1rem;
            margin-right: 0.75rem;
        }

        .sidebar a h1,
        .sidebar div h1 {
            font-size: 1.125rem;
            font-weight: 600;
        }

        .main-content {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            box-sizing: border-box;
        }

        .main-content p {
            font-size: 1rem;
            margin-bottom: 1.5rem;
            text-align: start;
        }

        .form {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .form-group {
            width: 100%;
            margin-bottom: 1rem;
        }

        label {
            font-weight: bold;
            color: #555;
        }

        input, select {
            width: 100%;
            padding: 0.75rem;
            margin-top: .8rem;
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            box-sizing: border-box;
        }

        .btn {
            background-color: #3b82f6;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #2563eb;
        }

        a {
            display: block;
            margin-top: 1rem;
            text-align: center;
            color: #3b82f6;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #2563eb;
        }
        @media (min-width: 640px) {
            .sidebar {
                display: flex;
            }
            .main-content {
                width: 83.333333%;
            }
            .form-group {
                width: 48%;
            }
        }

        @media (min-width: 768px) {
            .sidebar {
                width: 20%;
            }
            .main-content {
                width: 80%;
            }
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }

        .delete-btn {
            background-color: #ef4444;
            color: white;
            padding: 0.375rem 0.75rem;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .delete-btn:hover {
            background-color: #dc2626;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <span></span>
            <a href="admin_dashboard.php">
                <i class="fa-solid fa-chart-line icon"></i>
                <h1>Dashboard</h1>
            </a>
            <a href="admin_manage_doctor.php">
                <i class="fa-solid fa-stethoscope icon"></i>
                <h1>Manage Doctors</h1>
            </a>
            <a href="admin_add_patient.php">
                <i class="fa-solid fa-hospital-user icon"></i>
                <h1>Add Patient</h1>
            </a>
            <span></span>
            <a href="admin_finance.php">
                <i class="fa-solid fa-notes-medical icon"></i>
                <h1>Finance Records</h1>
            </a>
            <a href="admin_patient_records.php" class="active">
                <i class="fa-solid fa-hospital-user icon"></i>
                <h1>Patient Records</h1>
            </a>
            <a href="index.html">
                <i class="fa-solid fa-house icon"></i>
                <h1>Home</h1>
            </a>
            <span></span>
            <a href="logout.php">
                <i class="fa-solid fa-right-from-bracket icon"></i>
                <h1>Logout</h1>
            </a>
        </div>
        <main class="main-content">
            <h1>Patient Records</h1>
            <?php if (isset($success)): ?>
                <p style="color: green;"><?php echo $success; ?></p>
            <?php endif; ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>State of Origin</th>
                        <th>Disability</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Patient ID</th>
                        <th>Date of Birth</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($patients as $patient): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($patient['name']); ?></td>
                            <td><?php echo htmlspecialchars($patient['email']); ?></td>
                            <td><?php echo htmlspecialchars($patient['state_origin']); ?></td>
                            <td><?php echo htmlspecialchars($patient['disability']); ?></td>
                            <td><?php echo htmlspecialchars($patient['gender']); ?></td>
                            <td><?php echo htmlspecialchars($patient['phone']); ?></td>
                            <td><?php echo htmlspecialchars($patient['patient_id']); ?></td>
                            <td><?php echo htmlspecialchars($patient['dob']); ?></td>
                            <td>
                                <form method="POST" action="admin_patient_records.php" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                    <input type="hidden" name="patient_id" value="<?php echo $patient['patient_id']; ?>">
                                    <button type="submit" name="delete" class="delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
