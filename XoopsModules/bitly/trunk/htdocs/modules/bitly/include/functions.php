<?php


if (!function_exists("bitly_object2array")) {
	function bitly_object2array($objects) {
		$ret = array();
		foreach((array)$objects as $key => $value) {
			if (is_object($value)) {
				$ret[$key] = bitly_object2array($value);
			} elseif (is_array($value)) {
				$ret[$key] = bitly_object2array($value);
			} else {
				$ret[$key] = $value;
			}
		}
		return $ret;
	}
}

if (!function_exists("bitly_shortenurl")) {
	function bitly_shortenurl($url) {
		$module_handler = xoops_gethandler('module');
		$config_handler = xoops_gethandler('config');
		$GLOBALS['bitlyModule'] = $module_handler->getByDirname('bitly');
		$GLOBALS['bitlyModuleConfig'] = $config_handler->getConfigList($GLOBALS['bitlyModule']->getVar('mid'));
	
		if (!empty($GLOBALS['bitlyModuleConfig']['bitly_username'])&&!empty($GLOBALS['bitlyModuleConfig']['bitly_apikey'])) {
			$source_url = $GLOBALS['bitlyModuleConfig']['bitly_apiurl'].'/shorten?login='.$GLOBALS['bitlyModuleConfig']['bitly_username'].'&apiKey='.$GLOBALS['bitlyModuleConfig']['bitly_apikey'].'&format=json&longUrl='.urlencode($url);
			$cookies = XOOPS_ROOT_PATH.'/uploads/bitly_'.md5($GLOBALS['bitlyModuleConfig']['bitly_apikey']).'.cookie';
			if (!$ch = curl_init($source_url)) {
				return $url;
			}
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_USERAGENT, $GLOBALS['bitlyModuleConfig']['user_agent']); 
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $GLOBALS['bitlyModuleConfig']['curl_connect_timeout']);
			curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['bitlyModuleConfig']['curl_timeout']);
			$data = curl_exec($ch); 
			curl_close($ch); 
			$result = bitly_object2array(json_decode($data));
			if ($result['status_code']=200) {
				if (!empty($result['data']['url']))
					return $result['data']['url'];
				else 
					return $url;
			}
			return $url;
		} else {
			return $url;
		}
	}
}

if (!function_exists("bitly_getandwrite")) {
	function bitly_getandwrite($url, $writepathfile) {
		$module_handler = xoops_gethandler('module');
		$config_handler = xoops_gethandler('config');
		if (!isset($GLOBALS['bitlyModule']))
			$GLOBALS['bitlyModule'] = $module_handler->getByDirname('bitly');
		if (!isset($GLOBALS['bitlyModuleConfig']))
			$GLOBALS['bitlyModuleConfig'] = $config_handler->getConfigList($GLOBALS['bitlyModule']->getVar('mid'));
	
		$cookies = XOOPS_ROOT_PATH.'/uploads/bitly_'.md5($url).'.cookie';
		if (!$ch = curl_init($url)) {
			return false;
		}
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_USERAGENT, $GLOBALS['bitlyModuleConfig']['user_agent']); 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $GLOBALS['bitlyModuleConfig']['curl_connect_timeout']);
		curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['bitlyModuleConfig']['curl_timeout']);
		$data = curl_exec($ch); 
		curl_close($ch); 
		
		// Clears Existing Data
		if (file_exists($writepathfile)) {
			unlink($writepathfile);
		}
		// Writes file
		$file = fopen($writepathfile, 'w+');
		fwrite($file, $data, strlen($data));
		fclose($file);
		
		return true;
	}
}

if (!function_exists("bitly_getIP")) {
	function bitly_getIP() {
	    $ip = $_SERVER['REMOTE_ADDR'];
	    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	        $ip = $_SERVER['HTTP_CLIENT_IP'];
	    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    }
	    return $ip;
	}
}

