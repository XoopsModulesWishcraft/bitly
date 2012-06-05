<?php

	include(dirname(dirname(dirname(dirname(__FILE__)))).'/mainfile.php');
	
	$oauth_handler = xoops_getmodulehandler('oauth', 'bitly');
	
	if ($oauth_handler->getCount(new Criteria('root', 1))==0) {
		$oauth = $oauth_handler->create();
		$oauth->setVar('root', true);
		$oauth->oauth_access_token($_REQUEST['code'], $GLOBALS['bitlyModuleConfig']['callback_url']);
		$oauth->syncronise();
		$_SESSION['oauth']['bitly']['oauth_id'] = $oauth_handler->insert($oauth, true);
	} else {
		$oauth = $oauth_handler->create();
		$oauth->setVar('root', false);
		$oauth->oauth_access_token($_REQUEST['code'], $GLOBALS['bitlyModuleConfig']['callback_url']);
		if ($oauth_handler->getCount(new Criteria('bitly_username', $oauth->username))==0) {
			$oauth->syncronise();
			$_SESSION['oauth']['bitly']['oauth_id'] = $oauth_handler->insert($oauth, true);
		} else {
			$oauth = $oauth_handler->getByCriteria(new Criteria('bitly_username', $oauth->username));
			$oauth->oauth_access_token($_REQUEST['code'], $GLOBALS['bitlyModuleConfig']['callback_url']);
			$oauth->syncronise();
			$_SESSION['oauth']['bitly']['oauth_id'] = $oauth_handler->insert($oauth, true);
		}
	}
	
	// redirect the user back to the demo page
	header('Location: ' . XOOPS_URL.'/modules/bitly/index.php');
	
?>
