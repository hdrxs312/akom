<?php
	$GLOBALS['prefix'] = 'akom/';
	include 'Parsedown.php';

	function home() {
		return 'http://localhost/akom/';
	}
	function api() {
		return home().'api/public/';
	}
	
	function e($str) {
		echo htmlentities($str);
	}

	function http($path, $data = [])
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, home().'api/public/'.$path);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);

		$header = [
			'x-data: '. ( isset($_COOKIE['x-data']) ? $_COOKIE['x-data'] : base64_encode('{}') ),
			'referer: '.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
		];
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 

		if( count($data) > 0) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
		}

		$send = curl_exec($ch);
		curl_close($ch); 
		$response = json_decode($send,true);

		$GLOBALS['_community'] = isset($response['com']) ? $response['com'] : [];
		$GLOBALS['_user'] = isset($response['user']) ? $response['user'] : [];		
		if(isset($response['menu'])) {
			$GLOBALS['_menu'] = $response['menu'];
			setcookie('menu', json_encode($response['menu']), time()+86400*30, '/');
		} else {
			$GLOBALS['_menu'] = $_COOKIE['menu'];
		}
		$x_data = isset($response['x-data']) ? $response['x-data'] : base64_encode('{}');
		setcookie('x-data', $x_data, time()+86400*30, '/');
		
		return $response['result'];
	}

	function get_menu($name, $field)
	{
		$result = '-';
		$arr = isset($_COOKIE['menu']) ? json_decode($_COOKIE['menu'],true) : $GLOBALS['_menu'];
		if( count($arr) > 0)
		{
			foreach($arr as $key => $menu)
			{
				if($menu['mn_name'] == $name) {
					$result = $menu['mn_'.$field];
				}
			}
		}
		return $result;
	}

	function pagination($item_count, $limit, $cur_page, $link)
	{
		$page_count = ceil($item_count/$limit);
		$current_range = array(($cur_page-2 < 1 ? 1 : $cur_page-2), ($cur_page+2 > $page_count ? $page_count : $cur_page+2));

		# First and Last pages
		$first_page = $cur_page > 3 ? '<li class="page-item"><a class="page-link" href="'.sprintf($link, '1').'">1</a></li>'.($cur_page < 5 ? '' : '') : null;
		$last_page = $cur_page < $page_count-2 ? ($cur_page > $page_count-4 ? '' : '').'<li class="page-item"><a class="page-link" href="'.sprintf($link, $page_count).'">'.$page_count.'</a></li>' : null;

		# Previous and next page
		$previous_page = $cur_page > 1 ? '<li class="page-item"><a class="page-link" href="'.sprintf($link, ($cur_page-1)).'">Previous</a></li> ' : null;
		$next_page = $cur_page < $page_count ? '<li class="page-item"><a class="page-link" href="'.sprintf($link, ($cur_page+1)).'">Next</a></li>' : null;

		# Display pages that are in range
		for ($x=$current_range[0];$x <= $current_range[1]; ++$x)
			$pages[] = '
				<li class="page-item '.($x == $cur_page ? 'active':'').'">
					<a class="page-link " href="'.sprintf($link, $x).'">'.$x.'</a>
				</li>';

		if ($page_count > 1)

			return '
				<nav aria-label="Page navigation example">
				  <ul class="pagination pagination-sm">'.$previous_page.$first_page.implode(' ', $pages).$last_page.$next_page.'</ul>
				</nav>
			';
	}
	
	function avatar($co_id = 0, $mb_id = 0) {
		echo home().'api/storage/app/member_profile/co_'.$co_id.'/mb_'.$mb_id.'.png';
	}

	function files($co_id = 0, $name = '') {
		echo home().'api/storage/app/uploads/co_'.$co_id.'/'.$name;
	}

	function parsedown($text, $h=FALSE) {
		$Parsedown = new Parsedown();
		$Parsedown->setBreaksEnabled(true);
		$Parsedown->setSafeMode(true);
		$text = $Parsedown->text($text);
		$white_list = '<hr><p><strong><ul><li><a><em><del><code><ol><pre>';
		if($h) $white_list .= '<h2>';
		echo strip_tags_content($text,$white_list);
	}

	function parseup($text) {
		$Parsedown = new Parsedown();
		$Parsedown->setBreaksEnabled(true);
		$text = $Parsedown->text($text);
		echo strip_tags($text);
	}

	function strip_tags_content($text, $tags = '', $invert = FALSE) {

	  preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
	  $tags = array_unique($tags[1]);
	  
	  if(is_array($tags) AND count($tags) > 0) {
	    if($invert == FALSE) {
	      return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
	    }
	    else {
	      return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text);
	    }
	  }
	  elseif($invert == FALSE) {
	    return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
	  }
	  return $text;
	} 

	function format_date($str) {
		$time = strtotime($str) + 7*3600;
		return date('d M Y', $time);
	}

	function format_datetime($str) {
		$time = strtotime($str) + 7*3600;
		return date('d M Y - H:i', $time);
	}

	function get_prefix($str, $get) {
		$result = '';
		$arr_module = ['discuss', 'buynsell', 'event', 'article'];
		$arr_get = [
			'pfx' => ['dc_', 'bs_', 'ev_', 'ar_'],
			'pfc' => ['dcc_', 'bsc_', 'evc_', 'arc_'],
		];
		for ($i = 0; $i < count($arr_module); $i++) {
			if($arr_module[$i] == $str) {
				$result = $arr_get[$get][$i];
			}
		}
		return $result;
	}


?>