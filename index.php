<?php get_header(); ?>

<?php get_template_part('includes/breadcrumbs','index'); ?>

<div id="main-content">
	<div id="main-content-bg">
		<div id="main-content-bottom-bg" class="clearfix">
			<div id="left-area">
				<div id="main-products" class="clearfix">
					<?php get_template_part('includes/entry','index'); ?>
				</div> <!-- end #main-products -->		
			</div> <!-- end #left-area -->
			<?php get_sidebar(); ?>
		</div> <!-- end #main-content-bottom-bg -->
	</div> <!-- end #main-content-bg -->
</div> <!-- end #main-content -->

<?php get_footer(); ?>