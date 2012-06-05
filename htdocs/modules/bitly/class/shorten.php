<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

/**
 * Class for Blue Room Xcenter
 * @author Simon Roberts <simon@xoops.org>
 * @copyright copyright (c) 2009-2003 XOOPS.org
 * @package kernel
 */
class BitlyShorten extends XoopsObject
{
	
    function  __construct($id = null)
    {
   	
        $this->initVar('shorten_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('oauth_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('domain', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('url', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('hash', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('global_hash', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('long_url', XOBJ_DTYPE_TXTBOX, null, false, 500);
		$this->initVar('new_hash', XOBJ_DTYPE_INT, null, false);
		$this->initVar('title', XOBJ_DTYPE_TXTBOX, null, false, 128);
		$this->initVar('user_clicks', XOBJ_DTYPE_INT, null, false);
		$this->initVar('global_clicks', XOBJ_DTYPE_INT, null, false);
        $this->initVar('crawled', XOBJ_DTYPE_INT, null, false); // Removed Unicode in 2.10
		$this->initVar('expires', XOBJ_DTYPE_INT, null, false);
		$this->initVar('created', XOBJ_DTYPE_INT, null, false); // Removed Unicode in 2.10
		$this->initVar('updated', XOBJ_DTYPE_INT, null, false); // Removed Unicode in 2.10
		
		if ($id>0) {
			$handler = new BitlyShortenHandler($GLOBALS['xoopsDB']);
			$object = $handler->get($id);
			if (is_object($object)) {
				if (is_a($object, 'BitlyShorten')) {
					$this->assignVars($object->getValues());
				}
			}
			unset($object);
		}
    }

	function  setVar($field, $value) {
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
    	return $this->getVar('url');
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
    	} else {
    		$ret['created'] = '';
    	}
    	if (isset($ret['updated'])&&$ret['updated']>0) {
    		$ret['updated'] = date(_DATESTRING, $ret['updated']);
    	} else {
    		$ret['updated'] = '';
    	}
    	if (isset($ret['expired'])&&$ret['expired']>0) {
    		$ret['expired'] = date(_DATESTRING, $ret['expired']);
    	} else {
    		$ret['expired'] = '';
    	}
    	if (isset($ret['crawled'])&&$ret['crawled']>0) {
    		$ret['crawled'] = date(_DATESTRING, $ret['crawled']);
    	} else {
    		$ret['crawled'] = '';
    	}
    	if (is_array($form = $this->getForm(true, ''))) {
    		foreach($form as $field => $element) {
    			$ret['form'][$field] = $form[$field]->render();
    		}
    	} 
    	return $ret;
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
class BitlyShortenHandler extends XoopsPersistableObjectHandler
{

	var $oauth = NULL;
	
	function  __construct(&$db) 
    {
		$this->db = $db;
        parent::__construct($db, 'bitly_shorten', 'BitlyShorten', "shorten_id", "title");
        
        $module_handler = xoops_gethandler('module');
    	$config_handler = xoops_gethandler('config');
    	if (!isset($GLOBALS['bitlyModule']))
    		$GLOBALS['bitlyModule'] = $module_handler->getByDirname('Bitly');
    	if (!isset($GLOBALS['bitlyModuleConfig']))
    		$GLOBALS['bitlyModuleConfig'] = $config_handler->getConfigList($GLOBALS['bitlyModule']->getVar('mid'));

    	$oauth_handler = xoops_getmodulehandler('oauth', 'bitly');
    	$this->oauth = $oauth_handler->getRootOauth();

    }

    function  getDomain() {
    	if ($GLOBALS['bitlyModuleConfig']['domain']=='----')
    		return $GLOBALS['bitlyModuleConfig']['other_domain'];
    	return $GLOBALS['bitlyModuleConfig']['domain'];
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
    
	function shortenURL($url) {
		$criteria = new Criteria('`long_url`', $url);
		if ($this->getCount($criteria)>0)
		{
			$shorten = $this->getByCriteria($criteria);
			return $shorten->getVar('url'); 
		} else {
			if (!is_object($this->oauth)) {
				return $url;
			}
			if (is_a($this->oauth, 'BitlyOauth')) {
				$short = $this->oauth->shorten($url, $this->getDomain());
				$shorten = $this->create();
				$shorten->setVars($short);
				$info = $this->oauth->info($short['url']);
				$shorten->setVars($info);
				$shorten->setVar('expires', time()+3600);
				$this->insert($shorten, true);
				return $shorten->getVar('url');
			} else {
				return $url;
			}
		}	
		
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
    	$clicks = $this->oauth->clicks($object->getVar('url'));
    	$object->setVars($clicks);
    	return parent::insert($object, $force);
    }
}

?>