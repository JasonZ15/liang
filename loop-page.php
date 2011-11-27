<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<div class="entry post clearfix">				
		<?php if (get_option('boutique_page_thumbnails') == 'on') { ?>
			<?php 
				$thumb = '';
				$width = 200;
				$height = 200;
				$classtext = 'single-thumb';
				$titletext = get_the_title();
				$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext,false,'Entry');
				$thumb = $thumbnail["thumb"];
			?>
			
			<?php if($thumb <> '') { ?>
				<div class="thumb">
					<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
					<span class="overlay"></span>
				</div> 	<!-- end .thumb -->
			<?php } ?>
		<?php } ?>

		<?php the_content(); ?>
		<?php wp_link_pages(array('before' => '<p><strong>'.esc_attr__('Pages','Boutique').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		<?php edit_post_link(esc_attr__('Edit this page','Boutique')); ?>
	</div> <!-- end .entry -->
<?php endwhile; // end of the loop. ?>