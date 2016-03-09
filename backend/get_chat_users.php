<?php
// The source code packaged with this file is Free Software, Copyright (C) 2008 by
// Ricardo Galli <gallir at uib dot es> and 
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
// 		http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".
// The code below was made by Beldar <beldar at gmail dot com>

/**** 
Description: it shows the comment votes in a div, used in modal windows via AJAX
***/

include_once('../config.php');
include_once('pager.php');

header('Content-Type: text/html; charset=utf-8');

global $db, $globals;

if (!isset($_GET['p']))  {
	$votes_page = 1;
} else $votes_page = intval($_GET['p']);

$votes_page_size = 20;
$votes_offset=($votes_page-1)*$votes_page_size;


echo '<div style="width:550px;padding: 5px 5px;text-align:left">';
echo '<div style="padding-top: 20px;min-width:350px">';
$votes = $db->get_results("SELECT sneaker_user, user_avatar, user_login FROM sneakers, users WHERE user_id = sneaker_user LIMIT $votes_offset,$votes_page_size");
if ($votes) {
	echo '<div class="voters-list">';
	foreach ( $votes as $vote ) {
		echo '<div class="item">';
		echo '<a '.$style.' href="'.get_user_uri($vote->user_login).'" title="'.$vote->user_login.': '.$vote_detail.'" target="_blank">';
		echo '<img class="avatar" src="'.get_avatar_url($vote->sneaker_user, $vote->user_avatar, 20).'" width="20" height="20" alt=""/>';
		echo $vote->user_login.'</a>';
		echo '</div>';
	}
	echo "</div>\n";
}


do_contained_pages($id, 20, $votes_page, $votes_page_size, 'get_chat_users.php', 'voters');
echo '</div>';
echo '</div>';

