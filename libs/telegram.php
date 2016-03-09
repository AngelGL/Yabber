<?php

require('telegram-bot-api.php');
define('TOKEN', 'PUT_YOUR_TOKEN_HERE');


class telegram {

static function notificacion($id, $user_to, $type, $user_from) {
		$texto='';
		switch ($type) {
			case 'post':
				$texto="📄 ".$user_from." te mencionó en una nota. \n http://yabber.us/go?id=".$id."&what=posts";
				break;
			case 'private':
			    $texto="📩 ".$user_from." te envió un privado. \n http://yabber.us/go?id=".$id."&what=privates";
			    break;
			case 'comment':
			    $texto="💬 ".$user_from." te mencionó en un comentario. \n http://yabber.us/go?id=".$id."&what=comments";
			    break;
			case 'friend':
				$texto="👥 ".$user_from." te siguió. \n http://yabber.us/go?id=".$id."&what=friends";
				break;
			default:
				return false;
		}

		$bot = new telegram_bot(TOKEN);
		$bot->send_message($user_to, $texto);
	}


}