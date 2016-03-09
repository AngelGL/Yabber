<?
// The source code packaged with this file is Free Software, Copyright (C) 2011 by
// Ricardo Galli <gallir at gmail dot com> and Menéame COmunicaciones
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
// 		http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".

include('../config.php');

header('Content-Type: text/plain; charset=UTF-8');

$q = '';
if (isset($_GET['q'])) {
    $q = mb_strtolower(trim($_GET['q']));
}
if (!$q) {
    return;
}

$q = $db->escape($q);


$where = "name like '$q%'";

$subs = $db->get_results("select name from subs where $where and id > 1 order by name asc limit 20");



if ($subs) {
	foreach ($subs as $sub) {
		$filtered[] = $sub->name;
	}
}
echo json_encode($filtered);
?>
