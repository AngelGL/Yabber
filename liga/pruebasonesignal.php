<?PHP
define('REST_API_KEY', 'NDc4YjhkZGUtODMxYS0xMWU1LWI1MTQtYTAzNjlmMmQ5MzI4');
define('APP_ID', '478b8d70-831a-11e5-b514-a0369f2d9328');
define('PLAYER_ID','cb9af3d8-83d5-11e5-9c3c-a0369f2d9328');

  function sendMessage(){
    $content = array(
      "en" => 'Mensaje de prueba...'
      );
    
    $fields = array(
      'app_id' => APP_ID,
      'include_player_ids' => array(PLAYER_ID),
      'contents' => $content,
      'headings' => array("en" => 'Prueba')
    );
    
    $fields = json_encode($fields);
    print("\nJSON sent:\n");
    print($fields);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
                           'Authorization: Basic NGEwMGZmMjItY2NkNy0xMWUzLTk5ZDUtMDAwYzI5NDBlNjJj'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
  }
  
  $response = sendMessage();
  $return["allresponses"] = $response;
  $return = json_encode( $return);
  
  print("\n\nJSON received:\n");
  print($return);
  print("\n")
?>