<?php

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class LinkedinbombCorePreload extends XoopsPreloadItem
{
	//function eventCoreIncludeCommonEnd($args)
    //{
    //	include('../post.common.end.php');
    //}
    
	function eventCoreFooterEnd($args)
    {
    	include(dirname(dirname(__FILE__)).'/include/post.footer.end.php');
    }

    function eventCoreHeaderCacheEnd($args)
    {
    	include(dirname(dirname(__FILE__)).'/include/post.cache.end.php');
    }
    
}
?>