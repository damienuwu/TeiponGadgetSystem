<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Staff</title>
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="../global.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
  <?php include('../sidebar/admin_sidebar.php'); ?>
  <div class="container main-content">
    <div class="row justify-content-center min-vh-100">
      <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm p-4 mt-4">
          <h2 class="text-center mb-4">Register New Staff</h2>
          <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger text-center">
              <?= htmlspecialchars($_GET['error']); ?>
            </div>
          <?php elseif (isset($_GET['success'])): ?>
            <div class="alert alert-success text-center">
              <?= htmlspecialchars($_GET['success']); ?>
            </div>
          <?php endif; ?>
          <!-- Registration Form -->
          <form id="registerForm" action="process_register_staff.php" method="POST" onsubmit="return validateForm(event);">

            <div class="mb-3 d-flex justify-content-center">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="role" id="admin" value="admin" checked required>
                <label class="form-check-label" for="admin">Admin</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="role" id="staff" value="staff" required>
                <label class="form-check-label" for="staff">Staff</label>
              </div>
            </div>

            <div class="mb-3">
              <label for="staffName" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="staffName" name="staffName" placeholder="Enter staff full name" required>
              <div class="error-message" id="nameError"></div>
            </div>

            <div class="mb-3">
              <label for="staffUsername" class="form-label">Username</label>
              <input type="text" class="form-control" id="staffUsername" name="staffUsername" placeholder="Enter staff username" required>
              <div class="error-message" id="usernameError"></div>
            </div>

            <div class="mb-3">
              <label for="staffEmail" class="form-label">Email</label>
              <input type="email" class="form-control" id="staffEmail" name="staffEmail" placeholder="Enter staff email" required>
              <div class="error-message" id="emailError"></div>
            </div>

            <div class="mb-3">
              <label for="staffPassword" class="form-label">Password</label>
              <input type="password" class="form-control" id="staffPassword" name="staffPassword" placeholder="Enter staff password" required>
              <div class="error-message" id="passwordError"></div>
            </div>

            <div class="mb-3">
              <label for="confirm_password" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
              <div class="error-message" id="confirmPasswordError"></div>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-success">Register Staff</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script>
    async function validateForm(event) {
      event.preventDefault(); // Prevent default form submission

      const staffName = document.getElementById('staffName').value.trim();
      const staffUsername = document.getElementById('staffUsername').value.trim();
      const staffEmail = document.getElementById('staffEmail').value.trim();
      const staffPassword = document.getElementById('staffPassword').value.trim();
      const confirmPassword = document.getElementById('confirm_password').value.trim();
      const role = document.querySelector('input[name="role"]:checked').value;

      // Validation
      if (!staffName) {
        Swal.fire({
          icon: 'error',
          title: 'Validation Error',
          text: 'Staff name is required.',
        });
        return false;
      }

      if (!staffUsername) {
        Swal.fire({
          icon: 'error',
          title: 'Validation Error',
          text: 'Staff username is required.',
        });
        return false;
      }

      if (!staffEmail) {
        Swal.fire({
          icon: 'error',
          title: 'Validation Error',
          text: 'Staff email is required.',
        });
        return false;
      }

      if (!staffPassword) {
        Swal.fire({
          icon: 'error',
          title: 'Validation Error',
          text: 'Staff password is required.',
        });
        return false;
      }

      if (staffPassword !== confirmPassword) {
        Swal.fire({
          icon: 'error',
          title: 'Validation Error',
          text: 'Passwords do not match.',
        });
        return false;
      }

      // Send data to the server using AJAX
      try {
        const response = await fetch('process_register_staff.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams({
            staffName,
            staffUsername,
            staffEmail,
            staffPassword,
            role,
          }),
        });

        const data = await response.json();

        if (data.success) {
          // Show success message
          Swal.fire({
            icon: 'success',
            title: 'Success',
            text: data.message,
          }).then(() => {
            // Optionally, reset the form or redirect
            document.getElementById('registerForm').reset();
          });
        } else {
          // Show error message
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.message,
          });
        }
      } catch (error) {
        console.error('Error:', error);
        Swal.fire({
          icon: 'error',
          title: 'Server Error',
          text: 'An error occurred. Please try again later.',
        });
      }
    }
  </script>
</body>

</html>