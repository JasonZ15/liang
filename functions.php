<?php 

if ( ! function_exists( 'boutique_active_plugin' ) ) :
function boutique_active_plugin(){
	$active_plugins = get_option('active_plugins');
	$plugin_name = '';
	
	if ( in_array( 'shopp/Shopp.php',$active_plugins ) || in_array( 'Shopp.php',$active_plugins ) ) $plugin_name = 'shopp';
	elseif ( in_array( 'wp-e-commerce/wp-shopping-cart.php',$active_plugins ) ) $plugin_name = 'wp_ecommerce';
	elseif ( in_array( 'eshop/eshop.php',$active_plugins ) || in_array( 'eshop.php',$active_plugins ) ) $plugin_name = 'eshop';
	elseif ( in_array( 'cart66-lite/cart66.php',$active_plugins ) || in_array( 'cart66/cart66.php',$active_plugins ) || in_array( 'cart66-pro/cart66.php',$active_plugins ) )	$plugin_name = 'cart66';
	elseif ( in_array( 'wordpress-simple-paypal-shopping-cart/wp_shopping_cart.php',$active_plugins ) ) $plugin_name = 'wp_simple_paypal_sc';
	
	return ( $plugin_name <> '' ) ? $plugin_name : false;
}
endif;

add_action( 'after_setup_theme', 'et_setup_theme' );
if ( ! function_exists( 'et_setup_theme' ) ){
	function et_setup_theme(){
		require_once(TEMPLATEPATH . '/epanel/custom_functions.php'); 

		require_once(TEMPLATEPATH . '/includes/functions/comments.php'); 

		require_once(TEMPLATEPATH . '/includes/functions/sidebars.php'); 

		load_theme_textdomain('Boutique',get_template_directory().'/lang');

		require_once(TEMPLATEPATH . '/epanel/options_boutique.php');

		require_once(TEMPLATEPATH . '/epanel/core_functions.php'); 

		require_once(TEMPLATEPATH . '/epanel/post_thumbnails_boutique.php');
		
		include(TEMPLATEPATH . '/includes/widgets.php');
	}
}

add_action('wp_head','et_portfoliopt_additional_styles',100);
function et_portfoliopt_additional_styles(){ ?>
	<style type="text/css">
		#et_pt_portfolio_gallery { margin-left: -41px; margin-right: -51px; }
		.et_pt_portfolio_item { margin-left: 35px; }
		.et_portfolio_small { margin-left: -40px !important; }
		.et_portfolio_small .et_pt_portfolio_item { margin-left: 32px !important; }
		.et_portfolio_large { margin-left: -26px !important; }
		.et_portfolio_large .et_pt_portfolio_item { margin-left: 11px !important; }
	</style>
<?php }

function register_main_menus() {
	register_nav_menus(
		array(
			'primary-menu' => __( 'Primary Menu' ),
			'secondary-menu' => __( 'Secondary Menu' ),
			'footer-menu' => __( 'Footer Menu' )
		)
	);
}
if (function_exists('register_nav_menus')) add_action( 'init', 'register_main_menus' );

// add Home link to the custom menu WP-Admin page
function et_add_home_link( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'et_add_home_link' );

add_action( 'et_get_additional_color_scheme', 'et_disable_color_scheme' );
function et_disable_color_scheme( $color_scheme ){
	global $default_colorscheme;
	return $default_colorscheme;
}

add_action('wp_head','et_add_meta_javascript');
function et_add_meta_javascript(){
	global $shortname;
	echo '<!-- used in scripts -->';
	echo '<meta name="et_featured_auto_speed" content="'.get_option($shortname.'_slider_autospeed').'" />';
			
	$disable_toptier = get_option($shortname.'_disable_toptier') == 'on' ? 1 : 0;
	echo '<meta name="et_disable_toptier" content="'.$disable_toptier.'" />';
	
	$featured_slider_pause = get_option($shortname.'_slider_pause') == 'on' ? 1 : 0;
	echo '<meta name="et_featured_slider_pause" content="'.$featured_slider_pause.'" />';
	
	$featured_slider_auto = get_option($shortname.'_slider_auto') == 'on' ? 1 : 0;
	echo '<meta name="et_featured_slider_auto" content="'.$featured_slider_auto.'" />';
	
	echo '<meta name="et_theme_folder" content="'.get_template_directory_uri().'" />';
}

if ( ! function_exists( 'boutique_product_name' ) ) :
function boutique_product_name( $slug = '' ){
	global $post;
	$name = get_option('boutique_use_pages') == 'on' ? 'page' : 'post';
	
	return 'et-' . $slug . '-' . $name . '-' . $post->ID;
}
endif;

