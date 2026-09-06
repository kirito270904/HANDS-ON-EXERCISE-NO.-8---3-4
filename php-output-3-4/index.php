<?php
require_once 'includes/db.php';

$errors = [];
$success = "";

$full_name = $age = $gender = $email = $address = $contact_number = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_register'])) {

    $full_name      = trim($_POST['full_name'] ?? '');
    $age            = trim($_POST['age'] ?? '');
    $gender         = trim($_POST['gender'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $address        = trim($_POST['address'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');

    if ($full_name === "") {
        $errors['full_name'] = "Full name is required.";
    } elseif (!preg_match("/^[a-zA-Z\s\.\-]+$/", $full_name)) {
        $errors['full_name'] = "Full name must contain letters only.";
    }

    if ($age === "") {
        $errors['age'] = "Age is required.";
    } elseif (!ctype_digit($age) || (int)$age < 1 || (int)$age > 120) {
        $errors['age'] = "Enter a valid age (1-120).";
    }

    if ($gender === "" || !in_array($gender, ['Male', 'Female', 'Other'])) {
        $errors['gender'] = "Please select a gender.";
    }

    if ($email === "") {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Enter a valid email address.";
    }

    if ($address === "") {
        $errors['address'] = "Address is required.";
    }

    if ($contact_number === "") {
        $errors['contact_number'] = "Contact number is required.";
    } elseif (!preg_match("/^[0-9+\-\s]{7,15}$/", $contact_number)) {
        $errors['contact_number'] = "Enter a valid contact number.";
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO persons (full_name, age, gender, email, address, contact_number)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param($stmt, "sissss", $full_name, $age, $gender, $email, $address, $contact_number);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Record saved successfully!";
            $full_name = $age = $gender = $email = $address = $contact_number = "";
        } else {
            $errors['general'] = "Error saving record: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}

$result = mysqli_query($conn, "SELECT * FROM persons ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Registration Form - PHP Output #3 & #4</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">

    <h1>Person Registration</h1>
    <p class="subtitle">PHP Output #3 &amp; #4 — Form connected to database with live list</p>

    <div class="card">
        <h2>Registration Form</h2>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($errors['general']); ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php" novalidate>
            <div class="form-grid">

                <div class="form-group full">
                    <label>Full Name <span class="req">*</span></label>
                    <input type="text" name="full_name" placeholder="Juan Dela Cruz"
                           value="<?php echo htmlspecialchars($full_name); ?>">
                    <?php if (!empty($errors['full_name'])): ?>
                        <span class="error-text"><?php echo $errors['full_name']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Age <span class="req">*</span></label>
                    <input type="number" name="age" min="1" max="120" placeholder="e.g. 21"
                           value="<?php echo htmlspecialchars($age); ?>">
                    <?php if (!empty($errors['age'])): ?>
                        <span class="error-text"><?php echo $errors['age']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Gender <span class="req">*</span></label>
                    <select name="gender">
                        <option value=""> Select Gender </option>
                        <option value="Male"   <?php echo $gender === 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo $gender === 'Female' ? 'selected' : ''; ?>>Female</option>
                        <option value="Other"  <?php echo $gender === 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                    <?php if (!empty($errors['gender'])): ?>
                        <span class="error-text"><?php echo $errors['gender']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Email <span class="req">*</span></label>
                    <input type="email" name="email" placeholder="juan@example.com"
                           value="<?php echo htmlspecialchars($email); ?>">
                    <?php if (!empty($errors['email'])): ?>
                        <span class="error-text"><?php echo $errors['email']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Contact Number <span class="req">*</span></label>
                    <input type="tel" name="contact_number" placeholder="09XXXXXXXXX"
                           value="<?php echo htmlspecialchars($contact_number); ?>">
                    <?php if (!empty($errors['contact_number'])): ?>
                        <span class="error-text"><?php echo $errors['contact_number']; ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group full">
                    <label>Address <span class="req">*</span></label>
                    <input type="text" name="address" placeholder="Brgy., Municipality, Province"
                           value="<?php echo htmlspecialchars($address); ?>">
                    <?php if (!empty($errors['address'])): ?>
                        <span class="error-text"><?php echo $errors['address']; ?></span>
                    <?php endif; ?>
                </div>

            </div>

            <button type="submit" name="submit_register" class="btn">Submit Registration</button>
        </form>
    </div>

    <div class="card">
        <h2>List of Registered Persons</h2>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Contact No.</th>
                        <th>Date Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td data-label="#"><?php echo $row['id']; ?></td>
                            <td data-label="Full Name"><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td data-label="Age"><?php echo htmlspecialchars($row['age']); ?></td>
                            <td data-label="Gender"><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td data-label="Email"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td data-label="Address"><?php echo htmlspecialchars($row['address']); ?></td>
                            <td data-label="Contact No."><?php echo htmlspecialchars($row['contact_number']); ?></td>
                            <td data-label="Date Registered"><?php echo htmlspecialchars($row['date_registered']); ?></td>
                            <td class="actions" data-label="">
                                <a class="btn-small btn-edit" href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                                <a class="btn-small btn-delete" href="delete.php?id=<?php echo $row['id']; ?>"
                                   onclick="return confirm('Delete this record?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr class="empty-row"><td colspan="9">No registered persons yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>PHP Output #3 &amp; #4 — Saint Michael College of Caraga</footer>
</div>
</body>
</html>
