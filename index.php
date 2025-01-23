<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/TeiponGadgetSystem/config/db_config.php');

// Fetch all products from the database
$sql = "SELECT productName, productDescription, productPrice, productImage FROM Product";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home</title>
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
    .product-image-container {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
      height: 300px;
    }

    .product-image {
      width: 250px;
      height: 250px;
      object-fit: contain;
    }

    .member-image {
      width: 150px;
      height: 165px;
      object-fit: cover;
      border-radius: 20%;
    }

    .about-us-section {
      background-color: #f8f9fa;
      padding: 130px 0;
      /* Increase padding to make it taller */
    }

    .about-us-heading {
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 30px;
    }

    .container.my-5 {
      padding: 45px 0;
    }

    /* Ensure the arrows are visible and styled correctly */
    .fas.fa-chevron-left,
    .fas.fa-chevron-right {
      visibility: visible !important;
      opacity: 1 !important;
      width: 50px;
      /* Adjust the size of the arrows */
      color: blue;
      /* Change arrow color to blue */
    }

    /* Position the left arrow outside the image area */
    .carousel-control-prev {
      left: -150px;
      /* Move the left arrow to the far left */
      position: absolute;
      /* Ensure it’s positioned absolutely */
      z-index: 1;
      /* Keep the arrow in front of the carousel */
    }

    /* Position the right arrow outside the image area */
    .carousel-control-next {
      right: -150px;
      /* Move the right arrow to the far right */
      position: absolute;
      /* Ensure it’s positioned absolutely */
      z-index: 1;
      /* Keep the arrow in front of the carousel */
    }

    /* Optional: Removing the outline when clicking the button */
    .carousel-control-prev:focus,
    .carousel-control-next:focus {
      outline: none;
    }
  </style>
</head>

<body>
  <?php include('navbar/navbar.php'); ?>

  <!-- Hero Section -->
  <section class="bg-dark text-white text-center py-5">
    <div class="container">
      <h1 class="display-4">Teipon Gadget</h1>
    </div>
  </section>

  <!-- Product Carousel Section -->
  <div class="container my-5">
    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php
        if ($result->num_rows > 0) {
          $products = [];
          while ($row = $result->fetch_assoc()) {
            $productName = !empty($row['productName']) ? htmlspecialchars($row['productName']) : 'Unnamed Product';
            $productDescription = !empty($row['productDescription']) ? htmlspecialchars($row['productDescription']) : 'No description available.';
            $productPrice = !empty($row['productPrice']) ? number_format($row['productPrice'], 2) : '0.00';
            $productImage = !empty($row['productImage']) ? htmlspecialchars($row['productImage']) : 'placeholder.jpg';

            $products[] = [
              'name' => $productName,
              'description' => $productDescription,
              'price' => $productPrice,
              'image' => $productImage
            ];
          }

          // Display products in slides (4 products per slide)
          $chunkedProducts = array_chunk($products, 4);
          foreach ($chunkedProducts as $index => $productGroup) {
            $isActive = $index === 0 ? 'active' : '';
            echo '<div class="carousel-item ' . $isActive . '">
                    <div class="row">';
            foreach ($productGroup as $product) {
              echo '
                  <div class="col-md-3 col-sm-6">
                    <div class="card mb-4">
                      <div class="product-image-container">
                        <img src="uploads/' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '" class="card-img-top product-image">
                      </div>
                      <div class="card-body">
                        <h5 class="card-title">' . htmlspecialchars($product['name']) . '</h5>
                        <p class="card-text">' . htmlspecialchars($product['description']) . '</p>
                        <p class="fw-bold">Price: RM ' . htmlspecialchars($product['price']) . '</p>
                        <a href="#" class="btn btn-outline-primary" onclick="guestAlert(event)">Buy</a>
                      </div>
                    </div>
                  </div>';
            }
            echo '</div></div>';
          }
        } else {
          echo '<p class="text-center">No products available at the moment.</p>';
        }
        ?>
      </div>

      <!-- Carousel Controls -->
      <?php if ($result->num_rows > 4): ?>
        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
          <span class="fas fa-chevron-left" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
          <span class="fas fa-chevron-right" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      <?php endif; ?>
    </div>
  </div>

  <!-- About Us Section -->
  <section id="team-section" class="about-us-section">
    <div class="container text-center">
      <h2 class="about-us-heading">About Us</h2>
      <div class="row">
        <?php
        $members = [
          ["name" => "Hazik Haikal", "role" => "Team Leader", "image" => "members/hazik.jpeg"],
          ["name" => "Muhammad Naqib", "role" => "Software Requirement Analyst", "image" => "members/naqib.jpeg"],
          ["name" => "Muhammad Mahdi", "role" => "Software Designer", "image" => "members/mahdi.jpeg"],
          ["name" => "Muhammad Luqman", "role" => "Software Tester", "image" => "members/muizz.jpeg"],
          ["name" => "Eiman Damien", "role" => "Software Developer", "image" => "members/eiman.jpeg"],
          ["name" => "Mohamad Syazmir", "role" => "Software Developer", "image" => "members/syazmir.jpeg"],
          ["name" => "Muhammad Taufiq", "role" => "Software Engineer", "image" => "members/taufiq.jpeg"],
          ["name" => "Syariful Husaini", "role" => "UI/UX Designer", "image" => "members/syariful.jpeg"],
        ];

        foreach ($members as $member) {
          echo '
            <div class="col-md-3 col-sm-6 mb-4">
              <div class="text-center">
                <img src="' . $member['image'] . '" alt="' . $member['name'] . '" class="member-image mb-3">
                <h5>' . htmlspecialchars($member['name']) . '</h5>
                <p>' . htmlspecialchars($member['role']) . '</p>
              </div>
            </div>';
        }
        ?>
      </div>
    </div>
  </section>

  <!-- Bootstrap Modal -->
  <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="loginModalLabel">Login Required</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>You need to log in to make a purchase. Press <strong>Login</strong> to proceed to the login page or
            <strong>Cancel</strong> to stay here.
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <a href="login/login.php" class="btn btn-primary">Login</a>
        </div>
      </div>
    </div>
  </div>

  <footer class="bg-light text-center py-4">
    <p class="mb-0">© Teipon Gadget 2025. All Rights Reserved.</p>
  </footer>

  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script>
    function guestAlert(event) {
      event.preventDefault();
      // Show the Bootstrap modal
      const modal = new bootstrap.Modal(document.getElementById('loginModal'));
      modal.show();
    }
  </script>
</body>

</html>