if ( ! function_exists( 'boutique_price' ) ) :
function boutique_price(){
	global $post, $boutique_active_plugin_name, $wpdb;
	$price = 0;
	switch ($boutique_active_plugin_name){
		case 'shopp':
			global $Shopp;
			$Shopp->Catalog->tag('product',array('id'=>get_post_meta($post->ID,'_et_shopp_product_id',true)));
			$price = shopp('product','saleprice','echo=false') ? shopp('product','saleprice','echo=false') : shopp('product','price','echo=false');
			$price = preg_replace('/[^\d.,]/','',$price);
			
			break;
		case 'eshop':
			$eshop_post_settings = get_post_meta($post->ID,'_eshop_product',true);
			if ($eshop_post_settings) $price = $eshop_post_settings['products'][1]['price'];
			
			break;
		case 'cart66':
			$cart66_tablename = Cart66Common::getTableName('products');
			$results = $wpdb->get_results($wpdb->prepare("SELECT price FROM $cart66_tablename WHERE item_number = %s", get_post_meta($post->ID,'_et_cart66_product_id',true)));
			if ( $results ) $price = $results[0]->price;
			
			break;
		case 'wp_simple_paypal_sc':
			$price = get_post_meta($post->ID,'_et_simple_paypal_price',true);
			
			break;
		case 'wp_ecommerce':
			$price = get_post_meta( $post->ID, '_wpsc_special_price', true ) ? get_post_meta( $post->ID, '_wpsc_special_price', true ) : get_post_meta( $post->ID, '_wpsc_price', true );
			$price = apply_filters( 'et_wp_ecommerce_price_format', number_format( $price, 2 ), $price );
		break;
	}
	return $price;
}
endif;

if ( ! function_exists( 'boutique_currency_sign' ) ) :
function boutique_currency_sign(){
	global $post, $boutique_active_plugin_name, $wpsc_currency_data, $wpdb;
	$currency_sign = 0;
	switch ($boutique_active_plugin_name){
		case 'shopp':
			$shopp_currency = currency_format();
			$currency_sign = $shopp_currency['currency'];
			
			break;
		case 'eshop':
			global $eshopoptions;
			$currency_sign = $eshopoptions['currency_symbol'] ? $eshopoptions['currency_symbol'] : '$';
			
			break;
		case 'cart66':
			$currency_sign = defined('CART66_CURRENCY_SYMBOL') ? CART66_CURRENCY_SYMBOL : CURRENCY_SYMBOL;
			
			break;
		case 'wp_simple_paypal_sc':
			$currency_sign = get_option('cart_currency_symbol');
			
			break;
		case 'wp_ecommerce':
			if ( !$wpsc_currency_data ){
				$wpsc_currency_data = $wpdb->get_row( $wpdb->prepare("SELECT `symbol`, `symbol_html`, `code` FROM `" . WPSC_TABLE_CURRENCY_LIST . "` WHERE `id` = '" . '%d' . "' LIMIT 1", get_option( 'currency_type' )), ARRAY_A );
			}
			$currency_sign = $wpsc_currency_data['symbol'];
			
			break;
	}
	return $currency_sign;
}
endif;

add_action('init','et_global_active_plugin');
function et_global_active_plugin(){
	global $boutique_active_plugin_name;
	$boutique_active_plugin_name = boutique_active_plugin();
}

add_action('boutique_featured_product','boutique_featured_product_options');
function boutique_featured_product_options(){
	boutique_popup_info('featured'); 
}

add_action('boutique_special_offer','boutique_special_offer_options');
function boutique_special_offer_options(){
	boutique_popup_info('offer'); 
}

add_action('boutique_product_entry','boutique_product_entry_options');
function boutique_product_entry_options(){
	global $post, $boutique_active_plugin_name;
	boutique_popup_info('entry'); ?>
	<div class="boutique_description_border">
		<div class="boutique_description">
			<h4 class="description-title"><?php the_title(); ?></h4>
			<span class="price"><?php echo boutique_currency_sign() . boutique_price(); ?></span>
			<div class="clear"></div>
			<div class="entry-item-ratings clearfix"><?php et_boutique_display_rating(); ?></div>
			<?php
				if ( $boutique_active_plugin_name == 'wp_ecommerce' ) { 
					if(wpsc_the_product_additional_description())
						echo wpsc_the_product_additional_description();
					else 
						echo wpsc_the_product_description();
				} else { ?>
					<p><?php truncate_post(400); ?></p>
			<?php } ?>
		</div> <!-- end .boutique_description -->
	</div> <!-- end .boutique_description_border -->
<?php 
}

add_action('boutique_single_before_comments','boutique_related_posts');
function boutique_related_posts(){ ?>
	<?php
		global $post;
		
		if ( boutique_is_single_blog_post() ) return;
		
		$orig_post = $post;
		$tags = wp_get_post_tags($post->ID);
		if ($tags) {
			$tag_ids = array();
			
			foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
			$args=array(
				'tag__in' => $tag_ids,
				'post__not_in' => array($post->ID),
				'showposts'=> apply_filters('boutique_related_items','4'),
				'ignore_sticky_posts'=> '1'
			);
			$related_query = new WP_Query( $args );
			
			if( $related_query->have_posts() ) { ?>
				<div class="related-items clearfix">
					<h3 class="offer-title related_products"><span><?php esc_html_e('Related Products','Boutique'); ?></span></h3>
					<?php 
						$i=0; 
						while ( $related_query->have_posts() ) : $related_query->the_post();
							$i++;
							boutique_display_product($i,'entry');
						endwhile;
					?>
				</div> <!-- end .related-items -->
			<?php }
		}
		$post = $orig_post;
		wp_reset_postdata(); 
	?>
<?php
}

