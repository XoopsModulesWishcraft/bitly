<?php

	xoops_load('xoopscache');
	if (!class_exists('XoopsCache')) {
		// XOOPS 2.4 Compliance
		xoops_load('cache');
		if (!class_exists('XoopsCache')) {
			include_once XOOPS_ROOT_PATH.'/class/cache/xoopscache.php';
		}
	}
    $module_handler = xoops_gethandler('module');
    $config_handler = xoops_gethandler('config');
    $GLOBALS['bitlyModule'] = $module_handler->getByDirname('bitly');
    if (is_object($GLOBALS['bitlyModule'])) {
    	$GLOBALS['bitlyModuleConfig'] = $config_handler->getConfigList($GLOBALS['bitlyModule']->getVar('mid'));
		switch ($GLOBALS['bitlyModuleConfig']['crontype']) {
			case 'preloader':
				if (!$read = XoopsCache::read('bitly_pause_preload_poll')) {
					XoopsCache::write('bitly_pause_preload_poll', true, $GLOBALS['bitlyModuleConfig']['poll_every']);
					ob_start();
					include(XOOPS_ROOT_PATH.'/modules/bitly/cron/poll.php');
					ob_end_clean();
				}
				if (!$read = XoopsCache::read('bitly_pause_preload_crawl')) {
					XoopsCache::write('bitly_pause_preload_crawl', true, $GLOBALS['bitlyModuleConfig']['crawl_every']);
					ob_start();
					include(XOOPS_ROOT_PATH.'/modules/bitly/cron/crawl.php');
					ob_end_clean();
				}				
				break;
		}
    }