<?php 
/* 
Template Name: Home Page
*/
?>

<?php get_header(); ?>

<div id="main-content" class="fullwidth">
	<div id="main-content-bg">
		<div id="main-content-bottom-bg" class="clearfix">
			<div id="left-area">
				<div id="main-products" class="clearfix">
					<?php get_template_part('loop','page'); ?>
				</div> <!-- end #main-products -->
				<?php if (get_option('boutique_show_pagescomments') == 'on') comments_template('', true); ?>
			</div> <!-- end #left-area -->
			<div class="promos">
				<ul>				
					<li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/promo_03.jpg" alt=""></a></li>
					<li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/promo_05.jpg" alt=""></a></li>
					<li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/promo_07.jpg" alt=""></a></li>
					<li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/promo_09.jpg" alt=""></a></li>	
					<li style="clear: both; width:965px; margin: 10px 0 16px 0;height:166px;"><a href="#"><img style="display: inline;" src="<?php echo get_template_directory_uri(); ?>/images/featured_24.jpg" /><img style="display: inline;" src="<?php echo get_template_directory_uri(); ?>/images/featured_21.jpg" /><img style="display: inline;" src="<?php echo get_template_directory_uri(); ?>/images/featured_19.jpg" /><img style="display: inline;" src="<?php echo get_template_directory_uri(); ?>/images/featured_16.jpg" /></a></li>
				</ul>
			</div>
		</div> <!-- end #main-content-bottom-bg -->
	</div> <!-- end #main-content-bg -->
</div> <!-- end #main-content -->
<?php get_footer(); ?>