if ( ! function_exists( 'boutique_popup_info' ) ) :
function boutique_popup_info($template_name){ 
	global $boutique_active_plugin_name, $post;
	$div_name = boutique_product_name($template_name);
?>
	<div class="product_frame">
		<div id="<?php echo $div_name; ?>">
			<div class="et_popup_product_info et_<?php echo $boutique_active_plugin_name; ?>">
				<?php
					do_action('boutique_popup_product_description');
					
					if ( in_array( $boutique_active_plugin_name,array('cart66','eshop','wp_simple_paypal_sc') ) ){
						echo '<h2 class="et_popup_title"><a href="'.get_permalink().'">'.get_the_title().'</a></h2>';
						echo '<div class="et_popup_excerpt">' . get_the_excerpt() . '</div>';
					}
					
					do_action('boutique_popup_before_add_button');
					
					switch ($boutique_active_plugin_name){
						case 'shopp':
							if ( get_post_meta($post->ID,'_et_shopp_product_id',true) ) echo do_shortcode('[product id="'.get_post_meta($post->ID,'_et_shopp_product_id',true).'" ]');
							break;
						case 'eshop':
							#activate 'Show add to cart forms on WordPress post listings'
							global $eshopoptions;
							$eshopoptions['show_forms'] = 'yes';
							echo do_shortcode('[eshop_addtocart]');
							
							break;
						case 'cart66':
							echo do_shortcode('[add_to_cart item="'.get_post_meta($post->ID,'_et_cart66_product_id',true).'" ]');
							break;
						case 'wp_simple_paypal_sc':
							$sc_additional_settings = get_post_meta( $post->ID, '_et_simple_paypal_additional_settings', true ) ? get_post_meta( $post->ID, '_et_simple_paypal_additional_settings', true ) : '';
							$output = apply_filters('the_content',do_shortcode('[wp_cart:'.$post->post_title.':price:'.get_post_meta($post->ID,'_et_simple_paypal_price',true). esc_attr($sc_additional_settings) . ':end]'));
							$output = preg_replace('/"cartLink" value="([^"]+)"/','"cartLink" value="'.get_permalink($post->ID).'"',$output);
							echo $output;
							break;
						case 'wp_ecommerce':
							get_template_part('wpsc-boutique_index','index');
							break;
					}

					do_action('boutique_popup_after_add_button'); ?>
			</div> <!-- end .et_popup_product_info -->
		</div> <!-- end #<?php echo $div_name; ?> -->
	</div> <!-- end .product_frame -->
<?php }
endif;

$boutique_active_plugin_name = boutique_active_plugin();
if ( $boutique_active_plugin_name == 'eshop' ) {
	remove_filter('the_content', 'eshop_boing'); //don't add eshop data to the end of single post
}
if ( is_admin() && $boutique_active_plugin_name && !in_array($boutique_active_plugin_name,apply_filters('et_plugins_without_custom_panels',array('wp_ecommerce','eshop'))) ) {
	function boutique_add_custom_panels(){
		add_meta_box("et_post_meta", "ET Settings", "et_post_meta", "post", "normal", "high");
	}
	add_action("admin_init", "boutique_add_custom_panels");

	function et_post_meta() {
		global $post, $boutique_active_plugin_name;
		$active_plugin = $boutique_active_plugin_name;
		if ( $active_plugin == 'shopp' )
			$et_shopp_product_id = get_post_meta( $post->ID, '_et_shopp_product_id', true ) ? get_post_meta( $post->ID, '_et_shopp_product_id', true ) : '';
		if ( $active_plugin == 'wp_simple_paypal_sc' ) {
			$et_simple_paypal_price = get_post_meta( $post->ID, '_et_simple_paypal_price', true ) ? get_post_meta( $post->ID, '_et_simple_paypal_price', true ) : '';
			$et_simple_paypal_additional_settings = get_post_meta( $post->ID, '_et_simple_paypal_additional_settings', true ) ? get_post_meta( $post->ID, '_et_simple_paypal_additional_settings', true ) : '';
		}
		if ( $active_plugin == 'cart66' )
			$et_cart66_product_id = get_post_meta( $post->ID, '_et_cart66_product_id', true ) ? get_post_meta( $post->ID, '_et_cart66_product_id', true ) : ''; ?>
		
		<div id="et_custom_settings" style="margin: 13px 0 17px 4px;">
			<?php if ( $active_plugin == 'shopp' ) { ?>
				<div class="et_fs_setting" style="margin: 13px 0 26px 4px;">
					<label for="et_shopp_product_id" style="color: #000; font-weight: bold;"> Shopp Product ID: </label>
					<input type="text" style="width: 30em;" value="<?php echo esc_attr($et_shopp_product_id); ?>" id="et_shopp_product_id" name="et_shopp_product_id" size="67" />
					<br />
					<small style="position: relative; top: 8px;">Insert Shopp Product ID here</small>
				</div>
			<?php } ?>
			
			<?php if ( $active_plugin == 'wp_simple_paypal_sc' ) { ?>
				<div class="et_fs_setting" style="margin: 13px 0 26px 4px;">
					<label for="et_simple_paypal_price" style="color: #000; font-weight: bold;"> Product Price: </label>
					<input type="text" style="width: 30em;" value="<?php echo esc_attr($et_simple_paypal_price); ?>" id="et_simple_paypal_price" name="et_simple_paypal_price" size="67" />
					<br />
					<small style="position: relative; top: 8px;">ex. <code>19.99</code></small>
				</div>
				<div class="et_fs_setting" style="margin: 13px 0 26px 4px;">
					<label for="et_simple_paypal_additional_settings" style="color: #000; font-weight: bold;"> Additional Settings: </label>
					<input type="text" style="width: 30em;" value="<?php echo esc_attr($et_simple_paypal_additional_settings); ?>" id="et_simple_paypal_additional_settings" name="et_simple_paypal_additional_settings" size="67" />
					<br />
					<small style="position: relative; top: 8px;">You can set shipping and variations here. e.g. <code><?php echo esc_html(':shipping:15:var1[Size|Small|Medium|Large]:var2[Color|Red|Green]'); ?></code><br /> Learn more about it <a href="http://www.tipsandtricks-hq.com/ecommerce/simple-wp-shopping-cart-installation-usage-290" target="_blank">here</a></small>
				</div>
			<?php } ?>
			
			<?php if ( $active_plugin == 'cart66' ) { ?>
				<div class="et_fs_setting" style="margin: 13px 0 26px 4px;">
					<label for="et_cart66_product_id" style="color: #000; font-weight: bold;"> Cart66 Item Number: </label>
					<input type="text" style="width: 30em;" value="<?php echo esc_attr($et_cart66_product_id); ?>" id="et_cart66_product_id" name="et_cart66_product_id" size="67" />
					<br />
					<small style="position: relative; top: 8px;">Insert Cart66 Item Number here. ex: <code>0001</code></small>
				</div>
			<?php } ?>
		</div> <!-- #et_custom_settings -->
			
		<?php
	}

	add_action('save_post', 'boutique_save_details');
	function boutique_save_details($post_id){
		global $pagenow;
	
		if ( 'post.php' != $pagenow ) return $post_id;
		
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
			return $post_id;
			
		global $boutique_active_plugin_name;
		$active_plugin = $boutique_active_plugin_name;
			
		if ( $active_plugin == 'shopp' ) {
			if ( isset($_POST['et_shopp_product_id']) ) update_post_meta( $post_id, '_et_shopp_product_id', esc_attr($_POST['et_shopp_product_id']) );
		}
		if ( $active_plugin == 'wp_simple_paypal_sc' ) {
			if ( isset($_POST['et_simple_paypal_price']) ) update_post_meta( $post_id, '_et_simple_paypal_price', esc_attr($_POST['et_simple_paypal_price']) );
			if ( isset($_POST['et_simple_paypal_additional_settings']) ) update_post_meta( $post_id, '_et_simple_paypal_additional_settings', esc_attr($_POST['et_simple_paypal_additional_settings']) );
		}
		if ( $active_plugin == 'cart66' ) {
			if ( isset($_POST['et_cart66_product_id'] ) ) update_post_meta( $post_id, '_et_cart66_product_id', esc_attr($_POST['et_cart66_product_id']) );
		}
	}
}


