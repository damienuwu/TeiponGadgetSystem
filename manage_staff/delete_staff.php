<?php
session_start(); // Start session

// Check if the admin is logged in
if (!isset($_SESSION['adminID']) || !isset($_SESSION['adminName'])) {
    // Redirect to the login page if not logged in
    header("Location: ../admin_login/admin_login.php?error=Please login to access the dashboard");
    exit();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/TeiponGadgetSystem/config/db_config.php');

// Get the staff ID from the URL
if (isset($_GET['id'])) {
    $staffID = $_GET['id'];

    // Delete the staff member from the database
    $deleteSql = "DELETE FROM Staff WHERE staffID = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $staffID);

    if ($stmt->execute()) {
        header("Location: manage_staff.php?success=Staff member deleted successfully");
    } else {
        header("Location: manage_staff.php?error=Failed to delete staff member");
    }
} else {
    header("Location: manage_staff.php?error=Invalid staff ID");
}

$conn->close();
?>
