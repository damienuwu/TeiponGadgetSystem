<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/TeiponGadgetSystem/config/db_creation_config.php');
ob_start();

// Bootstrap styling header
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">';

$conn->select_db($dbname);

$adminUsername = 'admin';
$adminPassword = '$2a$12$MTkrwrZoblu7LrxeipevJOXIoCwpcR2CsuhssVFgjBKEcmGQLVnLy';
$adminEmail = 'admin@yopmail.com';
$adminName = 'Admin User';

$custUsername = 'customer';
$custName = 'Damien';
$custPassword = '$2a$12$r96rnr2VsaejCUsNGHczaOVnPMQfNtPG72n8QIxEqSywpWr87trWK';
$custEmail = 'cust@yopmail.com';
$custPhoneNumber = '+60123456789';
$custState = 'Selangor';
$custPostalCode = '43000';
$custCity = 'Kajang';
$custAddress = '123, Jalan Kajang, Taman Kajang, 43000 Kajang, Selangor';
$custStatus = 1;

$staffName = 'Staff User';
$staffUsername = 'staff';
$staffEmail = 'staff@yopmail.com';
$staffPassword = '$2a$12$A4stCzLxT5UvPuo5gQJSuOYbdcQtgyfPfJYjD9TqEEhmPkqZgUWZO';

$sqlInsertStaff = "INSERT INTO staff (staffName, staffUsername, staffEmail, staffPassword) 
                   VALUES ('$staffName', '$staffUsername', '$staffEmail', '$staffPassword')";

if ($conn->query($sqlInsertStaff) === TRUE) {
    echo '<div class="alert alert-success">Staff member inserted successfully!</div>';
} else {
    echo '<div class="alert alert-danger">Error inserting staff: ' . $conn->error . '</div>';
}

// Insert admin user
$sqlInsertAdmin = "INSERT IGNORE INTO staff (staffName, staffUsername, staffEmail, staffPassword) 
                   VALUES ('$adminName', '$adminUsername', '$adminEmail', '$adminPassword')";

if ($conn->query($sqlInsertAdmin) === TRUE) {
    $adminID = $conn->insert_id; // Get the newly inserted admin's staffID

    // Update the adminID field to point to itself
    $sqlUpdateAdminID = "UPDATE staff SET adminID = $adminID WHERE staffID = $adminID";
    if ($conn->query($sqlUpdateAdminID) === TRUE) {
        echo '<div class="alert alert-success">Admin user inserted and adminID updated successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Error updating adminID: ' . $conn->error . '</div>';
    }
} else {
    echo '<div class="alert alert-danger">Error inserting admin: ' . $conn->error . '</div>';
}

$sqlInsertCustomer = "INSERT INTO customer (
    customerUsername,customerName , customerPassword,customerEmail,customerPhoneNumber,customerState,customerPostalCode,customerCity,customerAddress,
    status ) VALUES ('$custUsername','$custName','$custPassword','$custEmail','$custPhoneNumber','$custState','$custPostalCode','$custCity','$custAddress',$custStatus);";

if ($conn->query($sqlInsertCustomer) === TRUE) {
    $custID = $conn->insert_id;
    echo '<div class="alert alert-success" role="alert">
    Customer inserted successfully!
  </div>';
} else {
    echo '<div class="alert alert-danger">Error inserting customer: ' . $conn->error . '</div>';
}


