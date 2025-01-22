const userID = window.userID || null;

function showProductDetails(productID) {
  $.ajax({
    url: "../product/get_product_details.php",
    method: "GET",
    data: {
      productID: productID,
    },
    success: function (response) {
      try {
        const product = JSON.parse(response);
        if (product) {
          // Use fallback values for price and variants
          const productPrice = Number(product.productPrice) || 0;
          const variantsHTML = product.variants
            ? product.variants
                .map((variant) => {
                  return `<option value="${variant.variantID}">${
                    variant.variantName
                  }</option>`;
                })
                .join("")
            : "<option disabled>No variants available</option>";

          const modalHTML = `
                <div class="modal fade" id="productDetailsModal_${
                  product.productID
                }" tabindex="-1" aria-labelledby="productName_${
            product.productID
          }">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="productName_${
                                  product.productID
                                }">
                                    ${product.productName || "Unknown Product"}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <img src="../uploads/${
                                  product.productImage || "default.jpg"
                                }" 
                                    id="productImage_${
                                      product.productID
                                    }" class="img-fluid mb-3" 
                                    alt="${product.productName || "Product"}">
                                <p id="productDescription_${
                                  product.productID
                                }">${
            product.productDescription || "No description available"
          }</p>
                                <p class="fw-bold" id="productPrice_${
                                  product.productID
                                }">
                                    Price: RM ${productPrice.toFixed(2)}
                                </p>

                                <!-- Variant Selection -->
                                <div class="mb-3">
                                    <label for="variantSelect_${
                                      product.productID
                                    }" class="form-label">Choose a Variant:</label>
                                    <select id="variantSelect_${
                                      product.productID
                                    }" class="form-select">
                                        ${variantsHTML}
                                    </select>
                                </div>

                                <!-- Quantity Selection -->
                                <input type="number" id="quantity_${
                                  product.productID
                                }" class="form-control mb-2" value="1" min="1">
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" id="addToCartButton_${
                                  product.productID
                                }" onclick="addToCart(${product.productID}, 
                                        document.getElementById('variantSelect_${
                                          product.productID
                                        }').value, 
                                        document.getElementById('quantity_${
                                          product.productID
                                        }').value)">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
          // Insert the modal HTML directly into the body or a predefined container
          document.body.insertAdjacentHTML("beforeend", modalHTML);

          // Manage inert and focus
          const modalElement = document.getElementById(
            `productDetailsModal_${product.productID}`
          );
          const modal = new bootstrap.Modal(modalElement, {
            backdrop: "static", // Ensures the backdrop remains and blocks interactions
            keyboard: false, // Optionally disable closing modal with keyboard (Esc)
          });

          // When the modal is shown, prevent interaction with the background content and set focus on the first button inside the modal
          modalElement.addEventListener("shown.bs.modal", () => {
            // Remove aria-hidden from the modal and prevent interaction with the background content
            modalElement.removeAttribute("aria-hidden");

            // Apply inert only to the background content, not the modal itself
            const backgroundContent = document.querySelector(
              "main, .container, .content"
            ); // Adjust based on your structure
            if (backgroundContent)
              backgroundContent.setAttribute("inert", "true");

            // Focus on the first focusable element inside the modal (usually a button)
            const firstFocusableElement = modalElement.querySelector(
              "button, input, select, textarea"
            );
            if (firstFocusableElement) firstFocusableElement.focus();
          });

          // When the modal is hidden, remove inert and ensure everything is focusable again
          modalElement.addEventListener("hidden.bs.modal", () => {
            modalElement.setAttribute("aria-hidden", "true");
            const backgroundContent = document.querySelector(
              "main, .container, .content"
            );
            if (backgroundContent) backgroundContent.removeAttribute("inert");
          });

          // Show the modal
          modal.show();
        }
      } catch (error) {
        console.error("Error parsing product details:", error);
      }
    },
    error: function (xhr, status, error) {
      console.error("Error fetching product details:", error);
    },
  });
}

// Add product to cart
function addToCart(productID, variantID, quantity) {
  // Validate inputs
  if (!productID || !variantID || !quantity || quantity <= 0) {
    displayError("Invalid product or quantity. Please try again.");
    return;
  }

  const customerID = userID; // Fetch customerID from session (PHP side)
  const createdAt = new Date().toISOString();
  const updatedAt = createdAt;

  // Get product details from the DOM
  const productNameElement = document.getElementById(`productName_${productID}`);
  const productPriceElement = document.getElementById(`productPrice_${productID}`);
  const productImageElement = document.getElementById(`productImage_${productID}`);

  if (!productNameElement || !productPriceElement || !productImageElement) {
    displayError("Error: Missing product details in the DOM.");
    return;
  }

  const productName = productNameElement.innerText || "Unknown Product";
  const productPrice = parseFloat(
    productPriceElement.innerText.replace("Price: RM ", "").trim()
  ) || 0.0;
  const productImage = productImageElement.src || "";

  // Validate price
  if (productPrice <= 0) {
    displayError("Invalid product price. Please try again.");
    return;
  }

  const addToCartButton = document.getElementById(`addToCartButton_${productID}`);

  $.ajax({
    url: "../cart/add_to_cart.php",
    method: "POST",
    data: {
      productID: parseInt(productID, 10),
      productName,
      productPrice,
      productImage,
      variantID: parseInt(variantID, 10),
      quantity: parseInt(quantity, 10),
      createdAt,
      updatedAt,
    },
    beforeSend: function () {
      // Disable the add-to-cart button to prevent multiple submissions
      if (addToCartButton) {
        addToCartButton.disabled = true;
      }
    },
    success: function (response) {
      try {
        const responseData = JSON.parse(response);

        if (responseData.error) {
          displayError(responseData.error);
          return;
        }

        // Success handling
        displaySuccess("Product added to cart successfully!");

        // Update cart count
        const cartCountElement = document.getElementById("cartCount");
        if (responseData.cartCount !== undefined && cartCountElement) {
          cartCountElement.innerText = responseData.cartCount;
        }

        // Update cart modal
        if (responseData.cart) {
          updateCartModal(responseData.cart);
        }

        // Close the product modal
        const productModal = document.getElementById(`productDetailsModal_${productID}`);
        if (productModal) {
          const modal = bootstrap.Modal.getInstance(productModal);
          if (modal) modal.hide();
        }
      } catch (error) {
        displayError("Error parsing server response.");
        console.error("Error parsing response:", error, "Response:", response);
      }
    },
    error: function (xhr, status, error) {
      const errorMessage = xhr.responseText || "An error occurred while adding the product to the cart.";
      displayError(errorMessage);
      console.error("Add to cart error:", errorMessage, "Status:", status, "XHR:", xhr);
    },
    complete: function () {
      // Re-enable the add-to-cart button
      if (addToCartButton) {
        addToCartButton.disabled = false;
      }
    },
  });
}



// Update the cart modal with current cart data
function updateCartModal(cart) {
  let cartItemsHTML = "";
  let total = 0;

  // Check if cart is an array and has items
  if (Array.isArray(cart) && cart.length > 0) {
    cart.forEach((item) => {
      if (
        item.productName &&
        item.quantity &&
        item.productPrice &&
        item.productImage
      ) {
        const itemPrice = Number(item.productPrice) || 0;
        const itemQuantity = item.quantity || 1;
        const itemTotalPrice = itemPrice * itemQuantity;

        // Build the cart item HTML
        cartItemsHTML += `
            <div class="cart-item card mb-3 shadow-sm" data-product-id="${
              item.productID
            }" data-variant-id="${item.variantID}">
                <div class="card-body d-flex justify-content-between align-items-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <img src="../uploads/${item.productImage}" alt="${
          item.productName
        }" class="rounded img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                        <div>
                            <h6 class="mb-1">${item.productName} 
                                <small class="text-muted">(x${itemQuantity})</small>
                                ${
                                  item.variantName
                                    ? `<br><small class="text-muted">Variant: ${item.variantName}</small>`
                                    : ""
                                }
                            </h6>
                            <p class="mb-0 text-primary fw-bold">RM ${itemTotalPrice.toFixed(
                              2
                            )}</p>
                        </div>
                    </div>
                    <button class="btn btn-danger btn-sm" onclick="removeFromCart(${
                      item.productID
                    }, ${item.variantID})">
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
  $("#cartItems").html(cartItemsHTML);
  $("#cartTotal").text(total.toFixed(2));

  // Open modal when the cart icon is clicked
  $("#cartIcon").click(function () {
    const modal = new bootstrap.Modal(document.getElementById("cartModal"));
    modal.show();
  });
}

// Remove product from cart
function removeFromCart(productID, variantID) {
  $.ajax({
    url: "../cart/remove_from_cart.php",
    method: "POST",
    data: {
      productID,
      variantID,
    },
    success: function (response) {
      try {
        const responseData = JSON.parse(response);

        if (responseData.error) {
          displayError(responseData.error);
          return;
        }

        // Update the modal with the new cart data
        updateCartModal(responseData.cart);

        // Update the cart count
        const cartCountElement = document.getElementById("cartCount");
        if (responseData.cartCount !== undefined) {
          cartCountElement.innerText = responseData.cartCount;
        }
      } catch (error) {
        displayError("Error parsing server response.");
        console.error("Error parsing response:", error);
      }
    },
    error: function (xhr, status, error) {
      const errorMessage = xhr.responseText || "An error occurred while removing the product from the cart.";
      displayError(errorMessage);
      console.error("Remove from cart error:", errorMessage);
    },
  });
}

// Initialize the cart modal when the page loads
$(document).ready(function () {
  $.ajax({
    url: "../cart/get_cart.php", // Endpoint to fetch the cart data
    method: "GET",
    success: function (response) {
      try {
        const responseData = JSON.parse(response); // Parse the JSON response
        if (responseData && responseData.cart) {
          const cart = responseData.cart || []; // Get the cart array
          updateCartModal(cart); // Update the modal with the cart data

          // Update the cart count
          const cartCount = cart.reduce(
            (total, item) => total + item.quantity,
            0
          );
          document.getElementById("cartCount").innerText = cartCount; // Update the cart count on the page
        }
      } catch (error) {
        console.error("Error parsing the cart data:", error); // Handle errors if parsing fails
      }
    },
    error: function (xhr, status, error) {
      console.error("Get cart error:", xhr.responseText); // Handle errors from the server response
    },
  });
});

function displayError(message) {
  Swal.fire({
    icon: "error",
    title: "Error",
    text: message,
    confirmButtonText: "OK",
  });
}

function displaySuccess(message) {
  Swal.fire({
    icon: "success",
    title: "Success",
    text: message,
    showConfirmButton: false,
    timer: 3000, // Auto-hide after 3 seconds
  });
}

