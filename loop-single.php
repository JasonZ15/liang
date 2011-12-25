<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<div class="entry post clearfix">
		<?php if (get_option('boutique_integration_single_top') <> '' && get_option('boutique_integrate_singletop_enable') == 'on') echo(get_option('boutique_integration_single_top')); ?>
		
	<?php
		if ( boutique_is_single_blog_post() )
			boutique_display_blogpost();
		else {
	?>
			<div id="et-slides">
				<div id="et-slides-items">
					<?php
						$custom_field_image = '';
						$custom_field_image = get_post_meta($post->ID, 'Single', true) ? get_post_meta($post->ID, 'Single', true) : get_post_meta($post->ID, 'Thumbnail', true);
						if ( $custom_field_image <> '' ) {
							echo '<div class="et-slide">';
								echo '<a href="'.$custom_field_image.'" class="fancybox" rel="single-gallery" title="' . get_the_title() . '">' . et_new_thumb_resize( et_multisite_thumbnail($custom_field_image), 249, 243 ) . '<span class="overlay"></span> <span class="magnify"></span></a>';
							echo '</div> <!-- #et-slide -->';
						}
						
						$images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'numberposts' => 999, 'order'=> 'ASC', 'orderby' => 'menu_order' ) ); 
						if ( $images ) {
							foreach ( $images as $image ){
								$gallery_image_info = wp_get_attachment_image_src( $image->ID, 'full' );
								$gallery_image = $gallery_image_info[0];
								if ( ($custom_field_image <> '') && ($custom_field_image == $gallery_image) ) continue;
								
								$gallery_image_alt = get_post_meta($image->ID, '_wp_attachment_image_alt', true) ? trim(strip_tags( get_post_meta($image->ID, '_wp_attachment_image_alt', true) )) : trim(strip_tags( $image->post_title ));
								
								echo '<div class="et-slide">';
									echo '<a href="'.$gallery_image.'" title="'.$gallery_image_alt.'" class="fancybox" rel="single-gallery">' . et_new_thumb_resize( et_multisite_thumbnail($gallery_image), 249, 243 ) . '<span class="overlay"></span> <span class="magnify"></span></a>';
								echo '</div> <!-- #et-slide -->';
							}
						}
					?>
				</div> <!-- #et-slides-items -->
				<span class="price-tag"><span>$<?php //echo boutique_currency_sign(); ?></span><?php echo boutique_price(); ?></span>
				<a id="et-single-left-arrow" href="#"><?php esc_html_e('Previous','Boutique'); ?></a>
				<a id="et-single-right-arrow" href="#"><?php esc_html_e('Next','Boutique'); ?></a>
			</div> <!-- #et-slides -->
			
			<div class="item-description">
				<div class="single-item-ratings clearfix">
					<?php et_boutique_display_rating(); ?>
				</div> <!-- end .single-item-ratings -->
			
				<?php the_excerpt(); ?>
				<a class="single-addtocart et-shop-item" href="#<?php echo boutique_product_name('entry'); ?>"><?php esc_html_e('加入购物车', 'Boutique'); ?></a>
				<?php do_action('boutique_product_entry'); ?>
			</div> <!-- end .item-description -->
			
			<div class="clear"></div>
		<?php } ?>
		
		<?php the_content(); ?>
		<?php wp_link_pages(array('before' => '<p><strong>'.esc_attr__('Pages','Boutique').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		<?php edit_post_link(esc_attr__('Edit this page','Boutique')); ?>
	</div> <!-- end .entry -->
	
	<?php do_action('boutique_single_before_comments'); ?>

	<?php if (get_option('boutique_integration_single_bottom') <> '' && get_option('boutique_integrate_singlebottom_enable') == 'on') echo(get_option('boutique_integration_single_bottom')); ?>		
					
	<?php if (get_option('boutique_468_enable') == 'on') { ?>
			  <?php if(get_option('boutique_468_adsense') <> '') echo(get_option('boutique_468_adsense'));
			else { ?>
			   <a href="<?php echo esc_url(get_option('boutique_468_url')); ?>"><img src="<?php echo esc_url(get_option('boutique_468_image')); ?>" alt="468 ad" class="foursixeight" /></a>
	   <?php } ?>   
	<?php } ?>

	<?php if (get_option('boutique_show_postcomments') == 'on') comments_template('', true); ?>
<?php endwhile; // end of the loop. ?>