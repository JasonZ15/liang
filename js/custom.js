jQuery.noConflict();

jQuery(document).ready(function(){
	var et_disable_toptier = jQuery("meta[name=et_disable_toptier]").attr('content'),
		et_theme_folder = jQuery("meta[name=et_theme_folder]").attr('content'),
		$et_top_menu = jQuery('ul#top-menu > li > ul'),
		$et_secondary_menu = jQuery('ul#secondary-menu li ul');
		
	jQuery('ul.nav').superfish({ 
		delay:       200,                            // one second delay on mouseout 
		animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation 
		speed:       'fast',                          // faster animation speed 
		autoArrows:  true,                           // disable generation of arrow mark-up 
		dropShadows: false                            // disable drop shadows 
	});

	jQuery('ul.nav > li > a.sf-with-ul').parent('li').addClass('sf-ul');

	if ( $et_secondary_menu.length ){
		$et_secondary_menu.find('>li:odd').addClass('even-item');
	}

	var $featured_content = jQuery('#featured #slides'),
		et_featured_slider_auto = jQuery("meta[name=et_featured_slider_auto]").attr('content'),
		et_featured_auto_speed = jQuery("meta[name=et_featured_auto_speed]").attr('content'),
		et_featured_slider_pause = jQuery("meta[name=et_featured_slider_pause]").attr('content');
		
	if ($featured_content.length){
		var et_featured_options = {
				timeout: 0,
				speed: 500,
				cleartypeNoBg: true,
				prev:   '#featured a#left-arrow', 
				next:   '#featured a#right-arrow',
				pager:  'div#controllers'
			}
			
		if ( et_featured_slider_auto == 1 ) et_featured_options.timeout = et_featured_auto_speed;
		if ( et_featured_slider_pause == 1 ) et_featured_options.pause = 1;
			
		$featured_content.cycle( et_featured_options );
		
		var $et_featured_single_slide = $featured_content.find('.slide');
		if ( $et_featured_single_slide.length === 1 ) $et_featured_single_slide.css('display','block');
	}

	var $et_single_slider = jQuery('#et-slides-items');
	if ($et_single_slider.length){
		$et_single_slider.cycle( {
			timeout: 0,
			speed: 500,
			cleartypeNoBg: true,
			prev:   '#et-slides a#et-single-left-arrow', 
			next:   '#et-slides a#et-single-right-arrow'
		});
		
		var $et_single_slide = $et_single_slider.find('.et-slide');
		if ( $et_single_slide.length === 1 ) $et_single_slide.css('display','block');
		
		jQuery('.et-slide a').hover(function(){
			jQuery(this).find('img').stop(true,true).fadeTo('fast', 0.8);
			jQuery(this).find('span.magnify').stop(true,true).fadeTo('fast', 1);
		}, function(){
			jQuery(this).find('img').stop(true,true).fadeTo('fast', 1);
			jQuery(this).find('span.magnify').stop(true,true).fadeTo('fast', 0);
		})
	}
		
	var $footer_widget = jQuery("#footer-widgets .footer-widget");
	if ( $footer_widget.length ) {
		$footer_widget.each(function (index, domEle) {
			if ((index+1)%4 == 0) jQuery(domEle).addClass("last").after("<div class='clear'></div>");
		});
	}

	et_search_bar();

	function et_search_bar(){
		var $searchform = jQuery('#header div#search-form'),
			$searchinput = $searchform.find("input#searchinput"),
			searchvalue = $searchinput.val();
			
		$searchinput.focus(function(){
			if (jQuery(this).val() === searchvalue) jQuery(this).val("");
		}).blur(function(){
			if (jQuery(this).val() === "") jQuery(this).val(searchvalue);
		});
	}

	if ( et_disable_toptier == 1 ) jQuery("ul.nav > li > ul").prev("a").attr("href","#");

	var $comment_form = jQuery('form#commentform');


	$comment_form.find('input, textarea').each(function(index,domEle){
		var $et_current_input = jQuery(domEle),
			$et_comment_label = $et_current_input.siblings('label'),
			et_comment_label_value = $et_current_input.siblings('label').text();
		if ( $et_comment_label.length ) {
			$et_comment_label.hide();
			if ( $et_current_input.siblings('span.required') ) { 
				et_comment_label_value += $et_current_input.siblings('span.required').text();
				$et_current_input.siblings('span.required').hide();
			}
			$et_current_input.val(et_comment_label_value);
		}
	}).live('focus',function(){
		var et_label_text = jQuery(this).siblings('label').text();
		if ( jQuery(this).siblings('span.required').length ) et_label_text += jQuery(this).siblings('span.required').text();
		if (jQuery(this).val() === et_label_text) jQuery(this).val("");
	}).live('blur',function(){
		var et_label_text = jQuery(this).siblings('label').text();
		if ( jQuery(this).siblings('span.required').length ) et_label_text += jQuery(this).siblings('span.required').text();
		if (jQuery(this).val() === "") jQuery(this).val( et_label_text );
	});

	$comment_form.find('input#submit').click(function(){
		if (jQuery("input#url").val() === jQuery("input#url").siblings('label').text()) jQuery("input#url").val("");
	});

	jQuery(function(){
		var $project_item = jQuery('.main-product');
		$project_item.mouseenter(function(e) {			
			move_thumb(jQuery(this),e);
			//jQuery(this).children(".boutique_description_border").css({'display':'block', 'opacity':'0', 'top': y, 'left': x + 10}).stop(true,true).animate({'opacity': 1,'left': x},400);
			jQuery(this).children(".boutique_description_border").css({'display':'block', 'opacity':'0', 'top': y, 'left': x + 10}).stop(true,true).animate({'opacity': 1},400);
		}).mousemove(function(e) {
			move_thumb(jQuery(this),e);	
			jQuery(this).children(".boutique_description_border").css({'top': y + 10,'left': x + 20});
		}).mouseleave(function() {
			jQuery(this)
			.children(".boutique_description_border")
			.stop(true,true).animate({"opacity": "hide"}, "fast");
		});
		
		function move_thumb(this_element,event_name){
			x = event_name.pageX - this_element.offset().left;
			y = event_name.pageY - this_element.offset().top;
		};
		
		jQuery("a[class*=et-shop-item]").fancybox({
			'overlayOpacity'	:	0.7,
			'overlayColor'		:	'#000000',
			'transitionIn'		: 'elastic',
			'transitionOut'		: 'elastic',
			'easingIn'      	: 'easeOutBack',
			'easingOut'     	: 'easeInBack',
			'speedIn' 			: '700',
			'centerOnScroll'	: true,
			'autoDimensions'	: false
		});
	});
});