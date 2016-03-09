<?php
// The Meneame source code is Free Software, Copyright (C) 2005-2009 by
// Ricardo Galli <gallir at gmail dot com> and Menéame Comunicacions S.L.
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Affero General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Affero General Public License for more details.

// You should have received a copy of the GNU Affero General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.

// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
// 		http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".

include_once('config.php');
include(mnminclude.'html1.php');

meta_get_current();

$page_size = $globals['page_size'];

$page = get_current_page();
$offset=($page-1)*$page_size;
$globals['ads_section'] = 'portada';

$pagetitle = $globals['site_name'];
if ($page > 1) {
	$pagetitle .= " ($page)";
}

$sticky_ids=array();

do_header($pagetitle, _('portada'));

$from = '';
switch ($globals['meta']) {
	case '_subs':
		if ($current_user->user_id && $current_user->has_subs) {
			print_index_tabs(7); // Show "personal" as default
			$from_time = '"'.date("Y-m-d H:00:00", $globals['now'] - $globals['time_enabled_votes']).'"';
			$from = ", links";
			$where = "id in ($current_user->subs) AND status in ('queued','published','closed') and link_last_comment > $from_time and link_id = link";
			//Link::$original_status = true; // Show status in original sub
			break;
		}
		break;
		// NOTE: If the user has no subscriptions it will fall into next: _*
	case '_*':
		$from_time = '"'.date("Y-m-d H:00:00", $globals['now'] - $globals['time_enabled_comments']).'"';
		$from = ", subs";
		$where = "sub_statuses.status='queued' AND sub_statuses.id = sub_statuses.origen and sub_statuses.date > $from_time and sub_statuses.origen = subs.id and subs.owner > 0";
		$rows = -1;
		print_index_tabs(8);
		break;
	case '_friends':
		if (! $current_user->user_id > 0) do_error(_('debe autentificarse'), 401); // Check authenticated users
		$from_time = '"'.date("Y-m-d H:00:00", $globals['now'] - 86400*100).'"';
		$from = ", friends, links";
		$where = "sub_statuses.id = ". SitesMgr::my_id() ." AND link_last_comment > $from_time and status in ('published','queued','closed') and friend_type='manual' and friend_from = $current_user->user_id and friend_to=link_author and friend_value > 0 and link_id = link";
		$rows = -1;
		print_index_tabs(1); // Friends
		break;
	default:
		print_index_tabs(0); // All
		Haanga::Load('site_search_box.html'); // Search box for search engines
		$from_time = '"'.date("Y-m-d H:00:00", $globals['now'] - 86400*100).'"';
		$from = ", links";
		if(SitesMgr::my_id() != 1){
		$where = "sub_statuses.id = ". SitesMgr::my_id() ." AND link_last_comment > $from_time and status in ('published','queued','closed') and link_id = link";
		} else {
		$where = "sub_statuses.id != 1 AND date > $from_time and status in ('published','queued','closed') and link_id = link";
		}

	}


/*** SIDEBAR ****/
echo '<div id="sidebar">';
do_sub_statistics();
if(SitesMgr::my_id() == 1){
echo '<div class="sidebox"><a class="button crear_plataforma" href="/subedit">Crear plataforma</a></div>';
}
do_sub_message_right();
do_banner_right();
if ($globals['show_popular_published']) {
	do_active_stories();
}

// do_banner_promotions();
if ($globals['show_popular_published']) {
	do_most_clicked_stories();
	do_best_stories();
}
do_banner_promotions();
// do_best_sites();
do_most_clicked_sites();
if ($page < 2) {
	do_best_comments();
}
// do_categories_cloud('published');
do_sub_admin_tools();
do_sub_moderators();
//do_last_subs('published');
do_apps_widget();

do_vertical_tags('published');
// do_last_blogs();
do_site_statistics();
echo '</div>';
/*** END SIDEBAR ***/

//echo '<div id="container">'."\n";

echo '<div id="newswrap">';

//echo '<div class="notice"><div class="container notice--container" ><span ><span class="notice--text">Participa en la <a style="text-decoration: underline" href="/liga">Quiniela</a> de Yabber</span></span></div></div>';
echo '<ul class="posts--group">'."\n";

do_active_subs();
do_banner_top_news();

