;(function( $ ){
  var csldMoving = false,
  csldOpened = false;
var privateMethods = {
    execute: function(action, callback) {
        var $menu = $("#wpmchimpas"),
		    $trig = $("#wpmchimpas-trig"),
		    $body = $("body"),
		    $html = $("html"),
		    menucWidth = $menu.data("menucWidth"),
		    speed = $menu.data("speed"),
		    side = $menu.data("side"),
		    bodyClass = "body-wpmchimpas-open",
		    bodyAnimation,
		    bodyInit,
		    scrollTop,
		    diff;
		if ('open' === action || ('toggle' === action && !$body.hasClass('body-wpmchimpas-open'))) {
		    
			$body.bind('mousewheel DOMMouseScroll', freezeCon);
			$('.wpmchimpas-cont').bind('mousewheel DOMMouseScroll', scrollCon);
		    $('.wpmchimpas-overlay').addClass('showo');
		    centresp();
		    $(window).resize(function() {
		        $(".wpmchimpas").hasClass("wpmchimpas-open") && centresp()
		    });
		    $body.addClass(bodyClass);
			$('.wpmchimpas').addClass('wpmchimpas-open');
		} else {
			$body.unbind('mousewheel DOMMouseScroll', freezeCon);
			$('.wpmchimpas-cont').unbind('mousewheel DOMMouseScroll', scrollCon);
			$('.wpmchimpas-overlay').removeClass('showo');
		    $('body > *').css({
		        transform: 'none'
		    });
		    setTimeout(function() {
		        $body.removeClass(bodyClass).removeAttr('style');
		    }, speed);
		    $('.wpmchimpas').removeClass('wpmchimpas-open');
		}
    }
};
function centresp() {
	var $menu = $("#wpmchimpas"),
	    $trig = $("#wpmchimpas-trig"),
	    $body = $("body"),
	    $html = $("html"),
	    menucWidth = $menu.data("menucWidth"),
	    movein = $menu.data("movein"),
	    speed = $menu.data("speed"),
	    side = $menu.data("side"),
	    bodytrans,sltrans,
	    scrollTop,
	    diff,
	    bodyClass = "body-wpmchimpas-open";
	
    $('.wpmchimpas-bg,.wpmchimpas-overlay').css({'height': $('body').height()});
	if ($('.wpmchimpas').height() < $('.wpmchimpas-scroller').height())
	    $('.wpmchimpas-scroller').removeClass('wpmchimpas-vertcent');
	else
	    $('.wpmchimpas-scroller').addClass('wpmchimpas-vertcent');

	if ($(window).width() < 560) {
	    diff = (560 - $(window).width());
	    var perc = (menucWidth - diff) / 500;
	    $('.wpmchimpas-resp').css({
	        transform: 'scale(' + perc + ',' + perc + ')'
	    });
	} else {
	    $('.wpmchimpas-resp').removeAttr('style');
	    diff = 0;
	}
	menuWidth = menucWidth - diff;
	if (side === 'left') {
	    bodytrans = {
	        transform: 'translate(' + menuWidth + 'px,0)'
	    };
	    sltrans = {left:'-'+menuWidth+'px'};
	} else {
	    bodytrans = {
	        transform: 'translate(-' + menuWidth + 'px,0)'
	    };
	    sltrans = {right:'-'+menuWidth+'px'};
	}
	if(movein)
		$('body > *').css(bodytrans);
	else
    	$('body > *').not('.wpmchimpas').css(bodytrans);
}
function scrollCon(e) {
	var scrollTo = null;
	if (e.type == 'mousewheel') {
		scrollTo = (e.originalEvent.wheelDelta * -1);
	}
	else if (e.type == 'DOMMouseScroll') {
		scrollTo = 40 * e.originalEvent.detail;
	}
	if (scrollTo) {
		e.preventDefault();
		$(this).scrollTop(scrollTo + $(this).scrollTop());
	}
}

function freezeCon(e) {
	if (e.type == 'mousewheel' || e.type == 'DOMMouseScroll') {
		e.preventDefault()
	}
}
var methods = {
    open: function(name, callback) {
        privateMethods.execute('open', name, callback);
    },
    close: function(name, callback) {
        privateMethods.execute('close', name, callback);
    },
    toggle: function(name, callback) {
        privateMethods.execute('toggle', name, callback);
    }
};

$.csld = function(method) {
    if (methods[method]) {
        return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    } else if (typeof method === 'function' || typeof method === 'string' || !method) {
        return methods.toggle.apply(this, arguments);
    } else {
        $.error('Method ' + method + ' does not exist on jQuery.sidr');
    }
};
$.fn.csld = function(options) {
   var settings=$.extend({speed:400,side:"left",source:null,menucWidth:$(".wpmchimpas-scroller").width(),movein:false},options);
    var name = 'wpmchimpas',
        $sideMenu = $('#wpmchimpas'),
        $trigMenu = $('#wpmchimpas-trig');
    $sideMenu
        .addClass('wpmchimpas')
        .addClass(settings.side)
        .data({
            speed: settings.speed,
            side: settings.side,
            menucWidth: settings.menucWidth,
            movein:settings.movein
        });
    $trigMenu
        .addClass('wpmchimpas-trig')
        .addClass(settings.side);
    $('.wpmchimpas-bg').css({
    'background-color' : $('body').css('background-color')||'#fff',
    'background-image' : $('body').css('background-image'),
    });
    if(settings.movein){
	    if(settings.side == 'left')
		    $('.wpmchimpas').css({left:'-'+settings.menucWidth+'px'});
		else
			$('.wpmchimpas').css({right:'-'+settings.menucWidth+'px'});
	}
    if (typeof settings.source === 'function') {
        var newContent = settings.source(name);
        privateMethods.loadContent($sideMenu, newContent);
    }
    return this.each(function() {
        var $this = $(this),
            data = $this.data('wpmchimpas');
        if (!data) {
            $this.data('wpmchimpas', name);
            if ('ontouchstart' in document.documentElement) {
                $this.bind('touchstart', function(e) {
                    var theEvent = e.originalEvent.touches[0];
                    this.touched = e.timeStamp;
                });
                $this.bind('touchend', function(e) {
                    var delta = Math.abs(e.timeStamp - this.touched);
                    if (delta < 200) {
                        e.preventDefault();
                        methods.toggle(name);
                    }
                });
            } else {
                $this.click(function(e) {
                    e.preventDefault();
                    methods.toggle(name);
                });
            }
        }
    });
};
	
	$(function () {

if (isset(wpmchimpa.goo)) {
WebFont.load({
    google: {
      families: JSON.parse(wpmchimpa.goo)
    }
  });
}

if(isset(wpmchimpa.addon) || isset(wpmchimpa.addon_scode) || isset(wpmchimpa.addon_unlock)){
	if(isset(wpmchimpa.addon_desktop)) var deskb = true;
	if(isset(wpmchimpa.addon_tablet)) var tabb = true;
	if(isset(wpmchimpa.addon_mobile)) var mobb = true;

	$('.wpmchimpat-close-button').click(function(){$('.wpmchimpat').addClass('wpmchimpat-close')});

	var flipclosed=0;
	$('.wpmchimpaf-close-button').click(function(){
	if($('.wpmchimpaf').hasClass('wpmchimpaf-close'))
		$('.wpmchimpaf').removeClass('wpmchimpaf-close');
	else{
		$('.wpmchimpaf').addClass('wpmchimpaf-close');
		flipclosed=1;
	}
	});

	$(document).scroll(function() {
		if(flipclosed)return;
		if($(window).scrollTop() >= $(document).height()/2)
			$('.wpmchimpaf').removeClass('wpmchimpaf-close');
		else
			$('.wpmchimpaf').addClass('wpmchimpaf-close');
    });

}

if((isset(mobb) && $(window).width()<=480) || (isset(tabb) && $(window).width()>480 && $(window).width()<=768) || (isset(deskb) && $(window).width()>768))
$('.wpmchimpab').show();
$('.wpmchimpa_lock').show();

if(isset(wpmchimpa.litebox)){
	var x=0;
	var docHeight = $(document).height();
    var timeoutID;
	function show_wpmca_litebox(cook){
		$('.wpmchimpa-overlay-bg').show();
		x=1;
		if(cook)$.get(wpmchimpa.ajax_url,{'action':'wpmchimpa_setcookie_ajax'});
		if(isset(wpmchimpa.lite_close_time)){
	  		setup();  		 
			function setup() {
			    document.addEventListener("mousemove", resetTimer, false);
			    document.addEventListener("mousedown", resetTimer, false);
			    document.addEventListener("keypress", resetTimer, false);
			    document.addEventListener("DOMMouseScroll", resetTimer, false);
			    document.addEventListener("mousewheel", resetTimer, false);
			    document.addEventListener("touchmove", resetTimer, false);
			    document.addEventListener("MSPointerMove", resetTimer, false);
			    startTimer();
			}
			function startTimer() {
			    timeoutID = window.setTimeout(goInactive, wpmchimpa.lite_close_time*1000);
			}
			function resetTimer(e) {
			    window.clearTimeout(timeoutID);setup();		     
			}
			function goInactive() {
			     $('.wpmchimpa-overlay-bg').hide();
			}
		}
	}
	if(isset(wpmchimpa.lite_behave_cookie)){
			}
	else {
		function setup() {
    document.addEventListener("mousemove", resetTimer, false);
    document.addEventListener("mousedown", resetTimer, false);
    document.addEventListener("keypress", resetTimer, false);
    document.addEventListener("DOMMouseScroll", resetTimer, false);
    document.addEventListener("mousewheel", resetTimer, false);
    document.addEventListener("touchmove", resetTimer, false);
    document.addEventListener("MSPointerMove", resetTimer, false);
    startTimer();
}
function startTimer() {
    timeoutID = window.setTimeout(goInactive, wpmchimpa.lite_behave_time*1000);
}
function resetTimer(e) {
    window.clearTimeout(timeoutID);setup();		     
}
function goInactive() {
     if(x==0)show_wpmca_litebox(0);
}
if(isset(wpmchimpa.lite_desktop)) var deskl = true;
if(isset(wpmchimpa.lite_tablet)) var tabl = true;
if(isset(wpmchimpa.lite_mobile)) var mobl = true;
if((isset(mobl) && $(window).width()<=480) || (isset(tabl) && $(window).width()>480 && $(window).width()<=768) || (isset(deskl) && $(window).width()>768)){
	if(isset(wpmchimpa.lite_behave_time)){
		if(isset(wpmchimpa.lite_behave_time_inac)) {
	 		 setup();
		}
		else
			if(x==0)setTimeout(function(){show_wpmca_litebox(0)}, wpmchimpa.lite_behave_time*1000);	}
	if(isset(wpmchimpa.lite_behave_scroll)){
		var scrollp = (isset(wpmchimpa.lite_behave_scrollp))?wpmchimpa.lite_behave_scrollp:50;
		$(document).scroll(function() {if(x==0)if(($(window).scrollTop() / ($(document).height()-$(window).height())) * 100 > scrollp)show_wpmca_litebox(0);});	}
	if(isset(wpmchimpa.lite_behave_depart)){
		var departs = 15000;
		if(isset(wpmchimpa.lite_behave_departs))
			departs= wpmchimpa.lite_behave_departs * 1000;
		setTimeout(function() {
			document.documentElement.addEventListener('mouseleave', function(e){
				if(x!==0 || e.clientY > 20)return;
				show_wpmca_litebox(0);
			});
		},departs);
	}
}
	}
	$('.wpmchimpa-close-button').click(function(){
        $('.wpmchimpa-overlay-bg').hide(); 
    });
	if(isset(wpmchimpa.lite_close_bck)){    
    	$('.wpmchimpa-overlay-bg').click(function(e){
	        if($(e.target).find('#wpmchimpa-newsletterform').length != 0)
	        	$('.wpmchimpa-overlay-bg').hide();
	    });
    }

} 

if(isset(wpmchimpa.litebox) || isset(wpmchimpa.addon) || isset(wpmchimpa.widget) || isset(wpmchimpa.slider) || isset(wpmchimpa.addon_scode) || isset(wpmchimpa.addon_unlock)){
	$('.wpmchimpa-subs-button').click(function (e){
		e.preventDefault();
		var form = $(this).parents('form:first');
		$(form).next(".wpmchimpa-feedback").html('');
		var sEmail = $(form).find('.wpmchimpa_email').val();
        if ($.trim(sEmail).length == 0 || !(validateEmail(sEmail))) {
        	if(isset(wpmchimpa.errorfe)) var errmsg = wpmchimpa.errorfe;
        	else var errmsg = 'Please enter valid email address';
            $(form).next(".wpmchimpa-feedback").html(errmsg);
        }
        else {
        	$(form).find('.wpmchimpa-signal').fadeIn();
           	var SubsForm = $(form).serialize();
			chimpSubmit(form,SubsForm);
		}
		return false;
	});

	$('.wpmchimpa-soc').click(function (e){
		e.preventDefault();
		var cont = $(this).closest('.wpmchimpselector');
		var form = $(cont).find('form');
		$(cont).find('.wpmchimpa-signal').fadeIn();
		if($(this).hasClass('wpmchimpa-fb')){

			FB.login(function(response) {if (response.status === 'connected') {FB.api('/me', function(response) {
			  SubsForm={name:response.first_name+' '+response.last_name,
			  		email:response.email,
			  		action:'wpmchimpa_add_email_ajax'};
			  		$(form).find('.wpmchimpa_name').val(response.first_name+' '+response.last_name);
			  		$(form).find('.wpmchimpa_email').val(response.email);
			  		chimpSubmit(form,SubsForm);
			});}else{
				if(isset(wpmchimpa.errorue)) var errmsg = wpmchimpa.errorue;
	        	else var errmsg = 'Unknown Error';
	            $(form).next(".wpmchimpa-feedback").html(errmsg);
	            $(cont).find('.wpmchimpa-signal').hide();
			}}, {scope: 'public_profile,email'});

		}
		else if($(this).hasClass('wpmchimpa-gp')){

			gapi.auth.signIn({'callback': signinCallback });
			  function signinCallback(authResult) {if (authResult['status']['signed_in']) {if (authResult['status']['method'] == 'PROMPT') {gapi.client.load('oauth2', 'v2', function () {gapi.client.oauth2.userinfo.get().execute(function (resp) {
			   SubsForm={name:resp.name,
			  		email:resp.email,
			  		action:'wpmchimpa_add_email_ajax'};
			  		$(form).find('.wpmchimpa_name').val(resp.name);
			  		$(form).find('.wpmchimpa_email').val(resp.email);
			  		chimpSubmit(form,SubsForm);
			});});}} else {
				if(isset(wpmchimpa.errorue)) var errmsg = wpmchimpa.errorue;
	        	else var errmsg = 'Unknown Error';
	            $(form).next(".wpmchimpa-feedback").html(errmsg);
	            $(cont).find('.wpmchimpa-signal').hide();
			}}
		}
		else if($(this).hasClass('wpmchimpa-ms')){

			WL.login({scope: ["wl.basic", "wl.emails"]}).then(function (response) {
	            WL.api({path: "me",method: "GET"}).then(function (response) {
	               SubsForm={name:response.name,
			  		email:response.emails.account,
			  		action:'wpmchimpa_add_email_ajax'};
			  		$(form).find('.wpmchimpa_name').val(response.name);
			  		$(form).find('.wpmchimpa_email').val(response.emails.account);
			  		chimpSubmit(form,SubsForm);
	            },function (responseFailed) {
					if(isset(wpmchimpa.errorue)) var errmsg = wpmchimpa.errorue;
		        	else var errmsg = 'Unknown Error';
		            $(form).next(".wpmchimpa-feedback").html(errmsg);
		            $(cont).find('.wpmchimpa-signal').hide();
	            });},function (responseFailed){
					if(isset(wpmchimpa.errorue)) var errmsg = wpmchimpa.errorue;
		        	else var errmsg = 'Unknown Error';
		            $(form).next(".wpmchimpa-feedback").html(errmsg);
		            $(cont).find('.wpmchimpa-signal').hide();
		    });

		}

	});

	function chimpSubmit(cont,info){
	$(cont).next(".wpmchimpa-feedback").html('');
	 $(cont).find('.wpmchimpa-signal').fadeIn();

		$.ajax({
			type:"POST",
			url: wpmchimpa.ajax_url,
			data: info,
			success:function(data){
				$(cont).find('.wpmchimpa-signal').hide();
				if(data == '1'){
					if(wpmchimpa.suc_sub == 'suc_url' && isset(wpmchimpa.suc_url)){
						 $('.wpmchimpa-overlay-bg').hide(); 
						if(isset(wpmchimpa.suc_url_tab)){
							var win=window.open(wpmchimpa.suc_url,'_blank');
							win.focus();
						}
						else window.open(wpmchimpa.suc_url,'_self');
					}
					else if(wpmchimpa.suc_sub == 'suc_msg' && isset(wpmchimpa.suc_msg)){
						$(cont).fadeOut(function () {
							$(cont).next(".wpmchimpa-feedback").addClass('wpmchimpa-done').html(wpmchimpa.suc_msg);
						});
					}
					else{
						$(cont).fadeOut(function () {
							$(cont).next(".wpmchimpa-feedback").addClass('wpmchimpa-done').html('error');
						});
					}
					wpmchimpa_unlock_func(cont);
				}
				else{
					data = $.parseJSON(data);
					if(data.s == 1)wpmchimpa_unlock_func(cont);
					$(cont).fadeOut(function () {
						$(cont).next(".wpmchimpa-feedback").addClass('wpmchimpa-done').html(data.d);
					});
				}		
			}
			});
	}
	function wpmchimpa_unlock_func(cont){
		if($(cont).parents('.wpmchimpa_lock').length > 0 && isset(wpmchimpa_unlock) && !wpmchimpa_unlocked){
			$(cont).parents('.wpmchimpab').hide().next('.wpmchimpa_verify').show();
			var unlocktimer = setInterval(function(){ 
				$.ajax({
				type:"POST",
				url: wpmchimpa.ajax_url,
				data: {'action':'wpmchimpa_secret_ajax',
				'secret':wpmchimpa_unlock,
				'email':$(cont).find('.wpmchimpa_email').val()},
				success:function(data){
					if(data){
						$(cont).parents('.wpmchimpa_lock').addClass('secretload').after(data);
						clearInterval(unlocktimer);
						wpmchimpa_unlocked = 1;
					}
				}});
			 }, 5000);
		}
	}
}


if (isset(wpmchimpa.slider)) {
    if (isset(wpmchimpa.slider_desktop)) var desks = true;
    if (isset(wpmchimpa.slider_tablet)) var tabs = true;
    if (isset(wpmchimpa.slider_mobile)) var mobs = true;
    if ((isset(mobs) && $(window).width() <= 480) || (isset(tabs) && $(window).width() > 480 && $(window).width() <= 768) || (isset(desks) && $(window).width() > 768)) {
        var opts = [];
        var timeoutIDs,s=0;
        if (isset(wpmchimpa.slider_orient)) opts['side'] = wpmchimpa.slider_orient;
        if (isset(wpmchimpa.slider_behave_movein)) opts['movein'] = true;
        $('#wpmchimpas-trig').csld(opts);
        if (isset(wpmchimpa.slider_trigger_scroll)){
        	if(isset(wpmchimpa.slider_trigger_scrollp))var p = wpmchimpa.slider_trigger_scrollp;
        	else var p = 50;
				if(($(window).scrollTop() / $(document).height())*100 >= p)
					$('#wpmchimpas-trig').removeClass('scrollhide');
				else
					$('#wpmchimpas-trig').addClass('scrollhide');
	        $(document).scroll(function() {
				if(($(window).scrollTop() / $(document).height())*100 >= p)
					$('#wpmchimpas-trig').removeClass('scrollhide');
				else
					$('#wpmchimpas-trig').addClass('scrollhide');
	        });
	    }else $('#wpmchimpas-trig').removeClass('scrollhide');
        if (isset(wpmchimpa.slider_close_bck)) {
            $('.wpmchimpas-overlay').click(function() {
                $.csld('close', 'wpmchimpas');
            });
        }
        var depopen = 1;
        if(isset(wpmchimpa.slider_behave_depart)){
			var sdeparts = 15000;
			if(isset(wpmchimpa.slider_behave_departs))
				sdeparts= wpmchimpa.slider_behave_departs * 1000;
				setTimeout(function() {
					document.documentElement.addEventListener('mouseleave', function(e){
						if(depopen !== 1 || e.clientY > 20)return;
							$.csld('open', 'wpmchimpas');
							depopen = 0;
					});
				},sdeparts);
		}
        function setups() {
            document.addEventListener("mousemove", resetTimers, false);
            document.addEventListener("mousedown", resetTimers, false);
            document.addEventListener("keypress", resetTimers, false);
            document.addEventListener("DOMMouseScroll", resetTimers, false);
            document.addEventListener("mousewheel", resetTimers, false);
            document.addEventListener("touchmove", resetTimers, false);
            document.addEventListener("MSPointerMove", resetTimers, false);
            startTimers();
        }
        function startTimers() {
            timeoutIDs = window.setTimeout(goInactives, wpmchimpa.slider_behave_time * 1000);
        }
        function resetTimers(e) {
            window.clearTimeout(timeoutIDs);
            setups();
        }
        function goInactives() {
            if(s==0){$.csld('open', 'wpmchimpas');s=1;}
        }
        if (isset(wpmchimpa.slider_behave_time)) {
            if (isset(wpmchimpa.slider_behave_time_inac)) {
                setups();
            } else
                setTimeout(function() {
                    $.csld('open', 'wpmchimpas');
                }, wpmchimpa.slider_behave_time * 1000);
        }
    }
}


	});

}(jQuery));
function isset(){var r,t=arguments,n=t.length,e=0;if(0===n)throw new Error("Empty isset");for(;e!==n;){if(t[e]===r||null===t[e])return!1;e++}return!0}
function validateEmail(w){var a=new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i),e=a.test(w);return e?!0:!1}


