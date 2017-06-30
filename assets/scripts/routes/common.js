import './functions-common';

export default {
	init() {

		// Toggle buttons
		$('[data-toggle]').toggler();

		$('[data-modal]').click( function( event) {
			if(event.preventDefault) { event.preventDefault(); }

			var id = $(this).data('modal');
			$('#' + id).toggleClass('active');
		});
	},
	finalize() {},
};