if ($page == 1 && empty($globals['meta']) && ($stickys = Link::sticky(SitesMgr::my_id()))) {
	$counter1= 0;
	foreach ($stickys as $sticky) {
		//print_r($sticky);
		$sticky_ids[$counter1]= $sticky->id;
		if ($globals['now'] - $sticky->last_comment < 3600){
			$sticky->recent = true;
		}
		$vars = array('self' => $sticky);
		Haanga::Load("link_sticky.html", $vars);
		$counter1++;
	}
	echo "<hr>";
}

if($current_user->user_id >0){
	$user_code = urlencode(trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, 'whateveryouwant1', $current_user->user_login, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)))));
	$invite_url ="http://yabber.us/register?code=".$user_code;
} else{
	$invite_url="http://yabber.us/register?code=NVWf/z6GNByruIR9yjt5EfEG6bBOfRWpxjph9mitCl8=";
}
$twitter_url="https://twitter.com/intent/tweet?text=".urlencode("Regístrate en Yabber, una nueva red social española ").$invite_url;
$facebook_url="https://www.facebook.com/sharer/sharer.php?u=".$invite_url."&t=".urlencode("Regístrate en Yabber, una nueva red social española ");
$whatsapp_url="whatsapp://send?text=".urlencode("Regístrate en Yabber, una nueva red social española ").$invite_url;


$order_by = "ORDER BY link_last_comment DESC ";
if (!$rows) $rows = $db->get_var("SELECT SQL_CACHE count(*) FROM sub_statuses $from WHERE $where");

// We use a "INNER JOIN" in order to avoid "order by" whith filesorting. It was very bad for high pages
$sql = "SELECT".Link::SQL."INNER JOIN (SELECT link FROM sub_statuses $from WHERE $where $order_by LIMIT $offset,$page_size) as ids ON (ids.link = link_id)";

$links = $db->object_iterator($sql, "Link");
if ($links) {
	$counter = 0;
	foreach($links as $link) {
		if(!in_array($link->id, $sticky_ids)){
		$link->max_len = 600;
		$link->print_summary('short');
			if($globals['ads']){
				$counter++; Haanga::Safe_Load('private/ad-interlinks.html', compact('counter', 'page_size'));
			} else{
				$counter++; Haanga::Safe_Load('private/invite-friends.html', compact('counter', 'page_size','twitter_url', 'facebook_url','whatsapp_url'));
			}
		}
	}
}


do_pages($rows, $page_size);
echo '</ul>';
echo '</div>';


do_footer_menu();
do_footer();
if($globals['mobile']){
echo '<style>#backTop {right: 76px;}</style>';
}
echo '<a href="'.$globals['base_url'].'submit"><div id="newPost" style="display: block;"><div class="index"></div></div></a>';
exit(0);

function print_index_tabs($option=-1) {
	global $globals, $db, $current_user;

	if (($globals['mobile'] && ! $current_user->has_subs) || (!empty($globals['submnm']) && ! $current_user->user_id)) return;

	$items = array();
	$items[] = array('id' => 0, 'url' => $globals['meta_skip'], 'title' => _('todas'));
	if (isset($current_user->has_subs)) {
		$items[] = array('id' => 7, 'url' => $globals['meta_subs'], 'title' => _('suscripciones'));
	}

	// if (! $globals['mobile'] && empty($globals['submnm']) && ($subs = SitesMgr::get_sub_subs())) {
	// 	foreach ($subs as $sub) {
	// 		$items[] = array(
	// 			'id'  => 9999, /* fake number */
	// 			'url' => 'm/'.$sub->name,
	// 			'selected' => false,
	// 			'title' => $sub->name,
	// 		);
	// 	}
	// }

	//$items[] = array('id' => 8, 'url' => '?meta=_*', 'title' => _('m/*'));

	// RSS teasers
	switch ($option) {
		case 7: // Personalised, published
			$feed = array("url" => "?subs=".$current_user->user_id, "title" => _('suscripciones'));
			break;
		default:
			$feed = array("url" => '', "title" => "");
			break;
	}

	if ($current_user->user_id > 0) {
		$items[] = array('id' => 1, 'url' => '?meta=_friends', 'title' => _('amigos'));
	}

	$vars = compact('items', 'option', 'feed');
	return Haanga::Load('print_tabs.html', $vars);
}