if (!function_exists("bitly_getIPData")) {
	function bitly_getIPData($ip=false){
		$ret = array();
		if (is_object($GLOBALS['xoopsUser'])) {
			$ret['uid'] = $GLOBALS['xoopsUser']->getVar('uid');
			$ret['uname'] = $GLOBALS['xoopsUser']->getVar('uname');
			$ret['email'] = $GLOBALS['xoopsUser']->getVar('email');
		} else {
			$ret['uid'] = 0;
			$ret['uname'] = (isset($_REQUEST['uname'])?$_REQUEST['uname']:'');
			$ret['email'] = (isset($_REQUEST['email'])?$_REQUEST['email']:'');
		}
		$ret['agent'] = $_SERVER['HTTP_USER_AGENT'];
		if (!$ip) {
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])||isset($_SERVER["HTTP_CLIENT_IP"])){ 
				$ip = (string)bitly_getIP(); 
				$ret['is_proxied'] = true;
				$proxy_ip = $_SERVER["REMOTE_ADDR"]; 
				$ret['network-addy'] = @gethostbyaddr($ip); 
				$ret['long'] = @ip2long($ip);
				if (is_ipv6($ip)) {
					$ret['ip6'] = $ip;
					$ret['proxy-ip6'] = $proxy_ip;
				} else {
					$ret['ip4'] = $ip;
					$ret['proxy-ip4'] = $proxy_ip;
				}
			}else{ 
				$ret['is_proxied'] = false;
				$ip = (string)bitly_getIP(); 
				$ret['network-addy'] = @gethostbyaddr($ip); 
				$ret['long'] = @ip2long($ip);
				if (is_ipv6($ip)) {
					$ret['ip6'] = $ip;
				} else {
					$ret['ip4'] = $ip;
				}
			} 
		} else {
			$ret['is_proxied'] = false;
			$ret['network-addy'] = @gethostbyaddr($ip); 
			$ret['long'] = @ip2long($ip);
			if (is_ipv6($ip)) {
				$ret['ip6'] = $ip;
			} else {
				$ret['ip4'] = $ip;
			}
		}
		$ret['made'] = time();				
		return $ret;
	}
}
if (!function_exists("is_ipv6")) {
	function is_ipv6($ip = "") 
	{ 
		if ($ip == "") 
			return false;
			
		if (substr_count($ip,":") > 0){ 
			return true; 
		} else { 
			return false; 
		} 
	} 
}
if (!function_exists("bitly_adminMenu")) {
  function bitly_adminMenu ($currentoption = 0, $page = '')  {
	   	echo "<table width=\"100%\" border='0'><tr><td>";
	   	echo "<tr><td>";
	   	$indexAdmin = new ModuleAdmin();
	   	echo $indexAdmin->addNavigation(strtolower(basename($_SERVER['REQUEST_URI'])));
  	   	echo "</td></tr>";
	   	echo "<tr'><td><div id='form'>";
  }
  
  function bitly_footer_adminMenu()
  {
		echo "</div></td></tr>";
  		echo "</table>";
		echo "<div align=\"center\"><a href=\"http://www.xoops.org\" target=\"_blank\"><img src=" . XOOPS_URL . '/' . $GLOBALS['bitlyModule']->getInfo('icons32') . '/xoopsmicrobutton.gif'.' '." alt='XOOPS' title='XOOPS'></a></div>";
		echo "<div class='center smallsmall italic pad5'><strong>" . $GLOBALS['bitlyModule']->getVar("name") . "</strong> is maintained by the <a class='tooltip' rel='external' href='http://www.xoops.org/' title='Visit XOOPS Community'>XOOPS Community</a> and <a class='tooltip' rel='external' href='http://www.chronolabs.coop/' title='Visit Chronolabs Co-op'>Chronolabs Co-op</a></div>";
  		
  }
}


if (!function_exists('bitly_ExtractTags')) {
	function bitly_ExtractTags($tweet='', $as_array = false, $seperator=', ') {
		$ret = array();
		foreach(explode(' ', $tweet) as $node) {
    		if (in_array(substr($node, 0, 1), array('@','#'))) {
    			$ret[ucfirst(substr($node, 1, strlen($node)-1))] = ucfirst(substr($node, 1, strlen($node)-1)); 
    		}
    	}
		if ($as_array==true)
			return $ret;
		else 
			return implode($seperator, $ret);
				    	
	}
}

