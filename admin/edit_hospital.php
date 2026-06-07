<?php
include_once('../dbconn.php');
session_start();

if (isset($_GET['hospital_id'])) {
    $hospital_id = $_GET['hospital_id'];

    // Prevent SQL Injection (use prepared statement if needed)
    $stmt = $conn->prepare("SELECT * FROM hospital WHERE hospital_id = ?");
    $stmt->bind_param("s", $hospital_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Hospital not found.";
        exit();
    }

    $hospital = $result->fetch_assoc();
} else {
    header("Location: manage_hospitals.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hospital_name = trim($_POST['hospital_name']);
    $numberof_ward = intval($_POST['numberof_ward']);
    $numberof_cabin = intval($_POST['numberof_cabin']);
    $docreg = trim($_POST['docreg']);

    $update_query = "UPDATE hospital 
                     SET hospital_name = ?, numberof_ward = ?, numberof_cabin = ?, docreg = ? 
                     WHERE hospital_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("siiss", $hospital_name, $numberof_ward, $numberof_cabin, $docreg, $hospital_id);

    if ($stmt->execute()) {
        $success = "✅ Hospital information updated successfully.";
        // Refresh data
        $hospital['hospital_name'] = $hospital_name;
        $hospital['numberof_ward'] = $numberof_ward;
        $hospital['numberof_cabin'] = $numberof_cabin;
        $hospital['docreg'] = $docreg;
    } else {
        $error = "❌ Error updating hospital details: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Hospital</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Edit Hospital Information</h3>
        </div>
        <div class="card-body">

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="hospital_name" class="form-label">Hospital Name</label>
                    <input type="text" class="form-control" id="hospital_name" name="hospital_name"
                           value="<?= htmlspecialchars($hospital['hospital_name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="numberof_ward" class="form-label">Number of Wards</label>
                    <input type="number" class="form-control" id="numberof_ward" name="numberof_ward"
                           value="<?= htmlspecialchars($hospital['numberof_ward']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="numberof_cabin" class="form-label">Number of Cabins</label>
                    <input type="number" class="form-control" id="numberof_cabin" name="numberof_cabin"
                           value="<?= htmlspecialchars($hospital['numberof_cabin']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="docreg" class="form-label">Doctor Registration</label>
                    <input type="text" class="form-control" id="docreg" name="docreg"
                           value="<?= htmlspecialchars($hospital['docreg']) ?>" required>
                </div>

                <button type="submit" class="btn btn-success">Update Hospital</button>
                <a href="manage_hospitals.php" class="btn btn-secondary ms-2">Back to Hospital List</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
