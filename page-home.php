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
					<li><a href="/ipad/" onclick="s_objectID=&quot;http://www.apple.com/ipad/_2&quot;;return this.s_oc?this.s_oc(e):true"><img src="<?php echo get_template_directory_uri(); ?>/images/promo_03.jpg" alt="iPad 2. Now with iOS 5 and iCloud, it just got even harder to put down."></a></li>
					<li><a href="/macbookair/" onclick="s_objectID=&quot;http://www.apple.com/macbookair/_1&quot;;return this.s_oc?this.s_oc(e):true"><img src="<?php echo get_template_directory_uri(); ?>/images/promo_05.jpg" alt="MacBook Air. Everyone should have a notebook this advanced. And now everyone can."></a></li>
					<li><a href="/ipodtouch/videos/#tv-spot" onclick="s_objectID=&quot;http://www.apple.com/ipodtouch/videos/#tv-spot_1&quot;;return this.s_oc?this.s_oc(e):true"><img src="<?php echo get_template_directory_uri(); ?>/images/promo_07.jpg" alt="Watch the iPod touch TV ad."></a></li>
					<li><a href="http://store.apple.com/us/browse/holiday?aid=www-homepage-holidaygiftguide" onclick="s_objectID=&quot;http://store.apple.com/us/browse/holiday?aid=www-homepage-holidaygiftguide_1&quot;;return this.s_oc?this.s_oc(e):true"><img src="<?php echo get_template_directory_uri(); ?>/images/promo_09.jpg" alt="Shop the Holiday Gift Guide. Discover amazing presents. All with free shipping"></a></li>	
				</ul>
			</div>
		</div> <!-- end #main-content-bottom-bg -->
	</div> <!-- end #main-content-bg -->
</div> <!-- end #main-content -->
<?php get_footer(); ?>