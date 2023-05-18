<!DOCTYPE html>
<html <?php language_attributes();?>>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php the_title(); ?></title>

	<?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>

  

  <style>
  /* Add this code to style the mini-cart */
  #mini-cart {
    position: fixed;
    top: 0;
    right: -300px; /* Initially hidden */
    width: 300px;
    height: 100%;
    background-color: #fff;
    transition: right 0.3s ease-in-out;
  }

  #mini-cart.open {
    right: 0; /* Slide in from right to left when open */
  }
</style>

<a href="#" id="cart-icon">
  <img src="path/to/cart-icon.png" alt="Cart Icon">
</a>

<div id="mini-cart">
  <!-- The content of the mini-cart will be dynamically loaded here -->
</div>


<script>
  document.addEventListener('DOMContentLoaded', function() {
    var cartIcon = document.getElementById('cart-icon');
    var miniCart = document.getElementById('mini-cart');
    var isMiniCartLoaded = false;

    // Trigger the AJAX request when the cart icon is clicked
    cartIcon.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();

      // Toggle the mini-cart open/close
      miniCart.classList.toggle('open');

      // If the mini-cart is not yet loaded, fetch the content via AJAX
      if (!isMiniCartLoaded) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/wp-admin/admin-ajax.php'); // Adjust the URL as per your setup
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
          if (xhr.status === 200) {
            // Update the content of the mini-cart
            miniCart.innerHTML = xhr.responseText;
            isMiniCartLoaded = true;
          }
        };
        xhr.onerror = function(error) {
          console.log(error);
        };
        xhr.send('action=load_mini_cart'); // Custom AJAX action to load the mini-cart content
      }
    });

    // Close the mini-cart when clicking outside of it
    document.addEventListener('click', function(e) {
      if (!miniCart.contains(e.target) && !cartIcon.contains(e.target)) {
        miniCart.classList.remove('open');
      }
    });

    // Handle product removal from the mini cart using AJAX
    miniCart.addEventListener('click', function(e) {
      if (e.target.classList.contains('remove-button')) {
        e.preventDefault();
        e.stopPropagation();

        var productId = e.target.getAttribute('data-product-id');
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/wp-admin/admin-ajax.php'); // Adjust the URL as per your setup
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
          if (xhr.status === 200) {
            // Update the content of the mini-cart
            miniCart.innerHTML = xhr.responseText;
          }
        };
        xhr.onerror = function(error) {
          console.log(error);
        };
        xhr.send('action=remove_product&product_id=' + productId); // Custom AJAX action to remove the product
      }
    });
  });
</script>
