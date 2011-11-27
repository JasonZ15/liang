<div id="featured">
	<a id="left-arrow" href="#"><?php esc_html_e('Previous','Boutique'); ?></a>
	<a id="right-arrow" href="#"><?php esc_html_e('Next','Boutique'); ?></a>

	<div id="slides">
		<?php global $ids, $boutique_active_plugin_name;
		$ids = array();
		$arr = array();
		
		$featured_cat = get_option('boutique_feat_cat'); 
		$featured_num = get_option('boutique_featured_num'); 
	
		if (get_option('boutique_use_pages') == 'false') { 
			$featured_args = array(
				'showposts' => $featured_num,
				'cat' => get_cat_ID($featured_cat)
			);
		}
		else {
			global $pages_number;
			
			if (get_option('boutique_feat_pages') <> '') $featured_num = count(get_option('boutique_feat_pages'));
			else $featured_num = $pages_number;
			
			$featured_args = array(
							'post_type' => 'page',
							'orderby' => 'menu_order',
							'order' => 'ASC',
							'post__in' => (array) get_option('boutique_feat_pages'),
							'showposts' => (int) $featured_num
						);
		}
		
		if ( $boutique_active_plugin_name == 'wp_ecommerce' ){
			$et_featured_term = get_term_by('name', $featured_cat, 'wpsc_product_category');
			
			$featured_args = array(
				'post_type' => 'wpsc-product',
				'showposts' => $featured_num,
				'tax_query' => array(
					array(
						'taxonomy' => 'wpsc_product_category',
						'field' => 'id',
						'terms' => $et_featured_term->term_id
					)
				)
			);
		}
		
		$featured_query = new WP_Query($featured_args);
		?>
		<?php if ($featured_query->have_posts()) : while ($featured_query->have_posts()) : $featured_query->the_post(); ?>
			<div class="slide">
				<?php
					$width = 380;
					$height = 230;
					$titletext = get_the_title();
					$thumbnail = get_thumbnail($width,$height,'',$titletext,$titletext,false,'Featured');
					$thumb = $thumbnail["thumb"];
					$featured_link = get_post_meta($post->ID,'Link',true) ? get_post_meta($post->ID,'Link',true) : get_permalink();
				?>
				
				<div class="featured-image">
					<a href="<?php echo $featured_link; ?>"><?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, ''); ?></a>
					<div class="featured-price">
						<a href="#<?php echo boutique_product_name('featured'); ?>" class="add_cart et-shop-item"><?php esc_html_e('Add To Cart','Boutique'); ?></a>
						<a href="<?php echo esc_url($featured_link); ?>" class="more_info"><?php esc_html_e('More Info','Boutique'); ?></a>
						
						<span class="price"><?php echo boutique_price(); ?></span>
						<span class="currency_sign"><?php echo boutique_currency_sign(); ?></span>
					</div> <!-- end .featured-price -->
				</div> <!-- end .featured-image -->
				<div class="featured-description">
					<h2 class="featured-title"><a href="<?php echo esc_url($featured_link); ?>"><?php the_title(); ?></a></h2>
					<p><?php truncate_post(190); ?></p>
					<a href="<?php echo esc_url($featured_link); ?>" class="readmore"><span><?php esc_html_e('More Info', 'Boutique'); ?></span></a>
					
					<?php do_action('boutique_featured_product'); ?>
				</div> <!-- end .description -->
			</div> <!-- end .slide -->
		<?php $ids[] = $post->ID; endwhile; endif; wp_reset_postdata(); ?>
	</div> <!-- end #slides -->
	
	<div id="controllers"></div>
</div> <!-- end #featured -->