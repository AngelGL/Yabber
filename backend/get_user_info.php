<?php
// The source code packaged with this file is Free Software, Copyright (C) 2005 by
// Ricardo Galli <gallir at uib dot es> and
// Beldar <beldar.cat at gmail dot com>
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
// 		http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".
// The code below was made by Beldar <beldar at gmail dot com>
if (! defined('mnmpath')) {
	include_once('../config.php');
	header('Content-Type: text/html; charset=utf-8');
	header('Cache-Control: public, s-maxage=300');
}
include_once(mnminclude.'geo.php');


if (empty($_GET['id'])) die;
$id = intval($_GET['id']);
$user = new User;
if ($id > 0) {
	$user->id=$id;
} else {
	$user->username = $_GET['id'];
}
if (! $user->read()) {
	echo _('usuario inexistente');
	die;
}

echo '<div style="float: center;"><img class="lazy" style="align:center; float:center; border-radius: 50%" src="'.get_avatar_url($user->id, $user->avatar, 80).'" width="80" height="80" alt="'.$user->username.'"/></div>';

$user->print_medals();

if ($user->names) echo '<span class="tooltip-names">' . $user->names . '</span>';
if ($user->verified =='yes'){
	echo '&nbsp; <img width="16" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAB3UlEQVRIx51WPUvDUBR1yCAODsVBxEHEycGxOPgrxN/hD3AQOoj9UItCceokHTqG/oJuvkTpIJ2KSIciDiKF2iSUUs9JGpu8vqRJHlx6e3Pvue+ed3Nf1tYSrpKwz4rCfqNQTxqnXBXDygHktCSsfMUYb0M/B/CkKJyZJ/aENu+ZlacvYxKBl4W1D4Cv62fbBeNvEp0xjF2ZAI4VPzitMDZJAnNBRVqxzRjexztweExDi0onBrHkXV9Cphlp+YHItikx/UPd9QyZKOnzYNFFF0wiPZsSm/19mJGKPlrzv2u8JPARCx9i+xR1U9ISAr9/GWmwNSSfbvAMdEX5QwKpaAmBv4407LSh8NP9t3YLf76l8ofg77hsWHvQP6JoeZiDh2n0hJjE5u5bcgcA+CQwNtwkq8AjqGwxgaMor3lr/mrBJMloWcJxmEBEzJZQknhaVBS5CQTfgwMonYgSm3fmSEtDSyC2Q2x/TGzCMIgo060kOS1uzICY8rh4ihnFzXhawhQRSzVF9axjWkGPrkpQyD6mlygqqMb1Oh7UIZ9srww3mjOPrRMr9tK5McYbcKyloKTGmJW3WXBVTXeA9aTSZ4qx3KsGWjnVwhfDEQDakHfIFXaZo1Cf29r0icP4A2ufTpm/hVFnAAAAAElFTkSuQmCC"/>';
}
echo '<br/>';
echo '<span class="tooltip-username">@' . $user->username . '</span>';
echo '<br/>';
echo '<br/>';

if ($user->bio) {
	echo '<span class="tooltip-bio">' .text_to_html($user->bio) . '</span>';
}
echo '<br/>';
echo '<br/>';
if ($user->url) echo  '<span class="tooltip-web">' .$user->url . '</span>';
echo '<br/>';
echo '<br/>';
if ($current_user->user_id > 0 && $current_user->user_id  != $user->id)  {
	echo '&nbsp;' . User::friend_teaser($current_user->user_id, $user->id);
}

/*
$post = new Post;
if ($post->read_last($user->id)) {
	echo '<br clear="left"><strong>'._('última nota').'</strong>:<br/>';
	$post->show_avatar = false;
	$post->print_text(150);
}
*/