$productValues = "
	(1, 'Apple iPhone 15', 'Apple', 4499.96, 'Latest iPhone with A17 Bionic chip and improved camera.', '6.1 inches', '3279 mAh', '48MP + 12MP + 12MP', 'A17 Bionic', 'iOS 17', '2023-09-22', 'apple-iphone-15.jpg', '2024-12-20 09:13:20', '2025-01-07 03:00:47', 1),
	(2, 'Samsung Galaxy S23 Ultra', 'Samsung', 5399.96, 'Premium flagship with S Pen and 200MP quad-camera.', '6.8 inches', '5000 mAh', '200MP + 12MP + 10MP + 10MP', 'Snapdragon 8 Gen 2', 'Android 13', '2023-02-01', 'samsung-galaxy-s23-ultra-5g.jpg', '2024-12-20 09:13:20', '2025-01-07 03:08:02', 1),
	(3, 'Google Pixel 8 Pro', 'Google', 4049.96, 'Google’s latest flagship with AI-powered photography.', '6.7 inches', '4950 mAh', '50MP + 48MP + 48MP', 'Google Tensor G3', 'Android 14', '2023-10-12', 'google-pixel-8-pro.jpg', '2024-12-20 09:13:20', '2025-01-07 03:05:26', 1),
	(4, 'OnePlus 11', 'OnePlus', 3149.96, 'Flagship phone with Hasselblad camera system.', '6.7 inches', '5000 mAh', '50MP + 48MP + 32MP', 'Snapdragon 8 Gen 2', 'Android 13', '2023-01-04', 'oneplus-11.jpg', '2024-12-20 09:13:20', '2025-01-07 03:06:15', 1),
	(5, 'Xiaomi 13 Pro', 'Xiaomi', 3599.96, 'High-performance phone with Leica optics.', '6.73 inches', '4820 mAh', '50.3MP + 50MP + 50MP', 'Snapdragon 8 Gen 2', 'MIUI 14 (Android 13)', '2023-03-06', 'xiaomi-redmi-note-13-pro-5g.jpg', '2024-12-20 09:13:20', '2025-01-07 03:09:29', 1),
	(6, 'Samsung Galaxy Z Flip 5', 'Samsung', 4499.96, 'Foldable phone with high performance, innovative and beautiful design.', '6.7 inches (main), 3.4 inches (cover)', '3700 mAh', '12MP + 12MP', 'Snapdragon 8 Gen 2', 'Android 13', '2023-08-11', 'samsung-galaxy-z-flip5-5g.jpg', '2024-12-20 09:13:20', '2025-01-07 03:08:13', 1),
	(7, 'Sony Xperia 1 V', 'Sony', 5849.96, 'Sony’s flagship with cutting-edge camera technology.', '6.5 inches', '5000 mAh', '52MP + 12MP + 12MP', 'Snapdragon 8 Gen 2', 'Android 13', '2023-07-01', 'sony-xperia-1-vi-red.jpg', '2024-12-20 09:13:20', '2025-01-07 03:08:37', 1),
	(8, 'Realme GT 5', 'Realme', 2924.96, 'High-performance phone with Snapdragon 8 Gen 2.', '6.74 inches', '4600 mAh', '50MP + 8MP + 2MP', 'Snapdragon 8 Gen 2', 'Android 13', '2023-08-20', 'realme-gt5-150w.jpg', '2024-12-20 09:13:20', '2025-01-07 03:06:42', 1),
	(9, 'Google Pixel Fold', 'Google', 8099.96, 'Google’s first foldable with stunning design.', '7.6 inches (main), 5.8 inches (cover)', '4821 mAh', '48MP + 10.8MP + 10.8MP', 'Google Tensor G2', 'Android 14', '2023-05-10', 'google-pixel-fold.jpg', '2024-12-20 09:13:20', '2025-01-07 03:05:39', 1),
	(10, 'Apple iPhone SE (2024)', 'Apple', 1934.96, 'Compact and affordable iPhone with A16 Bionic chip.', '4.7 inches', '2018 mAh', '12MP', 'A16 Bionic', 'iOS 17', '2024-03-01', 'apple-iphone-SE.jpg', '2024-12-20 09:13:20', '2025-01-07 03:05:09', 1),
	(11, 'Samsung Galaxy Z Fold 5', 'Samsung', 1799.99, 'Premium foldable phone with innovative multitasking features.', '7.6 inches (main), 6.2 inches (cover)', '4400 mAh', '50MP + 12MP + 10MP', 'Snapdragon 8 Gen 2', 'Android 13', '2023-07-26', 'samsung-galaxy-z-fold5-5g.jpg', '2024-12-20 09:13:20', '2025-01-07 03:08:30', 1),
	(12, 'Oppo Find N3 Flip', 'Oppo', 1099.99, 'Clamshell foldable with advanced camera system.', '6.8 inches (main), 3.26 inches (cover)', '4300 mAh', '50MP + 32MP + 48MP', 'Dimensity 9200', 'Android 14', '2023-08-30', 'oppo-find-n3-flip.jpg', '2024-12-20 09:13:20', '2025-01-07 03:06:31', 1),
	(13, 'Xiaomi Mix Fold 3', 'Xiaomi', 1599.99, 'Xiaomi’s ultra-slim foldable with Leica optics.', '8.03 inches (main), 6.56 inches (cover)', '4800 mAh', '50MP + 10MP + 12MP + 20MP', 'Snapdragon 8 Gen 2', 'Android 14', '2023-08-16', 'xiaomi-mix-fold3-.jpg', '2024-12-20 09:13:20', '2025-01-07 03:09:18', 1),
	(14, 'Vivo X100 Pro+', 'Vivo', 1299.99, 'High-end flagship with advanced imaging capabilities.', '6.78 inches', '5000 mAh', '50MP + 50MP + 64MP + 12MP', 'Dimensity 9300', 'Android 14', '2023-12-15', 'vivo-x100-pro.jpg', '2024-12-20 09:13:20', '2025-01-07 03:08:55', 1),
	(15, 'Asus ROG Phone 7 Ultimate', 'Asus', 1399.99, 'Gaming smartphone with top-notch performance.', '6.78 inches', '6000 mAh', '50MP + 13MP', 'Snapdragon 8 Gen 2', 'Android 13', '2023-04-13', 'asus-rog-phone-7-ultimate.jpg', '2024-12-20 09:13:20', '2025-01-07 03:05:15', 1),
	(16, 'Honor Magic V2', 'Honor', 1599.99, 'Ultra-thin foldable with premium design.', '7.92 inches (main), 6.43 inches (cover)', '5000 mAh', '50MP + 50MP + 20MP', 'Snapdragon 8 Gen 2', 'Android 14', '2023-07-12', 'honor-magic-2.jpg', '2024-12-20 09:13:20', '2025-01-07 03:05:44', 1),
	(17, 'Realme Narzo 60 Pro', 'Realme', 329.99, 'Affordable phone with flagship-grade features.', '6.7 inches', '5000 mAh', '100MP + 2MP', 'Dimensity 7050', 'Android 13', '2023-06-15', 'realme-narzo60-pro-5g.jpg', '2024-12-20 09:13:20', '2025-01-07 03:07:11', 1),
	(18, 'Infinix Zero Ultra', 'Infinix', 299.99, 'Budget phone with 200MP camera and fast charging.', '6.8 inches', '4500 mAh', '200MP + 13MP + 2MP', 'Dimensity 920', 'Android 12', '2023-05-12', 'infinix-zero-ultra.jpg', '2024-12-20 09:13:20', '2025-01-07 03:06:04', 1),
	(19, 'Huawei Mate 60 Pro', 'Huawei', 1199.99, 'Premium flagship with HarmonyOS that is only avaiable only on China.', '6.82 inches', '5000 mAh', '50MP + 48MP + 12MP', 'Kirin 9000S', 'HarmonyOS 4.0', '2023-09-20', 'huawei-mate-60-pro.jpg', '2024-12-20 09:13:20', '2025-01-07 03:05:59', 1),
	(20, 'Tecno Phantom V Fold', 'Tecno', 999.99, 'Affordable foldable phone with powerful hardware.', '7.85 inches (main), 6.42 inches (cover)', '5000 mAh', '50MP + 50MP + 13MP', 'Dimensity 9000+', 'Android 13', '2023-03-02', 'tecno-phantom-v-fold.jpg', '2024-12-20 09:13:20', '2025-01-07 03:08:50', 1),
	(21, 'Samsung Galaxy A54', 'Samsung', 2024.95, 'Mid-range phone with durable design and solid performance.', '6.4 inches', '5000 mAh', '50MP + 12MP + 5MP', 'Exynos 1380', 'Android 13', '2023-03-15', 'samsung-galaxy-a54.jpg', '2024-12-20 09:13:20', '2025-01-07 03:07:23', 1),
	(22, 'Motorola Edge 40', 'Motorola', 2699.95, 'Stylish mid-range with curved-edge display.', '6.55 inches', '4400 mAh', '50MP + 13MP', 'Dimensity 8020', 'Android 13', '2023-05-23', 'motorola-edge-40.jpg', '2024-12-20 09:13:20', '2025-01-07 03:06:09', 1),
	(23, 'Apple iPhone 14 Pro', 'Apple', 4949.95, 'Flagship phone with Dynamic Island and 48MP camera.', '6.1 inches', '3200 mAh', '48MP + 12MP + 12MP', 'A16 Bionic', 'iOS 16', '2022-09-16', 'apple-iphone-14-pro.jpg', '2024-12-20 09:13:20', '2025-01-07 03:03:25', 1),
	(24, 'Google Pixel 7', 'Google', 2699.95, 'Compact phone with advanced AI features.', '6.3 inches', '4355 mAh', '50MP + 12MP', 'Google Tensor G2', 'Android 13', '2022-10-13', 'google-pixel7-new.jpg', '2024-12-20 09:13:20', '2025-01-07 03:05:32', 1),
	(25, 'OnePlus Nord 3', 'OnePlus', 2249.95, 'Affordable phone with flagship-grade specs.', '6.74 inches', '5000 mAh', '50MP + 8MP + 2MP', 'Dimensity 9000', 'Android 13', '2023-07-01', 'oneplus-nord-3r.jpg', '2024-12-20 09:13:20', '2025-01-07 03:06:22', 1),
	(26, 'Xiaomi Redmi Note 12 Pro+', 'Xiaomi', 1709.95, 'Mid-range phone with 200MP main camera.', '6.67 inches', '5000 mAh', '200MP + 8MP + 2MP', 'Dimensity 1080', 'Android 13', '2022-12-01', 'xiaomi-redmi-note-12-pro-plus.jpg', '2024-12-20 09:13:20', '2025-01-07 03:09:26', 1),
	(27, 'Samsung Galaxy S22', 'Samsung', 3599.95, 'Compact flagship with pro-grade cameras.', '6.1 inches', '3700 mAh', '50MP + 10MP + 12MP', 'Exynos 2200/Snapdragon 8 Gen 1', 'Android 12', '2022-02-25', 'samsung-galaxy-s22-5g.jpg', '2024-12-20 09:13:20', '2025-01-07 03:07:42', 1),
	(28, 'Sony Xperia 10 V', 'Sony', 2024.95, 'Lightweight and waterproof phone with good cameras.', '6.1 inches', '5000 mAh', '48MP + 8MP', 'Snapdragon 695', 'Android 13', '2023-06-15', 'sony-xperia-10-v-10.jpg', '2024-12-20 09:13:20', '2025-01-07 03:08:45', 1),
	(29, 'Vivo X90 Pro', 'Vivo', 4499.95, 'Flagship camera phone with Zeiss optics.', '6.78 inches', '4870 mAh', '50MP + 50MP + 12MP', 'Dimensity 9200', 'Android 13', '2023-01-31', 'vivo-x90-pro.jpg', '2024-12-20 09:13:20', '2025-01-07 03:09:00', 1),
	(30, 'Realme C55', 'Realme', 899.95, 'Affordable phone with Mini Capsule feature.', '6.72 inches', '5000 mAh', '64MP + 2MP', 'Helio G88', 'Android 13', '2023-04-01', 'realme-c55.jpg', '2024-12-20 09:13:20', '2025-01-07 03:06:37', 1);";

