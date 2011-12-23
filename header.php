<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php elegant_titles(); ?></title>
<?php elegant_description(); ?>
<?php elegant_keywords(); ?>
<?php elegant_canonical(); ?>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/colorpicker.css" type="text/css" media="screen" />

<link href='http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold' rel='stylesheet' type='text/css'/>
<link href='http://fonts.googleapis.com/css?family=Kreon:light,regular' rel='stylesheet' type='text/css'/>
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ie6style.css" />
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/DD_belatedPNG_0.0.8a-min.js"></script>
	<script type="text/javascript">DD_belatedPNG.fix('img#logo, span.overlay, a.zoom-icon, a.more-icon, #menu, #menu-right, #menu-content, ul#top-menu ul, #menu-bar, .footer-widget ul li, span.post-overlay, #content-area, .avatar-overlay, .comment-arrow, .testimonials-item-bottom, #quote, #bottom-shadow, #quote .container');</script>
<![endif]-->
<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ie7style.css" />
<![endif]-->
<!--[if IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ie8style.css" />
<![endif]-->

<script type="text/javascript">
	document.documentElement.className = 'js';
</script>

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
	<?php do_action('et_header_top'); ?>
	<div id="color-stripes"></div>
	<div id="header">
		<div class="container clearfix">
			
			
			<div id="search-form">
				<form method="get" id="searchform" action="<?php echo home_url(); ?>/">
					<input type="text" value="<?php esc_attr_e('Search...', 'Boutique'); ?>" name="s" id="searchinput" />
					<input type="image" src="<?php echo get_template_directory_uri(); ?>/images/search_btn.png" id="searchsubmit" />
				</form>
			</div> <!-- end #search-form -->
			
			<?php $menuClass = 'nav';
			$menuID = 'top-menu';
			$primaryNav = '';
			if (function_exists('wp_nav_menu')) {
				$primaryNav = wp_nav_menu( array( 'theme_location' => 'primary-menu', 'container' => '', 'fallback_cb' => '', 'menu_class' => $menuClass, 'menu_id' => $menuID, 'echo' => false ) );
			};
			if ($primaryNav == '') { ?>
				<ul id="<?php echo $menuID; ?>" class="<?php echo $menuClass; ?>">
					<?php if (get_option('boutique_home_link') == 'on') { ?>
						<li <?php if (is_home()) echo('class="current_page_item"') ?>><a href="<?php echo home_url(); ?>"><?php esc_html_e('Home','Boutique') ?></a></li>
					<?php }; ?>

					<?php show_page_menu($menuClass,false,false); ?>
					<?php show_categories_menu($menuClass,false); ?>
				</ul> <!-- end ul#nav -->
			<?php }
			else echo($primaryNav); ?>
			
			<div class="clear"></div>
						<div id="menu-content">
				<!--<div id="menu-shadow"></div>-->
				<?php $menuClass = 'nav clearfix';
				$menuID = 'secondary-menu';
				$secondaryNav = '';
				if (function_exists('wp_nav_menu')) {
					$secondaryNav = wp_nav_menu( array( 'theme_location' => 'secondary-menu', 'container' => '', 'fallback_cb' => '', 'menu_class' => $menuClass, 'menu_id' => $menuID, 'echo' => false ) );
				};
				if ($secondaryNav == '') { ?>
					<ul id="<?php echo $menuID; ?>" class="<?php echo $menuClass; ?>">
						<?php if (get_option('boutique_home_link') == 'on') { ?>
							<li <?php if (is_home()) echo('class="current_page_item"') ?>><a href="<?php echo home_url(); ?>"><?php esc_html_e('Home','Boutique') ?></a></li>
						<?php }; ?>

						<?php show_page_menu($menuClass,false,false); ?>
						<?php show_categories_menu($menuClass,false); ?>
					</ul> <!-- end ul#nav -->
				<?php }
				else echo($secondaryNav); ?>
			</div> <!-- menu-content -->
		</div> <!-- end .container -->
	</div> <!-- end #header -->
	
	<div id="top-area">
		<div id="top-area-bg">
			<div id="bottom-area-bg">
				<div class="container">
					<?php 
						if ( is_home() && get_option('boutique_featured') == 'on' ) get_template_part('includes/featured','home');
						else get_template_part('includes/top_info','index');
					?>
				</div> <!-- end .container -->
			</div> <!-- end #bottom-area-bg -->
		</div> <!-- end #top-area-bg -->
	</div> <!-- end #top-area -->
	
	<div id="main-content-area" class="container">