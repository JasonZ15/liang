<?php session_start();
/*
Template Name: Contact Page
*/
?>

<?php 
	$et_ptemplate_settings = array();
	$et_ptemplate_settings = maybe_unserialize( get_post_meta($post->ID,'et_ptemplate_settings',true) );
	
	$fullwidth = isset( $et_ptemplate_settings['et_fullwidthpage'] ) ? (bool) $et_ptemplate_settings['et_fullwidthpage'] : false;
	
	$et_regenerate_numbers = isset( $et_ptemplate_settings['et_regenerate_numbers'] ) ? (bool) $et_ptemplate_settings['et_regenerate_numbers'] : false;
		
	$et_error_message = '';
	$et_contact_error = false;
	
	if ( isset($_POST['et_contactform_submit']) ) {
		if ( !isset($_POST['et_contact_captcha']) || empty($_POST['et_contact_captcha']) ) {
			$et_error_message .= '<p>' . esc_html__('Make sure you entered the captcha. ','Boutique') . '</p>';
			$et_contact_error = true;
		} else if ( $_POST['et_contact_captcha'] <> ( $_SESSION['et_first_digit'] + $_SESSION['et_second_digit'] ) ) {			
			$et_numbers_string = $et_regenerate_numbers ? esc_html__('Numbers regenerated.') : '';
			$et_error_message .= '<p>' . esc_html__('You entered the wrong number in captcha. ','Boutique') . $et_numbers_string . '</p>';
			
			if ($et_regenerate_numbers) {
				unset( $_SESSION['et_first_digit'] );
				unset( $_SESSION['et_second_digit'] );
			}
			
			$et_contact_error = true;
		} else if ( empty($_POST['et_contact_name']) || empty($_POST['et_contact_email']) || empty($_POST['et_contact_subject']) || empty($_POST['et_contact_message']) ){
			$et_error_message .= '<p>' . esc_html__('Make sure you fill all fields. ','Boutique') . '</p>';
			$et_contact_error = true;
		}
		
		if ( !is_email( $_POST['et_contact_email'] ) ) {
			$et_error_message .= '<p>' . esc_html__('Invalid Email. ','Boutique') . '</p>';
			$et_contact_error = true;
		}
	} else {
		$et_contact_error = true;
		if ( isset($_SESSION['et_first_digit'] ) ) unset( $_SESSION['et_first_digit'] );
		if ( isset($_SESSION['et_second_digit'] ) ) unset( $_SESSION['et_second_digit'] );
	}
	
	if ( !isset($_SESSION['et_first_digit'] ) ) $_SESSION['et_first_digit'] = $et_first_digit = rand(1, 15);
	else $et_first_digit = $_SESSION['et_first_digit'];
	
	if ( !isset($_SESSION['et_second_digit'] ) ) $_SESSION['et_second_digit'] = $et_second_digit = rand(1, 15);
	else $et_second_digit = $_SESSION['et_second_digit'];
	
	if ( !$et_contact_error ) {
		$et_email_to = ( isset($et_ptemplate_settings['et_email_to']) && !empty($et_ptemplate_settings['et_email_to']) ) ? esc_attr($et_ptemplate_settings['et_email_to']) : esc_attr(get_site_option('admin_email'));
				
		$et_site_name = MULTISITE ? esc_attr($current_site->site_name) : esc_attr(get_bloginfo('name'));	
		wp_mail($et_email_to, sprintf( '[%s] ' . esc_attr($_POST['et_contact_subject']), $et_site_name ), esc_attr($_POST['et_contact_message']),'From: "'. esc_attr($_POST['et_contact_name']) .'" <' . esc_attr($_POST['et_contact_email']) . '>');
		
		$et_error_message = '<p>' . esc_html__('Thanks for contacting us','Boutique') . '</p>';
	}
?>

<?php get_header(); ?>

<?php get_template_part('includes/breadcrumbs','index'); ?>

<div id="main-content" <?php if($fullwidth) echo ('class="fullwidth"');?>>
	<div id="main-content-bg">
		<div id="main-content-bottom-bg" class="clearfix">
			<div id="left-area">
				<div id="main-products" class="clearfix">
					<?php get_template_part('loop','page'); ?>
					
					<div id="et-contact">
						<div id="et-contact-message"><?php echo($et_error_message); ?> </div>
						
						<?php if ( $et_contact_error ) { ?>
							<form action="<?php echo(get_permalink($post->ID)); ?>" method="post" id="et_contact_form">
								<div id="et_contact_left">
									<p class="clearfix">
										<input type="text" name="et_contact_name" value="<?php if ( isset($_POST['et_contact_name']) ) echo esc_attr($_POST['et_contact_name']); else esc_attr_e('Name','Boutique'); ?>" id="et_contact_name" class="input" />
									</p>
									
									<p class="clearfix">
										<input type="text" name="et_contact_email" value="<?php if ( isset($_POST['et_contact_email']) ) echo esc_attr($_POST['et_contact_email']); else esc_attr_e('Email Address','Boutique'); ?>" id="et_contact_email" class="input" />
									</p>
									
									<p class="clearfix">
										<input type="text" name="et_contact_subject" value="<?php if ( isset($_POST['et_contact_subject']) ) echo esc_attr($_POST['et_contact_subject']); else esc_attr_e('Subject','Boutique'); ?>" id="et_contact_subject" class="input" />
									</p>
								</div> <!-- #et_contact_left -->
								
								<div id="et_contact_right">
									<p class="clearfix">
										<?php 
											esc_html_e('Captcha: ','Boutique');	
											echo '<br/>';
											echo esc_html($et_first_digit) . ' + ' . esc_html($et_second_digit) . ' = ';
										?>
										<input type="text" name="et_contact_captcha" value="<?php if ( isset($_POST['et_contact_captcha']) ) echo esc_attr($_POST['et_contact_captcha']); ?>" id="et_contact_captcha" class="input" size="2" />
									</p>
								</div> <!-- #et_contact_right -->
								
								<div class="clear"></div>
								
								<p class="clearfix">
									<textarea class="input" id="et_contact_message" name="et_contact_message"><?php if ( isset($_POST['et_contact_message']) ) echo esc_textarea($_POST['et_contact_message']); else echo esc_textarea('Message','Boutique'); ?></textarea>
								</p>
									
								<input type="hidden" name="et_contactform_submit" value="et_contact_proccess" />
								
								<input type="reset" id="et_contact_reset" value="<?php esc_attr_e('Reset','Boutique'); ?>" />
								<input class="et_contact_submit" type="submit" value="<?php esc_attr_e('Submit','Boutique'); ?>" id="et_contact_submit" />
							</form>
						<?php } ?>
					</div> <!-- end #et-contact -->
				
					<div class="clear"></div>
					
				</div> <!-- end #main-products -->
			</div> <!-- end #left-area -->
			
			<?php if (!$fullwidth) get_sidebar(); ?>
		</div> <!-- end #main-content-bottom-bg -->
	</div> <!-- end #main-content-bg -->
</div> <!-- end #main-content -->
<?php get_footer(); ?>