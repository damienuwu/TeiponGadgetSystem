<?php
session_start(); // Start session

// Check if the admin is logged in
if (!(isset($_SESSION['userID']))) {
    // Redirect to the login page if neither is logged in
    header("Location: ../login/login.php?error=Access denied");
    exit();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/TeiponGadgetSystem/config/db_config.php');

// Fetch customer data if id is set
if (isset($_GET['id'])) {
    $customerID = $_GET['id'];
    $sql = "SELECT * FROM Customer WHERE customerID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $customer = $result->fetch_assoc();
    } else {
        echo "Customer not found!";
        exit();
    }
}

// Update customer details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phoneNumber'];
    $state = $_POST['state'];
    $postalCode = $_POST['postalCode'];
    $city = $_POST['city'];
    $address = $_POST['address'];

    $emailCheckSql = "SELECT customerEmail FROM Customer WHERE customerEmail = ? AND customerID != ?";
    $checkEmailStmt = $conn->prepare($emailCheckSql);
    $checkEmailStmt->bind_param("si", $email, $customerID);
    $checkEmailStmt->execute();
    $emailCheckResult = $checkEmailStmt->get_result();
    if ($emailCheckResult->num_rows > 0) {
        // Email is already in use by another customer
        $error = "The email address is already registered.";
    } else {
        $updateSql = "UPDATE Customer 
                      SET customerName = ?, customerEmail = ?, customerPhoneNumber = ?, 
                          customerState = ?, customerPostalCode = ?, customerCity = ?, 
                          customerAddress = ?
                          WHERE customerID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ssssissi", $name, $email, $phone, $state, $postalCode, $city, $address, $customerID);

        if ($updateStmt->execute()) {
            header("Location: manage_customer.php?success=Customer details updated successfully");
            exit();
        } else {
            $error = "Error updating customer: " . $updateStmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <?php include('../sidebar/admin_sidebar.php'); ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h1 class="mb-4">Edit Customer</h1>
            <!-- Display error message -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <!-- Edit Form -->
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($customer['customerName']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($customer['customerEmail']); ?>" required>
                    <div class="error-message" id="emailError"></div>
                </div>
                <div class="mb-3">
                    <label for="phoneNumber" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Phone" value="<?php echo htmlspecialchars(substr($customer['customerPhoneNumber'], offset: 0)); ?>" required oninput="validatePhoneNumber(event)">
                    <div class="error-message" id="phoneNumberError"></div>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="<?php echo htmlspecialchars($customer['customerAddress']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="state" class="form-label">State</label>
                    <select class="form-select" id="state" name="state" required data-saved-state="<?php echo htmlspecialchars($customer['customerState']); ?>">
                        <option value="">Select State</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="city" class="form-label">City</label>
                    <select class="form-select" id="city" name="city" required data-saved-city="<?php echo htmlspecialchars($customer['customerCity']); ?>">
                        <option value="">Select City</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="postalCode" class="form-label">Postal Code</label>
                    <select class="form-select" id="postalCode" name="postalCode" required data-saved-postal="<?php echo htmlspecialchars($customer['customerPostalCode']); ?>">
                        <option value="">Select Postal Code</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="state-city-postal.js"></script>
    <script>
        function validatePhoneNumber(event) {
            const input = event.target;
            const prefix = "+60";
            let phoneNumber = input.value;
            const phoneNumberError = document.getElementById('phoneNumberError');
            const cursorPosition = input.selectionStart;
            const numberPart = phoneNumber.substring(3);

            phoneNumber = phoneNumber.replace(/[^\d+]/g, '');

            // Ensure the phone number starts with the prefix
            if (!phoneNumber.startsWith(prefix)) {
                phoneNumber = prefix + phoneNumber.replace(/^\+*\d*/, '');
            }

            if (numberPart.length > 10) {
                phoneNumber = prefix + numberPart.substring(0, 10);
            }

            // Set the value so the user can only edit the number after the prefix
            input.value = phoneNumber;

            // Restore cursor position after prefix if user wasn't editing prefix
            if (cursorPosition > 3) {
                input.setSelectionRange(cursorPosition, cursorPosition);
            } else {
                // If user was trying to edit prefix, move cursor after prefix
                input.setSelectionRange(3, 3);
            }

            // Validate number length and show appropriate message
            if (numberPart.length === 0) {
                phoneNumberError.textContent = "Please enter a phone number";
                phoneNumberError.style.color = "red";
                input.classList.add('is-invalid');
            } else if (numberPart.length < 9) {
                phoneNumberError.textContent = "Phone number must be at least 9 digits";
                phoneNumberError.style.color = "red";
                input.classList.add('is-invalid');
            } else if (numberPart.length > 10) {
                phoneNumberError.textContent = "Phone number cannot exceed 10 digits";
                phoneNumberError.style.color = "red";
                input.classList.add('is-invalid');
            } else {
                phoneNumberError.textContent = "Valid phone number";
                phoneNumberError.style.color = "green";
                input.classList.remove('is-invalid');
            }
        }
    </script>
    <script>
        document.getElementById('email').addEventListener('input', function () {
            const emailInput = this.value.trim();
            const errorDiv = document.getElementById('emailError');
            const emailField = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;  // Basic email format

            // Clear previous error message if input is empty
            if (emailInput.length === 0) {
                errorDiv.textContent = "";
                emailField.classList.remove('is-invalid');
            }
            // Check email format
            else if (!emailRegex.test(emailInput)) {
                errorDiv.textContent = "Please enter a valid email address.";
                errorDiv.style.color = "red";
                emailField.classList.add('is-invalid');
            }
            // If the email format is valid, proceed to check if it's already taken
            else {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "../register/check_register.php", true);  // Reusing check_register.php
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const response = xhr.responseText.trim();
                        console.log("Server response:", response);  // Add this line for debugging

                        // Handle server response: 'taken' or 'available'
                        if (response === "taken") {
                            errorDiv.textContent = "Email is already registered.";
                            errorDiv.style.color = "red";
                            emailField.classList.add('is-invalid');
                        } else if (response === "available") {
                            errorDiv.textContent = "Email is available.";
                            errorDiv.style.color = "green";
                            emailField.classList.remove('is-invalid');
                        } else if (response === "invalid_email") {
                            errorDiv.textContent = "Please enter a valid email address.";
                            errorDiv.style.color = "red";
                            emailField.classList.add('is-invalid');
                        } else {
                            errorDiv.textContent = "An error occurred. Please try again later.";
                            errorDiv.style.color = "red";
                            emailField.classList.add('is-invalid');
                        }
                    }
                };

                // Send the email to the server for validation
                xhr.send("email=" + encodeURIComponent(emailInput));
            }
        });
    </script>
    <script>
        // Form validation function
        function validateForm(event) {
            let isValid = true;

            // Email validation
            const email = document.getElementById('email');
            const emailError = document.getElementById('emailError');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(email.value)) {
                emailError.textContent = "Please enter a valid email address.";
                emailError.style.color = "red";
                email.classList.add('is-invalid');
                isValid = false;
            }

            // Phone number validation
            const phoneNumber = document.getElementById('phoneNumber');
            const phoneNumberError = document.getElementById('phoneNumberError');
            const numberPart = phoneNumber.value.substring(3); // Remove +60 prefix

            if (numberPart.length < 9 || numberPart.length > 10) {
                phoneNumberError.textContent = numberPart.length < 9
                    ? "Phone number must be at least 9 digits"
                    : "Phone number cannot exceed 10 digits";
                phoneNumberError.style.color = "red";
                phoneNumber.classList.add('is-invalid');
                isValid = false;
            }

            // Prevent form submission if validation fails
            if (!isValid) {
                event.preventDefault();
            }

            return isValid;
        }

        // Add event listeners
        document.addEventListener('DOMContentLoaded', function () {
            const phoneInput = document.getElementById('phoneNumber');
            const form = document.querySelector('form');

            phoneInput.addEventListener('input', validatePhoneNumber);
            form.addEventListener('submit', validateForm);
        });
    </script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>