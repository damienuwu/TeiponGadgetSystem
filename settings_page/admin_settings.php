<?php
session_start(); // Start session

// Check if the admin is logged in
if (!isset($_SESSION['userID']) || !isset($_SESSION['username'])) {
    header("Location: ../login/login.php?error=Access denied");
    exit();
}

// Fetch admin session data
$adminID = $_SESSION['userID'];
$staffName = $_SESSION['username'];

require_once($_SERVER['DOCUMENT_ROOT'] . '/TeiponGadgetSystem/config/db_config.php');

// Fetch admin data from the database
$sql = "SELECT staffName, staffEmail,staffUsername FROM Staff WHERE adminID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $adminID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
} else {
    die("Admin details not found.");
}

// Handle form submission for updating admin details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newName = $_POST['name'];
    $newEmail = $_POST['email'];
    $newUsername = $_POST['username'];
    $newPassword = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    $error = "";

    // Email check
    $emailCheckSql = "SELECT staffEmail FROM Staff WHERE staffEmail = ? AND staffEmail != ?";
    $emailCheckStmt = $conn->prepare($emailCheckSql);
    $emailCheckStmt->bind_param("ss", $newEmail, $admin['staffEmail']);
    $emailCheckStmt->execute();
    $emailCheckResult = $emailCheckStmt->get_result();

    if ($emailCheckResult->num_rows > 0) {
        $error = "The email address is already registered.";
        header("Location: admin_settings.php?error=" . urlencode($error));
        exit();
    }

    // Username check
    $usernameCheckSql = "SELECT staffUsername FROM Staff WHERE staffUsername = ? AND staffUsername != ?";
    $usernameCheckStmt = $conn->prepare($usernameCheckSql);
    $usernameCheckStmt->bind_param("ss", $newUsername, $admin['staffUsername']);
    $usernameCheckStmt->execute();
    $usernameCheckResult = $usernameCheckStmt->get_result();

    if ($usernameCheckResult->num_rows > 0) {
        $error = "The username is already in use.";
        header("Location: admin_settings.php?error=" . urlencode($error));
        exit();
    }
    // Update admin details in the database
    if (!$error) {
        if ($newPassword) {
            $updateSql = "UPDATE Staff SET staffName = ?, staffEmail = ?, staffUsername = ?, staffPassword = ? WHERE adminID = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("ssssi", $newName, $newEmail, $newUsername, $newPassword, $adminID);
        } else {
            $updateSql = "UPDATE Staff SET staffName = ?, staffEmail = ?, staffUsername = ? WHERE adminID = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("sssi", $newName, $newEmail, $newUsername, $adminID);
        }


        if ($updateStmt->execute()) {
            $_SESSION['staffName'] = $newName; // Update session with the new admin name
            header("Location: admin_settings.php?success=Details updated successfully");
            exit();
        } else {
            $error = "Error updating details: " . $updateStmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Sidebar -->
    <?php include('../sidebar/admin_sidebar.php'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h1 class="mb-4">Admin Settings</h1>

            <!-- Success and Error Messages -->
            <?php if (isset($_GET['success'])) {
                echo '<div class="alert alert-success">' . htmlspecialchars($_GET['success']) . '</div>';
            } elseif (isset($_GET['error'])) {
                echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
            }
            ?>

            <!-- Admin Edit Form -->
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($admin['staffName']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($admin['staffEmail']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="username" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($admin['staffUsername']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">New Password (optional)</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                </div>
                <button type="submit" class="btn btn-primary">Update Details</button>
            </form>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Close database connection
$conn->close();
?>