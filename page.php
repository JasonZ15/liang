<?php get_header(); ?>

<?php get_template_part('includes/breadcrumbs','index'); ?>

<div id="main-content">
	<div id="main-content-bg">
		<div id="main-content-bottom-bg" class="clearfix">
			<?php get_sidebar(); ?>
			<div id="left-area">
				<div id="main-products" class="clearfix">
					<?php get_template_part('loop','page'); ?>
				</div> <!-- end #main-products -->
				<?php if (get_option('boutique_show_pagescomments') == 'on') comments_template('', true); ?>
			</div> <!-- end #left-area -->
				
		</div> <!-- end #main-content-bottom-bg -->
	</div> <!-- end #main-content-bg -->
</div> <!-- end #main-content -->
<?php get_footer(); ?>