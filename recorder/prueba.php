<?php
$target="https://upload.clyp.it/upload";

# http://php.net/manual/en/curlfile.construct.php

if(isset($_FILES['file']) and !$_FILES['file']['error']){
  
// Create a CURLFile object / procedural method 
$cfile = curl_file_create($_FILES['file']['tmp_name'],'audio/wav','prueba.wav'); // try adding 
}
$audiodata = array('audioFile' => $cfile);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $target);
curl_setopt($curl, CURLOPT_USERAGENT,'Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.15');
curl_setopt($curl, CURLOPT_HTTPHEADER,array('User-Agent: Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.15','Referer: http://someaddress.tld','Content-Type: multipart/form-data'));
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // stop verifying certificate
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($curl, CURLOPT_POST, true); // enable posting
curl_setopt($curl, CURLOPT_POSTFIELDS, $audiodata); // post images 
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // if any redirection after upload
$r = curl_exec($curl); 
curl_close($curl);

$rjson = json_decode($r);

echo $r;


?>