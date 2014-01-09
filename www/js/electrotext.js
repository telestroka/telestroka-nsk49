var electrotext_index = 0;

function electrotext_show(notes) {
	var $electrotext = $('#electrotext');


	if (!notes[electrotext_index]) {
		electrotext_index = 0;
	}

	var note = notes[electrotext_index];

	$electrotext.html(note);
	$electrotext.fadeIn('slow').delay(1000).fadeOut('slow', function() {electrotext_show(notes)});
	electrotext_index++;
}

$(function() {
    $.ajax({
        type: 'get',
        url: '/notes/electrotext.php',
        success: function (notes) {
            electrotext_show(notes)
        }
    });
});