	</div> <!-- end .container -->

	<div id="footer">
		<div id="footer-top-bg">
			<div id="footer-bottom-bg">
				<div class="container">
					<?php if ( is_active_sidebar('footer-area') ) { ?>
						<div id="footer-widgets" class="clearfix">
							<?php dynamic_sidebar('footer-area'); ?>
						</div> <!-- end #footer-widgets -->
					<?php } ?>
				</div> <!-- end .container -->
			</div> <!-- end #footer-bottom-bg -->	
		</div> <!-- end #footer-top-bg -->
	</div> <!-- end #footer -->	
		
	<div id="footer-bottom">
		<div class="container clearfix">
			<?php
				$menuID = 'bottom-nav';
				$footerNav = '';

				if (function_exists('wp_nav_menu')) $footerNav = wp_nav_menu( array( 'theme_location' => 'footer-menu', 'container' => '', 'fallback_cb' => '', 'menu_id' => $menuID, 'menu_class' => 'bottom-nav', 'echo' => false, 'depth' => '1' ) );
				if ($footerNav == '') show_page_menu($menuID);
				else echo($footerNav);
			?>
			<p id="copyright">© 建恩（北京）科技有限公司</p>
		</div> <!-- end .container -->
	</div> <!-- end #footer-bottom -->
	<div id="bottom-color-stripes"></div>
	
	<?php wp_footer(); ?>

</body>
</html>