add_action('et_header_top','et_boutique_control_panel');
function et_boutique_control_panel(){
	$admin_access = apply_filters( 'et_showcontrol_panel', current_user_can('switch_themes') );
	if ( !$admin_access ) return;
	if ( get_option('boutique_show_control_panel') <> 'on' ) return;
	global $et_bg_texture_urls, $et_google_fonts; ?>
	<div id="et-control-panel">
		<div id="control-panel-main">
			<a id="et-control-close" href="#"></a>
			<div id="et-control-inner">
				<h3 class="control_title">Example Colors</h3>
				<a href="#" class="et-control-colorpicker" id="et-control-background"></a>
				
				<div class="clear"></div>
				
				<?php 
					$sample_colors = array( '6a8e94', '8da49c', 'b0b083', '859a7c', 'c6bea6', 'b08383', 'a4869d', 'f5f5f5', '4e4e4e', '556f6a', '6f5555', '6f6755' );
					for ( $i=1; $i<=12; $i++ ) { ?>
						<a class="et-sample-setting" id="et-sample-color<?php echo $i; ?>" href="#" rel="<?php echo esc_attr($sample_colors[$i-1]); ?>" title="#<?php echo esc_attr($sample_colors[$i-1]); ?>"><span class="et-sample-overlay"></span></a>
				<?php } ?>
				<p>or define your own in ePanel</p>
				
				<h3 class="control_title">Texture Overlays</h3>
				<div class="clear"></div>
				
				<?php 
					$sample_textures = $et_bg_texture_urls;
					for ( $i=1; $i<=count($et_bg_texture_urls); $i++ ) { ?>
						<a title="<?php echo esc_attr($sample_textures[$i-1]); ?>" class="et-sample-setting et-texture" id="et-sample-texture<?php echo $i; ?>" href="#" rel="bg<?php echo $i+1; ?>"><span class="et-sample-overlay"></span></a>
				<?php } ?>
				
				<p>or define your own in ePanel</p>
				
				<?php 
					$google_fonts = $et_google_fonts;
					$font_setting = 'Kreon';
					$body_font_setting = 'Droid+Sans';
					if ( isset( $_COOKIE['et_boutique_header_font'] ) ) $font_setting = $_COOKIE['et_boutique_header_font'];
					if ( isset( $_COOKIE['et_boutique_body_font'] ) ) $body_font_setting = $_COOKIE['et_boutique_body_font'];
				?>
				
				<h3 class="control_title">Fonts</h3>
				<div class="clear"></div>
				
				<label for="et_control_header_font">Header
					<select name="et_control_header_font" id="et_control_header_font">
						<?php foreach( $google_fonts as $google_font ) { ?>
							<?php $encoded_value = urlencode($google_font); ?>
							<option value="<?php echo esc_attr($encoded_value); ?>" <?php selected( $font_setting, $encoded_value ); ?>><?php echo esc_html($google_font); ?></option>
						<?php } ?>
					</select>
				</label>
				<a href="#" class="et-control-colorpicker et-font-control" id="et-control-headerfont_bg"></a>
				<div class="clear"></div>
				
				<label for="et_control_body_font">Body
					<select name="et_control_body_font" id="et_control_body_font">
						<?php foreach( $google_fonts as $google_font ) { ?>
							<?php $encoded_value = urlencode($google_font); ?>
							<option value="<?php echo esc_attr($encoded_value); ?>" <?php selected( $body_font_setting, $encoded_value ); ?>><?php echo esc_html($google_font); ?></option>
						<?php } ?>
					</select>
				</label>
				<a href="#" class="et-control-colorpicker et-font-control" id="et-control-bodyfont_bg"></a>
				<div class="clear"></div>
				
			</div> <!-- end #et-control-inner -->
		</div> <!-- end #control-panel-main -->
	</div> <!-- end #et-control-panel -->
<?php
}

