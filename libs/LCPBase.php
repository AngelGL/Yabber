<?php
// The source code packaged with this file is Free Software, Copyright (C) 2011 by
// Ricardo Galli <gallir at gallir dot com>.
// It's licensed under the AFFERO GENERAL PUBLIC LICENSE unless stated otherwise.
// You can get copies of the licenses here:
// 		http://www.affero.org/oagpl.html
// AFFERO GENERAL PUBLIC LICENSE is also included in the file called "COPYING".


class LCPBase {

	// Static function tht allows to convert to html for anonymous strings
	public static function html($string, $fancy = true) {
		$o = new LCPBase();
		return $o->to_html($string, $fancy);
	}

	function to_html($string, $fancy = true) {
		global $globals;

		$string = nl2br($string, true);

		$regexp = '#[^\s\.\,\:\;\¡\!\)\-<>&\?]{1,42}';

		if ($fancy) {
			// Add smileys
			$regexp .= '|\{\S{3,14}\}';
		}

		if (is_a($this, 'Post')) { // references to @users
			$regexp .= '|@[\p{L}\.\_][\.\d\-_\p{L}]+(?:,\d+){0,1}';
		} elseif (is_a($this, 'Comment')) {
			$regexp .= '|@[\p{L}\.][\.\d\-_\p{L}]+\w';
		} elseif (is_a($this, 'Link')) {
			$regexp .= '|@[\p{L}\.][\.\d\-_\p{L}]+\w';
		}

		$regexp .= '|(https{0,1}:\/\/)([^\s<>]{5,500}[^\s<>,;:\.])';
		$regexp .= '|\|([\p{L}\d_]+)';
		$regexp = '/([\s\(\[{}¡;,:¿>\*]|^)('.$regexp.')/Smu';
		$callback = function ($matches) {
			global $globals;

			switch ($matches[2][0]) {
				case '#':
					if (preg_match('/^#\d+$/', $matches[2])) {
						$id = substr($matches[2], 1);
						if (is_a($this, 'Comment')) {
							if ($id > 0) {
								return $matches[1].'<a class="tooltip c:'.$this->link.'-'.$id.'" href="'.$this->link_permalink.'/c0'.$id.'#c-'.$id.'" rel="nofollow">#'.$id.'</a>';
							} else {
								return $matches[1].'<a class="tooltip l:'.$this->link.'" href="'.$this->link_permalink.'" rel="nofollow">#'.$id.'</a>';
							}
						} elseif (is_a($this, 'Link')) {
							return $matches[1].'<a class="tooltip c:'.$this->id.'-'.$id.'" href="'.$this->get_permalink().'/c0'.$id.'#c-'.$id.'" rel="nofollow">#'.$id.'</a>';
						}
					} else {
						switch (get_class($this)) {
							case 'Link':
								$w = 'links';
								break;
							case 'Comment':
								$w = 'comments';
								break;
							case 'Post':
								$w = 'posts';
								break;
						}
						return $matches[1].'<a href="'.$globals['base_url'].'search?w='.$w.'&amp;q=%23'.substr($matches[2], 1).'&amp;o=date">#'.substr($matches[2], 1).'</a>';
					}
					break;

				case '@':
					$ref = substr($matches[2], 1);
					if (is_a($this, 'Post')) {
						$a = explode(',', $ref);
						if (count($a) > 1) {
							$user = $a[0];
							$id = ','.$a[1];
						} else {
							$user = $ref;
							$id = '';
						}
						$user_url = urlencode($user);
						return $matches[1]."<a class='tooltip p:$user_url$id-$this->date' href='".$globals['base_url']."backend/get_post_url?id=$user_url$id;".$this->date."'>@$user</a>";
					} else {
						return $matches[1]."<a class='tooltip u:$ref' href='".get_user_uri($ref)."'>@$ref</a>";
					}
					break;

				case '{':
					$m = array($matches[2], substr($matches[2], 1, -1));
					return $matches[1].put_emojis_callback($m);
				case 'h':
					$suffix = '';
					if (substr($matches[4], -1) == ')' && strrchr($matches[4], '(') === false) {
						$matches[4] = substr($matches[4], 0, -1);
						$suffix = ')';
					}

					if (preg_match('/\:(x\-){0,1}(spoiler)$/', $matches[0] )) {
						return '<img class="post-img spoiler" src="'.substr($matches[0], 0, -8).'">'; 
					} else if (preg_match('/\.(x\-){0,1}(gif|GIF|jpeg.*?|jpg.*?|pjpeg|pjpg|png.*?|tif|tiff)$/', $matches[0] )) {
						return '<a href="'.$matches[0].'" target="_blank"><img class="post-img lazy" src="'.$matches[0].'"></a>';
					} else if(preg_match('/\.(x\-){0,1}(gifv|webm)$/', $matches[0] )) {
						return '<video autoplay controls loop class="post-img lazy">
						<source src="'.substr($matches[0],0,-4).'webm" type="video/webm">
						<source src="'.substr($matches[0],0,-4).'mp4" type="video/mp4">	
						</video>';
					} else if(preg_match('/\.(x\-){0,1}(mp4.*?)$/', $matches[0] )) {
						return '<video controls class="post-img lazy">
						<source src="'.$matches[0].'" type="video/mp4">	
						</video>';
					} 
					else if(preg_match('/(?:youtube\.com\/(?:embed\/|.*v=)|youtu\.be\/)([\w\-_]+).*?(#.+)*$/', $matches[0], $v)){
						//return '<div class="video"><div class="videoWrapper"><iframe class="lazy" width="560" height="315" src="//www.youtube.com/embed/'.$v[1].'" frameborder="0" allowfullscreen></iframe></div></div>';
						return '<div style="max-width: 640px; max-height:390px; margin: 0 auto"><div class="lazyYT" data-youtube-id="'.$v[1].'" data-ratio="16:9"></div></div>';
					}
					else if(preg_match('/((http:\/\/(clyp\.it\/?([A-Za-z0-9]+)))|(https:\/\/(clyp\.it\/?([A-Za-z0-9]+))))/i', $matches[0], $v)){
						return '<audio controls preload="none">
						  <source src="https://a.clyp.it/'.$v[7].'.ogg" type="audio/ogg">
						  <source src="http://a.clyp.it/'.$v[7].'.mp3" type="audio/mpeg">
						Your browser does not support the audio element.
						</audio>';
						// '/((http:\/\/(clyp\.it\/.*))|(https:\/\/(clyp\.it\/.*))/i'
						//return '<iframe width="100%" height="160" src="'.$matches[0].'/widget" frameborder="0"></iframe>';
					}
					else{
						return $matches[1].'<a href="'.$matches[3].$matches[4].'" title="'.$matches[4].'"target="_blank" rel="nofollow">'.substr($matches[4], 0, 70).'</a>'.$suffix;
					}

				case '|':
					return $matches[1].'<a href="'.$globals['base_url_general'].'p/'.$matches[5].'">|'.$matches[5].'</a>';
			}
			return $matches[1].$matches[2];
		};

		return preg_replace_callback($regexp, $callback, $string);
	}

