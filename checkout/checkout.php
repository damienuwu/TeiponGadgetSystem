<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/TeiponGadgetSystem/config/db_config.php');

// Check if the user is logged in and fetch customer data
if (!isset($_SESSION['userID'])) {
    header("Location: ../login/login.php?error=Access denied");
    exit();
}

$customerID = $_SESSION['userID'];


// Fetch cart items from the database for the logged-in user, including variant information
$sql = "
    SELECT 
        c.cartID, c.productID, c.variantID, c.quantity, 
        p.productName, p.productPrice, p.productImage, 
        v.variantName 
    FROM 
        cart c
    JOIN 
        product p ON c.productID = p.productID
    LEFT JOIN 
        productVariant v ON c.variantID = v.variantID
    WHERE 
        c.customerID = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customerID);
$stmt->execute();
$result = $stmt->get_result();

// Store the cart items in an array
$cart = [];
$totalPrice = 0;

while ($item = $result->fetch_assoc()) {
    $cart[] = $item;
    $totalPrice += $item['productPrice'] * $item['quantity'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <!-- Link to Bootstrap CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .price {
            font-size: 1.5rem;
            font-weight: bold;
            color: rgb(4, 4, 4);
        }
    </style>
</head>

<body>
    <?php include('../navbar/customer_navbar.php'); ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Checkout</h1>

        <<div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Your Cart Items</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($cart)): ?>
                            <div class="cart-list">
                                <?php foreach ($cart as $item):
                                    $itemTotalPrice = $item['productPrice'] * $item['quantity']; // Calculate total price for each item
                                ?>
                                    <div class="cart-item d-flex justify-content-between mb-3">
                                        <div class="d-flex">
                                            <img src="../uploads/<?= htmlspecialchars($item['productImage']); ?>" alt="<?= htmlspecialchars($item['productName']); ?>" class="me-3 img-thumbnail product-image">
                                            <div>
                                                <h5><?= htmlspecialchars($item['productName']); ?>
                                                    <?php if (!empty($item['variantName'])): ?>
                                                        (<?= htmlspecialchars($item['variantName']); ?>)
                                                    <?php endif; ?>
                                                    (x<?= $item['quantity']; ?>)
                                                </h5>
                                                <small class="price">RM <?= number_format($itemTotalPrice, 2); ?></small> <!-- Show the total price for the item -->
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p>Your cart is empty. <a href="../customer/customer_home.php">Browse products</a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Order Summary</h3>
                    </div>
                    <div class="card-body">
                        <form action="process_checkout.php" method="POST">
                            <p><strong>Total Price: </strong><span class="price">RM <?= number_format($totalPrice, 2); ?></span></p>

                            <!-- Hidden fields to send cart details including variantID -->
                            <?php foreach ($cart as $item):
                                $itemTotalPrice = $item['productPrice'] * $item['quantity']; // Calculate total price for each item again
                                $totalPrice += $itemTotalPrice; // Add to the total price
                            ?>
                                <input type="hidden" name="cart[<?= $item['cartID']; ?>][productID]" value="<?= $item['productID']; ?>">
                                <input type="hidden" name="cart[<?= $item['cartID']; ?>][variantID]" value="<?= $item['variantID']; ?>">
                                <input type="hidden" name="cart[<?= $item['cartID']; ?>][quantity]" value="<?= $item['quantity']; ?>">
                                <input type="hidden" name="cart[<?= $item['cartID']; ?>][itemTotalPrice]" value="<?= $itemTotalPrice; ?>"> <!-- Store total price for each item -->
                            <?php endforeach; ?>

                            <button type="submit" class="btn btn-primary w-100">Complete Purchase</button>
                        </form>
                    </div>
                </div>
            </div>
    </div>

    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
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
    </script>
</body>

</html>