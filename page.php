<?php get_header();?>

<div class="container <?php if(is_cart()){ echo 'cart-container'; } ?><?php if(is_checkout()){ echo 'checkout-container'; } ?>">
	
    <?php 
    if( have_posts() ): ?>
         <?php the_content();?>
    <?php endif;?>
</div>

<?php get_footer();?>