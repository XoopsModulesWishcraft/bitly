<?php
	xoops_load('XoopsFormLoader');
	
	require_once('formcheckboxskills.php');
	require_once('formselectcountries.php');
	require_once('formselectlocations.php');

	if (file_exists($GLOBALS['xoops']->path('/modules/twitterbomb/include/formselectcampaigns.php')))
		require_once ($GLOBALS['xoops']->path('/modules/twitterbomb/include/formselectcampaigns.php'));
		
?>