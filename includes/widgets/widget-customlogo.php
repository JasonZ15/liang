<?php class CustomLogoWidget extends WP_Widget
{
    function CustomLogoWidget(){
		$widget_ops = array('description' => 'Displays Logo and Additional Information');
		$control_ops = array('width' => 400, 'height' => 300);
		parent::WP_Widget(false,$name='ET Custom Logo Widget',$widget_ops,$control_ops);
    }

  /* Displays the Widget in the front-end */
    function widget($args, $instance){
		extract($args);
		$logoImagePath = empty($instance['logoImagePath']) ? '' : esc_url($instance['logoImagePath']);
		$textInfo = empty($instance['textInfo']) ? '' : stripslashes(html_entity_decode($instance['textInfo'],ENT_QUOTES));

		echo $before_widget;
?>	
<p id="footer-logo"><img alt="" src="<?php echo $logoImagePath; ?>" /></p>
<?php echo $textInfo; ?>

<?php
		echo $after_widget;
	}

  /*Saves the settings. */
    function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['logoImagePath'] = esc_url($new_instance['logoImagePath']);
		$instance['textInfo'] = esc_textarea($new_instance['textInfo']);

		return $instance;
	}

  /*Creates the form for the widget in the back-end. */
    function form($instance){
		//Defaults
		$instance = wp_parse_args( (array) $instance, array('logoImagePath'=>'', 'textInfo'=>'') );

		$logoImagePath =  esc_url($instance['logoImagePath']);
		$textInfo = stripslashes(html_entity_decode($instance['textInfo'],ENT_QUOTES));
		
		# Logo Image
		echo '<p><label for="' . $this->get_field_id('logoImagePath') . '">' . 'Logo Image URL (183x41px):' . '</label><textarea cols="20" rows="2" class="widefat" id="' . $this->get_field_id('logoImagePath') . '" name="' . $this->get_field_name('logoImagePath') . '" >'. $logoImagePath .'</textarea></p>';
		# Text
		echo '<p><label for="' . $this->get_field_id('textInfo') . '">' . 'Text:' . '</label><textarea cols="20" rows="5" class="widefat" id="' . $this->get_field_id('textInfo') . '" name="' . $this->get_field_name('textInfo') . '" >'. $textInfo .'</textarea></p>';
	}

}// end CustomLogoWidget class

function CustomLogoWidgetInit() {
  register_widget('CustomLogoWidget');
}

add_action('widgets_init', 'CustomLogoWidgetInit');

?>