	function truncate($length) {
		$this->is_truncated  = FALSE;
		if ($length > 0 && mb_strlen($this->content) > $length + $length/2) {
			$this->is_truncated = TRUE;
			$this->content = rtrim(preg_replace('/(?:[&<\{]\w{1,10}|[^}>\s]{1,15}|http\S+)$/u', '', mb_substr($this->content, 0 , $length)));
			$this->content .= '&hellip;';
			if (preg_match('/<\w+>/', $this->content)) {
				$this->content = close_tags($this->content);
			}
		}
	}

	function sanitize($string) {
		//$string = preg_replace('/&[^ ;]{1,8};/', ' ', $string);
		$string = html_entity_decode($string, ENT_COMPAT, 'UTF-8');
		$string = strip_tags($string);
		return $string;
	}

	function store_image_from_form($type, $field = 'image') {
		$media = new Upload($type, $this->id, 0);
		if ($type == 'private') {
			$media->to = $this->to;
			$media->access = 'private';
		}
		if ($media->from_form($field, 'image')) {
			$this->media_size = $media->size;
			$this->media_mime = $media->mime;
			$this->media_extension = $media->extension;
			return true;
		}
		return false;
	}

	function store_image($type, $file) {
		$media = new Upload($type, $this->id, 0);
		if ($type == 'private') {
			$media->to = $this->to;
			$media->access = 'private';
		}
		if ($media->from_temporal($file, 'image')) {
			$this->media_size = $media->size;
			$this->media_mime = $media->mime;
			$this->media_extension = $media->extension;
			return true;
		}
		return false;
	}

	function get_media($type) {
		$media = new Upload($type, $this->id);
		if ($media) {
			$media->read();
		}
		return $media;
	}

	function move_tmp_image($type, $file, $mime) {
		$media = new Upload($type, $this->id, 0);
		if ($type == 'private') {
			$media->to = $this->to;
			$media->access = 'private';
		}
		if ($media->from_tmp_upload($file, $mime)) {
			$this->media_size = $media->size;
			$this->media_mime = $media->mime;
			$this->media_extension = $media->extension;
			return true;
		}
		return false;
	}

	function delete_image($type) {
		$media = new Upload($type, $this->id, 0);
		$media->delete();
		$this->media_size = 0;
		$this->media_mime = '';
	}
}

