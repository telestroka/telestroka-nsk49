<?php
require('../core/init.php');
require('./init.php');

if ($site->getUserAccess() > 3) {
	$notes = $site->db->query('SELECT * FROM notes ORDER BY date DESC;')->fetchAll(PDO::FETCH_ASSOC);
} else {
	$per_page = 11;
	$first = $site->getPageItemsFirst($per_page);
	$notes = $site->db->query(
		'SELECT * FROM notes ORDER BY date LIMIT ' . $first . ',' . $per_page . ';'
	)->fetchAll(PDO::FETCH_ASSOC);
	$count = $site->db->query('SELECT COUNT(*) FROM notes')->fetchColumn();
	$pager = $site->getPager($per_page, $count);

	$currentPage = $site->getParam('page');
	if ($currentPage == '') $currentPage = 1;
	$countPages = ceil($count / $per_page);
}

include($site->layoutPath . '/default.phtml');