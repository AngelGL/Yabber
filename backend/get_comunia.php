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

	$id=$_GET['id'];;
	$json = file_get_contents('http://app.comunia.me/api/v1/user/'.$id.'?fields=*,lineup(type,playersID),players');
	$obj = json_decode($json);

	$lineup_type = $obj->data->lineup->type;
	$lineup_players = $obj->data->lineup->playersID;
	//var_dump($lineup_players);
	$players = $obj->data->players;
	$lineas = explode('-', $lineup_type);
	//var_dump($lineas);

	

	function findPlayer($id, $players){
		$arrIt = new RecursiveIteratorIterator(new RecursiveArrayIterator($players));
		foreach ($arrIt as $sub) {
		    $subArray = $arrIt->getSubIterator();
		    if ($subArray['id'] === $id) {
		        $outputArray[] = iterator_to_array($subArray);
		        //var_dump($outputArray);
		        return $outputArray;

		    }
		}
	}


		echo '<div class="field football" style="background-image: url(http://i.imgur.com/ZPqmFUL.png)">';
		
		$counter = 10;
		for($i=count($lineas)-1; $i>=0;$i--){
			echo '<div>';
			for($j=0; $j<$lineas[$i];$j++){
			$tmpPlayer= findPlayer($lineup_players[$counter], $players);
			echo '<a class="player">';
			echo '<img class="player-icon" src='.$tmpPlayer[0]['icon'].'>';
			echo '<div class="info">'.$tmpPlayer[0]['name'].'<span class="points bg points-best">'.$tmpPlayer[0]['points'].'</span></div>';
			echo '</a>';
			$counter--;
			}
			echo '</div>'; // fin linea alineacion
		}
		
		$tmpPlayer= findPlayer($lineup_players[0], $players);
		print_r($tmpPlayer[0]->name);
		echo '<div>';
		echo '<a class="player">';
		echo '<img class="player-icon" src='.$tmpPlayer[0]['icon'].'>';
		echo '<div class="info">'.$tmpPlayer[0]['name'].'<span class="points bg points-best">'.$tmpPlayer[0]['points'].'</span></div>';
		echo '</a>';
		echo '</div>'; // fin linea alineacion



		echo '</div>'; // fin campo

