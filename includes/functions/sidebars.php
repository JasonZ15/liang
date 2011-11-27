<?php
if ( function_exists('register_sidebar') ) {
    register_sidebar(array(
		'name' => 'Sidebar',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div> <!-- end .widget-content--></div> <!-- end .widget -->',
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4><div class="widget-content">',
    ));
	
	register_sidebar(array(
		'id' => 'footer-area',
		'name' => 'Footer',
		'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
		'after_widget' => '</div> <!-- end .footer-widget -->',
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
    ));
} 
?>