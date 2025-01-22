<?php
session_start(); // Start session

// Check if the customer is logged in
if (!isset($_SESSION['userID']) || !isset($_SESSION['username'])) {
    header("Location: ../login/login.php?error=Access denied");
    exit();
}

// Fetch customer session data
$customerID = $_SESSION['userID'];
$customerUsername = $_SESSION['username'];

require_once($_SERVER['DOCUMENT_ROOT'] . '/TeiponGadgetSystem/config/db_config.php');

// Fetch customer data from the database
$sql = "SELECT customerName, customerEmail, customerUsername, customerPhoneNumber, customerState, customerPostalCode, customerCity, customerAddress FROM Customer WHERE customerID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customerID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $customer = $result->fetch_assoc();
} else {
    die("Customer details not found.");
}

// Handle form submission for updating customer details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newName = $_POST['name'];
    $newEmail = $_POST['email'];
    $newUsername = $_POST['username'];
    $newPhoneNumber = $_POST['phoneNumber'];
    $newState = $_POST['state'];
    $newPostalCode = $_POST['postalCode'];
    $newCity = $_POST['city'];
    $newAddress = $_POST['address'];
    $newPassword = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // Check if the email is already taken by another user
    $emailCheckSql = "SELECT customerEmail FROM Customer WHERE customerEmail = ? AND customerID != ?";
    $emailCheckStmt = $conn->prepare($emailCheckSql);
    $emailCheckStmt->bind_param("si", $newEmail, $customerID);
    $emailCheckStmt->execute();
    $emailCheckResult = $emailCheckStmt->get_result();

    if ($emailCheckResult->num_rows > 0) {
        // Email already exists, redirect with error message
        header("Location: customer_settings.php?error=The email address is already taken");
        exit();
    }

    // Check if the username is already taken by another user
    $usernameCheckSql = "SELECT customerUsername FROM Customer WHERE customerUsername = ? AND customerID != ?";
    $usernameCheckStmt = $conn->prepare($usernameCheckSql);
    $usernameCheckStmt->bind_param("si", $newUsername, $customerID);
    $usernameCheckStmt->execute();
    $usernameCheckResult = $usernameCheckStmt->get_result();

    if ($usernameCheckResult->num_rows > 0) {
        // Username already exists, redirect with error message
        header("Location: customer_settings.php?error=The username is already taken.");
        exit();
    }
    // Update customer details in the database
    if ($newPassword) {
        $updateSql = "UPDATE Customer SET customerName = ?, customerEmail = ?, customerUsername = ?, customerPhoneNumber = ?, customerState = ?, customerPostalCode = ?, customerCity = ?, customerAddress = ?, customerPassword = ? WHERE customerID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("sssssssssi", $newName, $newEmail, $newUsername, $newPhoneNumber, $newState, $newPostalCode, $newCity, $newAddress, $newPassword, $customerID);
    } else {
        $updateSql = "UPDATE Customer SET customerName = ?, customerEmail = ?, customerUsername = ?, customerPhoneNumber = ?, customerState = ?, customerPostalCode = ?, customerCity = ?, customerAddress = ? WHERE customerID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ssssssssi", $newName, $newEmail, $newUsername, $newPhoneNumber, $newState, $newPostalCode, $newCity, $newAddress, $customerID);
    }

    if ($updateStmt->execute()) {
        $_SESSION['customerUsername'] = $newUsername; // Update session with the new username
        header("Location: customer_settings.php?success=Details updated successfully");
        exit();
    } else {
        $error = "Error updating details: " . $updateStmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Settings</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .form-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-light">
    <!-- Navbar -->
    <?php include('../navbar/customer_navbar.php'); ?>

    <div class="container-fluid mt-1 pt-1">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-5 mb-3 mt-4">Profile Settings</h1>
                <p class="text-muted mb-1">Manage your account information and preferences</p>
            </div>
        </div>
    </div>
    <!-- Main Content -->
    <div class="container py-4">
        <!-- Success and Error Messages -->
        <?php if (isset($_GET['success'])): ?>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <?php echo htmlspecialchars($_GET['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?php echo htmlspecialchars($_GET['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="" method="POST" class="form-section p-4">
                    <div class="row g-4">
                        <!-- Personal Information Section -->
                        <div class="col-12">
                            <h4 class="mb-3"><i class="bi bi-person-fill me-2"></i>Personal Information</h4>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Your Name"
                                    value="<?php echo htmlspecialchars($customer['customerName']); ?>" required>
                                <label for="name">Full Name</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Username"
                                    value="<?php echo htmlspecialchars($customer['customerUsername']); ?>" required>
                                <label for="username">Username</label>
                            </div>
                            <div class="error-message" id="usernameError"></div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                                    value="<?php echo htmlspecialchars($customer['customerEmail']); ?>" required>
                                <label for="email">Email Address</label>
                            </div>
                            <div class="error-message" id="emailError"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber"
                                    placeholder="Phone"
                                    value="<?php echo htmlspecialchars(substr($customer['customerPhoneNumber'], 0)); ?>"
                                    required oninput="validatePhoneNumber(event)">
                                <label for="phoneNumber">Phone Number</label>
                            </div>
                        </div>
                        <!-- Address Section -->
                        <div class="col-12 mt-4">
                            <h4 class="mb-3"><i class="bi bi-geo-alt-fill me-2"></i>Address Information</h4>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="address" name="address"
                                    placeholder="Address"
                                    value="<?php echo htmlspecialchars($customer['customerAddress']); ?>" required>
                                <label for="address">Street Address</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select" id="state" name="state" required
                                    data-saved-state="<?php echo htmlspecialchars($customer['customerState']); ?>">
                                    <option value="">Select State</option>
                                </select>
                                <label for="state">State</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select" id="city" name="city" required
                                    data-saved-city="<?php echo htmlspecialchars($customer['customerCity']); ?>">
                                    <option value="">Select City</option>
                                </select>
                                <label for="city">City</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <select class="form-select" id="postalCode" name="postalCode" required
                                    data-saved-postal="<?php echo htmlspecialchars($customer['customerPostalCode']); ?>">
                                    <option value="">Select Postal Code</option>
                                </select>
                                <label for="postalCode">Postal Code</label>
                            </div>
                        </div>
                        <!-- Password Section -->
                        <div class="col-12 mt-4">
                            <h4 class="mb-3"><i class="bi bi-lock-fill me-2"></i>Security</h4>
                        </div>

                        <div class="col-12">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="New Password">
                                <label for="password">New Password (leave blank to keep current)</label>
                            </div>
                        </div>
                        <!-- Submit Button -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-check-circle me-2"></i>Update Profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="state-city-postal.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Add product to cart
        function addToCart(productID, productName, productPrice, productImage) {
            $.ajax({
                url: '../cart/add_to_cart.php',
                method: 'POST',
                data: {
                    productID: productID,
                    productName: productName,
                    productPrice: productPrice,
                    productImage: productImage
                },
                success: function (response) {
                    try {
                        const responseData = JSON.parse(response);
                        const cartCountElement = document.getElementById('cartCount');
                        if (responseData.cartCount !== undefined) {
                            cartCountElement.innerText = responseData.cartCount;
                        }
                        updateCartModal(responseData.cart);
                    } catch (error) {
                        console.error("Error parsing response:", error);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Add to cart error:", xhr.responseText);
                }
            });
        }

        function updateCartModal(cart) {
            let cartItemsHTML = '';
            let total = 0;

            // Check if cart is an array and has items
            if (Array.isArray(cart) && cart.length > 0) {
                cart.forEach(item => {
                    if (item.productName && item.quantity && item.productPrice && item.productImage) {
                        const itemPrice = Number(item.productPrice) || 0;
                        const itemQuantity = item.quantity || 1;
                        const itemTotalPrice = itemPrice * itemQuantity;

                        // Build the cart item HTML
                        cartItemsHTML += `
                    <div class="cart-item card mb-3 shadow-sm" data-product-id="${item.productID}" data-variant-id="${item.variantID}">
                        <div class="card-body d-flex justify-content-between align-items-center gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="../uploads/${item.productImage}" alt="${item.productName}" class="rounded img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-1">${item.productName} 
                                        <small class="text-muted">(x${itemQuantity})</small>
                                        ${item.variantName ? `<br><small class="text-muted">Variant: ${item.variantName}</small>` : ''}
                                    </h6>
                                    <p class="mb-0 text-primary fw-bold">RM ${(itemTotalPrice).toFixed(2)}</p>
                                </div>
                            </div>
                            <button class="btn btn-danger btn-sm" onclick="removeFromCart(${item.productID}, ${item.variantID})">
                                <i class="bi bi-trash3"></i> Remove
                            </button>
                        </div>
                    </div>
                `;


                        total += itemTotalPrice;
                    }
                });
            } else {
                cartItemsHTML = '<p class="text-center">Your cart is empty.</p>';
            }

            // Update the cart items and total price displayed in the modal
            $('#cartItems').html(cartItemsHTML);
            $('#cartTotal').text(total.toFixed(2));

            // Open modal when the cart icon is clicked
            $('#cartIcon').click(function () {
                const modal = new bootstrap.Modal(document.getElementById('cartModal'));
                modal.show();
            });
        }

        // Remove product from cart
        function removeFromCart(productID, variantID) {
            $.ajax({
                url: '../cart/remove_from_cart.php',
                method: 'POST',
                data: {
                    productID: productID, // Make sure productID is included
                    variantID: variantID // Include variantID as well
                },
                success: function (response) {
                    try {
                        const responseData = JSON.parse(response);
                        if (responseData.error) {
                            console.error("Error:", responseData.error);
                            return;
                        }

                        // Update the modal with the new cart data
                        updateCartModal(responseData.cart);

                        // Update the cart count in the UI
                        const cartCountElement = document.getElementById('cartCount');
                        if (responseData.cartCount !== undefined) {
                            cartCountElement.innerText = responseData.cartCount;
                        }
                    } catch (error) {
                        console.error("Error parsing response:", error);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Remove from cart error:", xhr.responseText);
                }
            });
        }

        // Initialize the cart modal when the page loads
        $(document).ready(function () {
            $.ajax({
                url: '../cart/get_cart.php', // Endpoint to fetch the cart data
                method: 'GET',
                success: function (response) {
                    try {
                        const responseData = JSON.parse(response); // Parse the JSON response
                        if (responseData && responseData.cart) {
                            const cart = responseData.cart || []; // Get the cart array
                            updateCartModal(cart); // Update the modal with the cart data

                            // Update the cart count
                            const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
                            document.getElementById('cartCount').innerText = cartCount; // Update the cart count on the page
                        }
                    } catch (error) {
                        console.error("Error parsing the cart data:", error); // Handle errors if parsing fails
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Get cart error:", xhr.responseText); // Handle errors from the server response
                }
            });
        });
    </script>
    <script>
        function validatePhoneNumber(event) {
            const input = event.target;
            const prefix = "+60";
            let phoneNumber = input.value;

            // Ensure the phone number starts with the prefix
            if (!phoneNumber.startsWith(prefix)) {
                phoneNumber = prefix + phoneNumber.substring(3);  // Keep prefix non-editable
            }

            // Set the value so the user can only edit the number after the prefix
            input.value = phoneNumber;

            // Limit the phone number length (10 digits total, including +60)
            if (input.value.length > 12) {
                input.value = input.value.substring(0, 12);
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
        // Real-time Username Check
        document.getElementById('username').addEventListener('input', function () {
            const usernameInput = this.value.trim();
            const errorDiv = document.getElementById('usernameError');
            const usernameField = document.getElementById('username');

            if (usernameInput.length === 0) {
                errorDiv.textContent = "";
                usernameField.classList.remove('is-invalid');
            } else if (usernameInput.length < 4) {
                errorDiv.textContent = "Username must be at least 4 characters long.";
                errorDiv.style.color = "red";
                usernameField.classList.add('is-invalid');
            } else {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "../register/check_register.php", true);  // Correct URL
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        if (xhr.responseText === "taken") {
                            errorDiv.textContent = "Username is already taken.";
                            errorDiv.style.color = "red";
                            usernameField.classList.add('is-invalid');
                        } else {
                            errorDiv.textContent = "Username is available.";
                            errorDiv.style.color = "green";
                            usernameField.classList.remove('is-invalid');
                        }
                    }
                };

                xhr.send("username=" + encodeURIComponent(usernameInput));
            }
        });
    </script>
</body>

</html>

<?php
// Close database connection
$conn->close();
?>