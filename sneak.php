<?php
// The source code packaged with this file is Free Software, Copyright (C) 2005 by
// Ricardo Galli <gallir at uib dot es>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
// 		http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".

include('config.php');
include(mnminclude.'html1.php');
include(mnminclude.'sneak.php');

$globals['favicon'] = 'img/favicons/favicon-chat.png';

init_sneak();

$this_site = SitesMgr::get_info();

// Check the tab options and set corresponging JS variables
if ($current_user->user_id > 0) {
	if (!empty($_REQUEST['friends'])) {
		$option = _('amigos');
	} elseif (!empty($_REQUEST['admin']) && $current_user->admin) {
		$option = _('admin');
	} else {
		$option = $this_site->name;
	}
	// Haanga::Load('sneak/tabs.html', compact('option'));
}
//////

// Start html
$globals['extra_css'][] = 'es/sneak.css';
if (!empty($_REQUEST['friends'])) {
	do_header(_('amigos'), _('chat'), sneak_menu_items($option));
} elseif ($current_user->user_id > 0 && !empty($_REQUEST['admin']) && $current_user->admin) {
	do_header(_('admin'), _('chat'), sneak_menu_items($option));
} else {
	do_header(_('chat'), _('chat'), sneak_menu_items($option));
}
echo '<style> #wrap.wrap-color {background-color: #e0dad6} body {background-color: #e0dad6 }</style>';

$globals['site_id'] = SitesMgr::my_id();
Haanga::Load('sneak/base.html');



$globals['sneak_telnet'] = false;
Haanga::Load('sneak/form.html', compact('max_items'));

do_footer();

function sneak_menu_items($id) {
	global $globals, $current_user;
	$this_site = SitesMgr::get_info();
	$this_site_properties = SitesMgr::get_extended_properties();


	$items = array();

	if ($this_site->sub) {
	 $items[] = new MenuOption(_('general'), $globals['base_url_general'].'chat', $id, _('general'));	
	 $items[] =new MenuOption($this_site->name, $globals['base_url'].'chat', $id, $this_site->name);
	} else {
	 $items[] = new MenuOption($this_site->name, $globals['base_url'].'chat', $id, $this_site->name);
	}

	$items[] = new MenuOption(_('amigos'), $globals['base_url'].'chat?friends=1', $id, _('amigos'));
	if ($current_user->admin) {
		$items[] = new MenuOption(_('admin'), $globals['base_url'].'chat?admin=1', $id, _('admin'));
	}
	$items[] = new MenuOption(_('consola'), $globals['base_url'].'telnet', $id, _('consola'));

	return $items;
}
