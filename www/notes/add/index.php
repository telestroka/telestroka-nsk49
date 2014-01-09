<?php
require('../../core/init.php');
require('../init.php');
$site->setPage(basename(dirname(__FILE__)));

if ($site->getUserAccess() < $config['access']['add']) $site->redirect($site->moduleUrl);

$form = new Form();
$form->add('notes', array('title' => 'Список объявлений'));
$form->add('date', array('title' => 'Дата', 'value' => date('Y-m-d')));

$form->fill();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//process
	if ($form->isValid()) {
		$formValues = $form->toArray();

		$fields = $placeholders = $values = array();
		foreach ($formValues as $field => $value) {
			if (!isset($config['items'][$field])) continue;
			$fields[] = $field;
			$placeholders[] = ':' . $field;
			$values[$field] = $value;
		}
		$st = $site->db->prepare(
			'INSERT INTO notes (' . implode(',', $fields) . ') values (' . implode(',', $placeholders) . ')'
		);
		$st->execute($values);

		if (!$site->isAjaxRequest()) $site->redirect($site->url . '/ok');
	}

	if ($site->isAjaxRequest()) {
		$ajaxResponse = array();
		if (!$form->isValid()) {
			$ajaxResponse['errors'] = $form->getErrors();
		} else {
			$ajaxResponse['redirect'] = $site->moduleUrl;
		}
		$site->ajaxResponse($ajaxResponse);
	}
}

include($site->layoutPath . '/default.phtml');