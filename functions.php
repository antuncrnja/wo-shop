<?php

//Load css and js
function load_scss_and_js(){
	wp_enqueue_style ('theme-style', get_template_directory_uri().'/css/style.min.css');
	wp_enqueue_script ('theme-style', get_template_directory_uri().'/js/app.js', [], '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'load_scss_and_js');


//Images
add_filter('jpeg_quality', function($arg){return 60;});


//Woocommerce
add_theme_support( 'woocommerce');
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
 
function shop_setup() {
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'shop_setup' );

function email_order_image( $args ) {
    $args['show_image'] = true;
    $args['image_size'] = array( 75, 75 );
    return $args;
}
add_filter( 'woocommerce_email_order_items_args', 'email_order_image', 10, 1 );

//Remove product meta tags
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );






// Add this code to handle the AJAX request and load the mini-cart content
add_action('wp_ajax_load_mini_cart', 'load_mini_cart');
add_action('wp_ajax_nopriv_load_mini_cart', 'load_mini_cart');
function load_mini_cart() {
  ob_start();

  // WooCommerce mini-cart template
  wc_get_template('cart/mini-cart.php');

  $mini_cart = ob_get_clean();

  echo $mini_cart;

  wp_die();
}

// Add this code to handle the AJAX request and remove a product from the mini-cart
add_action('wp_ajax_remove_product', 'remove_product');
add_action('wp_ajax_nopriv_remove_product', 'remove_product');
function remove_product() {
  if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    WC()->cart->remove_cart_item($product_id);

    // WooCommerce mini-cart template
    wc_get_template('cart/mini-cart.php');
  }

  wp_die();
}

