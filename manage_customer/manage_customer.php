<?php
session_start(); // Start session

// Ensure staffID exists in the session
if (!isset($_SESSION['userID'])) {
    // Redirect to login page if staffID is not set
    header("Location: ../login/login.php?error=Access denied");
    exit();
}

if(isset($_SESSION['adminID'])) {
    $adminID = $_SESSION['adminID'];
    $userType = 'Admin';
} else{
    $userType = 'Staff';
}

// Since staffUsername is always set during login, use it directly
$userName = $_SESSION['username'];

require_once($_SERVER['DOCUMENT_ROOT'] . '/TeiponGadgetSystem/config/db_config.php');

// Fetch all customers except their password
$sql = "SELECT customerID, customerName, customerEmail, customerPhoneNumber, customerState, customerPostalCode, customerCity, customerAddress, status FROM Customer";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .modal-body {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .modal-footer .btn {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .modal-header {
            background-color: #f44336;
            color: white;
        }

        .modal-content {
            border-radius: 10px;
        }

        .btn-danger {
            background-color: #e53935;
            border-color: #e53935;
        }

        .btn-danger:hover {
            background-color: #d32f2f;
            border-color: #d32f2f;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php
    if ($userType === 'Admin') {
        include('../sidebar/admin_sidebar.php');
    } else if ($userType === 'Staff') {
        include('../sidebar/staff_sidebar.php');
    }
    ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h1 class="mb-4">Manage Customers</h1>

            <!-- Success or error message -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>

            <!-- Customers Table -->
            <table class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Phone</th>
                    <th class="text-center">Address</th>
                    <th class="text-center">State</th>
                    <th class="text-center">City</th>
                    <th class="text-center">Postal Code</th>
                    <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if there are customer records in the database
                    if ($result->num_rows > 0) {
                        // Output data for each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['customerID'] . "</td>";
                            echo "<td>" . htmlspecialchars($row['customerName']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['customerEmail']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['customerPhoneNumber']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['customerAddress']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['customerState']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['customerCity']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['customerPostalCode']) . "</td>";
                            echo "<td>
                        <div class='d-flex justify-content-center align-items-center flex-wrap gap-2'>
                            <a href='edit_customer.php?id=" . $row['customerID'] . "' class='btn btn-sm btn-warning'>Edit</a>";

                            if ($row['status'] == 0) {
                                echo "<a href='#' class='btn btn-sm btn-success' data-bs-toggle='modal' data-bs-target='#enableModal" . $row['customerID'] . "'>Enable</a>
                          </div>
                          <div class='modal fade' id='enableModal" . $row['customerID'] . "' tabindex='-1' aria-labelledby='enableModalLabel" . $row['customerID'] . "' aria-hidden='true'>
                              <div class='modal-dialog'>
                                  <div class='modal-content'>
                                      <div class='modal-header'>
                                          <h5 class='modal-title' id='enableModalLabel" . $row['customerID'] . "'>Confirm Enabling</h5>
                                          <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                      </div>
                                      <div class='modal-body'>
                                          <p><strong>Are you sure you want to enable this customer?</strong></p>
                                          <p>This action will activate the customer, allowing them to access their account again.</p>
                                      </div>
                                      <div class='modal-footer'>
                                          <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                          <a href='enable_customer.php?id=" . $row['customerID'] . "' class='btn btn-success'>Enable</a>
                                      </div>
                                  </div>
                              </div>
                          </div>";
                            } else {
                                echo "<a href='#' class='btn btn-sm btn-danger' data-bs-toggle='modal' data-bs-target='#disableModal" . $row['customerID'] . "'>Disable</a>
                          </div>
                          <div class='modal fade' id='disableModal" . $row['customerID'] . "' tabindex='-1' aria-labelledby='disableModalLabel" . $row['customerID'] . "' aria-hidden='true'>
                              <div class='modal-dialog'>
                                  <div class='modal-content'>
                                      <div class='modal-header'>
                                          <h5 class='modal-title' id='disableModalLabel" . $row['customerID'] . "'>Confirm Disabling</h5>
                                          <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                      </div>
                                      <div class='modal-body'>
                                          <p><strong>Are you sure you want to disable this customer?</strong></p>
                                          <p>This action will prevent the customer from accessing their account. They will no longer be able to log in.</p>
                                      </div>
                                      <div class='modal-footer'>
                                          <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                          <a href='disable_customer.php?id=" . $row['customerID'] . "' class='btn btn-warning'>Disable</a>
                                      </div>
                                  </div>
                              </div>
                          </div>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>No customers found</td></tr>";
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