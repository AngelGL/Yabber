<?php
$routes = array(
	''			=> 'index.php',
	'story'		=> 'story.php',
	'submit'	=> 'submit.php',
	'subedit'	=> 'subedit.php',
	'moderators'   => 'submods.php',
	'plataformas'		=> 'subs.php',
	'editlink'	=> 'editlink.php',
	'comment_ajax'	=> 'backend/comment_ajax.php',
	'login'		=> 'login.php',
	'register'	=> 'register.php',
	'cloud'		=> 'cloud.php',
	'sites_cloud'	=> 'sitescloud.php',
	'rsss'		=> 'rsss.php',
	'promote'	=> 'promote.php',
	'values'	=> 'values.php',
	'queue'		=> 'shakeit.php',
	'legal'		=> 'legal.php',
	'liga'		=> 'liga/dispatcher.php',
	'go'		=> 'go.php',
	'b'			=> 'bar.php',
	'c'			=> 'comment.php',
	'p'			=> 'submnm.php',
	'user'		=> 'user.php',
	'search'	=> 'search.php',
	'between'	=> 'between.php',
	'rss'		=> 'rss2.php',
	'comments_rss'	=> 'comments_rss2.php',
	'sneakme_rss'	=> 'sneakme_rss2.php',
	'chat'		=> 'sneak.php',
	'telnet'		=> 'telnet.php',
	'popular'	=> 'topstories.php',
	'top_visited'	=> 'topclicked.php',
	'top_active'	=> 'topactive.php',
	'top_comments'	=> 'topcomments.php',
	'top_users'		=> 'topusers.php',
	'top_commented'	=> 'topcommented.php',
	'profile'	=> 'profile.php',
	'sitemap'	=> 'sitemap.php',
	'trends'	=> 'trends.php',
	'faq-es'	=> 'faq-es.php',
	'opensearch' => 'opensearch_plugin.php',
	'backend'	=> 'backend/dispatcher.php',
	'api'		=> 'api/dispatcher.php',
	'plaza'	=> 'sneakme/dispatcher.php',
	'mobile'	=> 'mobile/dispatcher.php',
	'captcha'	=> 'captcha.php',
	'comenzando'    => 'getting_started.php',
	'equipo'    => 'staff.php',
);

$globals['path'] = $path = preg_split('/\/+/', $_SERVER['PATH_INFO'], 10, PREG_SPLIT_NO_EMPTY);
$script = $routes[$path[0]];

if (empty($script) || !file_exists($script)) {
	include_once 'config.php';
	do_error("not found", 404, true);
}

$globals['script'] = $script;
if ((include './'.$script) === FALSE) {
	include_once 'config.php';
	do_error("bad request $script", 400, true);
}

