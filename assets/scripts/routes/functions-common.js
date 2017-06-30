/**
 * Toggle anything
 */
$.fn.toggler = function() {

	return this.each( function() {

		$(this).click(function(event) {

			// Prevent click event on a tag
			if ($(this).is('a')) {
				if(event.preventDefault) { event.preventDefault(); }
			}

			var target = $(this).data('toggle'),
				elem   = $('#' + target),
				height = elem.prop('scrollHeight'),
				css    = 'active-' + target;

			// Remove inline max-height
			if (typeof elem.attr('style') !== typeof undefined && elem.attr('style') !== false) {
				elem.removeAttr('style');
			}
			else {
				elem.css('max-height', height);
			}

			$('body').toggleClass(css);
			elem.toggleClass('active');
		});
	});
};