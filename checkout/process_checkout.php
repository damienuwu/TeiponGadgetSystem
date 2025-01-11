<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/TeiponGadgetSystem/config/db_config.php');

// Check if cart is empty or customer is not logged in
if (!isset($_SESSION['cart']) || empty($_SESSION['cart']) || !isset($_SESSION['userID'])) {
    header("Location: ../cart/cart.php");
    exit();
}

$cart = $_SESSION['cart'];
$totalPrice = 0;
foreach ($cart as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

$customerID = $_SESSION['userID'];

// Retrieve customer address details from the customer table
$customerSql = "SELECT customerAddress, customerCity, customerPostalCode, customerState 
                FROM customer 
                WHERE customerID = ?";
$customerStmt = $conn->prepare($customerSql);
$customerStmt->bind_param("i", $customerID);
$customerStmt->execute();
$customerResult = $customerStmt->get_result();

if ($customerResult->num_rows > 0) {
    $customer = $customerResult->fetch_assoc();
    $customerAddress = $customer['customerAddress'];
    $customerCity = $customer['customerCity'];
    $customerPostalCode = $customer['customerPostalCode'];
    $customerState = $customer['customerState'];
} else {
    echo "Customer address not found.";
    exit();
}

// Get current date for the order
$orderDate = date("Y-m-d");

// Set order status (initial status could be 'Pending')
$orderStatus = 'Pending Payment';

// Create the order in the orders table
$orderDetails = "Shipping Address: $customerAddress, $customerCity, $customerState, $customerPostalCode";
$sql = "INSERT INTO orders (orderDetails, orderDate, totalAmount, orderStatus, customerID) 
        VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $orderDetails, $orderDate, $totalPrice, $orderStatus, $customerID);
$stmt->execute();
$orderID = $stmt->insert_id;  // Get the ID of the newly created order

// Insert products from the cart into orderProducts table
foreach ($cart as $item) {
    $productID = $item['id'];
    $quantity = $item['quantity'];
    $price = $item['price'];
    $totalItemPrice = $price * $quantity;

    $orderProductSql = "INSERT INTO orderProducts (orderID, productID, quantity, price, totalPrice) 
                        VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($orderProductSql);
    $stmt->bind_param("iiidi", $orderID, $productID, $quantity, $price, $totalItemPrice);
    $stmt->execute();
}

// Clear the cart after successful order creation
unset($_SESSION['cart']);

// Redirect to payment page
header("Location: ../payment/payment.php?orderID=" . $orderID);
exit();
?>
