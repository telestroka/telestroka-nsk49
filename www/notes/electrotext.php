<?php
require('../core/init.php');
require('./init.php');

$item = $site->db->query('SELECT notes FROM notes ORDER BY date DESC LIMIT 0,1;')->fetch(PDO::FETCH_ASSOC);
if ($item) {
	$notes = str_replace("\r", "\n", $item['notes']);
	$notes = str_replace("\n\n", "\n", $notes);
	$notes = explode("\n", $notes);
} else {
	$notes = array();
}

$site->ajaxResponse($notes);