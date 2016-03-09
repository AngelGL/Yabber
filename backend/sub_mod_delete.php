<?php
// The source code packaged with this file is Free Software, Copyright (C) 2005 by
// Ricardo Galli <gallir at uib dot es>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
// 		http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".

include('../config.php');

header('Content-Type: application/json; charset=UTF-8');

if(!($id=intval($_POST['id']))) {
	error(_('falta el ID del sub'). " $id");
}

if(!($user_id=intval($_POST['user']))) {
	error(_('falta el ID de usuario'). " $id");
}

if (! $current_user->user_id) {
	error(_('usuario incorrecto'));
}

if (! check_security_key($_POST['key'])) {
	error(_('clave de control incorrecta'));
}

$db->transaction();
$exists = SitesMgr::delete_mod($id, $user_id);
$db->commit();

echo json_encode($exists);

// end

function error($mess) {
	$dict['error'] = $mess;
	echo json_encode($dict);
    die;
}

/******
function check_delete_defaults($uid) {
	// Check if the user is suscribed to all default
	global $db;

	// Get original site
	$site = SitesMgr::my_parent();
	$defaults = SitesMgr::get_sub_subs_ids($site);

	$suscriptions = pref_read($uid, 'sub_follow');
	if (count($defaults) == count($suscriptions) && count(array_diff($defaults, $suscriptions)) == 0) {
		pref_delete($uid, 'sub_follow');
	}
}
*******/