add_action( 'wp_enqueue_scripts', 'et_load_boutique_scripts' );
function et_load_boutique_scripts(){
	if ( !is_admin() ){
		$template_dir = get_template_directory_uri();
		
		wp_enqueue_script('easing', $template_dir . '/js/jquery.easing.1.3.js', array('jquery'), '1.0', true);
		wp_enqueue_script('cycle', $template_dir . '/js/jquery.cycle.all.min.js', array('jquery'), '1.0', true);
		wp_enqueue_script('superfish', $template_dir . '/js/superfish.js', array('jquery'), '1.0', true);
		wp_enqueue_script('custom_script', $template_dir . '/js/custom.js', array('jquery'), '1.0', true);
		
		$admin_access = apply_filters( 'et_showcontrol_panel', current_user_can('switch_themes') );
		if ( $admin_access && get_option('boutique_show_control_panel') == 'on' ) {
			wp_enqueue_script('et_colorpicker', $template_dir . '/epanel/js/colorpicker.js', array('jquery'), '1.0', true);
			wp_enqueue_script('et_eye', $template_dir . '/epanel/js/eye.js', array('jquery'), '1.0', true);
			wp_enqueue_script('et_cookie', $template_dir . '/js/jquery.cookie.js', array('jquery'), '1.0', true);
			wp_enqueue_script('et_control_panel', $template_dir . '/js/et_control_panel.js', array('jquery'), '1.0', true);
		}
	}
}

add_action( 'wp_head', 'et_set_bg_properties' );
function et_set_bg_properties(){
	global $et_bg_texture_urls;
	
	$bgcolor = '';
	$bgcolor = ( isset( $_COOKIE['et_boutique_bgcolor'] ) && get_option('boutique_show_control_panel') == 'on' ) ? $_COOKIE['et_boutique_bgcolor'] : get_option('boutique_bgcolor');
	
	$bgtexture_url = '';
	$bgimage_url = '';
	if ( get_option('boutique_bgimage') == '' ) {
		if ( isset( $_COOKIE['et_boutique_texture_url'] ) && get_option('boutique_show_control_panel') == 'on' ) $bgtexture_url =  $_COOKIE['et_boutique_texture_url'];
		else {
			$bgtexture_url = get_option('boutique_bgtexture_url');
			if ( $bgtexture_url == 'Default' ) $bgtexture_url = '';
			else $bgtexture_url = get_template_directory_uri() . '/images/cp/body-bg' . ( array_search( $bgtexture_url, $et_bg_texture_urls )+2 ) . '.png';
		}
	} else {
		$bgimage_url = get_option('boutique_bgimage');
	}
	
	$style = '';
	$style .= '<style type="text/css">';
	if ( $bgcolor <> '' ) { 
		$style .= '#top-area,#footer { background-color: #' . esc_attr($bgcolor) . '; }';
	}
	if ( $bgtexture_url <> '' ) $style .= '#top-area,#footer { background-image: url(' . esc_attr($bgtexture_url) . '); }';
	if ( $bgimage_url <> '' ) $style .= '#top-area,#footer { background-image: url(' . esc_attr($bgimage_url) . '); background-position: top center; background-repeat: no-repeat; }';
	$style .= '</style>';
	
	if ( $bgcolor <> '' || $bgtexture_url <> '' || $bgimage_url <> '' ) echo $style;
}

