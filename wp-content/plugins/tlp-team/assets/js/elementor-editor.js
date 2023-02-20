(function($) {
	$(document).ready(function() {
		elementor.hooks.addAction('panel/open_editor/widget', function(panel, model, view) {
			$('body').on('click', '.image-selector-inner.rttm-pro, .rttm-pro-field', function(e) {
				$(this).parents().find('.image-selector-inner, rttm-pro-field').removeClass('is-pro');
				$(this).addClass('is-pro');

				setTimeout(function() {
					$(e.target).removeClass('is-pro');
				}, 1800);

				return false;
			});
		});
	});
})(jQuery);
