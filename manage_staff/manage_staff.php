<?php
session_start(); // Start session

// Check if the admin is logged in
if (!isset($_SESSION['adminID']) || !isset($_SESSION['adminName'])) {
    // Redirect to the login page if not logged in
    header("Location: ../admin_login/admin_login.php?error=Please login to access the dashboard");
    exit();
}

$adminName = $_SESSION['adminName']; // Get the admin's name from the session

require_once($_SERVER['DOCUMENT_ROOT'] . '/TeiponGadgetSystem/config/db_config.php');

// Fetch all staff from the Staff table
$sql = "SELECT * FROM Staff";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php include('../sidebar/admin_sidebar.php'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h1 class="mb-4">Manage Staff</h1>
            <!-- Success or error message -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>
            <!-- Staff Table -->
            <table class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if there are staff records in the database
                    if ($result->num_rows > 0) {
                        // Output data for each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['staffID'] . "</td>"; // Assuming staffID is the primary key
                            echo "<td>" . htmlspecialchars($row['staffName']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['staffUsername']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['staffEmail']) . "</td>";
                            echo "<td>
                    <a href='edit_staff.php?id=" . $row['staffID'] . "' class='btn btn-sm btn-warning'>Edit</a>
                    <a href='delete_staff.php?id=" . $row['staffID'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this staff member?\")'>Delete</a>
                    </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No staff members found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Close database connection
$conn->close();
?>