add_action( 'wp_head', 'et_set_font_properties' );
function et_set_font_properties(){
	$font_style = '';
	$font_color = '';
	$font_family = '';
	$font_color_string = '';
	
	if ( isset( $_COOKIE['et_boutique_header_font'] ) && get_option('boutique_show_control_panel') == 'on' ) $et_header_font =  $_COOKIE['et_boutique_header_font'];
	else {
		$et_header_font = get_option('boutique_header_font');
		if ( $et_header_font == 'Kreon' ) $et_header_font = '';
	}
	
	if ( isset( $_COOKIE['et_boutique_header_font_color'] ) && get_option('boutique_show_control_panel') == 'on' ) 	
		$et_header_font_color =  $_COOKIE['et_boutique_header_font_color'];
	else 
		$et_header_font_color = get_option('boutique_header_font_color');
	
	if ( $et_header_font <> '' || $et_header_font_color <> '' ) {
		$et_header_font_id = strtolower( str_replace( '+', '_', $et_header_font ) );
		$et_header_font_id = str_replace( ' ', '_', $et_header_font_id );
		
		if ( $et_header_font <> '' ) { 
			$font_style .= "<link id='" . $et_header_font_id . "' href='http://fonts.googleapis.com/css?family=" . $et_header_font . "' rel='stylesheet' type='text/css' />";
			$font_family = "font-family: '" . str_replace( '+', ' ', $et_header_font ) . "', Arial, sans-serif !important; ";
		}
		
		if ( $et_header_font_color <> '' ) {
			$font_color_string = "color: #" . esc_attr($et_header_font_color) . " !important; ";
		}
		
		$font_style .= "<style type='text/css'>h1,h2,h3,h4,h5,h6 { ". $font_family .  " }</style>";
		$font_style .= "<style type='text/css'>h1,h2,h3,h4,h5,h6, h2 a, h3 a, h4 a, h5 a, h6 a { ". esc_attr($font_color_string) .  " }
		h2.featured-title a, #footer h4.widget-title { color: #fff !important; }
		</style>";
		
		echo $font_style;
	}
	
	$font_style = '';
	$font_color = '';
	$font_family = '';
	$font_color_string = '';
	
	if ( isset( $_COOKIE['et_boutique_body_font'] ) && get_option('boutique_show_control_panel') == 'on' ) $et_body_font =  $_COOKIE['et_boutique_body_font'];
	else {
		$et_body_font = get_option('boutique_body_font');
		if ( $et_body_font == 'Droid+Sans' ) $et_body_font = '';
	}
	
	if ( isset( $_COOKIE['et_boutique_body_font_color'] ) && get_option('boutique_show_control_panel') == 'on' ) 	
		$et_body_font_color =  $_COOKIE['et_boutique_body_font_color'];
	else 
		$et_body_font_color = get_option('boutique_body_font_color');
	
	if ( $et_body_font <> '' || $et_body_font_color <> '' ) {
		$et_body_font_id = strtolower( str_replace( '+', '_', $et_body_font ) );
		$et_body_font_id = str_replace( ' ', '_', $et_body_font_id );
		
		if ( $et_body_font <> '' ) { 
			$font_style .= "<link id='" . $et_body_font_id . "' href='http://fonts.googleapis.com/css?family=" . $et_body_font . "' rel='stylesheet' type='text/css' />";
			$font_family = "font-family: '" . str_replace( '+', ' ', $et_body_font ) . "', Arial, sans-serif !important; ";
		}
		
		if ( $et_body_font_color <> '' ) {
			$font_color_string = "color: #" . esc_attr($et_body_font_color) . " !important; ";
		}
		
		$font_style .= "<style type='text/css'>body { ". html_entity_decode( $font_family ) .  " }</style>";
		$font_style .= "<style type='text/css'>body { ". esc_attr($font_color_string) .  " }</style>";
		
		echo $font_style;
	}
} 

if ( ! function_exists( 'boutique_display_product' ) ) :
function boutique_display_product( $post_iterator, $template_name ){
	global $post; ?>
	<div class="main-product<?php if ( $post_iterator % 4 == 0 ) echo ' last'; ?>">
		<?php
			$width = 113;
			$height = 96;
			$titletext = get_the_title();
			$thumbnail = get_thumbnail($width,$height,'',$titletext,$titletext,false,'Entry');
			$thumb = $thumbnail["thumb"];
		?>
		<a href="<?php the_permalink(); ?>">
			<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, ''); ?>
			<span class="price-tag"><span>$<?php //echo boutique_currency_sign(); ?></span><?php echo boutique_price(); ?></span>
		</a>
		<h4 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
		<div class="et-links clearfix">
			<a href="#<?php echo boutique_product_name('entry'); ?>" class="add-to-cart et-shop-item"><?php esc_html_e('Cart','Boutique'); ?></a>
			<a href="<?php the_permalink(); ?>" class="more-info"><?php esc_html_e('Info','Boutique'); ?></a>
		</div> <!-- end .et-links -->
		<?php do_action('boutique_product_entry'); ?>
	</div> <!-- end .main-product -->
	<?php if ( $post_iterator % 4 == 0 ) echo '<div class="clear"></div>'; ?>
<?php 
}
endif;

if ( ! function_exists( 'boutique_display_blogpost' ) ) :
function boutique_display_blogpost(){
	global $post;
	
	$thumb = '';
	$width = 249;
	$height = 244;
	$classtext = 'blog-thumb';
	$titletext = get_the_title();
	$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext,false,'Blog');
	$thumb = $thumbnail["thumb"];
	
	if ( is_category() ){ ?>
		<div class="blogpost clearfix">
			<h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php get_template_part('includes/postinfo','blog'); ?>
	<?php }
	
	if( $thumb <> '' && ( ( get_option('boutique_thumbnails_index') == 'on' && is_category() ) || ( get_option('boutique_thumbnails') == 'on' && is_single() ) ) ) { ?>
		<div class="post-thumbnail">
			<?php if ( is_category() ) echo '<a href="' . get_permalink() . '">'; ?>
				<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
			<?php if ( is_category() ) echo '</a>'; ?>
		</div> 	<!-- end .post-thumbnail -->
	<?php }
	
	if ( is_category() ) {
			if (get_option('boutique_blog_style') == 'on') the_content(''); else { ?>
				<p><?php truncate_post(500); ?></p>
			<?php } ?>
		
			<a href="<?php the_permalink(); ?>" class="read-more"><span><?php _e('Read More','Boutique'); ?></span></a>
		</div> 	<!-- end .blogpost -->
	<?php }
}
endif;

if ( ! function_exists( 'et_is_blog_post' ) ) :
function et_is_blog_post( $blog_categories ){
	global $post;
	
	$categories = get_the_category();
	foreach ( $categories as $category ){
		if ( in_array( $category->cat_ID, $blog_categories ) ) return true;
	}
	
	return false;
}
endif;

