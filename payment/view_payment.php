<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/TeiponGadgetSystem/config/db_config.php');

// Function to check if the customer is logged in
function checkLogin()
{
    if (!isset($_SESSION['userID'])) {
        header("Location: ../login/login.php?error=Access denied");
        exit();
    }
    return $_SESSION['userID'];
}

function cancelOrder($conn, $orderID, $customerID)
{
    // Check if the order belongs to the logged-in customer and is in 'Pending Payment' status
    $sql = "SELECT orderStatus FROM orders WHERE orderID = ? AND customerID = ? AND orderStatus = 'Pending Payment'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $orderID, $customerID);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update the order status to 'Order Cancelled'
        $updateSql = "UPDATE orders SET orderStatus = 'Order Cancelled' WHERE orderID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("i", $orderID);
        $updateStmt->execute();

        // If the order status is successfully updated, delete the order products
        $deleteOrderProductsSql = "DELETE FROM orderProducts WHERE orderID = ?";
        $deleteOrderProductsStmt = $conn->prepare($deleteOrderProductsSql);
        $deleteOrderProductsStmt->bind_param("i", $orderID);
        $deleteOrderProductsStmt->execute();

        return $updateStmt->affected_rows > 0;
    }
    return false;
}


// Function to fetch orders by status
function getOrdersByStatus($conn, $customerID, $status)
{
    $sql = "SELECT orderID, orderDate, totalAmount, orderStatus FROM orders WHERE customerID = ? AND orderStatus = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $customerID, $status);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Function to fetch orders based on multiple statuses
function getAllOrderData($conn, $customerID)
{
    $orderData = [];
    $statuses = ['Pending Payment', 'Processing Payment', 'Order Shipped', 'Order Completed', 'Order Cancelled', 'Order Rejected'];

    foreach ($statuses as $status) {
        $orderData[$status] = getOrdersByStatus($conn, $customerID, $status);
    }

    return $orderData;
}

// Function to update order status to 'Order Completed'
// Function to mark order as completed
function markOrderCompleted($conn, $orderID, $customerID)
{
    // Check if the order belongs to the logged-in customer and is in 'Order Shipped' status
    $sql = "SELECT orderStatus FROM orders WHERE orderID = ? AND customerID = ? AND orderStatus = 'Order Shipped'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $orderID, $customerID);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update the order status to 'Order Completed'
        $updateSql = "UPDATE orders SET orderStatus = 'Order Completed' WHERE orderID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("i", $orderID);
        $updateStmt->execute();

        return $updateStmt->affected_rows > 0;
    }
    return false;
}

function getOrderProducts($conn, $orderID)
{
    $sql = "SELECT op.*, p.productName, p.productImage, v.variantName
            FROM orderProducts op
            LEFT JOIN product p ON op.productID = p.productID
            LEFT JOIN productVariant v ON op.variantID = v.variantID
            WHERE op.orderID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();

    $orderProducts = [];
    while ($row = $result->fetch_assoc()) {
        $orderProducts[] = $row;
    }

    return $orderProducts;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['completeOrder']) && isset($_POST['orderID'])) {
    $customerID = checkLogin();
    $orderID = $_POST['orderID'];

    $success = markOrderCompleted($conn, $orderID, $customerID);
    echo json_encode(['success' => $success]);
    exit();
}

