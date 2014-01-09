function orderChange() {
    var text = $.trim($('#text').val()),
        phones = $.trim($('#phones').val()),
        num = $('#num option:selected').val(),
        words, phones_words, clear_summa, summa, total_words, price, discount;

	text = text.replace(/\n/g, ' ');
	text = text.replace(/-/g, ' ');
	text = text.replace(/\\/g, ' ');
	text = text.replace(/\//g, ' ');
	words = text.split(' ');
	words = words.length;
	if (text == '') words = 0;

	phones_words = phones.split("\n");
	phones_words = phones_words.length;
	if (phones == '') phones_words = 0;

    total_words = words + phones_words * priceList.wordsInPhone;
    price = priceList['social']['full'];
    clear_summa = num * price * total_words;
    discount = discounts[num] ? discounts[num] : 0;
    summa = clear_summa - clear_summa * discount / 100;

    $('#discount').html(discount.toFixed(2));
    $('#total_summa').html(summa.toFixed(2));
}

$(function() {
	var $form = $('form[name="order"]');

    var text = document.getCookie('text'),
        phones = document.getCookie('phones'),
        name = document.getCookie('name'),
        phone = document.getCookie('phone');

    if (text) $form[0].text.value = text;
    if (phones) $form[0].phones.value = phones;
    if (name) $form[0].name.value = name;
    if (phone) $form[0].phone.value = phone;
	
	orderChange();

	$form.submit(function() {
        document.setCookie('text', $form[0].text.value, 365);
        document.setCookie('phones', $form[0].phones.value, 365);
        document.setCookie('name', $form[0].name.value, 365);
        document.setCookie('phone', $form[0].phone.value, 365);

		if ($form.attr('data-request') == 'ajax') {
			sendAjaxForm($form);
			return false;
		}

		return true;
	});

    $('#text, #phones, #num').change(orderChange);
    $('#text, #phones').keyup(orderChange);
});