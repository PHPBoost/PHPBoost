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
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->check_page_exists();
		
		$this->check_authorizations();
		
		$this->build_view($request);
		
		return $this->build_response($this->view);
	}

	private function build_view($request)
	{
		//Configuration load
		$config = BugtrackerConfig::load();
		$types = $config->get_types();
		$versions = $config->get_versions();
		$categories = $config->get_categories();
		$severities = $config->get_severities();
		
		$display_types = sizeof($types) > 1 ? true : false;
		$display_categories = sizeof($categories) > 1 ? true : false;
		$display_versions = sizeof($versions) > 1 ? true : false;
		$display_severities = sizeof($severities) > 1 ? true : false;
		
		//Reverse versions array to put the newest one at first
		$versions = array_reverse($versions, true);
		
		$nbr_versions = array_keys($versions);
		
		$r_version = $request->get_value('version', Url::encode_rewrite($versions[key($versions)]['name']));
		
		$roadmap_version = key($versions);
		foreach ($versions as $id => $version)
		{
			if ($r_version == Url::encode_rewrite($versions[$id]['name']))
				$roadmap_version = $id;
		}
		
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
		
		$result = PersistenceContext::get_querier()->select("SELECT b.*, com.number_comments
		FROM " . BugtrackerSetup::$bugtracker_table . " b
		LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = b.id AND com.module_id = 'bugtracker'
		WHERE fixed_in = " . $roadmap_version . "
		" . ($roadmap_status != 'all' ? ($roadmap_status == 'in_progress' ? "AND (b.status = 'in_progress' OR b.status = 'reopen')" : "AND b.status = 'fixed'") : "") . "
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
			if (!in_array($row['severity'], $displayed_severities)) $displayed_severities[] = $row['severity'];
			
			$this->view->assign_block_vars('bug', array(
				'C_FIXED'			=> $row['status'] == Bug::FIXED,
				'ID'				=> $row['id'],
				'TITLE'				=> ($config->is_cat_in_title_displayed() && $display_categories) ? '[' . $categories[$row['category']] . '] ' . $row['title'] : $row['title'],
				'LINE_COLOR' 		=> (!empty($row['severity']) && isset($severities[$row['severity']])) ? 'style="background-color:' . stripslashes($severities[$row['severity']]['color']) . ';"' : '',
				'INFOS'				=> ($config->is_progress_bar_displayed() && $row['progress'] ? '<span class="progressBar progress' . $row['progress'] . '">' . $row['progress'] . '%</span><br/>' : '') . $this->lang['bugs.labels.fields.status'] . ' : ' . $this->lang['bugs.status.' . $row['status']] . ($config->are_comments_enabled() ? '<br /><a href="' . BugtrackerUrlBuilder::detail($row['id'] . '/#comments_list')->absolute() . '">' . (int) $row['number_comments'] . ' ' . ($row['number_comments'] <= 1 ? LangLoader::get_message('comment', 'comments-common') : LangLoader::get_message('comments', 'comments-common')) . '</a>' : ''),
				'STATUS'			=> $this->lang['bugs.status.' . $row['status']],
				'DATE' 				=> !empty($row['fix_date']) ? gmdate_format($config->get_date_form(), $row['fix_date']) : $this->lang['bugs.labels.not_yet_fixed'],
				'LINK_BUG_DETAIL'	=> BugtrackerUrlBuilder::detail($row['id'] . '/' . Url::encode_rewrite($row['title']))->absolute()
			));
		}
		
		$bugs_colspan = 5;
		if ($config->are_comments_enabled()) $bugs_colspan = $bugs_colspan + 1;
		if ($display_severities) $bugs_colspan = $bugs_colspan + 1;
		
		$this->view->put_all(array(
			'C_BUGS'					=> $bugs_number[$roadmap_status],
			'C_PAGINATION'				=> $bugs_number[$roadmap_status] > $pagination->get_number_items_per_page(),
			'PAGINATION' 				=> $pagination->display(),
			'BUGS_COLSPAN'				=> $bugs_colspan,
			'SELECT_VERSION'			=> $this->build_form($versions[$roadmap_version], $roadmap_status, (int)$bugs_number[$roadmap_status])->display(),
			'L_NO_BUG'					=> $roadmap_status == 'in_progress' ? $this->lang['bugs.notice.no_bug_in_progress'] : $this->lang['bugs.notice.no_bug_fixed'],
			'PROGRESS_BAR'				=> BugtrackerViews::build_progress_bar(),
			'LEGEND'					=> BugtrackerViews::build_legend($displayed_severities, 'roadmap'),
			'LINK_BUG_ID_TOP' 			=> BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/id/top/'. $current_page)->absolute(),
			'LINK_BUG_ID_BOTTOM' 		=> BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/id/bottom/'. $current_page)->absolute(),
			'LINK_BUG_TITLE_TOP' 		=> BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/title/top/'. $current_page)->absolute(),
			'LINK_BUG_TITLE_BOTTOM' 	=> BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/title/bottom/'. $current_page)->absolute(),
			'LINK_BUG_SEVERITY_TOP' 	=> BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/severity/top/'. $current_page)->absolute(),
			'LINK_BUG_SEVERITY_BOTTOM'	=> BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/severity/bottom/'. $current_page)->absolute(),
			'LINK_BUG_STATUS_TOP'		=> BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/status/top/'. $current_page)->absolute(),
			'LINK_BUG_STATUS_BOTTOM'	=> BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/status/bottom/'. $current_page)->absolute(),
			'LINK_BUG_DATE_TOP' 		=> BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/date/top/'. $current_page)->absolute(),
			'LINK_BUG_DATE_BOTTOM'		=> BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/date/bottom/'. $current_page)->absolute()
		));
		
		return $this->view;
	}
	
	private function build_form($requested_version, $requested_status, $nbr_bugs)
	{
		$form = new HTMLForm('version');
		
		$fieldset = new FormFieldsetHorizontal('choose-version');
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('roadmap_version', $this->lang['bugs.titles.choose_version'], Url::encode_rewrite($requested_version['name']), $this->build_select_versions(),
			array('events' => array('change' => 'document.location = "'. BugtrackerUrlBuilder::roadmap()->absolute() .'" + HTMLForms.getField("roadmap_version").getValue() + "/" + HTMLForms.getField("status").getValue();')
		)));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('status', $this->lang['bugs.labels.fields.status'], $requested_status, $this->build_select_status(),
			array('events' => array('change' => 'document.location = "'. BugtrackerUrlBuilder::roadmap(Url::encode_rewrite($requested_version['name']))->absolute() .'" + "/" + HTMLForms.getField("status").getValue();')
		)));
		
		$fieldset = new FormFieldsetHorizontal('informations');
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldHTML('informations', ($requested_version['release_date'] != '00/00/0000' ? $this->lang['bugs.labels.fields.version_release_date'] . ' : <b>' . $requested_version['release_date'] . '</b><br />' : '') . ($requested_status == 'in_progress' ? $this->lang['bugs.labels.number_in_progress'] : $this->lang['bugs.labels.number_fixed']) . ' : ' . $nbr_bugs));
		
		return $form;
	}
	
	private function build_select_versions()
	{
		$versions = BugtrackerConfig::load()->get_versions();
		//Reverse versions array to put the newest one at first
		$versions = array_reverse($versions, true);
		
		$array_versions = array();
		//Versions
		foreach ($versions as $key => $version)
		{
			$array_versions[] = new FormFieldSelectChoiceOption(stripslashes($version['name']), Url::encode_rewrite($version['name']));
		}
		return $array_versions;
	}
	
	private function build_select_status()
	{
		$array_status[] = new FormFieldSelectChoiceOption($this->lang['bugs.status.' . Bug::FIXED] . ' / ' . $this->lang['bugs.status.' . Bug::IN_PROGRESS], 'all');
		$array_status[] = new FormFieldSelectChoiceOption($this->lang['bugs.status.' . Bug::FIXED], Bug::FIXED);
		$array_status[] = new FormFieldSelectChoiceOption($this->lang['bugs.status.' . Bug::IN_PROGRESS], Bug::IN_PROGRESS);
		
		return $array_status;
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'bugtracker');
		$this->view = new FileTemplate('bugtracker/BugtrackerRoadmapListController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function check_page_exists()
	{
		if (!BugtrackerConfig::load()->is_roadmap_enabled())
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
	
	private function get_pagination($bugs_number, $page, $roadmap_version, $roadmap_status, $field, $sort)
	{
		$pagination = new ModulePagination($page, $bugs_number, (int)BugtrackerConfig::load()->get_items_per_page());
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
		$request = AppContext::get_request();
		$success = $request->get_value('success', '');
		$bug_id = $request->get_int('id', 0);
		
		$body_view = BugtrackerViews::build_body_view($view, 'roadmap');
		
		//Success messages
		switch ($success)
		{
			case 'add':
				$errstr = StringVars::replace_vars($this->lang['bugs.success.add'], array('id' => $bug_id));
				break;
			default:
				$errstr = '';
		}
		if (!empty($errstr))
			$body_view->put('MSG', MessageHelper::display($errstr, E_USER_SUCCESS, 5));
		
		$response = new BugtrackerDisplayResponse();
		$response->add_breadcrumb_link($this->lang['bugs.module_title'], BugtrackerUrlBuilder::home());
		$response->add_breadcrumb_link($this->lang['bugs.titles.roadmap'], BugtrackerUrlBuilder::roadmap());
		$response->set_page_title($this->lang['bugs.titles.roadmap']);
		
		return $response->display($body_view);
	}
}
?>
