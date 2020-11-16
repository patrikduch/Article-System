/**
 * AJAX Nette Framwork plugin for jQuery
 *
 * @copyright  Copyright (c) 2009, 2010 Jan Marek
 * @copyright  Copyright (c) 2009, 2010 David Grudl
 * @license    MIT
 * @link       http://nettephp.com/cs/extras/jquery-ajax
 */

(function($) {

	$.nette = {
		success: function(payload)
		{
			// redirect
			if (payload.redirect) {
				window.location.href = payload.redirect;
				return;
			}

			// state
			if (payload.state) {
				$.nette.state = payload.state;
			}

			// snippets
			if (payload.snippets) {
				for (var i in payload.snippets) {
					$.nette.updateSnippet(i, payload.snippets[i]);
				}
				$(document).load();
			}
		},

		updateSnippet: function(id, html)
		{
			if(typeof this.onSnippedLoad === "function")
			{
				// call the event handler first and let its do some stuff with html code (jQuery object)
				var live = $("<div>").html(html);
				
				// call handler, and if returns false do nothing
				if(this.onSnippedLoad.call(live) === false) return;
				
				// replace old code with snipped
				$('#' + id).replaceWith(live.contents());
			}
			else
			{
				// default, just put the html code to page
				$('#' + id).html(html);
			}
		},

		// create animated spinner
		createSpinner: function(id)
		{
			return this.spinner = $('<div></div>').attr('id', id ? id : 'ajax-spinner').html('<div></div>').ajaxStart(function() {
				$(this).show();

			}).ajaxStop(function() {
				$(this).fadeOut();

			}).appendTo('body').hide();
		},

		// current page state
		state: null,

		// spinner element
		spinner: null,
		
		// event before sniped is inserted to page
		onSnippedLoad: null
		
	};


})(jQuery);



jQuery(function($) {

	$.nette.createSpinner();

	// apply AJAX unobtrusive way
	$('a.ajax').live('click', function(event) {
		
		$.ajaxSetup({
			success: $.nette.success,
			dataType: 'json'
		});		
		
		event.preventDefault();
		if ($.active) return;

		$.post(this.href, $.nette.success);

	});

});