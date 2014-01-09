<?php
require('../../core/init.php');
require('../init.php');
$site->setPage(basename(dirname(__FILE__)));

if ($site->getUserAccess() < $config['access']['edit']) $site->redirect($site->moduleUrl);

$id = $site->getParamInt('id');
if (!$id) $site->back();

$item = $site->db->query('SELECT * FROM notes WHERE id = ' . $id . ';')->fetch(PDO::FETCH_ASSOC);
if (empty($item)) $site->back();

$form = new Form();
$form->add('notes', array('title' => 'Список объявлений', 'value' => $item['notes']));
$form->add('date', array('title' => 'Дата', 'value' => $item['date']));
$form->fill();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//process
	if ($form->isValid()) {
		$formValues = $form->toArray();

		$fields = $values = array();
		$values['id'] = $id;
		foreach ($formValues as $field => $value) {
			if (!isset($config['items'][$field])) continue;
			$fields[] = '`' . $field . '`=:' . $field;
			$values[$field] = $value;
		}
		$st = $site->db->prepare(
			'UPDATE notes SET ' . implode(',', $fields) . ' WHERE id = :id'
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