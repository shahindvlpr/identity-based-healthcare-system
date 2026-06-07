<?php
include_once('../dbconn.php');

if (isset($_GET['nid'])) {
    $nid = $_GET['nid'];

    $query = $conn->query("
        SELECT person.nid, person.name, person.mobile_no AS phone, person.gender, person.blood, patient.p_nid 
        FROM patient
        INNER JOIN person ON patient.p_nid = person.nid
        WHERE person.nid = '$nid'
    ");

    if ($query->num_rows > 0) {
        $patient = $query->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger'>Patient not found!</div>";
        exit;
    }

    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $gender = $_POST['gender'];
        $blood = $_POST['blood'];

        $update_person = $conn->query("
            UPDATE person SET name='$name', mobile_no='$phone', gender='$gender', blood='$blood' WHERE nid='$nid'
        ");

        if ($update_person) {
            echo "<div class='alert alert-success'>Patient details updated successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error updating details: " . $conn->error . "</div>";
        }
    }
} else {
    echo "<div class='alert alert-warning'>No patient ID specified!</div>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Patient</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f0f4f8;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
        }
    </style>
</head>
<body class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card p-4">
                <h3 class="mb-4 text-center text-primary">Edit Patient</h3>

                <a href="manage_patients.php" class="btn btn-outline-secondary mb-3">← Back to Patients List</a>

                <form method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($patient['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Mobile Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($patient['phone']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="1" <?= $patient['gender'] == '1' ? 'selected' : '' ?>>Male</option>
                            <option value="2" <?= $patient['gender'] == '2' ? 'selected' : '' ?>>Female</option>
                            <option value="3" <?= $patient['gender'] == '3' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="blood" class="form-label">Blood Group</label>
                        <input type="text" class="form-control" id="blood" name="blood" value="<?= htmlspecialchars($patient['blood']) ?>" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary w-100">Update Patient</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
