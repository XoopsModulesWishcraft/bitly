<?php
	
	require('header.php');
	
	$op = isset($_REQUEST['op'])?$_REQUEST['op']:"dashboard";
	$fct = isset($_REQUEST['fct'])?$_REQUEST['fct']:"list";
	$limit = !empty($_REQUEST['limit'])?intval($_REQUEST['limit']):30;
	$start = !empty($_REQUEST['start'])?intval($_REQUEST['start']):0;
	$order = !empty($_REQUEST['order'])?$_REQUEST['order']:'DESC';
	$sort = !empty($_REQUEST['sort'])?''.$_REQUEST['sort'].'':'created';
	$filter = !empty($_REQUEST['filter'])?''.$_REQUEST['filter'].'':'1,1';
	$id = !empty($_REQUEST['id'])?(is_array($_REQUEST['id'])?array_unique($_REQUEST['id']):intval($_REQUEST['id'])):0;
	
	switch($op) {
		default:
		case "dashboard":
			xoops_cp_header();		
	   		
			$indexAdmin = new ModuleAdmin();
   			echo $indexAdmin->addNavigation(strtolower(basename($_SERVER['REQUEST_URI'])));

   			/*
   			
		 	$indexAdmin = new ModuleAdmin();	
		    $indexAdmin->addInfoBox(_AM_LINKEDINBOMB_ADMIN_COUNTS);
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_AUTHORITIES."</label>", $count = $authorities_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_CAUSES."</label>", $count = $causes_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_COMPANY_TYPE."</label>", $count = $company_type_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_CONTACT_INFO."</label>", $count = $contact_info_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_FOLLOW_COMPANIES."</label>", $count = $follow_companies_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_INDUSTRY."</label>", $count = $industry_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_LANGUAGES."</label>", $count = $languages_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_LOCATIONS."</label>", $count = $locations_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_ORGANIZATION."</label>", $count = $organization_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PERSONS."</label>", $count = $persons_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PROFICIENCIES."</label>", $count = $proficiencies_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PROFILE_CERTIFICATIONS."</label>", $count = $profiles_certificiations_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PROFILE_COMPANIES."</label>", $count = $profiles_companies_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PROFILE_COURSES."</label>", $count = $profiles_courses_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PROFILE_EDUCTIONS."</label>", $count = $profiles_educations_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PROFILE_IMS."</label>", $count = $profiles_ims_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PROFILE_LANGUAGES."</label>", $count = $profiles_languages_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PROFILE_PATENTS_INVENTORS."</label>", $count = $profiles_patents_inventors_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PROFILE_PATENTS_OFFICE."</label>", $count = $profiles_patents_office_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PROFILE_PATENTS_STATUS."</label>", $count = $profiles_patents_status_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PROFILE_PATENTS."</label>", $count = $profiles_patents_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PROFILE_PHONE."</label>", $count = $profiles_phones_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PROFILE_POSITIONS."</label>", $count = $profiles_positions_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PROFILE_PROVIDER."</label>", $count = $profiles_providers_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PROFILE_PUBLICATIONS_AUTHORS."</label>", $count = $profiles_publications_authors_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PROFILE_PUBLICATIONS."</label>", $count = $profiles_publications_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PROFILE_RECOMMENDATIONS."</label>", $count = $profiles_recommendations_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PROFILE_SKILLS."</label>", $count = $profiles_skills_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PROFILE_VOLUNTEER."</label>", $count = $profiles_volunteer_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_PROFILE."</label>", $count = $profiles_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_SKILLS."</label>", $count = $skills_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_SPECIALTIES."</label>", $count = $specialties_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_STATUS."</label>", $count = $status_handler->getCount(NULL), ($count>0?'Green':'Red'));
		    $indexAdmin->addInfoBoxLine(_AM_LINKEDINBOMB_ADMIN_COUNTS, "<label>"._AM_LINKEDINBOMB_DASHBOARD_THEREARE_YEARS."</label>", $count = $years_handler->getCount(NULL), ($count>0?'Green':'Red'));

		    if (isset($_SESSION['location_id'])) {
		    	$ids = $profiles_handler->getIDs(new Criteria('location_id', $_SESSION['location_id']));
		    	$location = $locations_handler->get($_SESSION['location_id']);
		    	if (is_object($location)) {		    	
					$indexAdmin->addInfoBox(sprintf(_AM_LINKEDINBOMB_LOCATION_COUNTS, $location->getVar('name')));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_LOCATION_COUNTS, $location->getVar('name')), "<label>"._AM_LINKEDINBOMB_DASHBOARD_LOCATIONHAS_PROFILE_CERTIFICATIONS."</label>", $count = $profiles_certificiations_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_LOCATION_COUNTS, $location->getVar('name')), "<label>"._AM_LINKEDINBOMB_DASHBOARD_LOCATIONHAS_PROFILE_COMPANIES."</label>", $count = $profiles_companies_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_LOCATION_COUNTS, $location->getVar('name')), "<label>"._AM_LINKEDINBOMB_DASHBOARD_LOCATIONHAS_PROFILE_COURSES."</label>", $count = $profiles_courses_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_LOCATION_COUNTS, $location->getVar('name')), "<label>"._AM_LINKEDINBOMB_DASHBOARD_LOCATIONHAS_PROFILE_EDUCTIONS."</label>", $count = $profiles_educations_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_LOCATION_COUNTS, $location->getVar('name')), "<label>"._AM_LINKEDINBOMB_DASHBOARD_LOCATIONHAS_PROFILE_IMS."</label>", $count = $profiles_ims_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_LOCATION_COUNTS, $location->getVar('name')), "<label>"._AM_LINKEDINBOMB_DASHBOARD_LOCATIONHAS_PROFILE_LANGUAGES."</label>", $count = $profiles_languages_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_LOCATION_COUNTS, $location->getVar('name')), "<label>"._AM_LINKEDINBOMB_DASHBOARD_LOCATIONHAS_PROFILE_PATENTS_INVENTORS."</label>", $count = $profiles_patents_inventors_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_LOCATION_COUNTS, $location->getVar('name')), "<label>"._AM_LINKEDINBOMB_DASHBOARD_LOCATIONHAS_PROFILE_PATENTS_OFFICE."</label>", $count = $profiles_patents_office_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_LOCATION_COUNTS, $location->getVar('name')), "<label>"._AM_LINKEDINBOMB_DASHBOARD_LOCATIONHAS_PROFILE_PATENTS_STATUS."</label>", $count = $profiles_patents_status_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_LOCATION_COUNTS, $location->getVar('name')), "<label>"._AM_LINKEDINBOMB_DASHBOARD_LOCATIONHAS_PROFILE_PATENTS."</label>", $count = $profiles_patents_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_LOCATION_COUNTS, $location->getVar('name')), "<label>"._AM_LINKEDINBOMB_DASHBOARD_LOCATIONHAS_PROFILE_PHONE."</label>", $count = $profiles_phones_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_LOCATION_COUNTS, $location->getVar('name')), "<label>"._AM_LINKEDINBOMB_DASHBOARD_LOCATIONHAS_PROFILE_POSITIONS."</label>", $count = $profiles_positions_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_LOCATION_COUNTS, $location->getVar('name')), "<label>"._AM_LINKEDINBOMB_DASHBOARD_LOCATIONHAS_PROFILE_PROVIDER."</label>", $count = $profiles_providers_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_LOCATION_COUNTS, $location->getVar('name')), "<label>"._AM_LINKEDINBOMB_DASHBOARD_LOCATIONHAS_PROFILE_PUBLICATIONS_AUTHORS."</label>", $count = $profiles_publications_authors_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_LOCATION_COUNTS, $location->getVar('name')), "<label>"._AM_LINKEDINBOMB_DASHBOARD_LOCATIONHAS_PROFILE_PUBLICATIONS."</label>", $count = $profiles_publications_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_LOCATION_COUNTS, $location->getVar('name')), "<label>"._AM_LINKEDINBOMB_DASHBOARD_LOCATIONHAS_PROFILE_RECOMMENDATIONS."</label>", $count = $profiles_recommendations_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_LOCATION_COUNTS, $location->getVar('name')), "<label>"._AM_LINKEDINBOMB_DASHBOARD_LOCATIONHAS_PROFILE_SKILLS."</label>", $count = $profiles_skills_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_LOCATION_COUNTS, $location->getVar('name')), "<label>"._AM_LINKEDINBOMB_DASHBOARD_LOCATIONHAS_PROFILE_VOLUNTEER."</label>", $count = $profiles_volunteer_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_LOCATION_COUNTS, $location->getVar('name')), "<label>"._AM_LINKEDINBOMB_DASHBOARD_LOCATIONHAS_PROFILE."</label>", $count = $profiles_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
		    	}
		    }

		    if (isset($_SESSION['skill_ids'])) {
		    	$ids = $skills_handler->getProfileIDs(new Criteria('skill_id', '('.implode(',',$_SESSION['skill_ids']).')', 'IN'));
		    	$skills = count($_SESSION['skill_ids']) . ' Skill\'s';
		    	if (!empty($skills)) {		    	
					$indexAdmin->addInfoBox(sprintf(_AM_LINKEDINBOMB_SKILL_COUNTS, $skills));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_SKILL_COUNTS, $skills), "<label>"._AM_LINKEDINBOMB_DASHBOARD_SKILLHAS_PROFILE_CERTIFICATIONS."</label>", $count = $profiles_certificiations_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_SKILL_COUNTS, $skills), "<label>"._AM_LINKEDINBOMB_DASHBOARD_SKILLHAS_PROFILE_COMPANIES."</label>", $count = $profiles_companies_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_SKILL_COUNTS, $skills), "<label>"._AM_LINKEDINBOMB_DASHBOARD_SKILLHAS_PROFILE_COURSES."</label>", $count = $profiles_courses_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_SKILL_COUNTS, $skills), "<label>"._AM_LINKEDINBOMB_DASHBOARD_SKILLHAS_PROFILE_EDUCTIONS."</label>", $count = $profiles_educations_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_SKILL_COUNTS, $skills), "<label>"._AM_LINKEDINBOMB_DASHBOARD_SKILLHAS_PROFILE_IMS."</label>", $count = $profiles_ims_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_SKILL_COUNTS, $skills), "<label>"._AM_LINKEDINBOMB_DASHBOARD_SKILLHAS_PROFILE_LANGUAGES."</label>", $count = $profiles_languages_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_SKILL_COUNTS, $skills), "<label>"._AM_LINKEDINBOMB_DASHBOARD_SKILLHAS_PROFILE_PATENTS_INVENTORS."</label>", $count = $profiles_patents_inventors_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_SKILL_COUNTS, $skills), "<label>"._AM_LINKEDINBOMB_DASHBOARD_SKILLHAS_PROFILE_PATENTS_OFFICE."</label>", $count = $profiles_patents_office_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_SKILL_COUNTS, $skills), "<label>"._AM_LINKEDINBOMB_DASHBOARD_SKILLHAS_PROFILE_PATENTS_STATUS."</label>", $count = $profiles_patents_status_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_SKILL_COUNTS, $skills), "<label>"._AM_LINKEDINBOMB_DASHBOARD_SKILLHAS_PROFILE_PATENTS."</label>", $count = $profiles_patents_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_SKILL_COUNTS, $skills), "<label>"._AM_LINKEDINBOMB_DASHBOARD_SKILLHAS_PROFILE_PHONE."</label>", $count = $profiles_phones_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_SKILL_COUNTS, $skills), "<label>"._AM_LINKEDINBOMB_DASHBOARD_SKILLHAS_PROFILE_POSITIONS."</label>", $count = $profiles_positions_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_SKILL_COUNTS, $skills), "<label>"._AM_LINKEDINBOMB_DASHBOARD_SKILLHAS_PROFILE_PROVIDER."</label>", $count = $profiles_providers_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_SKILL_COUNTS, $skills), "<label>"._AM_LINKEDINBOMB_DASHBOARD_SKILLHAS_PROFILE_PUBLICATIONS_AUTHORS."</label>", $count = $profiles_publications_authors_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_SKILL_COUNTS, $skills), "<label>"._AM_LINKEDINBOMB_DASHBOARD_SKILLHAS_PROFILE_PUBLICATIONS."</label>", $count = $profiles_publications_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_SKILL_COUNTS, $skills), "<label>"._AM_LINKEDINBOMB_DASHBOARD_SKILLHAS_PROFILE_RECOMMENDATIONS."</label>", $count = $profiles_recommendations_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_SKILL_COUNTS, $skills), "<label>"._AM_LINKEDINBOMB_DASHBOARD_SKILLHAS_PROFILE_SKILLS."</label>", $count = $profiles_skills_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_SKILL_COUNTS, $skills), "<label>"._AM_LINKEDINBOMB_DASHBOARD_SKILLHAS_PROFILE_VOLUNTEER."</label>", $count = $profiles_volunteer_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
				    $indexAdmin->addInfoBoxLine(sprintf(_AM_LINKEDINBOMB_SKILL_COUNTS, $skills), "<label>"._AM_LINKEDINBOMB_DASHBOARD_SKILLHAS_PROFILE."</label>", $count = $profiles_handler->getCount(new Criteria('profile_id', '('.implode(',', $ids).')', 'IN')), ($count>0?'Green':'Red'));
		    	}
		    }
		    */
		    echo $indexAdmin->renderIndex();	
			
			break;	
	}
	
	xoops_cp_footer();
?>