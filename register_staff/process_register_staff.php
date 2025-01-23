<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/TeiponGadgetSystem/config/db_config.php');

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $staffName = trim($_POST['staffName'] ?? '');
  $staffUsername = trim($_POST['staffUsername'] ?? '');
  $staffEmail = trim($_POST['staffEmail'] ?? '');
  $staffPassword = $_POST['staffPassword'] ?? '';
  $role = $_POST['role'] ?? '';

  // Validate required fields
  if (empty($staffName) || empty($staffUsername) || empty($staffEmail) || empty($staffPassword)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit();
  }

  // Validate email format
  if (!filter_var($staffEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
    exit();
  }

  // Check if username or email already exists
  $checkSql = "SELECT staffUsername, staffEmail FROM Staff WHERE staffUsername = ? OR staffEmail = ?";
  $stmt = $conn->prepare($checkSql);
  $stmt->bind_param("ss", $staffUsername, $staffEmail);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $existingStaff = $result->fetch_assoc();
    if ($existingStaff['staffUsername'] === $staffUsername) {
      echo json_encode(['success' => false, 'message' => 'Username already exists.']);
    } elseif ($existingStaff['staffEmail'] === $staffEmail) {
      echo json_encode(['success' => false, 'message' => 'Email already exists.']);
    }
    exit();
  }

  // Hash the password securely
  $hashedPassword = password_hash($staffPassword, PASSWORD_DEFAULT);

  // Determine adminID based on role
  $adminID = null; // Default to NULL
  if ($role === 'admin') {
    $adminID = $_SESSION['adminID'] ?? null; // Use logged-in admin's ID, if available
    if (!$adminID) {
      echo json_encode(['success' => false, 'message' => 'Invalid admin session.']);
      exit();
    }
  }

  // Insert into the database
  $sql = "INSERT INTO Staff (staffName, staffUsername, staffEmail, staffPassword, adminID) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssssi", $staffName, $staffUsername, $staffEmail, $hashedPassword, $adminID);

  if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Staff registered successfully.']);
  } else {
    echo json_encode(['success' => false, 'message' => 'Failed to register staff.']);
  }
  exit();
}

$conn->close();
