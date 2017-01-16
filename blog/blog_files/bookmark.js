/*
 * @author Ryan Sutana
 * @description procees all datas passed from front-end
 * since v 1.5.0
 */

jQuery(document).ready(function($) {	
	
	$(".add_boomark_form").each(function(i){
	
		$("#add_boomark_form"+i).submit(function(){
			var data = {
				action: 'add_bookmarks_action',
				nonce: bookmarkAjax.nonce,
				item_id: this.item_id.value,
				user_id: this.user_id.value
			};
		
			$.post(bookmarkAjax.url, data, post_response);
			
			return false;
		});
		
	});
	

	$(".remove_boomark_form, .remove_boomark_items").each(function(i){
		
		$(this).live('submit', function() {
		//$(this).submit(function(){
			var data = {
				action: 'remove_bookmarks_action',
				nonce: bookmarkAjax.nonce,
				item_id: this.item_id.value
			};
		
			$.post(bookmarkAjax.url, data, function(data){
				if( data == '1' ) {
					location.reload();
				}
			});
			
			return false;
		});
		
	});
	
	
	function post_response(data){
		alert(data);
		location.reload();
	}
	
	
	$("#email_bookmarks").submit(function(){
		var data = {
			action: 'email_bookmarks_action',
			nonce: this._wpnonce.value
		};
		
		// disable button onsubmit to avoid dubble submittion
		$("#submit").attr("disabled", "disabled");
		
		// add our pre-loading
		$("#submit").val("loading...");
		
		$.post(bookmarkAjax.url, data, function(data){
			// return the value to default
			$("#submit").val("Email Bookmarks");
			
			//append data to #message
			$(".sb-container #message").append(data);
		});
		
		return false;
	});
	
	
	
	/*
	 * data fetcher list control
	 */
	$(".list-view").live('click', function() {
		//remove and add cookie class
		$(".df-category ul").removeClass().addClass('item-list');
		
		//set jquery cookie
		$.cookie('item-layout', 'list');
	});
	
	$(".grid-view").live('click', function() {
		//remove and add cookie class
		$(".df-category ul").removeClass().addClass('item-grid');
		
		//set jquery cookie
		$.cookie('item-layout', 'grid');
	});
	
	
	/*
	 * author cookie control
	 */
	$(".author-list").live('click', function() {
		//remove and add cookie class
		$(".df-author ul").removeClass().addClass('item-list');
		
		//set jquery cookie
		$.cookie('author-layout', 'list');
	});
	
	$(".author-grid").live('click', function() {
		//remove and add cookie class
		$(".df-author ul").removeClass().addClass('item-grid');
		
		//set jquery cookie
		$.cookie('author-layout', 'grid');
	});
	
});