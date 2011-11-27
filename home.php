<?php get_header(); ?>

<?php if (get_option('boutique_special_offers') == 'on') { ?>
	<h3 class="offer-title" id="special-offers"><span><?php echo esc_html(get_option('boutique_special_offers_heading')); ?></span></h3>
	<div id="special-items" class="clearfix">
		<?php 
			$special_offers_args = array(
				'showposts' => (int) get_option('boutique_special_offers_numposts'),
				'cat' => get_cat_ID(get_option('boutique_special_offers_category'))
			);
			
			global $boutique_active_plugin_name;
			if ( $boutique_active_plugin_name == 'wp_ecommerce' ){
				$special_offers_term = get_term_by('name', get_option('boutique_special_offers_category'), 'wpsc_product_category');
				
				$special_offers_args = array(
					'post_type' => 'wpsc-product',
					'showposts' => (int) get_option('boutique_special_offers_numposts'),
					'tax_query' => array(
						array(
							'taxonomy' => 'wpsc_product_category',
							'field' => 'id',
							'terms' => $special_offers_term->term_id
						)
					)
				);
			}
			
			$i = 0;
			
			$special_offers_query = new WP_Query($special_offers_args);
		?>
		<?php if ($special_offers_query->have_posts()) : while ($special_offers_query->have_posts()) : $special_offers_query->the_post(); ?>
			<?php $i++; ?>
			<div class="special-item<?php if ( $i % 5 == 0 ) echo ' last'; ?>">
				<?php
					$width = 137;
					$height = 121;
					$titletext = get_the_title();
					$thumbnail = get_thumbnail($width,$height,'',$titletext,$titletext,false,'Offer');
					$thumb = $thumbnail["thumb"];
				?>
				<a href="<?php the_permalink(); ?>">
					<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, ''); ?>
					<span class="price-tag"><span><?php echo boutique_currency_sign(); ?></span><?php echo boutique_price(); ?></span>
				</a>
				<h4 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
				<div class="et-links clearfix">
					<a href="#<?php echo boutique_product_name('offer'); ?>" class="add-to-cart et-shop-item"><?php esc_html_e('Add to cart','Boutique'); ?></a>
					<a href="<?php the_permalink(); ?>" class="more-info"><?php esc_html_e('More Info','Boutique'); ?></a>
				</div> <!-- end .et-links -->
				<?php do_action('boutique_special_offer'); ?>
			</div> <!-- end .special-item -->
		<?php endwhile; endif; wp_reset_postdata(); ?>
	</div> <!-- end #special-items -->	
<?php } ?>
	
<div id="main-content">
	<div id="main-content-bg">
		<div id="main-content-bottom-bg" class="clearfix">
			<div id="left-area">
				<h3 class="offer-title" id="recent-products"><span><?php echo esc_html(get_option('boutique_recent_products_heading')); ?></span></h3>
				<div id="main-products" class="clearfix">
					<?php get_template_part('includes/entry','home'); ?>
				</div> <!-- end #main-products -->		
			</div> <!-- end #left-area -->
			
			<?php get_sidebar(); ?>
			
		</div> <!-- end #main-content-bottom-bg -->
	</div> <!-- end #main-content-bg -->
</div> <!-- end #main-content -->

<?php get_footer(); ?>