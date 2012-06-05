<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

include_once (dirname(dirname(__FILE__)).'/include/functions.php');

/**
 * Class for Blue Room Xcenter
 * @author Simon Roberts <simon@xoops.org>
 * @copyright copyright (c) 2009-2003 XOOPS.org
 * @package kernel
 */
class BitlyOauth extends XoopsObject
{

	/**
	 * The corresponding bit.ly users API key
	 */
	var $api_key = '';
	
	/**
	 * The OAuth access token for specified user.
	 */
	var $access_token = '';
	 
	/**
	 * The corresponding bit.ly users username.
	 */
	var $username = '';
	
	/**
	 * An indicator of weather or not the login and password combination is valid.
	 */
	var $sucessful = false;
	
	/**
	 * The URI of the standard bitly v3 API.
	 */
	const bitly_api = 'http://api.bit.ly/v3/';
	
	/**
	 * The URI of the bitly OAuth endpoints.
	 */
	const bitly_oauth_api = 'https://api-ssl.bit.ly/v3/';

	/**
	 * The URI for OAuth access token requests.
	 */
	const bitly_oauth_access_token = 'https://api-ssl.bit.ly/oauth/';
	
    function  __construct($id = null)
    {
   	
        $this->initVar('oauth_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('mode', XOBJ_DTYPE_ENUM, 'valid', false, false, false, false, array('valid','invalid'));
		$this->initVar('bitly_key', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('bitly_username', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('bitly_clientid', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('bitly_secret', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('bitly_access_token', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('ip', XOBJ_DTYPE_TXTBOX, null, false, 64);
        $this->initVar('netbios', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('uid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('root', XOBJ_DTYPE_INT, null, false);
        $this->initVar('calls', XOBJ_DTYPE_INT, null, false);
		$this->initVar('created', XOBJ_DTYPE_INT, null, false); // Removed Unicode in 2.10
		$this->initVar('updated', XOBJ_DTYPE_INT, null, false); // Removed Unicode in 2.10
		
		if ($id>0) {
			$handler = new BitlyOauthHandler($GLOBALS['xoopsDB']);
			$object = $handler->get($id);
			if (is_object($object)) {
				if (is_a($object, 'BitlyOauth')) {
					$this->assignVars($object->getValues());
				}
			}
			unset($object);
		}
    }

    function syncronise() {
    	if ($this->successful==true)
    		$this->setVar('mode', 'valid');
    	else 
    		$this->setVar('mode', 'invalid');
    	$this->setVar('bitly_key', $this->api_key);
    	$this->setVar('bitly_username', $this->username);
    	$this->setVar('bitly_access_token', $this->access_token);
    	$this->setVar('bitly_clientid', $GLOBALS['bitlyModuleConfig']['client_id']);
    	$this->setVar('bitly_secret', $GLOBALS['bitlyModuleConfig']['client_secret']);
    	$this->setVar('ip', bitly_getIP());
    	$this->setVar('netbios', @gethostbyaddr(bitly_getIP()));
    	if (is_object($GLOBALS['xoopsUser']))
    		$this->setVar('uid', $GLOBALS['xoopsUser']->getVar('uid'));
    	else 
    		$this->setVar('uid', 0);
    }
    
    function  assignVar($field, $value) {
    	if ($field == 'bitly_access_token')
    		$this->access_token = $value;
    	elseif ($field == 'bitly_username')
    		$this->username = $value;
    	elseif ($field == 'bitly_key')
    		$this->api_key = $value;
    	parent::assignVar($field, $value);
	}
	
    function  setVar($field, $value) {
    	if ($field == 'bitly_access_token')
    		$this->access_token = $value;
    	elseif ($field == 'bitly_username')
    		$this->username = $value;
    	elseif ($field == 'bitly_key')
    		$this->api_key = $value;
    	
    	if (isset($this->vars[$field]))
		  	switch ($this->vars[$field]['data_type']) {
		  		case XOBJ_DTYPE_ARRAY:
		  			if (md5(serialize($value))!=md5(serialize($this->getVar($field))))
		  				parent::setVar($field, $value);
		  			break;
		  		default:
		  			if (is_array($value))
			  			if (md5(serialize($value))!=md5(serialize($this->getVar($field))))
			  				parent::setVar($field, $value);
			  		elseif (md5($value)!=md5($this->getVar($field)))
		  				parent::setVar($field, $value);
		  			break;
		  	}
    }
            
    function  setVars($arr, $not_gpc=false) {
    	foreach($arr as $field => $value) {
    		if (isset($this->vars[$field]))
		  		switch ($this->vars[$field]['data_type']) {
		  			case XOBJ_DTYPE_ARRAY:
		  				if (md5(serialize($value))!=md5(serialize($this->getVar($field))))
		  					parent::setVar($field, $value);
		  				break;
		  			default:
			  			if (is_array($value))
				  			if (md5(serialize($value))!=md5(serialize($this->getVar($field))))
				  				parent::setVar($field, $value);
				  		elseif (md5($value)!=md5($this->getVar($field)))
			  				parent::setVar($field, $value);
		  				break;
		  		}
    	}	
    }   
       
    function  getName() {
    	return $this->getVar('mode').', '.$this->getVar('ip').', '.$this->getVar('uid');
    }
    
    function  getForm($as_array=false, $title='') {
    	$class = explode('.',basename(__FILE__));
		unset($class[sizeof($class)-1]);
		$class = implode('.',$class);
		// Gets Title
		xoops_loadLanguage('forms', 'Bitly');
		if (empty($title)) {
    		if ($this->isNew()) {
    			if (defined("FRM_LINKEDIN_TITLE_NEW_".strtoupper($class)))
    				$title = constant("FRM_LINKEDIN_TITLE_NEW_".strtoupper($class));
    		} else {
    			if (defined("FRM_LINKEDIN_TITLE_EDIT_".strtoupper($class)))
    				$title = sprintf(constant("FRM_LINKEDIN_TITLE_EDIT_".strtoupper($class)), $this->getName());
    		}
    	}
    	// Gets Form
		$func = 'linkedin_form_item_'.$class;
		if (function_exists($func)) {
			return $func($this, $title, $as_array);
		}
    }
    
    function toArray() {
    	$ret = array();
    	foreach(parent::toArray() as $field => $value) {
    		$ret[str_replace('-', '_', $field)] = $value;
    	}
    	
    	if (isset($ret['created'])&&$ret['created']>0) {
    		$ret['created'] = date(_DATESTRING, $ret['created']);
    	}
    	if (isset($ret['updated'])&&$ret['updated']>0) {
    		$ret['updated'] = date(_DATESTRING, $ret['updated']);
    	}
    	if (isset($ret['emailed'])&&$ret['emailed']>0) {
    		$ret['emailed'] = date(_DATESTRING, $ret['emailed']);
    	}
    	if (is_array($form = $this->getForm(true, ''))) {
    		foreach($form as $field => $element) {
    			$ret['form'][$field] = $form[$field]->render();
    		}
    	} 
    	return $ret;
    }
    
	/**
	 * Given a longUrl, get the bit.ly shortened version.
	 *
	 * Example usage:
	 * @code
	 *   $results = bitly_v3_shorten('http://knowabout.it', 'j.mp');
	 * @endcode
	 *
	 * @param $longUrl
	 *   Long URL to be shortened.
	 * @param $domain
	 *   Uses bit.ly (default), j.mp, or a bit.ly pro domain.
	 *
	 * @return
	 *   An associative array containing:
	 *   - url: The unique shortened link that should be used, this is a unique
	 *     value for the given bit.ly account.
	 *   - hash: A bit.ly identifier for long_url which is unique to the given
	 *     account.
	 *   - global_hash: A bit.ly identifier for long_url which can be used to track
	 *     aggregate stats across all matching bit.ly links.
	 *   - long_url: An echo back of the longUrl request parameter.
	 *   - new_hash: Will be set to 1 if this is the first time this long_url was
	 *     shortened by this user. It will also then be added to the user history.
	 *
	 * @see http://code.google.com/p/bitly-api/wiki/ApiDocumentation#/v3/shorten
	 */
	function shorten($longUrl, $domain = '') {
		$result = array();
		$url = $this->bitly_api . "shorten?login=" . $this->getVar('bitly_username') . "&apiKey=" . $this->getVar('bitly_key') . "&format=json&longUrl=" . urlencode($longUrl);
		if ($domain != '') {
		  $url .= "&domain=" . $domain;
		}
		$output = json_decode($this->bitly_get_curl($url));
		if (isset($output->{'data'}->{'hash'})) {
		  $result['url'] = $output->{'data'}->{'url'};
		  $result['hash'] = $output->{'data'}->{'hash'};
		  $result['global_hash'] = $output->{'data'}->{'global_hash'};
		  $result['long_url'] = $output->{'data'}->{'long_url'};
		  $result['new_hash'] = $output->{'data'}->{'new_hash'};
		}
		return $result;
	}
	
	/**
	 * Expand a bit.ly url or hash.
	 *
	 * @param $data
	 *   Either a full bit.ly short url or a bit.ly hash to be expanded.
	 *
	 * @return
	 *   An associative array containing:
	 *   - hash: A bit.ly identifier for long_url which is unique to the given
	 *     account.
	 *   - long_url: The URL that the requested short_url or hash points to.
	 *   - user_hash: The corresponding bit.ly user identifier.
	 *   - global_hash: A bit.ly identifier for long_url which can be used to track
	 *     aggregate stats across all matching bit.ly links.
	 *
	 * @see http://code.google.com/p/bitly-api/wiki/ApiDocumentation#/v3/expand
	 */
	function expand($data) {
		$results = array();
		if (is_array($data)) {
		  // we need to flatten this into one proper command
		  $recs = array();
		  foreach ($data as $rec) {
		    $tmp = explode('/', $rec);
		    $tmp = array_reverse($tmp);
		    array_push($recs, $tmp[0]);
		  }
		  $data = implode('&hash=', $recs);
		} else {
		  $tmp = explode('/', $data);
		  $tmp = array_reverse($tmp);
		  $data = $tmp[0];
		}
		// make the call to expand
		$url = $this->bitly_api . "expand?login=" . $this->getVar('bitly_username') . "&apiKey=" . $this->getVar('bitly_key') . "&format=json&hash=" . $data;
		$output = json_decode($this->bitly_get_curl($url));
		if (isset($output->{'data'}->{'expand'})) {
		  foreach ($output->{'data'}->{'expand'} as $tmp) {
		    $rec = array();
		    $rec['hash'] = $tmp->{'hash'};
		    $rec['long_url'] = $tmp->{'long_url'};
		    $rec['user_hash'] = $tmp->{'user_hash'};
		    $rec['global_hash'] = $tmp->{'global_hash'};
		    array_push($results, $rec);
		  }
		}
		return $results;
	}
	
	/**
	 * Validate that a bit.ly login/apiKey combination is valid.
	 *
	 * @param $x_login
	 *   The end users user's bit.ly login (for validation).
	 * @param $x_apiKey
	 *   The end users bit.ly apiKey (for validation).
	 *
	 * @return
	 *   TRUE if the combination is valid.
	 *
	 * @see http://code.google.com/p/bitly-api/wiki/ApiDocumentation#/v3/validate
	 */
	function validate() {
		$result = 0;
		$url = $this->bitly_api . "validate?login=" . $this->getVar('bitly_username') . "&apiKey=" . $this->getVar('bitly_key') . "&format=json&x_login=" . $x_login . "&x_apiKey=" . $x_apiKey;
		$output = json_decode($this->bitly_get_curl($url));
		if (isset($output->{'data'}->{'valid'})) {
		  $result = $output->{'data'}->{'valid'};
		}
		return (bool) $result;
	}
	
	/**
	 * For one or more bit.ly URL's or hashes, returns statistics about the clicks
	 * on that link.
	 *
	 * @param $data
	 *   Can be a bit.ly shortened URL, a bit.ly hash, or an array of bit.ly URLs
	 *   and/or hashes.
	 *
	 * @return
	 *   A multidimensional numbered associative array containing:
	 *   - short_url: The unique bit.ly hash.
	 *   - global_hash: A bit.ly identifier for long_url which can be used to track
	 *     aggregate stats across all matching bit.ly links.
	 *   - user_clicks: The total count of clicks to this user's bit.ly link.
	 *   - user_hash: The corresponding bit.ly user identifier.
	 *   - global_clicks: The total count of clicks to all bit.ly links that point
	 *     to the same same long url.
	 *
	 * @see http://code.google.com/p/bitly-api/wiki/ApiDocumentation#/v3/clicks
	 */
	function clicks($data) {
		$results = array();
		if (is_array($data)) {
		  // we need to flatten this into one proper command
		  $recs = array();
		  foreach ($data as $rec) {
		    $tmp = explode('/', $rec);
		    $tmp = array_reverse($tmp);
		    array_push($recs, $tmp[0]);
		  }
		  $data = implode('&hash=', $recs);
		} else {
		  $tmp = explode('/', $data);
		  $tmp = array_reverse($tmp);
		  $data = $tmp[0];
		}
		$url = $this->bitly_api . "clicks?login=" . $this->getVar('bitly_username') . "&apiKey=" . $this->getVar('bitly_key') . "&format=json&hash=" . $data;
		$output = json_decode($this->bitly_get_curl($url));
		if (isset($output->{'data'}->{'clicks'})) {
		  foreach ($output->{'data'}->{'clicks'} as $tmp) {
		    $rec = array();
		    $rec['short_url'] = $tmp->{'short_url'};
		    $rec['global_hash'] = $tmp->{'global_hash'};
		    $rec['user_clicks'] = $tmp->{'user_clicks'};
		    $rec['user_hash'] = $tmp->{'user_hash'};
		    $rec['global_clicks'] = $tmp->{'global_clicks'};
		    array_push($results, $rec);
		  }
		}
		return $results;
	}
	
	/**
	 * Provides a list of referring sites for a specified bit.ly short link or hash,
	 * and the number of clicks per referrer.
	 *
	 * @param $data
	 *   A bit.ly shortened URL or bit.ly hash.
	 *
	 * @return
	 *   An associative array containing:
	 *   - created_by: The service that created the link.
	 *   - global_hash: A bit.ly identifier for long_url which can be used to track
	 *     aggregate stats across all matching bit.ly links.
	 *   - short_url: The unique bit.ly hash.
	 *   - user_hash: The corresponding bit.ly user identifier.
	 *   - referrers: A multidimensional numbered associative array containing:
	 *     - clicks: Number of clicks from this referrer.
	 *     - referrer: (optional) Referring site.
	 *     - referrer_app: (optional) Referring application (e.g.: Tweetdeck).
	 *     - url: (optional) URL of referring application
	 *
	 * @see http://code.google.com/p/bitly-api/wiki/ApiDocumentation#/v3/referrers
	 */
	function referrers($data) {
		$results = array();
		$tmp = explode('/', $data);
		$tmp = array_reverse($tmp);
		$data = $tmp[0];
		$url = $this->bitly_api . "referrers?login=" . $this->getVar('bitly_username') . "&apiKey=" . $this->getVar('bitly_key') . "&format=json&hash=" . $data;
		$output = json_decode($this->bitly_get_curl($url));
		if (isset($output->{'data'}->{'referrers'})) {
		  $results['created_by'] = $output->{'data'}->{'created_by'};
		  $results['global_hash'] = $output->{'data'}->{'global_hash'};
		  $results['short_url'] = $output->{'data'}->{'short_url'};
		  $results['user_hash'] = $output->{'data'}->{'user_hash'};
		  $results['referrers'] = array();
		  foreach ($output->{'data'}->{'referrers'} as $tmp) {
		    $rec = array();
		    $rec['clicks'] = $tmp->{'clicks'};
		    $rec['referrer'] = $tmp->{'referrer'};
		    $rec['referrer_app'] = $tmp->{'referrer_app'};
		    $rec['url'] = $tmp->{'url'};
		    array_push($results['referrers'], $rec);
		  }
		}
		return $results;
	}
	
	/**
	 * Provides a list of countries from which clicks on a specified bit.ly short
	 * link or hash have originated, and the number of clicks per country.
	 *
	 * @param $data
	 *   A bit.ly shortened URL or bit.ly hash.
	 *
	 * @return
	 *   An associative array containing:
	 *   - created_by: The service that created the link.
	 *   - global_hash: A bit.ly identifier for long_url which can be used to track
	 *     aggregate stats across all matching bit.ly links.
	 *   - short_url: The unique bit.ly hash.
	 *   - user_hash: The corresponding bit.ly user identifier.
	 *   - countries: A multidimensional numbered associative array containing:
	 *     - clicks: Number of clicks from this country.
	 *     - country: The country code these clicks originated from or null when
	 *       displaying clicks that could not be mapped to a specific country.
	 *
	 * @see http://code.google.com/p/bitly-api/wiki/ApiDocumentation#/v3/countries
	 */
	function countries($data) {
		$results = array();
		$tmp = explode('/', $data);
		$tmp = array_reverse($tmp);
		$data = $tmp[0];
		$url = $this->bitly_api . "countries?login=" . $this->getVar('bitly_username') . "&apiKey=" . $this->getVar('bitly_key') . "&format=json&hash=" . $data;
		$output = json_decode($this->bitly_get_curl($url));
		if (isset($output->{'data'}->{'countries'})) {
		  $results['created_by'] = $output->{'data'}->{'created_by'};
		  $results['global_hash'] = $output->{'data'}->{'global_hash'};
		  $results['short_url'] = $output->{'data'}->{'short_url'};
		  $results['user_hash'] = $output->{'data'}->{'user_hash'};
		  $results['countries'] = array();
		  foreach ($output->{'data'}->{'countries'} as $tmp) {
		    $rec = array();
		    $rec['clicks'] = $tmp->{'clicks'};
		    $rec['country'] = $tmp->{'country'};
		    array_push($results['countries'], $rec);
		  }
		}
		return $results;
	}
	
	/**
	 * For one or more bit.ly short urls or hashes, provides time series clicks per
	 * minute for the last hour in reverse chronological order (most recent to least
	 * recent).
	 *
	 * @param $data
	 *   Can be a bit.ly shortened URL, a bit.ly hash, or an array of bit.ly URLs
	 *   and/or hashes.
	 *
	 * @return
	 *   A multidimensional numbered associative array containing:
	 *   - clicks: An array with sixty entires, each for the number of clicks
	 *     received for the given link that minute.
	 *   - global_hash: A bit.ly identifier for long_url which can be used to track
	 *     aggregate stats across all matching bit.ly links.
	 *   - short_url: The unique bit.ly hash.
	 *   - user_hash: The corresponding bit.ly user identifier.
	 *
	 * @see http://code.google.com/p/bitly-api/wiki/ApiDocumentation#/v3/clicks_by_minute
	 */
	function clicks_by_minute($data) {
		$results = array();
		if (is_array($data)) {
		  // we need to flatten this into one proper command
		  $recs = array();
		  foreach ($data as $rec) {
		    $tmp = explode('/', $rec);
		    $tmp = array_reverse($tmp);
		    array_push($recs, $tmp[0]);
		  }
		  $data = implode('&hash=', $recs);
		} else {
		  $tmp = explode('/', $data);
		  $tmp = array_reverse($tmp);
		  $data = $tmp[0];
		}
		$url = $this->bitly_api . "clicks_by_minute?login=" . $this->getVar('bitly_username') . "&apiKey=" . $this->getVar('bitly_key') . "&format=json&hash=" . $data;
		$output = json_decode($this->bitly_get_curl($url));
		if (isset($output->{'data'}->{'clicks_by_minute'})) {
		  foreach ($output->{'data'}->{'clicks_by_minute'} as $tmp) {
		    $rec = array();
		    $rec['clicks'] = $tmp->{'clicks'};
		    $rec['global_hash'] = $tmp->{'global_hash'};
		    $rec['short_url'] = $tmp->{'short_url'};
		    $rec['user_hash'] = $tmp->{'user_hash'};
		    array_push($results, $rec);
		  }
		}
		return $results;
	}
	
	/**
	 * For one or more bit.ly short urls or hashes, provides time series clicks per
	 * day for the last 30 days in reverse chronological order (most recent to least
	 * recent).
	 *
	 * @param $data
	 *   Can be a bit.ly shortened URL, a bit.ly hash, or an array of bit.ly URLs
	 *   and/or hashes.
	 *
	 * @return
	 *   A multidimensional numbered associative array containing:
	 *   - global_hash: A bit.ly identifier for long_url which can be used to track
	 *     aggregate stats across all matching bit.ly links.
	 *   - short_url: The unique bit.ly hash.
	 *   - user_hash: The corresponding bit.ly user identifier.
	 *   - clicks: A multidimensional numbered associative array containing:
	 *     - clicks: The number of clicks received for a given link that day.
	 *     - day_start: A time code representing the start of the day for which
	 *       click data is provided.
	 *
	 * @see http://code.google.com/p/bitly-api/wiki/ApiDocumentation#/v3/clicks_by_day
	 */
	function clicks_by_day($data, $days = 7) {
		$results = array();
		if (is_array($data)) {
		  // we need to flatten this into one proper command
		  $recs = array();
		  foreach ($data as $rec) {
		    $tmp = explode('/', $rec);
		    $tmp = array_reverse($tmp);
		    array_push($recs, $tmp[0]);
		  }
		  $data = implode('&hash=', $recs);
		} else {
		  $tmp = explode('/', $data);
		  $tmp = array_reverse($tmp);
		  $data = $tmp[0];
		}
		$url = $this->bitly_api . "clicks_by_day?login=" . $this->getVar('bitly_username') . "&apiKey=" . $this->getVar('bitly_key') . "&format=json&days=" . $days . "&hash=" . $data;
		$output = json_decode($this->bitly_get_curl($url));
		if (isset($output->{'data'}->{'clicks_by_day'})) {
		  foreach ($output->{'data'}->{'clicks_by_day'} as $tmp) {
		    $rec = array();
		    $rec['global_hash'] = $tmp->{'global_hash'};
		    $rec['short_url'] = $tmp->{'short_url'};
		    $rec['user_hash'] = $tmp->{'user_hash'};
		    $rec['clicks'] = array();
		    $clicks = $tmp->{'clicks'};
		    foreach ($clicks as $click) {
		      $clickrec = array();
		      $clickrec['clicks'] = $click->{'clicks'};
		      $clickrec['day_start'] = $click->{'day_start'};
		      array_push($rec['clicks'], $clickrec);
		    }
		    array_push($results, $rec);
		  }
		}
		return $results;
	}
	
	/**
	 * This is used to query whether a given short domain is assigned for bitly.Pro,
	 * and is consequently a valid shortUrl parameter for other API calls.
	 *
	 * @param $domain
	 *   The short domain to check.
	 *
	 * @return
	 *   An associative array containing:
	 *   - domain: An echo back of the request parameter.
	 *   - bitly_pro_domain: 0 or 1 designating whether this is a current bitly.Pro
	 *     domain.
	 *
	 * @see http://code.google.com/p/bitly-api/wiki/ApiDocumentation#/v3/bitly_pro_domain
	 */
	function bitly_pro_domain($domain) {
		$result = array();
		$url = $this->bitly_api . "bitly_pro_domain?login=" . $this->getVar('bitly_username') . "&apiKey=" . $this->getVar('bitly_key') . "&format=json&domain=" . $domain;
		$output = json_decode($this->bitly_get_curl($url));
		if (isset($output->{'data'}->{'bitly_pro_domain'})) {
		  $result['domain'] = $output->{'data'}->{'domain'};
		  $result['bitly_pro_domain'] = $output->{'data'}->{'bitly_pro_domain'};
		}
		return $result;
	}
	
	/**
	 * This is used to query for a bit.ly link based on a long URL.
	 *
	 * @param $data
	 *   One or more long URLs to lookup.
	 *
	 * @return
	 *   An associative array containing:
	 *   - global_hash: A bit.ly identifier for long_url which can be used to track
	 *     aggregate stats across all matching bit.ly links.
	 *   - short_url: The unique shortened link that should be used, this is a
	 *     unique value for the given bit.ly account.
	 *   - url: An echo back of the url parameter.
	 *
	 * @see http://code.google.com/p/bitly-api/wiki/ApiDocumentation#/v3/lookup
	 */
	function lookup($data) {
		$results = array();
		if (is_array($data)) {
		  // we need to flatten this into one proper command
		  $recs = array();
		  foreach ($data as $rec) {
		    array_push($recs, urlencode($rec));
		  }
		  $data = implode('&url=', $recs);
		} else {
		  $data = urlencode($data);
		}
		$url = $this->bitly_api . "lookup?login=" . $this->getVar('bitly_username') . "&apiKey=" . $this->getVar('bitly_key') . "&format=json&url=" . $data;
		$output = json_decode($this->bitly_get_curl($url));
		if (isset($output->{'data'}->{'lookup'})) {
		  foreach ($output->{'data'}->{'lookup'} as $tmp) {
		    $rec = array();
		    $rec['global_hash'] = $tmp->{'global_hash'};
		    $rec['short_url'] = $tmp->{'short_url'};
		    $rec['url'] = $tmp->{'url'};
		    array_push($results, $rec);
		  }
		}
		return $results;
	}
	
	/**
	 * This is used by applications to lookup a bit.ly API key for a user given a
	 * bit.ly username and password.
	 *
	 * @param $x_login
	 *   Bit.ly username.
	 * @param $x_password
	 *   Bit.ly password.
	 *
	 * @return
	 *   An associative array containing:
	 *   - successful: An indicator of weather or not the login and password
	 *     combination is valid.
	 *   - username: The corresponding bit.ly users username.
	 *   - api_key: The corresponding bit.ly users API key.
	 *
	 * @see http://code.google.com/p/bitly-api/wiki/ApiDocumentation#/v3/authenticate
	 */
	function authenticate($x_login, $x_password) {
		$result = array();
		$url = $this->bitly_api . "authenticate";
		$params = array();
		$params['login'] = $this->getVar('bitly_username');
		$params['apiKey'] = $this->getVar('bitly_key');
		$params['format'] = "json";
		$params['x_login'] = $x_login;
		$params['x_password'] = $x_password;
		$output = json_decode($this->bitly_post_curl($url, $params));
		if (isset($output->{'data'}->{'authenticate'})) {
		  $this->successful = $output->{'data'}->{'authenticate'}->{'successful'};
		  $this->username = $output->{'data'}->{'authenticate'}->{'username'};
		  $this->api_key =  $output->{'data'}->{'authenticate'}->{'api_key'};
		}
	}
	
	/**
	 * This is used to return the page title for a given bit.ly link.
	 *
	 * @param $data
	 *   Can be a bit.ly shortened URL, a bit.ly hash, or an array of bit.ly URLs
	 *   and/or hashes.
	 *
	 * @return
	 *   A multidimensional numbered associative array containing:
	 *   - created_by: The service that created the link.
	 *   - global_hash: A bit.ly identifier for long_url which can be used to track
	 *     aggregate stats across all matching bit.ly links.
	 *   - hash: The unique bit.ly hash.
	 *   - title: The HTML page title for the destination page (when available).
	 *   - user_hash: The corresponding bit.ly user identifier.
	 *
	 * @see http://code.google.com/p/bitly-api/wiki/ApiDocumentation#/v3/info
	 */
	function info($data) {
		$results = array();
		if (is_array($data)) {
		  // we need to flatten this into one proper command
		  $recs = array();
		  foreach ($data as $rec) {
		    $tmp = explode('/', $rec);
		    $tmp = array_reverse($tmp);
		    array_push($recs, $tmp[0]);
		  }
		  $data = implode('&hash=', $recs);
		} else {
		  $tmp = explode('/', $data);
		  $tmp = array_reverse($tmp);
		  $data = $tmp[0];
		}
		// make the call to expand
		$url = $this->bitly_api . "info?login=" . $this->getVar('bitly_username') . "&apiKey=" . $this->getVar('bitly_key') . "&format=json&hash=" . $data;
		$output = json_decode($this->bitly_get_curl($url));
		if (isset($output->{'data'}->{'info'})) {
		  foreach ($output->{'data'}->{'info'} as $tmp) {
		    $rec = array();
		    $rec['created_by'] = $tmp->{'created_by'};
		    $rec['global_hash'] = $tmp->{'global_hash'};
		    $rec['hash'] = $tmp->{'hash'};
		    $rec['title'] = $tmp->{'title'};
		    $rec['user_hash'] = $tmp->{'user_hash'};
		    array_push($results, $rec);
		  }
		}
		return $results;
	}
	
	/**
	 * Returns an OAuth access token as well as API users for a given code.
	 *
	 * @param $code
	 *   The OAuth verification code acquired via OAuth’s web authentication
	 *   protocol.
	 * @param $redirect
	 *   The page to which a user was redirected upon successfully authenticating.
	 *
	 * @return
	 *   An associative array containing:
	 *   - login: The corresponding bit.ly users username.
	 *   - api_key: The corresponding bit.ly users API key.
	 *   - access_token: The OAuth access token for specified user.
	 *
	 * @see http://code.google.com/p/bitly-api/wiki/ApiDocumentation#/oauth/access_token
	 */
	function oauth_access_token($code, $redirect) {
		$results = array();
		$url = $this->bitly_oauth_access_token . "access_token";
		$params = array();
		$params['client_id'] = $this->getVar('bitly_clientid');
		$params['client_secret'] = $this->getVar('bitly_secret');
		$params['code'] = $code;
		$params['redirect_uri'] = $redirect;
		$output = $this->bitly_post_curl($url, $params);
		$parts = explode('&', $output);
		foreach ($parts as $part) {
		  $bits = explode('=', $part);
		  $results[$bits[0]] = $bits[1];
		}
		$this->username = $results['login'];
		$this->api_key = $results['api_key'];
		$this->access_token = $results['access_token'];
	}
	
	
	/**
	 * Provides the total clicks per day on a user’s bit.ly links.
	 *
	 * @param $this->access_token
	 *   The OAuth access token for the user.
	 * @param $days
	 *   An integer value for the number of days (counting backwards from the
	 *   current day) from which to retrieve data (min:1, max:30, default:7).
	 *
	 * @return
	 *   An associative array containing:
	 *   - days: An echo of the dupplied days parameter.
	 *   - total_clicks: The total number of clicks over the supplied period.
	 *   - clicks: A multidimensional numbered associative array containing:
	 *     - clicks: The number of clicks received for a given link that day.
	 *     - day_start: A time code representing the start of the day for which
	 *       click data is provided.
	 *
	 * @see http://code.google.com/p/bitly-api/wiki/ApiDocumentation#/v3/user/clicks
	 */
	function user_clicks($days = 7) {
		// $results = bitly_v3_user_clicks('BITLY_SUPPLIED_ACCESS_TOKEN');
		$results = array();
		$url = $this->bitly_oauth_api . "user/clicks?access_token=" . $this->access_token . "&days=" . $days;
		$output = json_decode($this->bitly_get_curl($url));
		if (isset($output->{'data'}->{'clicks'})) {
		  $results['days'] = $output->{'data'}->{'days'};
		  $results['total_clicks'] = $output->{'data'}->{'total_clicks'};
		  $results['clicks'] = array();
		  foreach ($output->{'data'}->{'clicks'} as $clicks) {
		    $rec = array();
		    $rec['clicks'] = $clicks->{'clicks'};
		    $rec['day_start'] = $clicks->{'day_start'};
		    array_push($results['clicks'], $rec);
		  }
		}
		return $results;
	}
	
	/**
	 * Provides a list of referring sites for a specified bit.ly user, and the
	 * number of clicks per referrer.
	 *
	 * @param $this->access_token
	 *   The OAuth access token for the user.
	 * @param $days
	 *   An integer value for the number of days (counting backwards from the
	 *   current day) from which to retrieve data (min:1, max:30, default:7).
	 *
	 * @return
	 *   An associative array containing:
	 *   - days: An echo of the dupplied days parameter.
	 *   - referrers: A multidimensional numbered associative array containing:
	 *     - clicks: Number of clicks from this referrer.
	 *     - referrer: (optional) Referring site.
	 *
	 * @see http://code.google.com/p/bitly-api/wiki/ApiDocumentation#/v3/user/referrers
	 */
	function user_referrers($days = 7) {
		// $results = bitly_v3_user_referrers('BITLY_SUPPLIED_ACCESS_TOKEN');
		$results = array();
		$url = $this->bitly_oauth_api . "user/referrers?access_token=" . $this->access_token . "&days=" . $days;
		$output = json_decode($this->bitly_get_curl($url));
		if (isset($output->{'data'}->{'referrers'})) {
		  $results['days'] = $output->{'data'}->{'days'};
		  $results['referrers'] = array();
		  foreach ($output->{'data'}->{'referrers'} as $referrers) {
		    $recs = array();
		    foreach ($referrers as $ref) {
		      $rec = array();
		      $rec['referrer'] = $ref->{'referrer'};
		      $rec['clicks'] = $ref->{'clicks'};
		      array_push($recs, $rec);
		    }
		    array_push($results['referrers'], $recs);
		  }
		}
		return $results;
	}
	
	/**
	 * Provides a list of referring sites for a specified bit.ly short link or hash,
	 * and the number of clicks per referrer.
	 *
	 * @param $this->access_token
	 *   The OAuth access token for the user.
	 * @param $days
	 *   An integer value for the number of days (counting backwards from the
	 *   current day) from which to retrieve data (min:1, max:30, default:7).
	 *
	 * @return
	 *   An associative array containing:
	 *   - days: An echo of the dupplied days parameter.
	 *   - referrers: A multidimensional numbered associative array containing:
	 *     - clicks: Number of clicks from this referrer.
	 *     - countries: (optional) Country code for where the clicks originated.
	 *
	 * @see http://code.google.com/p/bitly-api/wiki/ApiDocumentation#/v3/user/countries
	 */
	function user_countries($days = 7) {
		// $results = bitly_v3_user_countries('BITLY_SUPPLIED_ACCESS_TOKEN');
		$results = array();
		$url = $this->bitly_oauth_api . "user/countries?access_token=" . $this->access_token . "&days=" . $days;
		$output = json_decode($this->bitly_get_curl($url));
		if (isset($output->{'data'}->{'countries'})) {
		  $results['days'] = $output->{'data'}->{'days'};
		  $results['countries'] = array();
		  foreach ($output->{'data'}->{'countries'} as $countries) {
		    $recs = array();
		    foreach ($countries as $country) {
		      $rec = array();
		      $rec['country'] = $country->{'country'};
		      $rec['clicks'] = $country->{'clicks'};
		      array_push($recs, $rec);
		    }
		    array_push($results['countries'], $recs);
		  }
		}
		return $results;
	}
	
	/**
	 * Provides a given user’s 100 most popular links based on click traffic in the
	 * past hour, and the number of clicks per link.
	 *
	 * @param $this->access_token
	 *   The OAuth access token for the user.
	 *
	 * @return
	 *   A multidimensional numbered associative array containing:
	 *   - user_hash: The corresponding bit.ly user identifier.
	 *   - clicks: Number of clicks on this link.
	 *
	 * @see http://code.google.com/p/bitly-api/wiki/ApiDocumentation#/v3/user/realtime_links
	 */
	function user_realtime_links() {
		// $results = bitly_v3_user_realtime_links('BITLY_SUPPLIED_ACCESS_TOKEN');
		$results = array();
		$url = $this->bitly_oauth_api . "user/realtime_links?format=json&access_token=" . $this->access_token;
		$output = json_decode($this->bitly_get_curl($url));
		if (isset($output->{'data'}->{'realtime_links'})) {
		  foreach ($output->{'data'}->{'realtime_links'} as $realtime_links) {
		    $rec = array();
		    $rec['clicks'] = $realtime_links->{'clicks'};
		    $rec['user_hash'] = $realtime_links->{'user_hash'};
		    array_push($results, $rec);
		  }
		}
		return $results;
	}
	
	/**
	 * Make a GET call to the bit.ly API.
	 *
	 * @param $uri
	 *   URI to call.
	 */
	private function bitly_get_curl($uri) {
		$output = "";
		try {
			$ch = curl_init($uri);
			curl_setopt($ch, CURLOPT_HEADER, 0);
		  	curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['bitlyModuleConfig']['curl_timeout']);
		  	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $GLOBALS['bitlyModuleConfig']['curl_connection_timeout']);
		  	curl_setopt($ci, CURLOPT_USERAGENT, $GLOBALS['bitlyModuleConfig']['user_agent']);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			$output = curl_exec($ch);
		} catch (Exception $e) {
		}
		return $output;
	}
	
	/**
	 * Make a POST call to the bit.ly API.
	 *
	 * @param $uri
	 *   URI to call.
	 * @param $fields
	 *   Array of fields to send.
	 */
	private function bitly_post_curl($uri, $fields) {
		$output = "";
		$fields_string = "";
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string,'&');
		try {
		  $ch = curl_init($uri);
		  curl_setopt($ch, CURLOPT_HEADER, 0);
		  curl_setopt($ch,CURLOPT_POST,count($fields));
		  curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
		  curl_setopt($ch, CURLOPT_TIMEOUT, $GLOBALS['bitlyModuleConfig']['curl_timeout']);
		  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $GLOBALS['bitlyModuleConfig']['curl_connection_timeout']);
		  curl_setopt($ci, CURLOPT_USERAGENT, $GLOBALS['bitlyModuleConfig']['user_agent']);
		  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		  $output = curl_exec($ch);
		} catch (Exception $e) {
		}
		return $output;
	}

	
    
}


/**
* XOOPS policies handler class.
* This class is responsible for providing data access mechanisms to the data source
* of XOOPS user class objects.
*
* @author  Simon Roberts <simon@chronolabs.coop>
* @package kernel
*/
class BitlyOauthHandler extends XoopsPersistableObjectHandler
{

	function  __construct(&$db) 
    {
		$this->db = $db;
        parent::__construct($db, 'lib_oauth', 'BitlyOauth', "oauth_id", "mode");
        
        $module_handler = xoops_gethandler('module');
    	$config_handler = xoops_gethandler('config');
    	if (!isset($GLOBALS['bitlyModule']))
    		$GLOBALS['bitlyModule'] = $module_handler->getByDirname('bitly');
    	if (!isset($GLOBALS['bitlyModuleConfig']))
    		$GLOBALS['bitlyModuleConfig'] = $config_handler->getConfigList($GLOBALS['bitlyModule']->getVar('mid'));

    }

    function getRootOauth() {
    	if (isset($_SESSION['oauth']['bitly']['oauth_id'])&&!empty($_SESSION['oauth']['bitly']['oauth_id'])) {
    		return $this->get($_SESSION['oauth']['bitly']['oauth_id']);
    	}
    	$object = $this->getByCriteria(new Criteria('root', true));
    	if (!$object->isNew())
    		return $object;
    	return false;
    }
    
    function  getByCriteria($criteria = NULL) {
    	if ($this->getCount($criteria)==0)
    		return $this->create();
    	$criteria->setStart(0);
    	$criteria->setLimit(1);
    	$objects = $this->getObjects($criteria, false);
    	if (!is_object($objects[0]))
    		return $this->create();
    	return $objects[0];
    }
    
    
    function insert($object, $force = true) {
    	if($object->isNew()) {
    		  $criteria = new CriteriaCompo();
    		foreach($object->vars as $field => $values) {
    			if (!in_array($field, array($this->keyName, 'searched', 'polled', 'emailed', 'sms', 'synced', 'created', 'updated')))
    				if ($values['data_type']!=XOBJ_DTYPE_ARRAY)	
    					if (!empty($values['value'])||intval($values['value'])<>0)
    						$criteria->add(new Criteria('`'.$field.'`', $object->getVar($field)));
    		}
    		if ($this->getCount($criteria)>0) {
    			$obj = $this->getByCriteria($criteria);
    			if (is_object($obj)) {
    				return $obj->getVar($this->keyName);
    			}
    		}
    		
    		$object->setVar('created', time());
    	} else {
    		if (!$object->isDirty())
    			return $object->getVar($this->keyName);
    		$object->setVar('updated', time());
    	}
    	return parent::insert($object, $force);
    }
}

?>