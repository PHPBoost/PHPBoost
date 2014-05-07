<?php
/*##################################################
 *                      BugtrackerRoadmapListController.class.php
 *                            -------------------
 *   begin                : November 13, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class BugtrackerRoadmapListController extends ModuleController
{
	private $lang;
	private $view;
	
	private $config;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->check_page_exists();
		
		$this->check_authorizations();
		
		$this->build_view($request);
		
		return $this->build_response($this->view);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'bugtracker');
		$this->view = new FileTemplate('bugtracker/BugtrackerRoadmapListController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = BugtrackerConfig::load();
	}
	
	private function check_page_exists()
	{
		if (!$this->config->is_roadmap_enabled())
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function check_authorizations()
	{
		if (!BugtrackerAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function build_view($request)
	{
		$severities = $this->config->get_severities();
		$versions = array_reverse($this->config->get_versions(), true);
		
		if (!empty($versions))
		{
			$r_version = $request->get_value('version', Url::encode_rewrite($versions[key($versions)]['name']));
			
			$roadmap_version = $request->get_int('id_version', 0);
			$roadmap_status = $request->get_value('status', 'all');
			
			$field = $request->get_value('field', 'date');
			$sort = $request->get_value('sort', 'desc');
			$current_page = $request->get_int('page', 1);
			
			$mode = ($sort == 'top') ? 'ASC' : 'DESC';
			
			switch ($field)
			{
				case 'id' :
					$field_bdd = 'id';
					break;
				case 'title' :
					$field_bdd = 'title';
					break;
				case 'severity' :
					$field_bdd = 'severity';
					break;
				case 'status' :
					$field_bdd = 'status';
					break;
				default :
					$field_bdd = 'fix_date';
					break;
			}
			
			$stats_cache = BugtrackerStatsCache::load();
			$bugs_number = $stats_cache->get_bugs_number_per_version($roadmap_version);
			
			$pagination = $this->get_pagination($bugs_number[$roadmap_status], $current_page, $r_version, $roadmap_status, $field, $sort);
			
			$result = PersistenceContext::get_querier()->select("SELECT b.*, member.*
			FROM " . BugtrackerSetup::$bugtracker_table . " b
			LEFT JOIN " . DB_TABLE_MEMBER . " member ON member.user_id = b.author_id AND member.user_aprob = 1
			WHERE fixed_in = " . $roadmap_version . "
			" . ($roadmap_status != 'all' ? ($roadmap_status == Bug::IN_PROGRESS ? "AND (b.status = '" . Bug::IN_PROGRESS . "' OR b.status = '" . Bug::REOPEN . "')" : "AND b.status = '" . Bug::FIXED . "'") : "") . "
			ORDER BY " . $field_bdd . " " . $mode . "
			LIMIT :number_items_per_page OFFSET :display_from",
				array(
					'number_items_per_page' => $pagination->get_number_items_per_page(),
					'display_from' => $pagination->get_display_from()
				)
			);
			
			$displayed_severities = array();
			
			while ($row = $result->fetch())
			{
				$bug = new Bug();
				$bug->set_properties($row);
				
				if (!in_array($bug->get_severity(), $displayed_severities)) $displayed_severities[] = $bug->get_severity();
				
				$this->view->assign_block_vars('bug', array_merge($bug->get_array_tpl_vars(), array(
					'C_LINE_COLOR'	=> !empty($row['severity']) && isset($severities[$row['severity']]),
					'LINE_COLOR' 	=> stripslashes($severities[$row['severity']]['color'])
				)));
			}
			$result->dispose();
			
			$bugs_colspan = 4;
			if ($this->config->is_type_column_displayed()) $bugs_colspan++;
			if ($this->config->is_category_column_displayed()) $bugs_colspan++;
			if ($this->config->is_priority_column_displayed()) $bugs_colspan++;
			if ($this->config->is_detected_in_column_displayed()) $bugs_colspan++;
			
			$this->view->put_all(array(
				'C_VERSIONS_AVAILABLE'			=> true,
				'C_BUGS'						=> $bugs_number[$roadmap_status],
				'C_STATUS_IN_PROGRESS'			=> $roadmap_status == Bug::IN_PROGRESS,
				'C_DISPLAY_TYPE_COLUMN'			=> $this->config->is_type_column_displayed(),
				'C_DISPLAY_CATEGORY_COLUMN'		=> $this->config->is_category_column_displayed(),
				'C_DISPLAY_PRIORITY_COLUMN'		=> $this->config->is_priority_column_displayed(),
				'C_DISPLAY_DETECTED_IN_COLUMN'	=> $this->config->is_detected_in_column_displayed(),
				'C_PAGINATION'					=> $pagination->has_several_pages(),
				'PAGINATION' 					=> $pagination->display(),
				'BUGS_COLSPAN' 					=> $bugs_colspan,
				'SELECT_VERSION'				=> $this->build_form(isset($versions[$roadmap_version]) ? $roadmap_version . '-' . Url::encode_rewrite($versions[$roadmap_version]['name']) : '', $roadmap_status, (int)$bugs_number[$roadmap_status])->display(),
				'LEGEND'						=> BugtrackerViews::build_legend($displayed_severities, 'roadmap'),
				'LINK_BUG_ID_TOP' 				=> BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/id/top/'. $current_page)->rel(),
				'LINK_BUG_ID_BOTTOM' 			=> BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/id/bottom/'. $current_page)->rel(),
				'LINK_BUG_TITLE_TOP' 			=> BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/title/top/'. $current_page)->rel(),
				'LINK_BUG_TITLE_BOTTOM' 		=> BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/title/bottom/'. $current_page)->rel(),
				'LINK_BUG_SEVERITY_TOP' 		=> BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/severity/top/'. $current_page)->rel(),
				'LINK_BUG_SEVERITY_BOTTOM'		=> BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/severity/bottom/'. $current_page)->rel(),
				'LINK_BUG_STATUS_TOP'			=> BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/status/top/'. $current_page)->rel(),
				'LINK_BUG_STATUS_BOTTOM'		=> BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/status/bottom/'. $current_page)->rel(),
				'LINK_BUG_DATE_TOP' 			=> BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/date/top/'. $current_page)->rel(),
				'LINK_BUG_DATE_BOTTOM'			=> BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/date/bottom/'. $current_page)->rel()
			));
		}
		
		return $this->view;
	}
	
	private function build_form($requested_version, $requested_status, $nbr_bugs)
	{
		$form = new HTMLForm('version');
		
		$fieldset = new FormFieldsetHorizontal('choose-version');
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('roadmap_version', $this->lang['titles.choose_version'], $requested_version, $this->build_select_versions(),
			array('events' => array('change' => 'document.location = "'. BugtrackerUrlBuilder::roadmap()->rel() .'" + HTMLForms.getField("roadmap_version").getValue()' . ($requested_status != 'all' ? ' + "/" + HTMLForms.getField("status").getValue()' : '') . ';')
		)));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('status', $this->lang['labels.fields.status'], $requested_status, $this->build_select_status(),
			array('events' => array('change' => 'document.location = "'. BugtrackerUrlBuilder::roadmap($requested_version)->rel() .'" + "/" + HTMLForms.getField("status").getValue();')
		)));
		
		$release_date = !empty($requested_version['release_date']) && is_numeric($requested_version['release_date']) ? new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $requested_version['release_date']) : null;
		
		$fieldset = new FormFieldsetHorizontal('informations');
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldHTML('informations', (!empty($release_date) ? $this->lang['labels.fields.version_release_date'] . ' : <b>' . $release_date->format(Date::FORMAT_DAY_MONTH_YEAR) . '</b><br />' : '') . ($requested_status == Bug::IN_PROGRESS ? $this->lang['labels.number_in_progress'] : $this->lang['labels.number_fixed']) . ' : ' . $nbr_bugs));
		
		return $form;
	}
	
	private function build_select_versions()
	{
		$versions = array_reverse($this->config->get_versions(), true);
		
		$array_versions = array();
		//Versions
		foreach ($versions as $key => $version)
		{
			$array_versions[] = new FormFieldSelectChoiceOption(stripslashes($version['name']), $key . '-' . Url::encode_rewrite($version['name']));
		}
		return $array_versions;
	}
	
	private function build_select_status()
	{
		$array_status[] = new FormFieldSelectChoiceOption($this->lang['status.' . Bug::FIXED] . ' / ' . $this->lang['status.' . Bug::IN_PROGRESS], 'all');
		$array_status[] = new FormFieldSelectChoiceOption($this->lang['status.' . Bug::FIXED], Bug::FIXED);
		$array_status[] = new FormFieldSelectChoiceOption($this->lang['status.' . Bug::IN_PROGRESS], Bug::IN_PROGRESS);
		
		return $array_status;
	}
	
	private function get_pagination($bugs_number, $page, $roadmap_version, $roadmap_status, $field, $sort)
	{
		$pagination = new ModulePagination($page, $bugs_number, (int)$this->config->get_items_per_page());
		$pagination->set_url(BugtrackerUrlBuilder::roadmap($roadmap_version . '/' . $roadmap_status . '/' . $field . '/' . $sort . '/%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
	
	private function build_response(View $view)
	{
		$versions = array_reverse($this->config->get_versions(), true);
		
		$request = AppContext::get_request();
		$success = $request->get_value('success', '');
		$bug_id = $request->get_int('id', 0);
		$roadmap_version = $request->get_value('version', Url::encode_rewrite($versions[key($versions)]['name']));
		$roadmap_status = $request->get_value('status', 'all');
		
		$field = $request->get_value('field', 'date');
		$sort = $request->get_value('sort', 'desc');
		$page = $request->get_int('page', 1);
		
		$body_view = BugtrackerViews::build_body_view($view, 'roadmap');
		
		//Success messages
		switch ($success)
		{
			case 'add':
				$errstr = StringVars::replace_vars($this->lang['success.add'], array('id' => $bug_id));
				break;
			default:
				$errstr = '';
		}
		if (!empty($errstr))
			$body_view->put('MSG', MessageHelper::display($errstr, E_USER_SUCCESS, 5));
		
		$response = new SiteDisplayResponse($body_view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['titles.roadmap']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(BugtrackerUrlBuilder::roadmap($roadmap_version . '/' . $roadmap_status . '/' . $field . '/' . $sort . '/' . $page));
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], BugtrackerUrlBuilder::home());
		$breadcrumb->add($this->lang['titles.roadmap'], BugtrackerUrlBuilder::roadmap($roadmap_version . '/' . $roadmap_status . '/' . $field . '/' . $sort . '/' . $page));
		
		return $response;
	}
}
?>