if ( ! function_exists( 'boutique_is_single_blog_post' ) ) :
function boutique_is_single_blog_post(){
	#detect if single post should have a Blog layout
	return ( get_option('boutique_blog_categories') !== false && et_is_blog_post(get_option('boutique_blog_categories')) );
}
endif;


// RATING Functions //

add_action( 'admin_enqueue_scripts', 'upload_etsettings_scripts' );
function upload_etsettings_scripts( $hook_suffix ) {
	if ( in_array( $hook_suffix, array('post.php','post-new.php') ) ) {
		wp_enqueue_script('metadata', get_bloginfo('template_directory') . '/js/jquery.MetaData.js', array('jquery'), '3.13', true);
		wp_enqueue_script('rating', get_bloginfo('template_directory') . '/js/jquery.rating.pack.js', array('jquery'), '3.13', true);
		wp_enqueue_style('et-rating', get_bloginfo('template_directory') . '/css/jquery.rating.css');
	}
}

add_action('wp_enqueue_scripts', 'et_add_comment_rating');
function et_add_comment_rating(){
	#integrate jquery rating files into single post pages ( frontend )
	if ( is_single() ) {
		wp_enqueue_script('metadata', get_bloginfo('template_directory') . '/js/jquery.MetaData.js', array('jquery'), '3.13', true);
		wp_enqueue_script('rating', get_bloginfo('template_directory') . '/js/jquery.rating.pack.js', array('jquery'), '3.13', true);
		wp_enqueue_style('et-rating', get_bloginfo('template_directory') . '/css/jquery.rating.css');
	}
}

add_action('comment_post','et_add_rating_commentmeta', 10, 2);
function et_add_rating_commentmeta( $comment_id, $comment_approved ){
	#when user adds a comment, check if it's approved
	
	$comment_rating = ( isset($_POST['et_star']) ) ? $_POST['et_star'] : 0;
	add_comment_meta($comment_id,'et_comment_rating',$comment_rating);
	if ( $comment_approved == 1 ) {	
		$comment_info = get_comment($comment_id);		
		et_update_post_user_rating( $comment_info->comment_post_ID );
	}
}

add_action('et-comment-meta','et_show_comment_rating');
function et_show_comment_rating( $comment_id ){
	#displays user comment rating on single post page ( frontend )
	
	if ( boutique_is_single_blog_post() ) return;
	
	$user_comment_rating = get_comment_meta($comment_id,'et_comment_rating',true) ? get_comment_meta($comment_id,'et_comment_rating',true) : 0;				
	if ( $user_comment_rating <> 0 ) { ?>
		<div class="review-rating">
			<div class="review-score" style="width: <?php echo esc_attr(et_get_star_rating($user_comment_rating)); ?>px;"></div> 
		</div>

		<div class="clear"></div>
	<?php }
}

add_filter('comment_form_field_comment','et_boutique_comment_form_add_rating');
function et_boutique_comment_form_add_rating( $comment_field ){
	if ( is_page() || boutique_is_single_blog_post() ) return $comment_field;
	
	$rating_field = '<div id="et-rating" class="clearfix">
						<span id="choose_rating">' . esc_html__('Rating','Boutique') . '</span>
							<div class="clearfix">';
	
	for ( $increment = 0.5; $increment <= 5; $increment = $increment+0.5  ) {
		$rating_field .= '<input name="et_star" type="radio" class="star {half:true}" value="' . $increment . '" />';
	}
	
	$rating_field .= '		</div> <!-- end .clearfix -->
						
					</div> <!-- end #et-rating -->';
	
	return $rating_field . $comment_field;
}

if ( ! function_exists( 'et_get_post_user_rating' ) ){
	function et_get_post_user_rating( $post_id ){
		#calculates user (comments) rating for the post
		$approved_comments = et_get_approved_comments( $post_id );
		if ( empty($approved_comments) ) return 0;
		
		$user_rating = 0;
		$approved_comments_number = count($approved_comments);
		
		foreach ( $approved_comments as $comment ) {
			$comment_rating = get_comment_meta($comment->comment_ID,'et_comment_rating',true) ? get_comment_meta($comment->comment_ID,'et_comment_rating',true) : 0;
			if ( $comment_rating == 0 ) $approved_comments_number--;
			
			$user_rating += $comment_rating;
		}
		
		$result = ( $user_rating <> 0 ) ? round( $user_rating / $approved_comments_number, 2 ) : 0;
		# save user rating to the post meta
		if ( !get_post_meta($post_id,'_et_boutique_comments_rating',true) ) update_post_meta($post_id,'_et_boutique_comments_rating',$result);
		
		return $result;
	}
}

if ( ! function_exists( 'et_update_post_user_rating' ) ){
	function et_update_post_user_rating( $post_id ){
		#update (recalculate) user (comments) rating for the post
		$new_comments_rating = et_get_post_user_rating( $post_id );
		
		if ( get_post_meta($post_id,'_et_boutique_comments_rating',true) <> $new_comments_rating )
			update_post_meta($post_id,'_et_boutique_comments_rating',$new_comments_rating);
	}
}

add_action('wp_set_comment_status','et_comment_status_changed', 10, 2);
function et_comment_status_changed($comment_id, $comment_status){
	$comment_info = get_comment( $comment_id );		
	et_update_post_user_rating( $comment_info->comment_post_ID );
}

