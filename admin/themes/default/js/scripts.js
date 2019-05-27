jQuery(document).ready(function () {
	$('#navigation li.active').parents('li.parent').addClass('opened');
	$('button[data-dismiss="alert"]').on('click', alertDissmis);
	$('.datepicker-date').datepicker({
		format: 'yyyy-mm-dd',
	});
	$('.panel-hide-toggle').on('click', hidePanel);

	function hidePanel(event) {
		const target = event.currentTarget;
		const parent = $(target).parents(':eq(2)');
		parent.toggleClass('collapsed');
	}
});
