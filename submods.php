<?php
// The source code packaged with this file is Free Software, Copyright (C) 2005 by
// Ricardo Galli <gallir at uib dot es>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
//              http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".

include('config.php');
include(mnminclude.'html1.php');
$globals['ads'] = false;
$globals['extra_js'][] = 'autocomplete/jquery.autocomplete.min.js';
$globals['extra_js'][] = 'jquery.user_autocomplete.js';
$globals['extra_css'][] = 'jquery.autocomplete.css';

array_push($globals['cache-control'], 'no-cache');


if (empty($routes)) die; // Don't allow to be called bypassing dispatcher

force_authentication();

if (!empty($_POST['id'])) {
	$id = intval($_POST['id']);
} else {
	if ($globals['submnm']) {
		$id = SitesMgr::my_id();
	} else {
		$id = intval($_GET['id']);
	}
}
if (! $id) $id = -1;

$errors = array();
$site = SitesMgr::get_info();


$can_edit = SitesMgr::can_edit($id);

if (! $can_edit) {
	$errors[] = _("No tienes permisos para administrar esta plataformas");
} else {
	if ($_POST['created_from']) {
		$id = save_sub($id, $errors);
		if ($id && empty($errors)) {
			$sub = SitesMgr::get_info($id, true);
			//User::set_pref($current_user->user_id, 'sub_follow', $id);
			header("Location: ".$globals['base_url_general']."p/$sub->name/moderators");
			die;
		}
		if (! $id) {
			$sub = (object) $_POST; // Copy the data for the form, in case it failed to store
		}
	}
}

if ($id > 0 && $can_edit) {
	$globals['submnm_info'] = $sub = SitesMgr::get_info($id);
	$extended = SitesMgr::get_extended_properties($id);
}


if (!empty($_POST['id'])) {
	$id = intval($_POST['id']);
}
$mods_ids = SitesMgr::get_mods();
if(!empty($mods_ids)){
$ids = join(',',$mods_ids);
$ids = "(".$ids.")";

$sql = "select user_id, user_login, user_avatar from users where user_id in $ids";
$mods = $db->get_results($sql);
}


do_header(_("Moderadores"));
echo '<div id="singlewrap">'."\n";
Haanga::Load('sub_mods.html', compact('sub', 'mods', 'errors', 'site'));
echo "</div>"."\n";

do_footer();

function save_sub($id, &$errors) {
	global $current_user, $db;

	$mods = SitesMgr::get_mods();
	$u1 = User::get_valid_username(clean_input_string($_POST['u1']));
	$id1 = User::get_user_id($u1);
	if(in_array($id1, $mods)) array_push($errors, _('Este usuario ya es moderador'));

	array_push($mods,$id1);
	$mods_serialized = serialize($mods);

	if (! SitesMgr::can_edit($id)) {
		array_push($errors, _('usuario no autorizado a editar'));
		return false;
	}

	$site = SitesMgr::get_info();

	if($site->owner != $current_user->user_id && ! $current_user->admin) {
		array_push($errors, _('propietario erróneo'));
	}

	if (empty($errors)) {
		$db->transaction();
		if ($id > 0) {
			$r = $db->query("update subs set mods = '$mods_serialized' where id = $id");
		} 
		if ($r && $id > 0) {
			$db->commit();
			return $id;
		} else {
			array_push($errors, _('error actualizando la base de datos'));
			$db->rollback();
		}

	}
	return false;
}