$sqlInsertProducts = "INSERT INTO product (
    productID,productName, productBrand, productPrice, productDescription, 
    productScreenSize, productBatteryCapacity, productCameraSpecs, 
    productProcessor, productOS, productReleaseDate, productImage,productCreatedAt,productUpdatedAt , staffID
) VALUES $productValues";

if ($conn->query($sqlInsertProducts) === TRUE) {
    echo '<div class="alert alert-success" role="alert">
            Product inserted successfully!
          </div>';
} else {
    echo '<div class="alert alert-danger" role="alert">
            Error: ' . $conn->error . '
          </div>';
}

$productVariantValues = "
    (1, 1, 'Blue 128gb Storage 4gb RAM',  'Blue', 128, 4, 10, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(2, 1, 'Blue 256gb Storage 6gb RAM', 'Blue', 256, 6, 8, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(3, 1, 'Blue 512gb Storage 8gb RAM', 'Blue', 512, 8, 5, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(4, 1, 'Black 128gb Storage 4gb RAM', 'Black', 128, 4, 12, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(5, 1, 'Black 256gb Storage 6gb RAM', 'Black', 256, 6, 9, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(7, 1,'White 128gb Storage 4gb RAM', 'White', 128, 4, 10, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(6, 1,'Black 512gb Storage 8gb RAM', 'Black', 512, 8, 6, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(8, 1,'White 256gb Storage 6gb RAM', 'White', 256, 6, 7, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(9, 1,'White 512gb Storage 8gb RAM', 'White', 512, 8, 4, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(10, 2,'Black 256gb Storage 8gb RAM','Black', 256, 8, 20, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(11, 2,'Black 512gb Storage 12gb RAM','Black', 512, 12, 15, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(12, 2,'Black 1024gb Storage 16gb RAM','Black', 1024, 16, 8, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(13, 2,'Cream 256gb Storage 8gb RAM','Cream', 256, 8, 18, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(14, 2,'Cream 512gb Storage 12gb RAM','Cream', 512, 12, 12, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(15, 2,'Cream 1024gb Storage 16gb RAM','Cream', 1024, 16, 6, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(16, 2,'Red 256gb Storage 8gb RAM','Red', 256, 8, 15, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(17, 2,'Red 512gb Storage 12gb RAM','Red', 512, 12, 10, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(18, 2,'Red 1024gb Storage 16gb RAM','Red', 1024, 16, 5, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(19, 3,'Obsidian 128gb Storage 6gb RAM','Obsidian', 128, 6, 13, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(20, 3,'Obsidian 256gb Storage 8gb RAM','Obsidian', 256, 8, 10, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(21, 3,'Obsidian 512gb Storage 12gb RAM','Obsidian', 512, 12, 7, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(22, 3,'Porcelain 128gb Storage 6gb RAM','Porcelain', 128, 6, 12, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(23, 3,'Porcelain 256gb Storage 48gb RAM','Porcelain', 256, 8, 8, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(24, 3,'Porcelain 512gb Storage 12gb RAM','Porcelain', 512, 12, 5, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(25, 4,'Titan 128gb Storage 6gb RAM','Titan', 128, 6, 15, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(26, 4,'Titan 256gb Storage 8gb RAM','Titan', 256, 8, 12, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(27, 4,'Titan 512gb Storage 12gb RAM','Titan', 512, 12, 8, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(28, 4,'Emerald Green 128gb Storage 6gb RAM','Emerald Green', 128, 6, 14, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(29, 4,'Emerald Green 256gb Storage 8gb RAM','Emerald Green', 256, 8, 11, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(30, 4,'Emerald Green 512gb Storage 12gb RAM','Emerald Green', 512, 12, 6, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(31, 5,'White 256gb Storage 8gb RAM','White', 256, 8, 18, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(32, 5,'White 512gb Storage 12gb RAM','White', 512, 12, 10, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(33, 5,'White 1024gb Storage 16gb RAM','White', 1024, 16, 6, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(34, 5,'Silver 256gb Storage 8gb RAM','Silver', 256, 8, 16, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(35, 5,'Silver 512gb Storage 12gb RAM','Silver', 512, 12, 9, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(36, 5,'Silver 1024gb Storage 16gb RAM','Silver', 1024, 16, 5, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(37, 6,'Graphite 128gb Storage 6gb RAM','Graphite', 128, 6, 20, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(38, 6,'Graphite 256gb Storage 8gb RAM','Graphite', 256, 8, 14, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(39, 6,'Graphite 512gb Storage 12gb RAM','Graphite', 512, 12, 8, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(40, 6,'Lavender 128gb Storage 6gb RAM','Lavender', 128, 6, 18, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(41, 6,'Lavender 256gb Storage 8gb RAM','Lavender', 256, 8, 12, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(42, 6,'Lavender 512gb Storage 12gb RAM','Lavender', 512, 12, 6, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(43, 7,'Forest Green 256gb Storage 8gb RAM','Forest Green', 256, 8, 10, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(44, 7,'Forest Green 512gb Storage 12gb RAM','Forest Green', 512, 12, 8, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(45, 7,'Forest Green 1024gb Storage 16gb RAM','Forest Green', 1024, 16, 6, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(46, 7,'Burgundy 256gb Storage 8gb RAM','Burgundy', 256, 8, 9, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(47, 7,'Burgundy 512gb Storage 12gb RAM','Burgundy', 512, 12, 7, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(48, 7,'Burgundy 1024gb Storage 16gb RAM','Burgundy', 1024, 16, 5, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(49, 8,'Yellow 256gb Storage 8gb RAM','Yellow', 256, 8, 16, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(50, 8,'Yellow 512gb Storage 12gb RAM','Yellow', 512, 12, 11, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(51, 8,'Yellow 1024gb Storage 16gb RAM','Yellow', 1024, 16, 7, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(52, 8,'Lime Green 256gb Storage 8gb RAM','Lime Green', 256, 8, 14, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(53, 8,'Lime Green 512gb Storage 12gb RAM','Lime Green', 512, 12, 9, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(54, 8,'Lime Green 1024gb Storage 16gb RAM','Lime Green', 1024, 16, 5, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(55, 9,'Porcelain 256gb Storage 8gb RAM','Porcelain', 256, 8, 8, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(56, 9,'Porcelain 512gb Storage 12gb RAM','Porcelain', 512, 12, 6, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(57, 9,'Porcelain 1024gb Storage 16gb RAM','Porcelain', 1024, 16, 4, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(58, 9,'Black 256gb Storage 8gb RAM','Black', 256, 8, 10, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(59, 9,'Black 512gb Storage 12gb RAM','Black', 512, 12, 7, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(60, 9,'Black 1024gb Storage 16gb RAM','Black', 1024, 16, 5, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(61, 10,'Midnight 128gb Storage 4gb RAM', 'Midnight', 128, 4, 9, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(62, 10,'Midnight 256b Storage 6gb RAM', 'Midnight', 256, 6, 7, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(63, 10,'Midnight 512gb Storage 8gb RAM', 'Midnight', 512, 8, 5, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(64, 10,'Red 128gb Storage 4gb RAM', 'Red', 128, 4, 10, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(65, 10,'Red 256gb Storage 6gb RAM', 'Red', 256, 6, 8, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(66, 10,'Red 512gb Storage 8gb RAM', 'Red', 512, 8, 6, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (67, 11,'Black 128gb Storage 8gb RAM', 'Black', 128, 8, 15, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (68, 11,'Black 256gb Storage 8gb RAM', 'Black', 256, 8, 12, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (69, 11,'Silver 128gb Storage 8gb RAM', 'Silver', 128, 8, 13, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (70, 11,'Silver 256gb Storage 8gb RAM', 'Silver', 256, 8, 10, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (71, 12,'Black 128gb Storage 8gb RAM', 'Black', 128, 8, 20, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (72, 12,'Black 256gb Storage 8gb RAM', 'Black', 256, 8, 15, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (73, 12,'White 128gb Storage 8gb RAM', 'White', 128, 8, 18, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (74, 12,'White 256gb Storage 8gb RAM', 'White', 256, 8, 14, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (75, 13,'Black 128gb Storage 8gb RAM', 'Black', 128, 8, 17, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (76, 13,'Black 256gb Storage 8gb RAM', 'Black', 256, 8, 13, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (77, 13,'Silver 128gb Storage 8gb RAM', 'Silver', 128, 8, 10, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (78, 13,'Silver 256gb Storage 8gb RAM', 'Silver', 256, 8, 8, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (79, 14,'Blue 128gb Storage 8gb RAM', 'Blue', 128, 8, 22, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (80, 14,'Blue 256gb Storage 8gb RAM', 'Blue', 256, 8, 17, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (81, 14,'Red 128gb Storage 8gb RAM', 'Red', 128, 8, 19, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (82, 14,'Red 256gb Storage 8gb RAM', 'Red', 256, 8, 14, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (83, 15,'Black 128gb Storage 12gb RAM', 'Black', 128, 12, 25, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (84, 15,'Black 256gb Storage 12gb RAM', 'Black', 256, 12, 18, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (85, 15,'White 128gb Storage 12gb RAM', 'White', 128, 12, 20, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (86, 15,'White 256gb Storage 12gb RAM', 'White', 256, 12, 15, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (87, 16,'Black 128gb Storage 8gb RAM', 'Black', 128, 8, 18, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (88, 16,'Black 256gb Storage 8gb RAM', 'Black', 256, 8, 14, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (89, 16,'Silver 128gb Storage 8gb RAM', 'Silver', 128, 8, 12, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (90, 16,'Silver 256gb Storage 8gb RAM', 'Silver', 256, 8, 9, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (91, 17,'Blue 128gb Storage 6gb RAM', 'Blue', 128, 6, 20, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (92, 17,'Blue 256gb Storage 6gb RAM', 'Blue', 256, 6, 18, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (93, 17,'Red 128gb Storage 6gb RAM', 'Red', 128, 6, 22, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (94, 17,'Red 256gb Storage 6gb RAM', 'Red', 256, 6, 20, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (95, 18,'Black 128gb Storage 8gb RAM', 'Black', 128, 8, 15, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (96, 18,'Black 256gb Storage 8gb RAM', 'Black', 256, 8, 10, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (97, 18,'White 128gb Storage 8gb RAM', 'White', 128, 8, 13, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (98, 18,'White 256gb Storage 8gb RAM', 'White', 256, 8, 11, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (99, 19,'Black 128gb Storage 8gb RAM', 'Black', 128, 8, 18, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (100, 19,'Black 256gb Storage 8gb RAM', 'Black', 256, 8, 13, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (101, 19,'Silver 128gb Storage 8gb RAM', 'Silver', 128, 8, 10, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (102, 19,'Silver 256gb Storage 8gb RAM', 'Silver', 256, 8, 8, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (103, 20,'Blue 128gb Storage 8gb RAM', 'Blue', 128, 8, 12, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (104, 20,'Blue 256gb Storage 8gb RAM', 'Blue', 256, 8, 10, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (105, 20,'Red 128gb Storage 8gb RAM', 'Red', 128, 8, 14, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (106, 20,'Red 256gb Storage 8gb RAM', 'Red', 256, 8, 12, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(107, 21,'Black 128gb Storage 6gb RAM', 'Black', 128, 6, 15, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (108, 21,'Black 256GB Storage 8GB RAM', 'Black', 256, 8, 12, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (109, 21,'White 128GB Storage 6GB RAM', 'White', 128, 6, 14, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (110, 21,'White 256GB Storage 8GB RAM', 'White', 256, 8, 10, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (111, 22,'Blue 128GB Storage 6GB RAM', 'Blue', 128, 6, 20, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (112, 22,'Blue 256GB Storage 8GB RAM', 'Blue', 256, 8, 15, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (113, 22,'Black 128GB Storage 6GB RAM', 'Black', 128, 6, 18, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (114, 22,'Black 256GB Storage 8GB RAM', 'Black', 256, 8, 13, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (115, 23,'Silver 128GB Storage 6GB RAM', 'Silver', 128, 6, 16, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (116, 23,'Silver 256GB Storage 8GB RAM', 'Silver', 256, 8, 14, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (117, 23,'Gold 128GB Storage 6GB RAM', 'Gold', 128, 6, 19, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (118, 23,'Gold 256GB Storage 8GB RAM', 'Gold', 256, 8, 11, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (119, 24,'Black 128GB Storage 6GB RAM', 'Black', 128, 6, 21, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (120, 24,'Black 256GB Storage 8GB RAM', 'Black', 256, 8, 18, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (121, 24,'White 128GB Storage 6GB RAM', 'White', 128, 6, 16, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (122, 24,'White 256GB Storage 8GB RAM', 'White', 256, 8, 12, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (123, 25,'Black 128GB Storage 6GB RAM', 'Black', 128, 6, 22, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (124, 25,'Black 256GB Storage 8GB RAM', 'Black', 256, 8, 17, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (125, 25,'Blue 128GB Storage 6GB RAM', 'Blue', 128, 6, 19, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (126, 25,'Blue 256GB Storage 8GB RAM', 'Blue', 256, 8, 14, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (127, 26,'Black 128GB Storage 6GB RAM', 'Black', 128, 6, 15, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (128, 26,'Black 256GB Storage 8GB RAM', 'Black', 256, 8, 10, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (129, 26,'Silver 128GB Storage 6GB RAM', 'Silver', 128, 6, 13, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (130, 26,'Silver 256GB Storage 8GB RAM', 'Silver', 256, 8, 9, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(131, 27,'Phantom Black 128GB Storage 8GB RAM', 'Phantom Black', 128, 8, 18, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (132, 27,'Phantom Black 256GB Storage 8GB RAM', 'Phantom Black', 256, 8, 16, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (133, 27,'White 128GB Storage 8GB RAM', 'White', 128, 8, 20, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (134, 27,'White 256GB Storage 8GB RAM', 'White', 256, 8, 15, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (135, 28,'Black 128GB Storage 6GB RAM', 'Black', 128, 6, 14, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (136, 28,'Black 256GB Storage 8GB RAM', 'Black', 256, 8, 13, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (137, 28,'Blue 128GB Storage 6GB RAM', 'Blue', 128, 6, 17, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (138, 28,'Blue 256GB Storage 8GB RAM', 'Blue', 256, 8, 12, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (139, 29,'Black 128GB Storage 8GB RAM', 'Black', 128, 8, 19, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (140, 29,'Black 256GB Storage 8GB RAM', 'Black', 256, 8, 14, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (141, 29,'Silver 128GB Storage 8GB RAM', 'Silver', 128, 8, 15, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
    (142, 29,'Silver 256GB Storage 8GB RAM', 'Silver', 256, 8, 10, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(143, 30,'Sunrise Yellow 128gb Storage 4gb RAM', 'Sunrise Yellow', 128, 4, 12, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(144, 30,'Sunrise Yellow 256gb Storage 6gb RAM', 'Sunrise Yellow', 256, 6, 8, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(145, 30,'Sunrise Yellow 512gb Storage 8gb RAM', 'Sunrise Yellow', 512, 8, 5, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(146, 30,'Sunset Orange 128gb Storage 4gb RAM', 'Sunset Orange', 128, 4, 10, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(147, 30,'Sunset Orange 256gb Storage 6gb RAM', 'Sunset Orange', 256, 6, 7, '2025-01-06 16:17:05', '2025-01-06 16:28:42'),
	(148, 30,'Sunset Orange 512gb Storage 8gb RAM', 'Sunset Orange', 512, 8, 4, '2025-01-06 16:17:05', '2025-01-06 16:28:42');";

$sqlInsertProductVariant = "INSERT INTO productvariant (
        variantID,productID, variantName, productColor, productStorage, productRam, 
        productStock, createdAt, updatedAt
    ) VALUES $productVariantValues";


if ($conn->query($sqlInsertProductVariant) === TRUE) {
    echo '<div class="alert alert-success" role="alert">
            Product Variant inserted successfully!
          </div>';
    // Optionally redirect after a short delay
    echo '<script>
            setTimeout(function() {
                window.location.href = "index.php";
            }, 2000); // Redirect after 2 seconds
          </script>';
} else {
    echo '<div class="alert alert-danger" role="alert">
            Error: ' . $conn->error . '
          </div>';
}


$conn->close();
// Bootstrap closing tags
echo '</div>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>';

// End output buffering
ob_end_flush();
