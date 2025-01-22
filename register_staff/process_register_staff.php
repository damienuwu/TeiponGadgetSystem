<?php
session_start(); // Start session to access logged-in admin details

require_once($_SERVER['DOCUMENT_ROOT'] . '/TeiponGadgetSystem/config/db_config.php');

// Ensure the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Check if admin is logged in
  if (!isset($_SESSION['adminID']) && $_POST['role'] === 'admin') {
    header("Location: ../login/login.php?error=Access denied");
    exit();
  }

  $adminID = isset($_SESSION['adminID']) ? $_SESSION['adminID'] : NULL; // Get adminID from session if logged in, else set to null

  // Capture form data
  $staffName = trim($_POST['staffName']);
  $staffUsername = trim($_POST['staffUsername']);
  $staffEmail = trim($_POST['staffEmail']);
  $staffPassword = $_POST['staffPassword'];
  $role = $_POST['role']; // Role from form (either 'admin' or 'staff')

  // Validate required fields
  if (empty($staffName) || empty($staffUsername) || empty($staffEmail) || empty($staffPassword)) {
    header("Location: register_staff.php?error=All fields are required");
    exit();
  }

  // Check if email is valid
  if (!filter_var($staffEmail, FILTER_VALIDATE_EMAIL)) {
    header("Location: register_staff.php?error=Invalid email address");
    exit();
  }

  // Check if username or email already exists
  $checkSql = "SELECT * FROM Staff WHERE staffUsername = ? OR staffEmail = ?";
  $stmt = $conn->prepare($checkSql);
  $stmt->bind_param("ss", $staffUsername, $staffEmail);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // Redirect back if username or email already exists
    header("Location: register_staff.php?error=Staff already exists");
    exit();
  }

  // Hash the password for security
  $hashedPassword = password_hash($staffPassword, PASSWORD_DEFAULT);

  // Handle adminID for insertion (if role is 'admin', use the adminID; otherwise, NULL)
  $adminIDToInsert = ($role === 'admin') ? $adminID : NULL;

  // Insert the new staff record into the database with the appropriate adminID
  $sql = "INSERT INTO Staff (staffName, staffUsername, staffEmail, staffPassword, adminID) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);

  // Bind parameters and ensure we pass by reference
  $stmt->bind_param("ssssi", $staffName, $staffUsername, $staffEmail, $hashedPassword, $adminIDToInsert);
  if ($stmt->execute()) {
    // Redirect to register_staff.php with a success message
    header("Location: register_staff.php?success=1");
    exit();
  } else {
    // Redirect to register_staff.php with an error message if query fails
    header("Location: register_staff.php?error=Failed to register staff");
    exit();
  }
}

$conn->close();
