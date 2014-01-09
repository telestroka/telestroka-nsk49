<?php
require('core/init.php');

$item = new SimpleXMLElement(file_get_contents($site->path . '/channels/data/info.xml'));
$item = (array) $item;
$item['price'] = (array) $item['price'];
if (isset($item['price']['social']))
	$item['price']['social'] = (array) $item['price']['social'];
if (isset($item['price']['commerce']))
	$item['price']['commerce'] = (array) $item['price']['commerce'];

$discounts = array();
foreach($item['discounts'] as $discount) {
	$discounts[(int) $discount['num']] = (float) $discount;
}
$item['discounts'] = $discounts;

$form = new Form();
$form->add('text', array('title' => 'Текст объявления'));
$form->add('phones', array('title' => 'Телефоны объявления'));
$form->add('num', array('title' => 'Количество дней выходов'));
$form->add('name', array('title' => 'Имя'));
$form->add('phone', array('title' => 'Контакты'));
$form->fill();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//process
	if ($form->isValid()) {
		$order_text = trim($form->text->value);
		if ($order_text == '') {
			$order_text_words = 0;
		} else {
			$order_text_words = str_replace("\n", ' ', $order_text);
			$order_text_words = str_replace("/", ' ', $order_text);
			$order_text_words = str_replace("\\", ' ', $order_text_words);
			$order_text_words = str_replace('-', ' ', $order_text_words);
			$order_text_words = explode(" ", $order_text_words);
			$order_text_words = count($order_text_words);
		}

		$order_phones = trim($form->phones->value);
		$order_phones_words = explode("\n", $order_phones);
		$order_phones_words = count($order_phones_words);
		if ($order_phones == '') $order_phones_words = 0;

		$total_words = $order_text_words + $order_phones_words * $item['price']['wordsInPhone'];
		$price = $item['price']['social']['full'];
		$order_clear_summa = $total_words * $price * $form->num->value;
		$discount = (isset($item['discounts'][$form->num->value])) ? $item['discounts'][$form->num->value] : 0;
		$order_summa = $order_clear_summa - $order_clear_summa * $discount / 100;

		$message = '
Город: Новосибирск
Канал: 49
' . $form->name->title . ': ' . $form->name->value . '
' . $form->phone->title . ': ' . $form->phone->value . '
' . $form->num->title . ': ' . $form->num->value . '
' . $form->text->title . ': ' . $form->text->value . '
' . $form->phones->title . ': ' . $form->phones->value . '
Цена: ' . round($order_summa, 2) . '
' . round($order_clear_summa, 2);

		file_put_contents($site->path . '/admin/orders.txt', "Дата: " . date('d.m.Y') . $message . "\n=====", FILE_APPEND);
		$site->mail($site->owner, 'Заказ с сайта ' . $_SERVER['SERVER_NAME'], $message);

		if (!$site->isAjaxRequest()) $site->redirect($site->url . '/ok');
	}

	if ($site->isAjaxRequest()) {
		$ajaxResponse = array();
		if (!$form->isValid()) {
			$ajaxResponse['errors'] = $form->getErrors();
		} else {
			$ajaxResponse['redirect'] = $site->url . '/ok';
		}
		$site->ajaxResponse($ajaxResponse);
	}
}

include($site->layoutPath . '/default.phtml');