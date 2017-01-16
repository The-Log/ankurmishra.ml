jQuery(document).ready(function($) { 
	/**
	* Detect touch device
	*/
	if( is_touch_device() == false ){
		$('body').addClass( 'not-touch-device' );
	}

	/**
	* Scrolling state
	*/
	$(window).scroll( function(){

		var window_offset = $(window).scrollTop();

		// Adding scroll state
		if( window_offset > 5 ){
			$('body').addClass( 'scrolling' );
		} else {
			$('body').removeClass( 'scrolling' );			
		}
	});

	/**
	* Toggle expanded UI
	*/
	$('.toggle-button').click(function(e){
		e.preventDefault();

		// Cache selector
		var button = $(this);

		// Rotate this button
		if( button.is(':not(".no-animation")') ){
			button.addClass( 'rotate animated' );			
		}

		// Get target ID
		var target_id 		= button.attr( 'data-target-id' );
		var sliding_content = $('#'+target_id).find('.sliding-content');
		var direction		= sliding_content.attr( 'data-direction' );

		// Display target ID
		if( $('#'+target_id).is(':visible') ){
			$('#'+target_id).fadeOut(function(){
				// Remove rotation
				button.removeClass( 'rotate animated' );
			});

			if( 'left' == direction ){
				sliding_content.animate({ 'left' : '-100%' });
			}

			if( 'bottom' == direction ){
				sliding_content.animate({ 'top' : '100%' });
			}
		} else {
			$('#'+target_id).fadeIn(function(){
				// Remove rotation
				button.removeClass( 'rotate animated' );
			});

			if( 'left' == direction ){
				sliding_content.animate({ 'left' : '0' });
			}

			if( 'bottom' == direction ){
				sliding_content.animate({ 'top' : '0' });
			}
		}

		// Mark body
		$('body').toggleClass( target_id + '-expanded' );
	});

	/**
	* Toggle login links UI
	*/
	$('#toggle-login-links').click(function(e){
		e.preventDefault();

		var login_links = $('#login-links');

		if( login_links.is('.active') ){
			login_links.addClass( 'animated fast zoomOut' );

			setTimeout( function(){
				login_links.removeClass( 'animated fast zoomOut active' );
			}, 400 );
		} else {
			login_links.addClass( 'animated fast zoomIn active' );

			setTimeout( function(){
				login_links.removeClass('animated fast zoomIn' );
			}, 400 );
		}
	});

	/**
	* Sticky post adjustment
	*/
	var count_sticky_posts = $('.hentry.sticky').length;
	if( ( count_sticky_posts % 2 != 0 ) ){
		$('.hentry.sticky:last').addClass('last-sticky');
	}
	
	/**
	* Figure width adjustment for mobile
	*/
	$('.entry-content figure').each(function(){
		var figure 				= $(this);
		var main 				= $('#main');
		var figure_width 		= figure.width();
		var main_width 			= main.width();

		if( figure_width > ( main_width * .9 ) ){
			figure.removeAttr( 'style' );
			figure.find('img').css( 'width', '100%' );
		}
	});

	/**
	* Civil Footnotes Support
	* Slide the window instead of jumping it
	*/
	$('#main').on( 'click', 'a[rel="footnote"], a.backlink', function(e){
		e.preventDefault();
		var target_id = $(this).attr('href');
		var respond_offset = $(target_id).offset();

		$('html, body').animate({
			scrollTop : respond_offset.top - 100
		});
	});

	/**
	* Materializing inputs and textareas
	*/
	$('#respond, .drawer-widgets, .entry-content').find('input[type="text"], input[type="password"], input[type="email"], input[type="url"], input[type="search"], textarea').addClass('materialize-input');

	$('.materialize-input').each(function(){

		var input 		= $(this);
		var id 			= input.attr('id');
		var name 		= input.attr('name');
		var placeholder = input.attr('placeholder');
		var value 		= input.val();

		// Wrap the input
		input.wrap('<span class="material-input-wrap" id="material-input-wrap-'+name+'"></span>');

		// Input does'nt have ID, add ID on the fly
		if( typeof id == 'undefined' ){
			input.attr( 'id', 'material-id-' + name );
			id = 'material-id-' + name;
		}

		// input has ID
		if( typeof id != 'undefined' ){

			var label = $('label[for="'+id+'"]');

			// Create label on the fly if the input has no label but have placeholder
			if( label.length == 0 && typeof placeholder != 'undefined' ){
				// Remove native placeholder
				input.attr({ 'placeholder' : '', 'value' : '' });

				// Prepend fake label
				$('#material-input-wrap-'+name ).prepend('<label for="'+ id +'">'+ placeholder +'</label>');

				// Mark the wrapper has labe
				$('#material-input-wrap-'+name ).addClass('has-label');
			}

			// Input has label
			if( label.length > 0 ){

				// Move the label to input-wrap
				label.prependTo( $('#material-input-wrap-'+name ) );

				// Mark the wrapper has labe
				$('#material-input-wrap-'+name ).addClass('has-label');
			}
		} 

		// If input has been filled
		if( value != '' ){
			input.closest( '.material-input-wrap' ).addClass( 'filled' );	
		}

	});

	$('body').on({
		focusin: function(){
			$(this).closest('.material-input-wrap').removeClass('focusout');
			$(this).closest('.material-input-wrap').addClass('focus');
		},
		focusout: function(){
			$(this).closest('.material-input-wrap').addClass('focusout');
			$(this).closest('.material-input-wrap').removeClass('focus');

			if( $(this).val() == '' ){
				$(this).closest('.material-input-wrap').removeClass('filled');
			} else {
				$(this).closest('.material-input-wrap').addClass('filled');
			}
		}
	}, '.material-input-wrap input, .material-input-wrap textarea');

	/**
	* Manually add ripple effect
	*/
	$('.drawer-header .site-logo-link, #top-navigation .site-logo-link').addClass('ripple-effect').attr({ 'data-ripple-mode' : 'fixed' });
	$('#masthead .site-logo-link').addClass('ripple-effect').attr({ 'data-ripple-limit' : '#masthead', 'data-ripple-color' : '#40A9F2' });
	$('.drawer-navigation a').addClass('ripple-effect').attr({ 'data-ripple-mode' : 'fixed', 'data-ripple-color' : 'rgba(0,0,0,0.3)'});
	$('.entry-meta a').addClass( 'ripple-effect' );
	$('.nav-links a').addClass( 'ripple-effect' );
	$('.entry-author-box a').addClass( 'ripple-effect' ).attr({ 'data-ripple-limit' : '.entry-author-box', 'data-ripple-color' : '#02a8f3' });
	$('.cat-links a').addClass( 'ripple-effect' ).attr({ 'data-ripple-limit' : '.cat-links', 'data-ripple-color' : '#02a8f3' });
	$('.tags-links a').addClass( 'ripple-effect' ).attr({ 'data-ripple-limit' : '.tags-links', 'data-ripple-color' : '#02a8f3' });
	$('#submit').wrap('<span class="submit-wrap ripple-effect"></span>');
	$('.submit-your-work > a').attr({ 'data-ripple-limit' : 'body', 'data-ripple-color' : '#02a8f3', 'data-ripple-mode' : 'page' });

	// frontend-publishing-pro
	$('.wpfepp-tabs a, .wpfepp-message a').addClass('ripple-effect');

	/**
	* Submit your design
	*/

	$('.submit-your-work > a').attr({ 'data-ripple-limit' : 'body', 'data-ripple-color' : '#02a8f3', 'data-ripple-mode' : 'page' });

	/**
	* Ripple effect
	*/
	$('body').on( 'click', '.ripple-effect', function(e){
		e.preventDefault();

		var link = $(this);
		var limit = link.attr('data-ripple-limit');
		var limit_class = link.attr( 'data-ripple-wrap-class' );
		var mode = link.attr( 'data-ripple-mode' );
		var href = link.attr('href');
		
		if( typeof limit == 'undefined' ){
			var limit_dom = link;
		} else {
			var limit_dom = link.closest( limit );			
		}

		// Determine limit information
		var limit_dom_offset = limit_dom.offset();
		var limit_dom_width = limit_dom.outerWidth();
		var limit_dom_height = limit_dom.outerHeight();

		// Determine location of the click
		var click_x = e.pageX - limit_dom_offset.left;
		var click_y = e.pageY - limit_dom_offset.top;

		// Determining ripple color
		var color = link.attr( 'data-ripple-color' );

		if( typeof color == 'undefined' ){
			color = 'rgba(255,255,255,.7)';
		}

		// Draw ripple wrap
		var rippling_wrap = $('<span class="rippling-wrap"></span>').css({
			'position'	: 'absolute',
			'top' : limit_dom_offset.top,
			'left' : limit_dom_offset.left,
			'width' : limit_dom_width,
			'height' : limit_dom_height,
			'content' : '',
			'overflow' : 'hidden',
			'z-index' : 40
		});

		if( typeof limit_class != 'undefined' ){
			rippling_wrap.addClass( limit_class );
		}

		if( typeof mode != 'undefined' && mode == 'fixed' ){
			rippling_wrap.css({
				top: 0,
				right: 0,
				bottom: 0,
				left: 0
			});
			$(link).append( rippling_wrap );
		} else if( typeof mode != 'undefined' && mode == 'page' ){
			rippling_wrap.css({
				'z-index' : 100
			});
			$('body').append(rippling_wrap);
		} else {
			$('body').append(rippling_wrap);
		}

		// Draw rippling effect
		var rippling = $('<span class="rippling"></span>').css({
			'background' : color,
			'position'	: 'absolute',
			'top' : click_y - 500,
			'left' : click_x - 500,
			'width' : 1000,
			'height' : 1000,
			'content' : ''
		});

		// Slowing down rippling effect on smaller limit
		if( limit_dom_width < 300 && limit_dom_height < 300 ){
			rippling.addClass( 'small' );
		}

		$('.rippling-wrap:last').append( rippling );

		// Remove rippling component after a second
		setTimeout( function(){
			rippling_wrap.fadeOut(function(){
				$(this).remove();
			});
		}, 500 );

		// Safari appears to ignore all the effect if the clicked link is not prevented using preventDefault()
		// To overcome this, preventDefault any clicked link
		// If this isn't hash, redirect to the given link
		if( typeof href != 'undefined' && href.substring(0, 1) != '#' ){
			setTimeout( function(){
				window.location = href;
			}, 200 );
		}

		if( link.is('span') && link.has('input') ){
			setTimeout( function(){
				link.removeClass('ripple-effect');
				link.find('input').trigger('click');
				link.addClass('ripple-effect');
			}, 200 );
		}

		if( link.is('span') && link.has('button') ){
			setTimeout( function(){
				link.removeClass('ripple-effect');
				link.find('button').trigger('click');
				link.addClass('ripple-effect');
			}, 200 );
		}
	});
});

/**
* Detect touch device
*/
function is_touch_device() {
	return 'ontouchstart' in window // works on most browsers 
		|| 'onmsgesturechange' in window; // works on ie10
};

/**
* Skip link focus fix
*/
( function() {
	var is_webkit = navigator.userAgent.toLowerCase().indexOf( 'webkit' ) > -1,
	    is_opera  = navigator.userAgent.toLowerCase().indexOf( 'opera' )  > -1,
	    is_ie     = navigator.userAgent.toLowerCase().indexOf( 'msie' )   > -1;

	if ( ( is_webkit || is_opera || is_ie ) && document.getElementById && window.addEventListener ) {
		window.addEventListener( 'hashchange', function() {
			var element = document.getElementById( location.hash.substring( 1 ) );

			if ( element ) {
				if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) {
					element.tabIndex = -1;
				}

				element.focus();
			}
		}, false );
	}
})();