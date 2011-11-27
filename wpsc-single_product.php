<?php do_action('wpsc_product_before_description', wpsc_the_product_id(), $wp_query->post); ?>
<div class="product_description">
	<?php echo wpsc_the_product_description(); ?>
</div><!--close product_description -->
<?php do_action( 'wpsc_product_addons', wpsc_the_product_id() ); ?>		