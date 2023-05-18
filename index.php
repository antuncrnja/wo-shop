<?php get_header(); ?>
    <div id="app">

    <?php
// Initialize the product count and offset
$product_count = 0;
$offset = 0;

// WP_Query arguments
$args = array(
    'post_type'      => 'product',
    'posts_per_page' => 5, // Display 5 products initially, you can adjust this value as needed
);

// The Query
$query = new WP_Query( $args );

// The Loop
if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
        $query->the_post();

        // Get the product object
        global $product;

        // Get the product image URL
        $image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'single-post-thumbnail' );

        // Get the product permalink
        $product_permalink = get_permalink();

        // Display the product information
        ?>
        <div class="product">
            <h2><?php the_title(); ?></h2>
            <div class="product-image">
                <?php if ( has_post_thumbnail() ) : ?>
                    <a href="<?php echo $product_permalink; ?>">
                        <img src="<?php echo $image_url[0]; ?>" alt="<?php the_title(); ?>">
                    </a>
                <?php endif; ?>
            </div>
            <div class="price"><?php echo $product->get_price_html(); ?></div>
            <div class="excerpt"><?php the_excerpt(); ?></div>
            <div class="add-to-cart"><?php woocommerce_template_single_add_to_cart(); ?></div>
            <?php woocommerce_template_loop_add_to_cart(); ?>
        </div>
        <?php

        // Increment the product count
        $product_count++;

        // Break the loop if the product count is 5
        if ( $product_count === 5 ) {
            break;
        }
    }

    // Display the "Load More Products" button if there are more products
    if ( $query->found_posts > 5 ) {
        ?>
        <button id="load-more-products">Load More Products</button>
        <?php
    }
} else {
    // No products found
    echo 'No products found.';
}

// Restore original post data
wp_reset_postdata();
?>


<script>
document.addEventListener('DOMContentLoaded', function() {
  var loadMoreBtn = document.getElementById('load-more-products');
  var offset = 5; // Initial offset

  loadMoreBtn.addEventListener('click', function() {
    // Perform AJAX request to load more products
    fetch('/wp-admin/admin-ajax.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'action=load_more_products&offset=' + offset
    })
    .then(function(response) {
      if (response.ok) {
        return response.text();
      } else {
        throw new Error('Error: ' + response.status);
      }
    })
    .then(function(data) {
      // Append the loaded products to the existing ones
      var productsContainer = document.querySelector('.products-container');
      productsContainer.insertAdjacentHTML('beforeend', data);
      
      // Increment the offset
      offset += 5;
    })
    .catch(function(error) {
      console.log(error);
    });
  });
});
</script>



    </div>
<?php get_footer(); ?>
