<?php
/*##################################################
 *                      BugtrackerRoadmapListController.class.php
 *                            -------------------
 *   begin                : November 13, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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
		
		$this->build_view();
		
		return $this->build_response($this->view);
	}

	private function build_view()
	{
		$request = AppContext::get_request();
		
		//Configuration load
		$config = BugtrackerConfig::load();
		$authorizations = $config->get_authorizations();
		$items_per_page = $config->get_items_per_page();
		$roadmap_activated = $config->get_roadmap_activated();
		$progress_bar_activated = $config->get_progress_bar_activated();
		$comments_activated = $config->get_comments_activated();
		$cat_in_title_activated = $config->get_cat_in_title_activated();
		$types = $config->get_types();
		$versions = $config->get_versions();
		$categories = $config->get_categories();
		$severities = $config->get_severities();
		$rejected_bug_color = $config->get_rejected_bug_color();
		$fixed_bug_color = $config->get_fixed_bug_color();
		
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
		
		//Bugs number
		$nbr_bugs = BugtrackerService::count("WHERE fixed_in = " . $roadmap_version . ($roadmap_status != 'all' ? " AND status = '" . $roadmap_status . "'" : ""));
		
		$pagination = new BugtrackerListPagination($current_page, $nbr_bugs);
		$pagination->set_url(BugtrackerUrlBuilder::roadmap($r_version . '/' . $roadmap_status . '/' . $field . '/' . $sort . '/%d')->absolute());
		
		$bugs_colspan = 5;
		
		if ($comments_activated == true) $bugs_colspan = $bugs_colspan + 1;
		if ($display_severities == true) $bugs_colspan = $bugs_colspan + 1;
		
		$limit_page = $current_page > 0 ? $current_page : 1;
		$limit_page = (($limit_page - 1) * $items_per_page);
		
		$displayed_severities = PersistenceContext::get_querier()->select("SELECT severity
		FROM (SELECT severity FROM " . BugtrackerSetup::$bugtracker_table . "
		WHERE fixed_in = " . $roadmap_version . "
		" . ($roadmap_status != 'all' ? "AND status = '" . $roadmap_status . "'" : "") . "
		ORDER BY " . $field_bdd . " " . $mode . "
		LIMIT ". $items_per_page ." OFFSET :start_limit) as b
		GROUP BY severity",
			array(
				'start_limit' => $limit_page
			), SelectQueryResult::FETCH_ASSOC
		);
		
		$this->view->put_all(array(
			'C_BUGS'					=> (int)$nbr_bugs,
			'C_DISPLAY_SEVERITIES'		=> $display_severities,
			'C_DISPLAY_VERSIONS'		=> $display_versions,
			'C_COMMENTS_ACTIVATED'		=> ($comments_activated == true) ? true : false,
			'PAGINATION' 				=> $pagination->display()->render(),
			'BUGS_COLSPAN'				=> $bugs_colspan,
			'SELECT_VERSION'			=> $this->build_form($versions[$roadmap_version], $roadmap_status, $nbr_bugs)->display(),
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
		
		$result = PersistenceContext::get_querier()->select("SELECT b.*, com.number_comments
		FROM " . BugtrackerSetup::$bugtracker_table . " b
		LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = b.id AND com.module_id = 'bugtracker'
		WHERE fixed_in = " . $roadmap_version . "
		" . ($roadmap_status != 'all' ? "AND b.status = '" . $roadmap_status . "'" : "") . "
		ORDER BY " . $field_bdd . " " . $mode . "
		LIMIT ". $items_per_page ." OFFSET :start_limit",
			array(
				'start_limit' => $limit_page
			), SelectQueryResult::FETCH_ASSOC
		);
		
		while ($row = $result->fetch())
		{
			//Comments number
			$nbr_coms = $row['number_comments'];
			
			$this->view->assign_block_vars('bug', array(
				'C_FIXED'			=> $row['status'] == 'fixed' ? true : false,
				'ID'				=> $row['id'],
				'TITLE'				=> ($cat_in_title_activated == true && $display_categories) ? '[' . $categories[$row['category']] . '] ' . $row['title'] : $row['title'],
				'SEVERITY'			=> (!empty($row['severity']) && isset($severities[$row['severity']])) ? stripslashes($severities[$row['severity']]['name']) : $this->lang['bugs.notice.none'],
				'LINE_COLOR' 		=> (!empty($row['severity']) && isset($severities[$row['severity']])) ? 'style="background-color:' . stripslashes($severities[$row['severity']]['color']) . ';"' : '',
				'INFOS'				=> ($progress_bar_activated && $row['progress'] ? '<span class="progressBar progress' . $row['progress'] . '">' . $row['progress'] . '%</span><br/>' : '') . $this->lang['bugs.labels.fields.status'] . ' : ' . $this->lang['bugs.status.' . $row['status']] . ($comments_activated == true ? '<br /><a href="' . BugtrackerUrlBuilder::detail($row['id'] . '/#comments_list')->absolute() . '">' . (empty($nbr_coms) ? 0 : $nbr_coms) . ' ' . ($nbr_coms <= 1 ? LangLoader::get_message('comment', 'comments-common') : LangLoader::get_message('comments', 'comments-common')) . '</a>' : ''),
				'STATUS'			=> $this->lang['bugs.status.' . $row['status']],
				'DATE' 				=> !empty($row['fix_date']) ? gmdate_format($config->get_date_form(), $row['fix_date']) : $this->lang['bugs.labels.not_yet_fixed'],
				'LINK_BUG_DETAIL'	=> BugtrackerUrlBuilder::detail($row['id'] . '/' . Url::encode_rewrite($row['title']))->absolute(),
				'LINK_BUG_HISTORY'	=> BugtrackerUrlBuilder::history($row['id'])->absolute()
			));
		}
		
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
		$this->lang = LangLoader::get('bugtracker_common', 'bugtracker');
		$this->view = new FileTemplate('bugtracker/BugtrackerRoadmapListController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function check_page_exists()
	{
		if (BugtrackerConfig::load()->get_roadmap_activated() == false)
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
	
	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view();
		return BugtrackerViews::build_body_view($object->view, 'roadmap');
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
				$errstr = sprintf($this->lang['bugs.success.add'], $bug_id);
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
