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
    $password = $_POST['password'];
    $specialty = $_POST['specialty'];
    $department = $_POST['department'];
    $phone = $_POST['phone'];
    $doctor_id = $_POST['doctor_id'];
    $dob = $_POST['dob'];

    if (!empty($name) && !empty($email) && !empty($password) && !empty($specialty) && !empty($department)) {
        $stmt = $pdo->prepare("SELECT * FROM doctors WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $existingDoctor = $stmt->fetch();

        if ($existingDoctor) {
            $error = "Email is already registered";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO doctors (name, email, password, specialty, department, phone, doctor_id, dob) VALUES (:name, :email, :password, :specialty, :department, :phone, :doctor_id, :dob)");
            $stmt->execute([
                'name' => $name, 
                'email' => $email, 
                'password' => $hashedPassword, 
                'specialty' => $specialty, 
                'department' => $department, 
                'phone' => $phone, 
                'doctor_id' => $doctor_id, 
                'dob' => $dob
            ]);

            $success = "Doctor account created successfully!";
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
    <title>Dashboard</title>
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
            width: 100%;
            height: full;
            padding: 20px 20px 30px 10px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-start;
            overflow-y: auto;
            overflow-x: hidden;
            user-select: none;
        }

        .containerTop {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .containerTop .subDivs {
            min-width: 300px;
            background-color: white;
            margin: 0px 5px 10px;
            padding: 10px;
            cursor: pointer;
            border-radius: 7px;
            border: 1px solid rgb(155, 155, 155)/50;
            box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
        }
        .containerTop .subDivs h3 {
            color: rgb(155, 155, 155);
            font-size: 15px;
            margin-bottom: 3px;
        }
        .containerTop .subDivs .flexSubDivs {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0px;
        }
        .containerTop .subDivs .flexSubDivs h4 {
            font-size: 25px;
            font-weight: 600;
            color: rgb(163, 163, 163);
        }
        .containerTop .subDivs .flexSubDivs .fa-solid {
            font-size: 25px;
            margin-right: 10px;
            font-weight: 700;
            color: rgb(163, 163, 163);
        }
        .containerTop .subDivs .progressBar {
            width: 100%;
            height: 7px;
            background-color: #f5f5f5;
            border-radius: 15px;
            overflow: hidden;
            margin-top: 5px;
        }
        .containerTop .subDivs .progressBar .progressGreen {
            width: 88%;
            height: 100%;
            border-radius: 20px;
            background-color: green;
        }
        .containerTop .subDivs .progressBar .progressOrange {
            width: 66%;
            height: 100%;
            border-radius: 20px;
            background-color: orange;
        }
        .containerTop .subDivs .progressBar .progressAmbar {
            width: 55%;
            height: 100%;
            border-radius: 20px;
            background-color: rgb(34, 0, 156);
        }
        .containerTop .subDivs .progressBar .progressBrown {
            width: 46%;
            height: 100%;
            border-radius: 20px;
            background-color: brown;
        }
        .containerTop .subDivs .progressBar .progressRed {
            width: 15%;
            height: 100%;
            border-radius: 20px;
            background-color: red;
        }
        .containerChart {
            width: 100%;
            min-height: 500px;
            background-color: white;
            margin: 30px 5px 70px;
            padding: 15px 20px;
            border-radius: 7px;
            cursor: pointer;
            border: 1px solid rgb(155, 155, 155)/50;
            box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
        }

        @media (min-width: 640px) {
            .sidebar {
                display: flex;
            }
            .main-content {
                width: 83.333333%;
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
                <a href="admin_dashboard.php" class="active">
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
                <div class='containerTop'>
                    <div class='subDivs'>
                        <h3>Trips Last Week</h3>
                        <div class='flexSubDivs'>
                            <h4>N 1,232,000</h4>
                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                        </div>
                        <div class='progressBar'>
                            <div class='progressGreen'></div>
                        </div>
                    </div>
                    <div class='subDivs'>
                        <h3>Total Trips Yesterday</h3>
                        <div class='flexSubDivs'>
                            <h4>N 232,000</h4>
                            <i class="fa-solid fa-clipboard-user"></i>
                        </div>
                        <div class='progressBar'>
                            <div class='progressOrange'></div>
                        </div>
                    </div>
                    <div class='subDivs'>
                        <h3>Cargo Trips</h3>
                        <div class='flexSubDivs'>
                            <h4>18</h4>
                            <i class="fa-solid fa-truck"></i>
                        </div>
                        <div class='progressBar'>
                            <div class='progressRed'></div>
                        </div>
                    </div>
                    <div class='subDivs'>
                        <h3>Passengers Last Week</h3>
                        <div class='flexSubDivs'>
                            <h4>600</h4>
                            <i class="fa-solid fa-person"></i>
                        </div>
                        <div class='progressBar'>
                            <div class='progressAmbar'></div>
                        </div>
                    </div>
                    <div class='subDivs'>
                        <h3>Total Cars Available</h3>
                        <div class='flexSubDivs'>
                            <h4>518</h4>
                            <i class="fa-solid fa-taxi"></i>
                        </div>
                        <div class='progressBar'>
                            <div class='progressBrown'></div>
                        </div>
                    </div>
                </div>
                <div class='containerChart'>
                    <canvas id="lineChart"></canvas>
                </div>
            </main>
        </div>
    </body>
    <script src="chart_script.js"></script>
</html>