if (!function_exists('bitly_getFilterElement')) {
	function bitly_getFilterElement($filter, $field, $sort='created', $op = '', $fct = '') {
		$components = bitly_getFilterURLComponents($filter, $field, $sort);
		include_once('formobjects.bitly.php');
		switch ($field) {
			case 'urlid':
				$ele = new TwitterBombFormSelectUrls('', 'filter_'.$field.'', $components['value']);
		    	$ele->setExtra('onchange="window.open(\''.$_SERVER['PHP_SELF'].'?'.$components['extra'].'&filter='.$components['filter'].(!empty($components['filter'])?'|':'').$field.',\'+this.options[this.selectedIndex].value'.(!empty($components['operator'])?'+\','.$components['operator'].'\'':'').',\'_self\')"');
		    	break;
			case 'rcid':
				$ele = new TwitterBombFormSelectCampaigns('', 'filter_'.$field.'', $components['value'], 1, false, true, 'bomb');
		    	$ele->setExtra('onchange="window.open(\''.$_SERVER['PHP_SELF'].'?'.$components['extra'].'&filter='.$components['filter'].(!empty($components['filter'])?'|':'').$field.',\'+this.options[this.selectedIndex].value'.(!empty($components['operator'])?'+\','.$components['operator'].'\'':'').',\'_self\')"');
		    	break;
			case 'cid':
				$ele = new TwitterBombFormSelectCampaigns('', 'filter_'.$field.'', $components['value']);
		    	$ele->setExtra('onchange="window.open(\''.$_SERVER['PHP_SELF'].'?'.$components['extra'].'&filter='.$components['filter'].(!empty($components['filter'])?'|':'').$field.',\'+this.options[this.selectedIndex].value'.(!empty($components['operator'])?'+\','.$components['operator'].'\'':'').',\'_self\')"');
		    	break;
		    case 'pcatdid':	
			case 'catid':
				$ele = new TwitterBombFormSelectCategories('', 'filter_'.$field.'', $components['value']);
		    	$ele->setExtra('onchange="window.open(\''.$_SERVER['PHP_SELF'].'?'.$components['extra'].'&filter='.$components['filter'].(!empty($components['filter'])?'|':'').$field.',\'+this.options[this.selectedIndex].value'.(!empty($components['operator'])?'+\','.$components['operator'].'\'':'').',\'_self\')"');
		    	break;
	    	case 'mode':
				$ele = new TwitterBombFormSelectMode('', 'filter_'.$field.'', $components['value']);
		    	$ele->setExtra('onchange="window.open(\''.$_SERVER['PHP_SELF'].'?'.$components['extra'].'&filter='.$components['filter'].(!empty($components['filter'])?'|':'').$field.',\'+this.options[this.selectedIndex].value'.(!empty($components['operator'])?'+\','.$components['operator'].'\'':'').',\'_self\')"');
		    	break;
	    	case 'provider':
		    case 'type':
				 if ($op=='retweet') {
					$ele = new TwitterBombFormSelectRetweetType('', 'filter_'.$field.'', $components['value'], 1, false, true);
			    	$ele->setExtra('onchange="window.open(\''.$_SERVER['PHP_SELF'].'?'.$components['extra'].'&filter='.$components['filter'].(!empty($components['filter'])?'|':'').$field.',\'+this.options[this.selectedIndex].value'.(!empty($components['operator'])?'+\','.$components['operator'].'\'':'').',\'_self\')"');
		    	} elseif ($op=='log') {
					$ele = new TwitterBombFormSelectLogType('', 'filter_'.$field.'', $components['value'], 1, false, true);
			    	$ele->setExtra('onchange="window.open(\''.$_SERVER['PHP_SELF'].'?'.$components['extra'].'&filter='.$components['filter'].(!empty($components['filter'])?'|':'').$field.',\'+this.options[this.selectedIndex].value'.(!empty($components['operator'])?'+\','.$components['operator'].'\'':'').',\'_self\')"');
		    	} else {
					$ele = new TwitterBombFormSelectType('', 'filter_'.$field.'', $components['value'], 1, false, true);
			    	$ele->setExtra('onchange="window.open(\''.$_SERVER['PHP_SELF'].'?'.$components['extra'].'&filter='.$components['filter'].(!empty($components['filter'])?'|':'').$field.',\'+this.options[this.selectedIndex].value'.(!empty($components['operator'])?'+\','.$components['operator'].'\'':'').',\'_self\')"');
		    	}
		    	break;
		    case 'measurement':
				$ele = new TwitterBombFormSelectMeasurement('', 'filter_'.$field.'', $components['value'], 1, false, true);
		    	$ele->setExtra('onchange="window.open(\''.$_SERVER['PHP_SELF'].'?'.$components['extra'].'&filter='.$components['filter'].(!empty($components['filter'])?'|':'').$field.',\'+this.options[this.selectedIndex].value'.(!empty($components['operator'])?'+\','.$components['operator'].'\'':'').',\'_self\')"');
		    	break;
		    case 'language':
				$ele = new TwitterBombFormSelectLanguage('', 'filter_'.$field.'', $components['value'], 1, false, true);
		    	$ele->setExtra('onchange="window.open(\''.$_SERVER['PHP_SELF'].'?'.$components['extra'].'&filter='.$components['filter'].(!empty($components['filter'])?'|':'').$field.',\'+this.options[this.selectedIndex].value'.(!empty($components['operator'])?'+\','.$components['operator'].'\'':'').',\'_self\')"');
		    	break;
		    case 'base':
		    case 'base1':
		    case 'base2':
			case 'base3':
			case 'base4':
			case 'base5':
			case 'base6':
			case 'base7':						    	
				$ele = new TwitterBombFormSelectBase('', 'filter_'.$field.'', $components['value']);
		    	$ele->setExtra('onchange="window.open(\''.$_SERVER['PHP_SELF'].'?'.$components['extra'].'&filter='.$components['filter'].(!empty($components['filter'])?'|':'').$field.',\'+this.options[this.selectedIndex].value'.(!empty($components['operator'])?'+\','.$components['operator'].'\'':'').',\'_self\')"');
		    	break;
		    case 'description':
		    case 'pre':
		    case 'alias':
		    case 'screen_name':
		    case 'source_nick':	
		    case 'keyword':
		    case 'tweet':
		    case 'name':
		    case 'search':
		    case 'skip':
		    case 'longitude':
			case 'latitude':
			case 'replies':
			case 'mentions':
			case 'user':
			case 'reply':
			case 'keywords':		    	
		    	$ele = new XoopsFormElementTray('');
				$ele->addElement(new XoopsFormText('', 'filter_'.$field.'', 11, 40, $components['value']));
				$button = new XoopsFormButton('', 'button_'.$field.'', '[+]');
		    	$button->setExtra('onclick="window.open(\''.$_SERVER['PHP_SELF'].'?'.$components['extra'].'&filter='.$components['filter'].(!empty($components['filter'])?'|':'').$field.',\'+$(\'#filter_'.$field.'\').val()'.(!empty($components['operator'])?'+\','.$components['operator'].'\'':'').',\'_self\')"');
		    	$ele->addElement($button);
		    	break;
		    case 'radius':
		    	$measurement = bitly_getFilterURLComponents($components['filter'], 'measurement', $sort);
				$ele = new XoopsFormElementTray('');
				$ele->addElement(new XoopsFormText('', 'filter_radius', 8, 40, $components['value']));
				$ele->addElement(new TwitterBombFormSelectMeasurement('', 'filter_measurement', $measurement['value']));
				$button = new XoopsFormButton('', 'button_'.$field.'', '[+]');
		    	$button->setExtra('onclick="window.open(\''.$_SERVER['PHP_SELF'].'?'.$measurement['extra'].'&filter='.$measurement['filter'].(!empty($measurement['filter'])?'|':'').'radius'.',\'+$(\'#filter_radius\').val()'.(!empty($components['operator'])?'+\','.$components['operator'].'\'':'').'+\'|'.'measurement'.',\'+$(\'#filter_measurement'.'\').val()'.(!empty($measurement['operator'])?'+\','.$measurement['operator'].'\'':'').',\'_self\')"');
		    	$ele->addElement($button);		    	
		}
		return isset($ele)?$ele:false;
	}
}

