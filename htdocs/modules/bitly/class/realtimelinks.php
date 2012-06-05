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
class BitlyRealtimelinks extends XoopsObject
{
	var $shorten = NULL;
	
    function  __construct($id = null)
    {
   	
        $this->initVar('realtime_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('shorten_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('oauth_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('user_hash', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('clicks', XOBJ_DTYPE_INT, null, false);
        $this->initVar('expires', XOBJ_DTYPE_INT, null, false);
        $this->initVar('created', XOBJ_DTYPE_INT, null, false);
        $this->initVar('updated', XOBJ_DTYPE_INT, null, false);
		
		if ($id>0) {
			$handler = new BitlyRealtimelinksHandler($GLOBALS['xoopsDB']);
			$object = $handler->get($id);
			if (is_object($object)) {
				if (is_a($object, 'BitlyRealtimelinks')) {
					$this->assignVars($object->getValues());
				}
			}
			unset($object);
		}
    }

	function assignVar($field, $value) {
		if  ($field=='shorten_id'&&$value>0) {
			$shorten_handler = xoops_getmodulehandler('shorten', 'bitly');
			$this->shorten = $shorten_handler->get($value); 
		}
		parent::assignVar($field, $value);
	}
	
	function setVar($field, $value) {
		if  ($field=='shorten_id'&&$value>0) {
			$shorten_handler = xoops_getmodulehandler('shorten', 'bitly');
			$this->shorten = $shorten_handler->get($value); 
		}
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
    	if (isset($ret['expires'])&&$ret['expires']>0) {
    		$ret['expires'] = date(_DATESTRING, $ret['expires']);
    	} else {
    		$ret['expires'] = '';
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
    	if (is_object($this->shorten))
    		$ret = array_merge($ret,$this->shorten->toArray());
    	
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
class BitlyRealtimelinksHandler extends XoopsPersistableObjectHandler
{

	var $oauth = NULL;
	
	function  __construct(&$db) 
    {
		$this->db = $db;
        parent::__construct($db, 'bitly_userrealtimelinks', 'BitlyRealtimelinks', "realtime_id", "user_hash");
        
        $module_handler = xoops_gethandler('module');
    	$config_handler = xoops_gethandler('config');
    	if (!isset($GLOBALS['bitlyModule']))
    		$GLOBALS['bitlyModule'] = $module_handler->getByDirname('Bitly');
    	if (!isset($GLOBALS['bitlyModuleConfig']))
    		$GLOBALS['bitlyModuleConfig'] = $config_handler->getConfigList($GLOBALS['bitlyModule']->getVar('mid'));

    	$oauth_handler = xoops_getmodulehandler('oauth', 'bitly');
    	$this->oauth = $oauth_handler->getRootOauth();

    }

    function doPoll() {
    	if ($this->getCount(new Criteria('`expires`', time(), '<='))>0) {
    		@$this->deleteAll(new Criteria('`expires`', time(), '<='), true);
    	}
    	$results = $this->oauth->user_realtime_links();
    	$shorten_handler = xoops_getmodulehandler('shorten', 'bitly');
    	foreach($results as $result) {
    		$link = $this->getByCriteria(new Criteria('`user_hash`', $result['user_hash']));
    		$link->setVars($result);
    		$shorten = $shorten_handler->getByCriteria(new Criteria('`hash`', $result['user_hash']));
    		if (is_object($shorten))
    			$link->setVar('shorten_id', $shorten->getVar('shorten_id'));
    		$link->setVar('oauth_id', $this->oauth->getVar('oauth_id'));
    		$link->setVar('expires', time()+$GLOBALS['bitlyModuleConfig']['links_kept']);
    		$this->insert($link);
    	}
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
    
	function getTopLinksURL($limit=10) {
		$criteria = new Criteria('`clicks`', 0, '>');
		$criteria->setLimit($limit);
		$criteria->setSort('`clicks`');
		$criteria->setOrder('DESC');
		if ($this->getCount($criteria)>0)
		{
			return $this->getObjects($criteria, true); 
		} else {
			return false;
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
    	if ($object->getVar('shorten_id')==0) {
    		$shorten_handler = xoops_getmodulehandler('shorten', 'bitly');
    		$shorten = $shorten_handler->getByCriteria(new Criteria('`hash`', $object->getVar('user_hash')));
    		if ($shorten->isNew()) {
    			$shorten->setVars($expand = $this->oauth->expand($object->getVar('user_hash')));
    			$shorten->setVars($info = $this->oauth->info($object->getVar('user_hash')));
    			$shorten->setVar('hash', $object->getVar('user_hash'));
    			$shorten->setVar('url', $info['short_url']);
    			$shorten->setVar('expires', time());
    			$object->setVar('shorten_id', $shorten_handler->insert($shorten));
    		} else {
    			$object->setVar('shorten_id', $shorten->getVar('shorten_id'));
    		}
    	}
    	return parent::insert($object, $force);
    }
}

?>