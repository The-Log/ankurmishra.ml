var mashcounter = jQuery.noConflict();

mashcounter(function() {

'use strict';

var mashpv_setviews_ajax = function() {
        if (mashpv !== undefined) {
            var id = mashpv.postID ? mashpv.postID : 1;
            mashcounter.ajax({
                //type: 'GET',
                method: 'get',
                data: {
                    id: id,
                },
                dataType: 'html',
                url: mashpv.ajaxurl + 'mashpvsetviews',
                success: function(data) {
                    //console.log('mashpvsetview: ' + data);
                }
            });
        }
    };
    
var mashpv_getviews_ajax = function() {
        if (mashpv !== undefined) {
            var id = mashpv.postID ? mashpv.postID : 1;
            
            var numItems = mashcounter('.mashpv .count').length;
            console.log('numItems: ' + numItems);
            
            if (numItems > 1){
                var oldValue = new Array();
                mashcounter('.mashpv .count').each(function(){
                    oldValue.push(mashcounter(this).text());

            }); 
                    oldValue = oldValue[1];
            } else {
                var oldValue = mashcounter('.mashpv .count').text();
            }


            mashcounter.ajax({
                //type: 'GET',
                method: 'get',
                data: {
                    id: id
                },
                dataType: 'html',
                url: mashpv.ajaxurl + 'mashpvgetviews',
                success: function(data) {

                       //Create the current value
                        var thisValue = mashpv_round(data);
                        console.log ('old value:' + oldValue);
                        console.log ('this value:' + thisValue);
                        //If the old value is different to the new, change start animation
                       //if (typeof oldValue[1] !== 'undefined'){
                        if (String(oldValue) !== String(thisValue) && String(thisValue) !== '') {
                           mashcounter('.mashpv .count').stop(true, false).animate({'opacity': 0}, 500, function() {
                            mashcounter(this).text(mashpv_round(data)).stop(true, false).animate({'opacity': 1}, 500);
                            //mashcounter('.mashpv .mashsb-sharetext').stop(true, false).animate({'opacity': 1}, 500);
                            });
                        }
                       //}
                        //Change the old value
                        oldValue = thisValue;

                    
                    //mashcounter('.mashpv .count').stop(true, false).animate({'opacity': 1}, 500);
                    //mashpv_round(mashcounter('.mashpv .count').text());
                }
            });
        }
    };
    
/* Load one time and increments the pagevisit ++1 after 2 sec delay.*/
function mashpv_init(){
     if (mashpv !== undefined) { 
        if (mashcounter('.mashpv .count').length) {
        //console.log('Count up ++1');
        //String to store the old value
        //mashpv_animateShare();
        setTimeout(mashpv_setviews_ajax, 2000);
         }
     }
                    
};  


/* Different modes - Request the Pageviews .*/
function mashpv_getcount(){
     if (typeof mashpv !== undefined) {
         
            // Mode 1: Runs only when ajax is enabled
            // 
            if (mashcounter('.mashpv .count').length && mashpv.enableajax === "1") {
                //setTimeout(mashpv_getviews_ajax, 1000);
                console.log('setTimeout: ' + mashpv.enableajax);
                mashpv_getviews_ajax();
            }
            // Mode 2: Runs only in Realtime mode
            if (mashcounter('.mashpv .count').length && mashpv.realtime === "1") {
                //console.log('setInterval');
                //mashpv_getviews_ajax();
                mashpv_getviews_ajax();
                setInterval(mashpv_getviews_ajax, mashpv.ajaxfreq * 1000);
            }
     }
};  


function mashpv_round(numbers){
     if (typeof mashpv !== "undefined" && mashpv.round === "1") {
         //console.log("valid");
         numbers = Math.round(numbers);
         var visits = ''
         //console.log("gerundet " + numbers);
             if (numbers > 1000000) {
                    visits = Math.round((numbers / 1000000)*10)/10 + 'M';
                    return visits;
                }
                if (numbers > 1000) {
                    visits = Math.round((numbers / 1000)*10)/10 + 'k';
                    return visits;
                    
                 }  
     }
     return numbers;
}
  
    /* Animate Shares with the same feed in effect like the Pageview counter */
    function mashpv_animateShare() {
        mashcounter('.mashsb-count .counts').stop(true, false).animate({'opacity': 0.1}, 100, function() {
            mashcounter(this).stop(true, false).animate({'opacity': 1}, 500);
            mashcounter('.mashsb-count .mashsb-sharetext').stop(true, false).animate({'opacity': 1}, 500);
        });

        mashcounter('.mashpv .count').stop(true, false).animate({'opacity': 1}, 100, function() {
            mashcounter(this).text().stop(true, false).animate({'opacity': 1}, 500);
            mashcounter('.mashpv .mashsb-sharetext').stop(true, false).animate({'opacity': 1}, 500);
        });
    }

mashpv_init();
mashpv_getcount(); 

    
    /* Tool tips */
    mashcounter('.mashpv').addClass('mashtip');
    mashcounter('.mashtip').each(function() {
        mashcounter(this).append('<span class="mashtip-content">' + mashcounter(this).attr('data-mashtip') + '</span>');
    });

    // browser.msie deprecated and removed in jQuery 1.9
    //if (mashcounter.browser.msie && $.browser.version.substr(0, 1) < 7) 
    if(navigator.appVersion.indexOf("MSIE 7.")!=-1)
    {
        mashcounter('.mashtip').mouseover(function() {
            mashcounter(this).children('.mashtip-content').css('visibility', 'visible');
        }).mouseout(function() {
            mashcounter(this).children('.mashtip-content').css('visibility', 'hidden');
        })
    }


});



