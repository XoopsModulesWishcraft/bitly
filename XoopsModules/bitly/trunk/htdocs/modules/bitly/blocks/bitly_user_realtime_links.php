<?php

	function b_bitly_block_user_realtime_links_show($options) {
		xoops_loadLanguage('blocks', 'bitly');
		$realtimelinks_handler = xoops_getmodulehandler('realtimelinks', 'bitly');
		$links = $realtimelinks_handler->getTopLinksURL($options[0]);
		$i=0;
		$ret=array();
		foreach($links as $link) {
			$i++;
			$ret['links'][$i] = $link->toArray();
			$ret['links'][$i]['position'] = $i; 
		}
		return (count($ret['links'])>0?$ret:false);
	}
	
	function b_bitly_block_user_realtime_links_edit($options) {
		xoops_loadLanguage('blocks', 'bitly');
		include_once(dirname(dirname(__FILE__)).'/include/formobjects.bitly.php');
		$limit = new XoopsFormText(_BK_BITLY_NUMBER_ITEMS_LIMIT, 'options[0]', 12, 15, $options[0]);
		return $limit->render(); 
	}
	
?>