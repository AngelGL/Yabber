<?PHP
define('REST_API_KEY', 'API_KEY_HERE');
define('APP_ID', 'APP_ID_HERE');

class OneSignal{

  static function notification($id, $user_push_id, $type, $user_from) {
    $texto='';
    $url='';
    $group_message='';
    switch ($type) {
      case 'post':
        $texto="📄 ".$user_from." te mencionó en una nota."; 
        $url="http://yabber.us/go?id=".$id."&what=posts";
        $group_message="menciones en notas";
        break;
      case 'private':
          $texto="📩 ".$user_from." te envió un privado.";
          $url="http://yabber.us/go?id=".$id."&what=privates";
          $group_message="privados nuevos";
          break;
      case 'comment':
          $texto="💬 ".$user_from." te mencionó en un comentario.";
          $url="http://yabber.us/go?id=".$id."&what=comments";
          $group_message="menciones en comentarios";
          break;
      case 'friend':
        $texto="👥 ".$user_from." te siguió.";
        $url="http://yabber.us/go?id=".$id."&what=friends";
        $group_message="amigos nuevos";
        break;
      default:
        return false;
    }
    $response = OneSignal::sendMessage($user_push_id, $texto, $url, $type, $group_message);
  }

  static function sendMessage($user_push_id,$texto,$url,$type,$group_message){
    $content = array(
      "en" => $texto
      );

    $group_message_object = array(
      "en" => "$[notif_count] ".$group_message
      );
    
    $fields = array(
      'app_id' => APP_ID,
      'include_player_ids' => array($user_push_id),
      'data' => array("url" => $url),
      'contents' => $content
      // ,'android_group' => $type,
      // 'android_group_message' => $group_message_object
    );
    
    $fields = json_encode($fields);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                           'Authorization: Basic '.REST_API_KEY));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
  }
  
}
?>