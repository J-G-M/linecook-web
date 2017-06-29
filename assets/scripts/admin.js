(function($) {

	$(document).ready(function() {

		$('body').on('click', '[data-event=remove-layout]', function() {
			return confirm('Delete widget?');
		});
	});
})(jQuery);