if (!function_exists('bitly_getFilterURLComponents')) {
	function bitly_getFilterURLComponents($filter, $field, $sort='created') {
		$parts = explode('|', $filter);
		$ret = array();
		$value = '';
    	foreach($parts as $part) {
    		$var = explode(',', $part);
    		if (count($var)>1) {
	    		if ($var[0]==$field) {
	    			$ele_value = $var[1];
	    			if (isset($var[2]))
	    				$operator = $var[2];
	    		} elseif ($var[0]!=1) {
	    			$ret[] = implode(',', $var);
	    		}
    		}
    	}
    	$pagenav = array();
    	$pagenav['op'] = isset($_REQUEST['op'])?$_REQUEST['op']:"campaign";
		$pagenav['fct'] = isset($_REQUEST['fct'])?$_REQUEST['fct']:"list";
		$pagenav['limit'] = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
		$pagenav['start'] = 0;
		$pagenav['order'] = !empty($_REQUEST['order'])?$_REQUEST['order']:'DESC';
		$pagenav['sort'] = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':$sort;
    	$retb = array();
		foreach($pagenav as $key=>$value) {
			$retb[] = "$key=$value";
		}
		return array('value'=>(isset($ele_value)?$ele_value:''), 'field'=>(isset($field)?$field:''), 'operator'=>(isset($operator)?$operator:''), 'filter'=>implode('|', (isset($ret)&&is_array($ret)?$ret:array())), 'extra'=>implode('&', (isset($retb)&&is_array($retb)?$retb:array())));
	}
}
?>