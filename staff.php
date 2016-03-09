<?
// The source code packaged with this file is Free Software, Copyright (C) 2005 by
// Ricardo Galli <gallir at uib dot es>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
// 		http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".

include('config.php');
include(mnminclude.'html1.php');

include_once(mnminclude.'ban.php');

$page_size = $globals['page_size'] * 2;

$items = array(_('usuario'),  _('level'));


		$select = "SELECT user_id";
		$from_where = " FROM users WHERE user_title not in ('Usuario') ";
		$order_by = " ORDER BY user_login ";
	

$sql = "$select $from_where $order_by";
if (! ($users = unserialize(memcache_mget($sql))) ) {
	$users = $db->get_results($sql);
	memcache_madd($sql, serialize($users), 3600);
}


do_header(_('Equipo') . ' | ' . $globals['site_name']);

echo '<div id="singlewrap">' . "\n";
echo '<h1 class="m-centered">'._('Sobre nosotros').'</h2></div> <br>';

echo '<h3 class="m-centered">Somos un equipo de gente joven con ganas de innovar en el mundo de las redes sociales.</h3>';


echo '<br><br><br>';
echo '<h1 class="m-centered">'._('Equipo').'</h2></div><br>';

echo '<section class="p-about--team"><ol class="p-about--team--list">';

// // Print headers
// for($i=0; $i<count($items); $i++) {
// 	echo '<th class="short">';
// 		echo '<span class="info_s">'.$items[$i].'</span>';
// 	echo '</th>';
// }

// echo '</tr>';

$user = new User;

if ($users) {
	foreach($users as $dbuser) {
		$user->id=$dbuser->user_id;
		$user->read();
		$user->all_stats();
		echo '<li class="p-about--team--item">';
		echo '<div class="p-about--team--member">';
		echo '<span class="p-about--team--member--image" style="border-radius: 50%"><a href="'.get_user_uri($user->username).'" class="tooltip u:'.$user->id.'"><img class="avatar" src="'.get_avatar_url($user->id, $user->avatar, 80).'" width="80" height="80" alt="avatar"/></a></span>';
		echo '<div class="p-about--team--member--name">'.$user->names.'</div>';
		echo '<div class="p-about--team--member--title">'.$user->title.'</div>';
		echo '<div class="p-about--team--member--username"><a href="'.get_user_uri($user->username).'">@'.$user->username.'</a></div>';
		echo '</li>';
	}
}
echo "</ol></section>\n\n";
echo "</div>\n";
do_footer_menu();
do_footer();