// Process the cancel order POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelOrder']) && isset($_POST['orderID'])) {
    $customerID = checkLogin();
    $orderID = $_POST['orderID'];

    $success = cancelOrder($conn, $orderID, $customerID);
    echo json_encode(['success' => $success]);
    exit();
}
$customerID = checkLogin();
$orderData = getAllOrderData($conn, $customerID);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payment</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            border: 1px solid #ddd;
            border-radius: 0.75rem;
        }

        .card-header {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f8f9fa;
        }

        .table th {
            background-color: #f1f1f1;
            font-weight: bold;
        }

        .modal-content {
            border-radius: 0.75rem;
        }

        .nav-tabs .nav-link {
            border-radius: 0.375rem 0.375rem 0 0;
            padding: 10px 20px;
        }

        .nav-tabs .nav-link.active {
            background-color: #007bff;
            color: white;
        }

        .nav-tabs {
            margin-bottom: 20px;
        }

        .action-btns button {
            margin-right: 10px;
        }

        .action-btns {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
    </style>
</head>

<body>
    <?php include('../navbar/customer_navbar.php'); ?>
    <!-- Main Content -->
    <div class="container my-5" style="margin-top: 80px;">
        <h1 class="text-center mb-4">My Purchases</h1>

        <!-- Tabs -->
        <ul class="nav nav-tabs justify-content-center" id="orderTabs" role="tablist">
            <?php foreach ($orderData as $status => $orders): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $status === 'Pending Payment' ? 'active' : ''; ?>"
                        id="<?= strtolower(str_replace(' ', '-', $status)) ?>-tab" data-bs-toggle="tab"
                        data-bs-target="#<?= strtolower(str_replace(' ', '-', $status)) ?>" type="button" role="tab"
                        aria-controls="<?= strtolower(str_replace(' ', '-', $status)) ?>"
                        aria-selected="<?= $status === 'Pending Payment' ? 'true' : 'false'; ?>">
                        <?= $status ?>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="orderTabContent">
            <?php foreach ($orderData as $status => $orders): ?>
                <div class="tab-pane fade <?= $status === 'Pending Payment' ? 'show active' : ''; ?>"
                    id="<?= strtolower(str_replace(' ', '-', $status)) ?>" role="tabpanel"
                    aria-labelledby="<?= strtolower(str_replace(' ', '-', $status)) ?>-tab">
                    <?php if (count($orders) > 0): ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><?= htmlspecialchars($status); ?> Orders</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped mt-3">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Order Date</th>
                                            <th>Total Amount</th>
                                            <th>Order Status</th>
                                            <?php if (in_array($status, ['Pending Payment', 'Order Shipped'])): ?>
                                                <th>Action</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $row): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['orderID']); ?></td>
                                                <td><?= htmlspecialchars($row['orderDate']); ?></td>
                                                <td>RM <?= number_format($row['totalAmount'], 2); ?></td>
                                                <td><?= htmlspecialchars($row['orderStatus']); ?></td>
                                                <?php if (in_array($status, ['Pending Payment', 'Order Shipped'])): ?>
                                                    <td>
                                                        <div class="action-btns">
                                                            <?php if ($status === 'Pending Payment'): ?>
                                                                <a href="payment.php?orderID=<?= $row['orderID']; ?>"
                                                                    class="btn btn-primary btn-sm">Make Payment</a>
                                                                <button class="btn btn-danger btn-sm"
                                                                    onclick="cancelOrder(<?= $row['orderID']; ?>)">Cancel Order</button>
                                                            <?php elseif ($status === 'Order Shipped'): ?>
                                                                <button class="btn btn-success btn-sm"
                                                                    onclick="markOrderAsCompleted(<?= $row['orderID']; ?>)">Mark as Completed</button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>

                                            <!-- Product Details -->
                                            <tr>
                                                <td colspan="<?= in_array($status, ['Pending Payment', 'Order Shipped']) ? 5 : 4; ?>">
                                                    <?php
                                                    $orderProducts = getOrderProducts($conn, $row['orderID']);
                                                    if (!empty($orderProducts)): ?>
                                                        <div class="table-responsive">
                                                            <table class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Product Image</th>
                                                                        <th>Product Name</th>
                                                                        <th>Variant Name</th> <!-- Added Variant Name Column -->
                                                                        <th>Quantity</th>
                                                                        <th>Price</th>
                                                                        <th>Total</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($orderProducts as $item): ?>
                                                                        <tr>
                                                                            <td>
                                                                                <img src="../uploads/<?= htmlspecialchars($item['productImage'] ?? 'default.jpg'); ?>"
                                                                                    alt="Product Image"
                                                                                    class="img-fluid"
                                                                                    style="max-width: 100px; height: auto;">
                                                                            </td>
                                                                            <td><?= htmlspecialchars($item['productName']); ?></td>
                                                                            <td><?= htmlspecialchars($item['variantName']); ?></td> <!-- Displaying the Variant Name -->
                                                                            <td><?= htmlspecialchars($item['quantity']); ?></td>
                                                                            <td>RM <?= number_format($item['price'], 2); ?></td>
                                                                            <td>RM <?= number_format($item['quantity'] * $item['price'], 2); ?></td>
                                                                        </tr>
                                                                    <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    <?php else: ?>
                                                        <p>No products for this order.</p>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>

                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-center mt-3">No orders found in <?= htmlspecialchars($status); ?>.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelOrderModalLabel">Confirm Cancellation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to cancel this order?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="confirmCancelBtn" class="btn btn-danger">Cancel Order</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="completeOrderModal" tabindex="-1" aria-labelledby="completeOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeOrderModalLabel">Confirm Completion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to mark this order as completed?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="confirmCompleteBtn" class="btn btn-success">Mark as Completed</button>
            </div>
        </div>
    </div>
