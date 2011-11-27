<?php 
	$i = 0;
	$et_is_blog_category = false;
	if ( is_home() ){
		$boutique_active_plugin_name = boutique_active_plugin();
		$recent_products_args = array(
			'posts_per_page' => (int) get_option('boutique_homepage_posts'),
			'paged' => $paged,
			'category__not_in' => (array) get_option('boutique_exlcats_recent')
		);
		
		if (get_option('boutique_duplicate') == 'false') {
			global $ids;
			$recent_products_args['post__not_in'] = $ids;
		}
		
		if ( $boutique_active_plugin_name == 'wp_ecommerce' ){
			unset($recent_products_args['category__not_in']);
			$recent_products_args['post_type'] = 'wpsc-product';
			$recent_products_args['tax_query'] = array(
				array(
					'taxonomy' => 'wpsc_product_category',
					'field' => 'id',
					'terms' => get_option('boutique_exlcats_recent'),
					'operator' => 'NOT IN',
				)
			);
		}
		
		query_posts($recent_products_args);
	}
	if ( is_category() ) {
		$et_blog_categories = get_option('boutique_blog_categories');
		if ( $et_blog_categories !== false && in_array( $cat, $et_blog_categories ) ) $et_is_blog_category = true;
	}
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php
		$i++; 
		if ( $et_is_blog_category ) boutique_display_blogpost();
		else boutique_display_product($i,'entry');
	?>
<?php 
endwhile; 
	if (function_exists('wp_pagenavi')) { wp_pagenavi(); }
	else { get_template_part('includes/navigation','entry'); }
else:
	get_template_part('includes/no-results','entry');
endif; 
if ( is_home() ) wp_reset_query(); ?>