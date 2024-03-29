jQuery(document).ready(function(){
	$et_sample_color = jQuery('#et-control-panel a.et-sample-setting');
	$et_sample_color.click(function(){
		var et_option_value = jQuery(this).attr('rel'),
			et_theme_folder = jQuery("meta[name=et_theme_folder]").attr('content');
		
		if ( jQuery(this).hasClass('et-texture') ) {
			var et_texture_url = et_theme_folder + '/images/cp/body-' + et_option_value + '.png';
			jQuery('#top-area,#footer').css( { 'backgroundImage': 'url(' + et_texture_url + ')', 'backgroundRepeat' : 'repeat' } );
			jQuery.cookie('et_boutique_texture_url', et_texture_url);
		} else { 
			jQuery('#top-area,#footer').css( 'backgroundColor', '#' + et_option_value );
			jQuery.cookie('et_boutique_bgcolor', et_option_value);
		}
		
		return false;
	});

	jQuery('#et-control-background').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery('#top-area,#footer').css('backgroundColor', '#' + hex);
			jQuery.cookie('et_boutique_bgcolor', hex);
		}
	});

	var et_header_font_elements = 'h1,h2,h3,h4,h5,h6',
		et_header_font_elements_color = 'h1,h2,h3,h4,h5,h6,h2 a,h3 a,h4 a,h5 a,h6 a',
		et_body_font_elements = '#main-content-area',
		et_body_font_elements_color = '#main-content-area';

	jQuery('#et-control-headerfont_bg').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery(et_header_font_elements_color).css('color', '#' + hex + ' !important');
			jQuery('h2.featured-title a,#footer h4.widget-title').css('color', '#fff !important');
			jQuery.cookie('et_boutique_header_font_color', hex);
		}
	});

	jQuery('#et_control_header_font').change(function(){
		var et_header_font_value = jQuery(this).val(),
			et_link_tag_id = et_header_font_value.replace('+','_').toLowerCase();
			
		et_link_tag_id = et_link_tag_id.replace(' ','_');
		
		if ( !jQuery( 'link#' + et_link_tag_id ).length )
			jQuery('head').append("<link id='" + et_link_tag_id + "' href='http://fonts.googleapis.com/css?family="+et_header_font_value+"' rel='stylesheet' type='text/css' />");
		
		jQuery('head').append("<style type='text/css'>" + et_header_font_elements + " { font-family: '" + et_header_font_value.replace('+',' ') + "', Arial, sans-serif !important; }</style>");
		
		jQuery.cookie('et_boutique_header_font', et_header_font_value);
	});


	jQuery('#et-control-bodyfont_bg').ColorPicker({
		onShow: function (colpkr) {
			jQuery(colpkr).fadeIn(500);
			return false;
		},
		onHide: function (colpkr) {
			jQuery(colpkr).fadeOut(500);
			return false;
		},
		onChange: function (hsb, hex, rgb) {
			jQuery(et_body_font_elements_color).css('color', '#' + hex + ' !important');
			jQuery.cookie('et_boutique_body_font_color', hex);
		}
	});

	jQuery('#et_control_body_font').change(function(){
		var et_body_font_value = jQuery(this).val(),
			et_link_tag_id = et_body_font_value.replace('+','_').toLowerCase();
			
		et_link_tag_id = et_link_tag_id.replace(' ','_');
		
		if ( !jQuery( 'link#' + et_link_tag_id ).length )
			jQuery('head').append("<link id='" + et_link_tag_id + "' href='http://fonts.googleapis.com/css?family="+et_body_font_value+"' rel='stylesheet' type='text/css' />");
		
		jQuery('head').append("<style type='text/css'>" + et_body_font_elements + " { font-family: '" + et_body_font_value.replace('+',' ') + "', Arial, sans-serif !important; }</style>");
		
		jQuery.cookie('et_boutique_body_font', et_body_font_value);
	});


	var $et_control_panel = jQuery('#et-control-panel'),
		$et_control_close = jQuery('#et-control-close');

	$et_control_close.click(function(){
		if ( jQuery(this).hasClass('control-open') ) {
			$et_control_panel.animate( { left: 0 } );
			jQuery(this).removeClass('control-open');
			jQuery.cookie('et_boutique_control_panel_open', 0);
		} else {
			$et_control_panel.animate( { left: -169 } );
			jQuery(this).addClass('control-open');
			jQuery.cookie('et_boutique_control_panel_open', 1);
		}
		return false;
	});

	if ( jQuery.cookie('et_boutique_control_panel_open') == 1 ) { 
		$et_control_panel.animate( { left: -169 } );
		$et_control_close.addClass('control-open');
	}
});