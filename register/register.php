<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <!-- Bootstrap CSS -->
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="../global.css" rel="stylesheet">
</head>

<body class="bg-light">
  <?php include('../navbar/navbar.php'); ?>
  <div class="container-fluid mt-5 pt-5">
    <div class="row">
      <div class="col-12 text-center">
        <h1 class="display-5 mb-3">Create New Account</h1>
        <p class="text-muted mb-4">Please fill in your information to register</p>
      </div>
    </div>
  </div>

  <div class="container-fluid pb-5">
    <div class="row justify-content-center">
      <div class="col-12 col-xl-10">
        <div class="card shadow-sm">
          <!-- Form Body -->
          <div class="card-body p-4">
            <form action="process_register.php" method="POST">
              <!-- Personal Information Section -->
              <div class="row mb-4">
                <div class="col-12">
                  <h5 class="border-bottom pb-2 mb-3">Personal Information</h5>
                </div>

                <div class="col-md-4 mb-3">
                  <label for="email" class="form-label">Email Address</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email"
                    required>
                  <div class="error-message" id="emailError"></div>
                </div>

                <div class="col-md-4 mb-3">
                  <label for="name" class="form-label">Name</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                  <div class="error-message" id="nameError"></div>
                </div>

                <!-- Phone Number -->

                <div class="col-md-4 mb-3">
                  <label for="phoneNumber" class="form-label">Phone Number</label>
                  <div class="input-group">
                    <span class="input-group-text" id="phonePrefix">+60</span>
                    <input type="text" class="form-control" id="phoneNumber" name="phoneNumber"
                      placeholder="Enter your phone number" maxlength="10" required
                      oninput="validatePhoneNumber(event)">
                  </div>
                  <div class="error-message" id="phoneNumberError"></div>
                </div>
              </div>

              <!-- Address Section -->
              <div class="row mb-4">
                <div class="col-12">
                  <h5 class="border-bottom pb-2 mb-3">Address Details</h5>
                </div>

                <div class="col-md-6 mb-3">
                  <label for="address" class="form-label">Street Address</label>
                  <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter your address"
                    required></textarea>
                  <div class="error-message" id="addressError"></div>
                </div>

                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <label for="state" class="form-label">State</label>
                      <select class="form-select" id="state" name="state" required>
                        <option value="" disabled selected>Select your state</option>
                        <option value="Johor">Johor</option>
                        <option value="Kedah">Kedah</option>
                        <option value="Kelantan">Kelantan</option>
                        <option value="Melaka">Melaka</option>
                        <option value="Negeri_Sembilan">Negeri Sembilan</option>
                        <option value="Pahang">Pahang</option>
                        <option value="Pulau Pinang">Pulau Pinang</option>
                        <option value="Perak">Perak</option>
                        <option value="Perlis">Perlis</option>
                        <option value="Selangor">Selangor</option>
                        <option value="Terengganu">Terengganu</option>
                        <option value="Sabah">Sabah</option>
                        <option value="Sarawak">Sarawak</option>
                      </select>
                      <div class="error-message" id="stateError"></div>
                    </div>

                    <!-- City -->
                    <div class="col-md-4 mb-3">
                      <label for="city" class="form-label">City</label>
                      <select class="form-select" id="city" name="city" required>
                        <option value="" disabled selected>Select your city</option>
                      </select>
                      <div class="error-message" id="cityError"></div>
                    </div>

                    <div class="col-md-4 mb-3">
                      <label for="postal_code" class="form-label">Postal Code</label>
                      <select class="form-select" id="postal_code" name="postal_code" required>
                        <option value="" disabled selected>Select your postal code</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Account Information Section -->
              <div class="row">
                <div class="col-12">
                  <h5 class="border-bottom pb-2 mb-3">Account Information</h5>
                </div>

                <div class="col-md-4 mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" class="form-control" id="username" name="username"
                    placeholder="Enter your username" required>
                  <div class="error-message" id="usernameError"></div>
                </div>

                <div class="col-md-4 mb-3">
                  <label for="password" class="form-label">Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password"
                      placeholder="Enter a password" required>
                    <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                      <i class="bi bi-eye-slash"></i> <!-- Eye icon to hide the password initially -->
                    </span>
                  </div>
                  <div class="error-message" id="passwordError"></div>
                </div>

                <!-- Confirm Password -->
                <div class="col-md-4 mb-3">
                  <label for="confirm_password" class="form-label">Confirm Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                      placeholder="Re-enter your password" required>
                    <span class="input-group-text" id="toggleConfirmPassword" style="cursor: pointer;">
                      <i class="bi bi-eye-slash"></i>
                    </span>
                  </div>
                  <div class="error-message" id="confirmPasswordError"></div>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="row mt-4">
                <div class="col-12">
                  <button type="submit" class="btn btn-primary w-100">Register</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Include the state-city-postal.js file -->
  <script src="state_city_postal.js"></script>

  <!-- Bootstrap JS Bundle -->
  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <!-- JavaScript to toggle password visibility -->
  <script>
    // Toggle visibility for Password
    document.getElementById('togglePassword').addEventListener('click', function () {
      const passwordField = document.getElementById('password');
      const passwordIcon = this.querySelector('i');

      if (passwordField.type === "password") {
        passwordField.type = "text";
        passwordIcon.classList.remove('bi-eye-slash');
        passwordIcon.classList.add('bi-eye');
      } else {
        passwordField.type = "password";
        passwordIcon.classList.remove('bi-eye');
        passwordIcon.classList.add('bi-eye-slash');
      }
    });

    // Toggle visibility for Confirm Password
    document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
      const confirmPasswordField = document.getElementById('confirm_password');
      const confirmPasswordIcon = this.querySelector('i');

      if (confirmPasswordField.type === "password") {
        confirmPasswordField.type = "text";
        confirmPasswordIcon.classList.remove('bi-eye-slash');
        confirmPasswordIcon.classList.add('bi-eye');
      } else {
        confirmPasswordField.type = "password";
        confirmPasswordIcon.classList.remove('bi-eye');
        confirmPasswordIcon.classList.add('bi-eye-slash');
      }
    });
  </script>
  <script>
    // Function to validate form including password match
    // Function to validate form including password match
    function validateForm(event) {
      // Get form fields
      const email = document.getElementById('email');
      const name = document.getElementById('name');
      const phoneNumber = document.getElementById('phoneNumber');
      const state = document.getElementById('state');
      const postalCode = document.getElementById('postal_code');
      const city = document.getElementById('city');
      const address = document.getElementById('address');
      const username = document.getElementById('username');
      const password = document.getElementById('password');
      const confirmPassword = document.getElementById('confirm_password');

      // Clear previous error messages
      document.querySelectorAll('.error-message').forEach(el => (el.textContent = ''));

      // Initialize form validity
      let isValid = true;

      // Email validation
      if (!email.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        document.getElementById('emailError').textContent = 'Valid email is required.';
        isValid = false;
      }

      // Name validation
      if (!name.value.trim()) {
        document.getElementById('nameError').textContent = 'Name is required.';
        isValid = false;
      }

      // Phone number validation
      if (!phoneNumber.value.trim() || (phoneNumber.value.length < 9 || phoneNumber.value.length > 10)) {
        document.getElementById('phoneNumberError').textContent = 'Phone number must have 9 or 10 digits.';
        isValid = false;
      }

      // State validation
      if (!state.value.trim()) {
        document.getElementById('stateError').textContent = 'State is required.';
        isValid = false;
      }

      // Postal code validation
      if (!postalCode.value.trim() || !/^\d{5}$/.test(postalCode.value)) {
        document.getElementById('postalCodeError').textContent = 'Valid postal code is required.';
        isValid = false;
      }

      // City validation
      if (!city.value.trim()) {
        document.getElementById('cityError').textContent = 'City is required.';
        isValid = false;
      }

      // Address validation
      if (!address.value.trim()) {
        document.getElementById('addressError').textContent = 'Address is required.';
        isValid = false;
      }

      // Username validation
      if (!username.value.trim()) {
        document.getElementById('usernameError').textContent = 'Username is required.';
        isValid = false;
      } else if (username.value.trim().length < 4) {
        document.getElementById('usernameError').textContent = 'Username must be at least 4 characters long.';
        isValid = false;
      }

      // Password validation
      if (!password.value.trim()) {
        document.getElementById('passwordError').textContent = 'Password is required.';
        isValid = false;
      } else if (password.value !== confirmPassword.value) {
        document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
        isValid = false;
      }

      // If the form is invalid, prevent submission
      if (!isValid) {
        event.preventDefault();
      }
    }

    // Attach the validateForm function to the form's submit event
    document.querySelector('form').addEventListener('submit', validateForm);
  </script>
  <script>
    // Real-time Email Check
    document.getElementById('email').addEventListener('input', function () {
      const emailInput = this.value.trim();
      const errorDiv = document.getElementById('emailError');
      const emailField = document.getElementById('email');
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;  // Basic email format

      if (emailInput.length === 0) {
        errorDiv.textContent = "";
        emailField.classList.remove('is-invalid');
      } else if (!emailRegex.test(emailInput)) {
        errorDiv.textContent = "Please enter a valid email address.";
        errorDiv.style.color = "red";
        emailField.classList.add('is-invalid');
      } else {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "check_register.php", true);  // Reusing check_register.php
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4 && xhr.status === 200) {
            if (xhr.responseText === "taken") {
              errorDiv.textContent = "Email is already registered.";
              errorDiv.style.color = "red";
              emailField.classList.add('is-invalid');
            } else {
              errorDiv.textContent = "Email is available.";
              errorDiv.style.color = "green";
              emailField.classList.remove('is-invalid');
            }
          }
        };

        xhr.send("email=" + encodeURIComponent(emailInput));
      }
    });
  </script>
  <script>
    // Function to validate phone number
    function validatePhoneNumber(event) {
      const input = event.target;

      // Allow only numbers (remove non-numeric characters)
      input.value = input.value.replace(/\D/g, '');

      // Restriction: First digit cannot be '0'
      if (input.value.length > 0 && input.value[0] === '0') {
        input.value = input.value.substring(1); // Remove leading '0'
      }

      // Restriction: Maximum of 10 digits after +60
      if (input.value.length > 10) {
        input.value = input.value.substring(0, 10);
      }
    }
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
        return;
      } else {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "check_register.php", true);  // Correct URL
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