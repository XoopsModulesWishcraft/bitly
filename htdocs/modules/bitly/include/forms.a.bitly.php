<?php

	function linkedinbomb_get_email() {
		
		xoops_loadLanguage('forms', 'linkedinbomb');
		
		$sform = new XoopsThemeForm(_FRM_LINKEDIN_FORM_GET_EMAIL, 'email', 'getemail.php', 'post');
		
		if (empty($id)) $id = '0';
		
		$ele = array();	
		$ele['op'] = new XoopsFormHidden('op', 'email');
		$ele['fct'] = new XoopsFormHidden('fct', 'save');
		$ele['id'] = new XoopsFormHidden('id', $id);
		
		$ele['email'] = new XoopsFormText(_FRM_LINKEDIN_FORM_EMAIL, 'email', 26,128);
		$ele['email']->setDescription(_FRM_LINKEDIN_FORM_DESC_EMAIL);
		
		$ele['submit'] = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
		
		$required = array('email');
		
		foreach($ele as $id => $obj)			
			if (in_array($id, $required))
				$sform->addElement($ele[$id], true);			
			else
				$sform->addElement($ele[$id], false);
		
		return $sform->render();
		
	}	
	

	function linkedinbomb_filter_form($country_id, $locations_id, $skill_ids) {
		
		xoops_loadLanguage('forms', 'linkedinbomb');
		$ele = array();
		$sform = new XoopsThemeForm(_FRM_LINKEDINBOMB_FORM_FILTER, 'filterform', $_SERVER['PHP_SELF'], 'post');
		
		if (empty($id)) $id = '0';
		
		$ele['op'] = new XoopsFormHidden('op', 'filter');
		$ele['fct'] = new XoopsFormHidden('fct', 'set');
		$ele['id'] = new XoopsFormHidden('id', $id);

		if ($locations_id>0) {
			$profiles_handler = xoops_getmodulehandler('profiles', 'linkedinbomb');
			$criteria = new CriteriaCompo();
			foreach($profiles_handler->getIDs(new Criteria('location_id', $locations_id)) as $id) {
				$criteria->add(new Criteria('profile_ids', '%"'.$id.'"%', "LIKE"), 'OR');
			}
		} else {
			$criteria = new Criteria('skill_id', 0, '<>');
		}
		
		$sform->addElement(new LinkedinbombFormCheckBoxSkills(_FRM_LINKEDIN_FORM_FILTER_SKILLS, 'filter_skill_ids[]', $skill_ids, '&nbsp', $criteria));
		$sform->addElement(new LinkedinbombFormSelectLocations(_FRM_LINKEDIN_FORM_FILTER_LOCATION_ID, 'filter_location_id', $locations_id, 1, false, NULL, $country_id));
		$sform->addElement(new LinkedinbombFormSelectCountries(_FRM_LINKEDIN_FORM_FILTER_COUNTRIES_ID, 'filter_country_id', $country_id, 1, false));
		
		$sform->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
		
		return $sform->render();
		
	}
?>