if ( ! function_exists( 'et_get_approved_comments' ) ){
	function et_get_approved_comments($post_id) {
		global $wpdb;
		return $wpdb->get_results($wpdb->prepare("SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_approved = '1' ORDER BY comment_date", $post_id));
	}
}

if ( ! function_exists( 'et_get_star_rating' ) ){
	function et_get_star_rating( $rating ) {
		#round to 0.5, 1.5, 2.5 etc. * 16px ( star sprite image )
		$width_value = ( round( $rating*2 ) / 2 ) * 16;
		
		return $width_value;
	}
}

function et_add_rating_custom_panel(){
	add_meta_box("et_cp_rating_meta", "Rating Settings", "et_cp_rating_meta", "post", "normal", "high");
	add_meta_box("et_cp_rating_meta", "Rating Settings", "et_cp_rating_meta", "wpsc-product", "normal", "high");
}
add_action("admin_init", "et_add_rating_custom_panel");

function et_cp_rating_meta() {
	global $post;
		
	$et_boutique_features_rating = get_post_meta($post->ID,'_et_boutique_rating',true) ? get_post_meta($post->ID,'_et_boutique_rating',true) : 0;
	do_action( 'et_ratings_get_features' ); ?>
	
	<div id="et_custom_settings" style="margin: 13px 0 17px 4px;">			
		<div class="et_setting" style="margin: 13px 0 26px 4px; overflow: hidden;">
			<p style="float: left;"><label for="et_feature_name" style="color: #000; font-weight: bold;"> Item Rating: </label></p>
			
			<p style="float: left;">
				<?php for ( $increment = 0.5; $increment <= 5; $increment = $increment+0.5  ) { ?>
					<?php 
						if ( $et_boutique_features_rating[0] == $increment ) $checked = ' checked="checked"';
						else $checked = '';
					?>
					<input name="et_star" type="radio" class="star {half:true}" value="<?php echo $increment; ?>"<?php echo $checked; ?> />
				<?php } ?>
			</p>
		</div>
	</div> <!-- #et_custom_settings -->
		
	<?php
}

add_action('save_post', 'et_cp_save_ratings');
function et_cp_save_ratings($post_id){
	global $pagenow;
	
	if ( 'post.php' != $pagenow ) return $post_id;
			
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;
	
	global $post;
	$rating_number = 0;
	$features_number = 0;
	
	$rating1 = isset( $_POST["et_star"] ) ? $_POST["et_star"] : 0;
	$features_rating = apply_filters( 'et_boutique_features_save', array($rating1) );
	
	foreach ( $features_rating as $rating ) {
		#actual number of features used in the post
		if ( $rating != 0 ) $features_number++;
	}
	for ( $i=1; $i<=$features_number; $i++ ){
		$rating_number += $features_rating[$i-1];
	}
	
	$post_rating = $features_number <> 0 ? round( $rating_number / $features_number, 2 ) : 0;
	
	if ( get_post_meta( $post_id, "_et_boutique_rating", true ) == $post_rating ) return $post_id;
	else {
		update_post_meta( $post_id, "_et_boutique_rating", $features_rating );
		update_post_meta( $post_id, "_et_boutique_user_rating", $post_rating );
	}
	
	do_action( 'et_boutique_save_features' );
}

if ( ! function_exists( 'et_boutique_display_rating' ) ) :
function et_boutique_display_rating(){
	global $post;
	$et_author_rating = get_post_meta($post->ID,'_et_boutique_user_rating',true) ? get_post_meta($post->ID,'_et_boutique_user_rating',true) : 0;
	$et_comments_rating = get_post_meta($post->ID,'_et_comments_rating',true) ? get_post_meta($post->ID,'_et_comments_rating',true) : et_get_post_user_rating($post->ID);
	
	if ( $et_author_rating <> 0 ) { ?>
		<div class="rating-container">
			<div class="rating-inner clearfix">
				<span><?php esc_html_e('Author','Boutique'); ?></span>
				<div class="review-rating">
					<div class="review-score" style="width: <?php echo esc_attr(et_get_star_rating($et_author_rating)); ?>px;"></div> 
				</div>
			</div> <!-- end .rating-inner -->
		</div> <!-- end .rating-container -->
	<?php }
	
	if ( $et_comments_rating <> 0 ) { ?>
		<div class="rating-container">
			<div class="rating-inner clearfix">
				<span><?php esc_html_e('Users','Boutique'); ?></span>
				<div class="review-rating">
					<div class="review-score" style="width: <?php echo esc_attr(et_get_star_rating($et_comments_rating)); ?>px;"></div>
				</div>
			</div> <!-- end .rating-inner -->
		</div> <!-- end .rating-container -->
	<?php }
}
endif;


add_filter('comments_open','wp_ecommerce_force_comments',10,2);
function wp_ecommerce_force_comments( $open, $post_id ){
	$post_info = get_post($post_id);
	if ( $post_info->post_type == 'wpsc-product' ) return true;
	else return $open;
}

add_action( 'admin_init', 'et_remove_wp_ecommerce_notification' );
function et_remove_wp_ecommerce_notification(){
	global $boutique_active_plugin_name;
	if ( 'wp_ecommerce' == $boutique_active_plugin_name ){
		remove_action( 'admin_notices', 'wpsc_theme_upgrade_notice' );
	}
}

if ( ! function_exists( 'et_list_pings' ) ){
	function et_list_pings($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment; ?>
		<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?> - <?php comment_excerpt(); ?>
	<?php } 
} ?>