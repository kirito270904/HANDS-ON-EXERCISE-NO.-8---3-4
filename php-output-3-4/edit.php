<?php
require_once 'includes/db.php';

$errors = [];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

// ---- Handle update submission ----
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_update'])) {

    $full_name      = trim($_POST['full_name'] ?? '');
    $age            = trim($_POST['age'] ?? '');
    $gender         = trim($_POST['gender'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $address        = trim($_POST['address'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');

    if ($full_name === "" || !preg_match("/^[a-zA-Z\s\.\-]+$/", $full_name)) {
        $errors['full_name'] = "Enter a valid full name.";
    }
    if ($age === "" || !ctype_digit($age) || (int)$age < 1 || (int)$age > 120) {
        $errors['age'] = "Enter a valid age (1-120).";
    }
    if ($gender === "" || !in_array($gender, ['Male', 'Female', 'Other'])) {
        $errors['gender'] = "Please select a gender.";
    }
    if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Enter a valid email address.";
    }
    if ($address === "") {
        $errors['address'] = "Address is required.";
    }
    if ($contact_number === "" || !preg_match("/^[0-9+\-\s]{7,15}$/", $contact_number)) {
        $errors['contact_number'] = "Enter a valid contact number.";
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE persons SET full_name=?, age=?, gender=?, email=?, address=?, contact_number=? WHERE id=?"
        );
        mysqli_stmt_bind_param($stmt, "sissssi", $full_name, $age, $gender, $email, $address, $contact_number, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: index.php?updated=1");
        exit;
    }
}

// ---- Fetch existing record ----
$stmt = mysqli_prepare($conn, "SELECT * FROM persons WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$person = mysqli_fetch_assoc($res);

if (!$person) {
    header("Location: index.php");
    exit;
}

// Use posted values on validation error, otherwise DB values
$full_name      = $_POST['full_name'] ?? $person['full_name'];
$age            = $_POST['age'] ?? $person['age'];
$gender         = $_POST['gender'] ?? $person['gender'];
$email          = $_POST['email'] ?? $person['email'];
$address        = $_POST['address'] ?? $person['address'];
$contact_number = $_POST['contact_number'] ?? $person['contact_number'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Record - PHP Output #3 & #4</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">

    <h1>Edit Registered Person</h1>
    <p class="subtitle">Update the record below</p>

    <div class="card">
        <form method="POST" action="edit.php?id=<?php echo $id; ?>" novalidate>
            <div class="form-grid">

                <div class="form-group full">
                    <label>Full Name <span class="req">*</span></label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>">
                    <?php if (!empty($errors['full_name'])): ?><span class="error-text"><?php echo $errors['full_name']; ?></span><?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Age <span class="req">*</span></label>
                    <input type="number" name="age" min="1" max="120" value="<?php echo htmlspecialchars($age); ?>">
                    <?php if (!empty($errors['age'])): ?><span class="error-text"><?php echo $errors['age']; ?></span><?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Gender <span class="req">*</span></label>
                    <select name="gender">
                        <option value="">-- Select Gender --</option>
                        <option value="Male"   <?php echo $gender === 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo $gender === 'Female' ? 'selected' : ''; ?>>Female</option>
                        <option value="Other"  <?php echo $gender === 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                    <?php if (!empty($errors['gender'])): ?><span class="error-text"><?php echo $errors['gender']; ?></span><?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Email <span class="req">*</span></label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <?php if (!empty($errors['email'])): ?><span class="error-text"><?php echo $errors['email']; ?></span><?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Contact Number <span class="req">*</span></label>
                    <input type="tel" name="contact_number" value="<?php echo htmlspecialchars($contact_number); ?>">
                    <?php if (!empty($errors['contact_number'])): ?><span class="error-text"><?php echo $errors['contact_number']; ?></span><?php endif; ?>
                </div>

                <div class="form-group full">
                    <label>Address <span class="req">*</span></label>
                    <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>">
                    <?php if (!empty($errors['address'])): ?><span class="error-text"><?php echo $errors['address']; ?></span><?php endif; ?>
                </div>

            </div>

            <button type="submit" name="submit_update" class="btn">Update Record</button>
            <a href="index.php" class="btn-small btn-cancel" style="padding:11px 24px; display:inline-block; margin-top:20px;">Cancel</a>
        </form>
    </div>

    <footer>PHP Output #3 &amp; #4 — Saint Michael College of Caraga</footer>
</div>
</body>
</html>
