<?php
session_start();
if (!isset($_SESSION['doctor_id'])) {
    header("Location: login.php");
    exit;
}

require 'config.php';

// Fetch all patient records
$stmt = $pdo->query("SELECT * FROM patients");
$patients = $stmt->fetchAll();

// Fetch all medicines
$stmt = $pdo->query("SELECT * FROM medicines");
$medicines = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            height: 100vh;
            overflow: hidden;
            font-family: sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background-color: #f9fafb;
        }
        .container {
            display: flex;
            height: 100%;
        }
        .sidebar {
            display: none;
            flex-direction: column;
            width: 13rem;
            height: 100%;
            margin: 0.3rem;
            border-radius: 0.375rem;
            background-color: white;
            overflow-y: auto;
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
            margin: 0.75rem 0;
        }
        .sidebar h1 {
            font-weight: 700;
            font-size: 1rem;
            text-align: center;
            margin: 0.75rem 0;
        }
        .sidebar span {
            display: block;
            border: 1px solid #d1d5db;
            width: 100%;
            margin: 1rem 0;
        }
        .sidebar a, .sidebar div {
            display: flex;
            align-items: center;
            padding: 0.4rem 0.5rem;
            border-radius: 0.375rem;
            margin-top: 1rem;
            border: 1px solid #e5e7eb;
            color: #4b5563;
            text-decoration: none;
            cursor: pointer;
        }
        .sidebar a:hover, .sidebar div:hover {
            background-color: #1f2937;
            color: white;
        }
        .sidebar a.active {
            background-color: #1f2937;
            color: white;
        }
        .sidebar a .icon, .sidebar div .icon {
            font-size: 1.1rem;
            margin-right: 0.75rem;
        }
        .main-content {
            flex-grow: 1;
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }
        .main-content p {
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }
        .form {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
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
        .diagnose-btn {
            background-color: #3b82f6;
            color: white;
            padding: 0.375rem 0.75rem;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .diagnose-btn:hover {
            background-color: #2563eb;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            width: 90%;
            max-width: 600px;
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
            margin-top: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 0.1rem;
            box-sizing: border-box;
        }
        .medicine-container {
            padding: 1.2rem .7rem 0rem;
            width: 100%;
        }
        .btn {
            width: 30%;
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
        .add-medicine-btn {
            width: 80%;
            background-color: #10b981;
            color: white;
            padding: 0.6rem 0.75rem;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 0rem auto .3rem;
        }
        .add-medicine-btn:hover {
            background-color: #059669;
        }
        .remove-medicine-btn {
            border: 1px solid #ef4444;
            color: #ef4444;
            font-size: .8rem;
            padding: 0.375rem 0.75rem;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 0.4rem;
            margin-left: 0.5rem;
        }
        .remove-medicine-btn:hover {
            border: 1px solid red;
        }
        .close-btn {
            background-color: #ef4444;
            color: white;
            padding: 0.375rem 0.75rem;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            float: right;
        }
        .close-btn:hover {
            background-color: #dc2626;
        }
    </style>
    <script>
        function openModal(patientId) {
            document.getElementById('modal').style.display = 'flex';
            document.getElementById('patient-id').value = patientId;
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        function addMedicine() {
            const medicineContainer = document.getElementById('medicine-container');
            const medicineName = document.createElement('span');
            const removeButton = document.createElement('button');

            // Get the selected medicine name
            const medicineSelect = document.querySelector('select[name="medicines[]"]');
            const selectedMedicineName = medicineSelect.options[medicineSelect.selectedIndex].text;

            medicineName.textContent = selectedMedicineName;

            removeButton.type = 'button';
            removeButton.className = 'remove-medicine-btn';
            removeButton.textContent = 'Remove';
            removeButton.onclick = function() {
                medicineContainer.removeChild(medicineName.parentNode);
            };

            const div = document.createElement('div');
            div.className = 'form-group';
            div.appendChild(medicineName);
            div.appendChild(removeButton);
            medicineContainer.appendChild(div);
        }

    </script>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Doctor Panel</h2>
            <span></span>
            <a href="doctor_dashboard.php" class="active">
                <i class="fa-solid fa-bed-pulse icon"></i>
                <h1>Diagnose Patient</h1>
            </a>
            <span></span>
            <a href="admin_patient_records.php">
                <i class="fa-solid fa-hospital-user icon"></i>
                <h1>Patient Records</h1>
            </a>
            <a href="index.html">
                <i class="fa-solid fa-house icon"></i>
                <h1>Home</h1>
            </a>
            <a href="contact.html">
                <i class="fa-solid fa-phone-volume icon"></i>
                <h1>Contact Us</h1>
            </a>
            <span></span>
            <a href="logout.php">
                <i class="fa-solid fa-right-from-bracket icon"></i>
                <h1>Logout</h1>
            </a>
        </div>
        <main class="main-content">
            <p>Select a patient to diagnose and send billing information to the pharmacy.</p>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>State of Origin</th>
                        <th>Disability</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>DOB</th>
                        <th>Actions</th>
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
                        <td><?php echo htmlspecialchars($patient['dob']); ?></td>
                        <td>
                            <button class="diagnose-btn" onclick="openModal(<?php echo $patient['id']; ?>)">Diagnose</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <h2>Diagnose Patient</h2>
            <form action="process_diagnosis.php" method="POST" class="form">
                <input type="hidden" id="patient-id" name="patient_id">
                <div class="form-group">
                    <label for="diagnosis">Diagnosis</label>
                    <input type="text" id="diagnosis" name="diagnosis" required>
                </div>
                <div class="form-group">
                    <label for="medicine">Select Medicine</label>
                    <select name="medicines[]" required>
                        <?php foreach ($medicines as $medicine): ?>
                        <option value="<?php echo htmlspecialchars($medicine['id']); ?>"><?php echo htmlspecialchars($medicine['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="medicine-container" id="medicine-container"></div>
                </div>
                <button type="button" class="add-medicine-btn" onclick="addMedicine()">Add Medicine</button>
                <button type="submit" class="btn">Submit</button>
                <button type="button" class="close-btn" onclick="closeModal()">Close</button>
            </form>
        </div>
    </div>
</body>
</html>