</div>



    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '../cart/get_cart.php', // Endpoint to fetch the cart data
                method: 'GET',
                success: function(response) {
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
                error: function(xhr, status, error) {
                    console.error("Get cart error:", xhr.responseText); // Handle errors from the server response
                }
            });
        });

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
                success: function(response) {
                    const responseData = JSON.parse(response);
                    const cartCountElement = document.getElementById('cartCount');
                    cartCountElement.innerText = responseData.cartCount; // Update cart count
                    updateCartModal(responseData.cart); // Update the modal with new cart data
                }
            });
        }

        // Update the cart modal with current cart data
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
            $('#cartIcon').click(function() {
                const modal = new bootstrap.Modal(document.getElementById('cartModal'));
                modal.show();
            });
        }

        // Remove product from cart
        function removeFromCart(productId) {
            $.ajax({
                url: '../cart/remove_from_cart.php',
                method: 'POST',
                data: {
                    productID: productId
                },
                success: function(response) {
                    try {
                        const responseData = JSON.parse(response);
                        updateCartModal(responseData.cart);
                        const cartCountElement = document.getElementById('cartCount');
                        if (responseData.cartCount !== undefined) {
                            cartCountElement.innerText = responseData.cartCount;
                        }
                    } catch (error) {
                        console.error("Error parsing response:", error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Remove from cart error:", xhr.responseText);
                }
            });
        }

        function cancelOrder(orderID) {
            // Open the modal
            $('#cancelOrderModal').modal('show');

            // Set the order ID to be cancelled
            $('#confirmCancelBtn').off('click').on('click', function() {
                // Close the modal
                $('#cancelOrderModal').modal('hide');

                // Proceed with cancelling the order
                $.ajax({
                    url: 'cancel_order.php', // Update to point to your cancellation logic handler
                    method: 'POST',
                    data: {
                        cancelOrder: true, // Signal to the server that this is a cancellation request
                        orderID: orderID
                    },
                    success: function(response) {
                        try {
                            const result = JSON.parse(response); // Parse the server's JSON response
                            if (result.success) {
                                // Show success message
                                showAlert('Order has been cancelled.', 'success');
                                location.reload(); // Refresh the page to update the order status
                            } else {
                                // Show error message from server
                                showAlert(result.message || 'Failed to cancel the order. Please try again.', 'danger');
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                            // Show error message for invalid JSON response
                            showAlert('An error occurred while canceling the order.', 'danger');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", status, error);
                        // Show error message for AJAX errors
                        showAlert('An error occurred while canceling the order.', 'danger');
                    }
                });
            });
        }
        // Function to display alerts
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');

            // Create the alert element
            const alertElement = document.createElement('div');
            alertElement.classList.add('alert', `alert-${type}`, 'alert-dismissible', 'fade', 'show');
            alertElement.setAttribute('role', 'alert');
            alertElement.innerHTML = `
        <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

            // Append the alert to the container at the top of the page
            alertContainer.prepend(alertElement);

            // Automatically remove the alert after 5 seconds
            setTimeout(() => {
                alertElement.classList.remove('show');
                alertElement.classList.add('fade');
            }, 5000);

            // Optionally, you can remove the alert after the fade-out transition ends
            setTimeout(() => {
                alertElement.remove();
            }, 6000); // This ensures the alert is fully removed after the fade effect
        }

        // Function to mark an order as completed
        function markOrderAsCompleted(orderID) {
            // Open the modal
            $('#completeOrderModal').modal('show');

            // Set up the confirmation for completion
            $('#confirmCompleteBtn').off('click').on('click', function() {
                // Close the modal
                $('#completeOrderModal').modal('hide');

                // Send AJAX request to mark the order as 'Order Completed'
                $.ajax({
                    url: 'mark_order_completed.php', // Update to point to your completion logic handler
                    method: 'POST',
                    data: {
                        completeOrder: true, // This will trigger the status update
                        orderID: orderID
                    },
                    success: function(response) {
                        try {
                            const result = JSON.parse(response);
                            if (result.success) {
                                // Show success message
                                showAlert('Order has been marked as completed.', 'success');
                                location.reload(); // Refresh the page to update the order status
                            } else {
                                // Show error message
                                showAlert(result.message || 'Failed to mark the order as completed. Please try again.', 'danger');
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                            // Show error message for invalid JSON response
                            showAlert('An error occurred while marking the order as completed.', 'danger');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", status, error);
                        // Show error message
                        showAlert('An error occurred while marking the order as completed.', 'danger');
                    }
                });
            });
        }

        // Initialize the cart modal when the page loads
        $(document).ready(function() {
            $.ajax({
                url: '../cart/get_cart.php', // Retrieve cart data on page load
                method: 'GET',
                success: function(response) {
                    const responseData = JSON.parse(response);
                    const cart = responseData.cart || [];
                    updateCartModal(cart); // Populate the modal with current cart items
                },
                error: function(xhr, status, error) {
                    console.error("Get cart error:", status, error); // Debug error
                }
            });
        });

        // Show the ordered items in a modal when the button is clicked
        $('.btn-info').click(function() {
            var orderID = $(this).data('order-id'); // Get the order ID
            fetchOrderItems(orderID); // Call the function to fetch and display items
        });

        // Function to fetch and display order items in a modal
        function fetchOrderItems(orderID) {
            $.ajax({
                url: '', // The current page, so PHP will handle fetching the items
                method: 'POST',
                data: {
                    orderID: orderID
                },
                success: function(response) {
                    const items = JSON.parse(response); // Get the order items
                    let itemsHtml = '';

                    // Build the items list to display in the modal
                    items.forEach(function(item) {
                        itemsHtml += `
                    <div class="item">
                        <p><strong>${item.productName}</strong></p>
                        <p>Quantity: ${item.quantity}</p>
                        <p>Price: RM ${item.price.toFixed(2)}</p>
                    </div>
                `;
                    });

                    // Show the modal with the items
                    $('#orderItemsModal .modal-body').html(itemsHtml);
                    $('#orderItemsModal').modal('show');
                }
            });
        }

        function showAlert(message, type) {
            const alertBox = document.createElement('div');
            alertBox.className = `alert alert-${type} alert-dismissible fade show`;
            alertBox.role = 'alert';
            alertBox.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
            document.body.prepend(alertBox); // Add the alert to the top of the page

            // Automatically dismiss the alert after 5 seconds
            setTimeout(() => {
                alertBox.remove();
            }, 5000);
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
    </script>
</body>

</html>