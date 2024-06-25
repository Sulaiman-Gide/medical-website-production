<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $state_origin = $_POST['state_origin'];
    $disability = $_POST['disability'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $patient_id = $_POST['patient_id'];
    $dob = $_POST['dob'];

    if (!empty($name) && !empty($email) && !empty($state_origin) && !empty($disability) && !empty($gender)) {
        $stmt = $pdo->prepare("SELECT * FROM patients WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $existingPatient = $stmt->fetch();

        if ($existingPatient) {
            $error = "Email is already registered";
        } else {
            $stmt = $pdo->prepare("INSERT INTO patients (name, email, state_origin, disability, gender, phone, patient_id, dob) VALUES (:name, :email, :state_origin, :disability, :gender, :phone, :patient_id, :dob)");
            $stmt->execute([
                'name' => $name, 
                'email' => $email, 
                'state_origin' => $state_origin, 
                'disability' => $disability, 
                'gender' => $gender, 
                'phone' => $phone, 
                'patient_id' => $patient_id, 
                'dob' => $dob
            ]);

            $success = "Patient record created successfully!";
        }
    } else {
        $error = "All fields are required";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Patient</title>
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
            user-select: none;
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
                    <h1>Manage patients</h1>
                </a>
                <a href="admin_add_patient.php" class="active">
                    <i class="fa-solid fa-hospital-user icon"></i>
                    <h1>Add Patient</h1>
                </a>
                <span></span>
                <a href="admin_finance.php">
                    <i class="fa-solid fa-notes-medical icon"></i>
                    <h1>Finance Records</h1>
                </a>
                <a href="admin_patient_records.php">
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
                <p>Welcome, Use the form below to create a patient record.</p>
                <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
                <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
                <form class="form" method="POST">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" required>
                    </div>
                    <div class="form-group">
                        <label for="state_origin">State of origin</label>
                        <input type="text" name="state_origin" id="state_origin" required>
                    </div>
                    <div class="form-group">
                        <label for="disability">Disability</label>
                        <select name="disability" id="disability" required>
                            <option value="">Select Disability Status</option>
                            <option value="True">True</option>
                            <option value="False">False</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select name="gender" id="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" name="phone" id="phone">
                    </div>
                    <div class="form-group">
                        <label for="patient_id">Patient ID</label>
                        <input type="text" id="patient_id" name="patient_id" placeholder="Doctor ID" value="<?php echo 'Patient ' . rand(1000, 9999); ?>" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" name="dob" id="dob">
                    </div>
                    <button class="btn" type="submit">Register Patient</button>
                </form>
            </main>
        </div>
    </body>
</html>
