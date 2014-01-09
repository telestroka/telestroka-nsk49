$(function() {
	var $form = $('form[name="edit"]');

	$form.submit(function() {
		if ($form.attr('data-request') == 'ajax') {
			sendAjaxForm($form);
			return false;
		}

		return true;
	});

	$('.datepicker').pickmeup({
		format  : 'Y-m-d',
		position		: 'right',
		hide_on_select	: true,
		before_show		: function () {
			var $this	= $(this);
			$this.pickmeup('set_date', $this.val());
		},
		change			: function (formatted) {
			$(this).val(formatted);
		}
	});
});