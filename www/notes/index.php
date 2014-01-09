<?php
require('../core/init.php');
require('./init.php');

if ($site->getUserAccess() > 3) {
	$notes = $site->db->query('SELECT * FROM notes ORDER BY date DESC;')->fetchAll(PDO::FETCH_ASSOC);
} else {
	$notes = $site->db->query('SELECT * FROM notes ORDER BY date DESC LIMIT 0,3;')->fetchAll(PDO::FETCH_ASSOC);
}

include($site->layoutPath . '/default.phtml');