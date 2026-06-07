<?php
include_once('../dbconn.php');

if (isset($_GET['nid'])) {
    $nid = $_GET['nid'];

    $stmt = $conn->prepare("SELECT person.nid, person.name, doctor.specialist, 
                            person.mobile_no AS phone, person.gender, person.blood 
                            FROM doctor 
                            INNER JOIN person ON doctor.d_nid = person.nid
                            WHERE person.nid = ?");
    $stmt->bind_param("s", $nid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $specialist = $_POST['specialist'];
            $phone = $_POST['phone'];
            $gender_text = $_POST['gender'];
                switch ($gender_text) {
                    case 'Male':
                        $gender = 1;
                        break;
                    case 'Female':
                        $gender = 2;
                        break;
                    case 'Other':
                        $gender = 3;
                        break;
                    default:
                        $gender = 0; 
                }

            $blood = $_POST['blood'];

            $updatePerson = $conn->prepare("UPDATE person SET name = ?, mobile_no = ?, gender = ?, blood = ? WHERE nid = ?");
            $updatePerson->bind_param("sssss", $name, $phone, $gender, $blood, $nid);
            $updatePerson->execute();

            $updateDoctor = $conn->prepare("UPDATE doctor SET specialist = ? WHERE d_nid = ?");
            $updateDoctor->bind_param("ss", $specialist, $nid);
            $updateDoctor->execute();

            header("Location: manage_doctors.php");
            exit();
        }
    } else {
        echo "<div class='alert alert-danger'>Doctor not found.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f0f4f8;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease-in-out;
        }
        .card:hover {
            transform: scale(1.01);
        }
        .form-control {
            border-radius: 10px;
        }
        .btn-primary {
            border-radius: 10px;
            padding: 10px 25px;
        }
        .header-title {
            color: #0d6efd;
            font-weight: bold;
        }
    </style>
</head>
<body class="container py-5">

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card p-4">
                <div class="card-body">
                    <h3 class="text-center header-title mb-4">Edit Doctor Information</h3>

                    <a href="manage_doctors.php" class="btn btn-outline-secondary mb-4">⬅️ Back to Doctor Management</a>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="specialist" class="form-label">Specialist Field</label>
                            <input type="text" name="specialist" class="form-control" value="<?= htmlspecialchars($row['specialist']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Mobile Number</label>
                            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($row['phone']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="Male" <?= $row['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= $row['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                                <option value="Other" <?= $row['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="blood" class="form-label">Blood Group</label>
                            <select name="blood" class="form-select" required>
                                <?php
                                $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
                                foreach ($bloodGroups as $group) {
                                    $selected = ($row['blood'] === $group) ? 'selected' : '';
                                    echo "<option value='$group' $selected>$group</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">💾 Update Doctor</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
