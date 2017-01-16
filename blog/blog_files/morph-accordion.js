jQuery(document).ready(function ($) {
'use strict';
	$('.morph-by-bonfire ul li ul').before($('<span><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve"><polygon id="arrow-24-icon" points="206.422,462 134.559,390.477 268.395,256 134.559,121.521 206.422,50 411.441,256 "/></svg></span>'));

	$(".menu > li > span, .sub-menu > li > span").on('touchstart click', function(e) {
	e.preventDefault();
		if (false == $(this).next().is(':visible')) {
			$(this).parent().siblings().find(".sub-menu").slideUp(300);
			$(this).siblings().find(".sub-menu").slideUp(300);
			$(this).parent().siblings().find("span").removeClass("morph-submenu-active");
		}
		$(this).next().slideToggle(300);
		$(this).toggleClass("morph-submenu-active");
	})
	
	
	$(".menu > li > span").on('touchstart click', function(e) {
	e.preventDefault();
		if($(".sub-menu > li > span").hasClass('morph-submenu-active'))
			{
				$(".sub-menu > li > span").removeClass("morph-submenu-active");
			}
	})
	
	$(".morph-main-menu-button-wrapper, .morph-main-menu-activator, .morph-background-overlay").on('touchstart click', function(e) {
		if($(".menu > li > span, .sub-menu > li > span").hasClass('morph-submenu-active'))
			{
				$(".menu > li").find(".sub-menu").slideUp(300);
				$(".menu > li > span, .sub-menu > li > span").removeClass("morph-submenu-active");
			}
	})
	
});