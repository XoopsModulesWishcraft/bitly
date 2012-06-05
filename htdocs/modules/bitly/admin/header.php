<?php

	require_once (dirname(dirname(dirname(dirname(__FILE__)))).'/include/cp_header.php');
	
	if (!defined('_CHARSET'))
		define ("_CHARSET","UTF-8");
	if (!defined('_CHARSET_ISO'))
		define ("_CHARSET_ISO","ISO-8859-1");
		
	$GLOBALS['myts'] = MyTextSanitizer::getInstance();
	
	$module_handler = xoops_gethandler('module');
	$config_handler = xoops_gethandler('config');
	$GLOBALS['linkedinbombModule'] = $module_handler->getByDirname('linkedinbomb');
	$GLOBALS['linkedinbombModuleConfig'] = $config_handler->getConfigList($GLOBALS['linkedinbombModule']->getVar('mid')); 
		
	xoops_load('pagenav');	
	xoops_load('xoopslists');
	xoops_load('xoopsformloader');
	
	include_once $GLOBALS['xoops']->path('class'.DS.'xoopsmailer.php');
	include_once $GLOBALS['xoops']->path('class'.DS.'xoopstree.php');
	
	if ( file_exists($GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php'))){
        include_once $GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php');
    }else{
        echo xoops_error("Error: You don't use the Frameworks \"admin module\". Please install this Frameworks");
    }
    
	$GLOBALS['linkedinbombImageIcon'] = XOOPS_URL .'/'. $GLOBALS['linkedinbombModule']->getInfo('icons16');
	$GLOBALS['linkedinbombImageAdmin'] = XOOPS_URL .'/'. $GLOBALS['linkedinbombModule']->getInfo('icons32');
	
	if ($GLOBALS['xoopsUser']) {
	    $moduleperm_handler =& xoops_gethandler('groupperm');
	    if (!$moduleperm_handler->checkRight('module_admin', $GLOBALS['linkedinbombModule']->getVar( 'mid' ), $GLOBALS['xoopsUser']->getGroups())) {
	        redirect_header(XOOPS_URL, 1, _NOPERM);
	        exit();
	    }
	} else {
	    redirect_header(XOOPS_URL . "/user.php", 1, _NOPERM);
	    exit();
	}
	
	if (!isset($GLOBALS['xoopsTpl']) || !is_object($GLOBALS['xoopsTpl'])) {
		include_once(XOOPS_ROOT_PATH."/class/template.php");
		$GLOBALS['xoopsTpl'] = new XoopsTpl();
	}
	
	$GLOBALS['xoopsTpl']->assign('pathImageIcon', $GLOBALS['linkedinbombImageIcon']);
	$GLOBALS['xoopsTpl']->assign('pathImageAdmin', $GLOBALS['linkedinbombImageAdmin']);

	include(dirname(dirname(__FILE__)).'/include/functions.php');
	include(dirname(dirname(__FILE__)).'/include/formobjects.linkedinbomb.php');
	include(dirname(dirname(__FILE__)).'/include/forms.a.linkedinbomb.php');
	
	xoops_loadLanguage('admin', 'linkedinbomb');
	 
	if (isset($_POST['filter_location_id'])) {
		$location_id = isset($_POST['filter_location_id'])&&intval($_POST['filter_location_id'])!=$_SESSION['location_id']?intval($_POST['filter_location_id']):$_SESSION['location_id']; 
		if (intval($_POST['filter_location_id'])!=$_SESSION['location_id']) {
			$locations_handler = xoops_getmodulehandler('locations', 'linkedinbomb');
			$location = $locations_handler->get($location_id);
			if ($location->getVar('country_id')>0) {
				$_SESSION['country_id'] = $location->getVar('country_id');
			} else { 
				$_SESSION['country_id'] = 0;
			}
			$_SESSION['skill_ids'] = array();
		} else {
			$_SESSION['country_id'] = isset($_POST['filter_country_id'])&&intval($_POST['filter_country_id'])!=$_SESSION['country_id']?intval($_POST['filter_country_id']):$_SESSION['country_id'];
			if (isset($_POST['filter_skill_ids'])) {
				$_SESSION['skill_ids'] = isset($_POST['filter_skill_ids'])?(array)($_POST['filter_skill_ids']):$_SESSION['skills_id'];
			}
		}
		$_SESSION['location_id'] = $location_id;
	}
	
	
	$authorities_handler = xoops_getmodulehandler('authorities', 'linkedinbomb');
    $causes_handler = xoops_getmodulehandler('causes', 'linkedinbomb');
    $company_type_handler = xoops_getmodulehandler('companies_type', 'linkedinbomb');
    $contact_info_handler = xoops_getmodulehandler('contact_info', 'linkedinbomb');
    $follow_companies_handler = xoops_getmodulehandler('following_companies', 'linkedinbomb');
    $industry_handler = xoops_getmodulehandler('industry', 'linkedinbomb');
    $languages_handler = xoops_getmodulehandler('languages', 'linkedinbomb');
    $locations_handler = xoops_getmodulehandler('locations', 'linkedinbomb');
    $organization_handler = xoops_getmodulehandler('organization', 'linkedinbomb');
    $persons_handler = xoops_getmodulehandler('persons', 'linkedinbomb');
    $proficiencies_handler = xoops_getmodulehandler('proficiencies', 'linkedinbomb');
    $profiles_certificiations_handler = xoops_getmodulehandler('profiles_certifications', 'linkedinbomb');
    $profiles_companies_handler = xoops_getmodulehandler('profiles_companies', 'linkedinbomb');
    $profiles_courses_handler = xoops_getmodulehandler('profiles_courses', 'linkedinbomb');
    $profiles_educations_handler = xoops_getmodulehandler('profiles_educations', 'linkedinbomb');
    $profiles_ims_handler = xoops_getmodulehandler('profiles_ims', 'linkedinbomb');
    $profiles_languages_handler = xoops_getmodulehandler('profiles_languages', 'linkedinbomb');
    $profiles_patents_inventors_handler = xoops_getmodulehandler('profiles_patents_inventors', 'linkedinbomb');
    $profiles_patents_office_handler = xoops_getmodulehandler('profiles_patents_office', 'linkedinbomb');
    $profiles_patents_status_handler = xoops_getmodulehandler('profiles_patents_status', 'linkedinbomb');
    $profiles_patents_handler = xoops_getmodulehandler('profiles_patents', 'linkedinbomb');
    $profiles_phones_handler = xoops_getmodulehandler('profiles_phones', 'linkedinbomb');
    $profiles_positions_handler = xoops_getmodulehandler('profiles_positions', 'linkedinbomb');
    $profiles_providers_handler = xoops_getmodulehandler('profiles_providers', 'linkedinbomb');
    $profiles_publications_authors_handler = xoops_getmodulehandler('profiles_publications_authors', 'linkedinbomb');
    $profiles_publications_handler = xoops_getmodulehandler('profiles_publications', 'linkedinbomb');
    $profiles_recommendations_handler = xoops_getmodulehandler('profiles_recommendations', 'linkedinbomb');
    $profiles_skills_handler = xoops_getmodulehandler('profiles_skills', 'linkedinbomb');
    $profiles_volunteer_handler = xoops_getmodulehandler('profiles_volunteer', 'linkedinbomb');
    $profiles_handler = xoops_getmodulehandler('profiles', 'linkedinbomb');
    $skills_handler = xoops_getmodulehandler('skills', 'linkedinbomb');
    $specialties_handler = xoops_getmodulehandler('specialties', 'linkedinbomb');
    $status_handler = xoops_getmodulehandler('status', 'linkedinbomb');
    $years_handler = xoops_getmodulehandler('years', 'linkedinbomb');
    
    set_time_limit(3600);
    ini_set('memory_limit', '256M');
    
    $filter = '1,1';
    
    error_reporting(E_ALL);
?>