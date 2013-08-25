<?php
/*##################################################
 *                          BugtrackerViews.class.php
 *                            -------------------
 *   begin                : April 29, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
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

 /**
 * @author Julien BRISWALTER <julien.briswalter@gmail.com>
 */
class BugtrackerViews
{
	public static function build_body_view(View $view, $current_page, $bug_id = 0)
	{
		$lang = LangLoader::get('bugtracker_common', 'bugtracker');
		
		$request = AppContext::get_request();
		$back_page = $request->get_value('back_page', '');
		$page = $request->get_int('page', 1);
		$back_filter = $request->get_value('back_filter', '');
		$filter = $request->get_value('filter', '');
		$filter_id = $request->get_value('filter_id', '');
		
		$config = BugtrackerConfig::load();
		$roadmap_activated = $config->get_roadmap_activated();
		$stats_activated = $config->get_stats_activated();
		$versions = $config->get_versions();
		$nbr_versions = array_keys($versions);
		
		$body_view = new FileTemplate('bugtracker/BugtrackerBody.tpl');
		$body_view->add_lang($lang);
		$body_view->put_all(array(
			'TEMPLATE'				=> $view,
			'C_ADD' 				=> BugtrackerAuthorizationsService::check_authorizations()->write() ? true : false,
			'C_ROADMAP_ACTIVATED' 	=> ($roadmap_activated && !empty($nbr_versions)) ? true : false,
			'C_STATS_ACTIVATED' 	=> $stats_activated ? true : false,
			'C_DETAIL_PAGE'			=> $current_page == 'detail' ? true : false,
			'C_HISTORY_PAGE'		=> $current_page == 'history' ? true : false,
			'C_ADD_PAGE'			=> $current_page == 'add' ? true : false,
			'C_EDIT_PAGE'			=> $current_page == 'edit' ? true : false,
			'CLASS_BUG_UNSOLVED'	=> $current_page == 'unsolved' ? 'bt_current' : 'bt_no_current',
			'CLASS_BUG_SOLVED'		=> $current_page == 'solved' ? 'bt_current' : 'bt_no_current',
			'CLASS_BUG_ROADMAP'		=> $current_page == 'roadmap' ? 'bt_current' : 'bt_no_current',
			'CLASS_BUG_STATS'		=> $current_page == 'stats' ? 'bt_current' : 'bt_no_current',
			'LINK_BUG_UNSOLVED'		=> BugtrackerUrlBuilder::unsolved()->absolute(),
			'LINK_BUG_SOLVED'		=> BugtrackerUrlBuilder::solved()->absolute(),
			'LINK_BUG_ROADMAP'		=> BugtrackerUrlBuilder::roadmap()->absolute(),
			'LINK_BUG_STATS'		=> BugtrackerUrlBuilder::stats()->absolute(),
			'LINK_BUG_DETAIL'		=> $current_page == 'detail' ? BugtrackerUrlBuilder::detail($bug_id)->absolute() : '',
			'LINK_BUG_HISTORY'		=> $current_page == 'history' ? BugtrackerUrlBuilder::history($bug_id)->absolute() : '',
			'LINK_BUG_ADD'			=> BugtrackerUrlBuilder::add((in_array($current_page, array('add', 'edit')) ? (!empty($back_page) ? $back_page . '/' . $page . (!empty($back_filter) ? '/' . $back_filter . '/' . $filter_id : '') : '') : (!in_array($current_page, array('detail', 'history')) ? $current_page : '')) . (in_array($current_page, array('unsolved', 'solved', 'roadmap')) ? '/' . $page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : '') : ''))->absolute(),
			'LINK_BUG_EDIT'			=> $current_page == 'edit' ? BugtrackerUrlBuilder::edit(!empty($back_page) ? $bug_id . '/' . $back_page . '/' . $page . (!empty($back_filter) ? '/' . $back_filter . '/' . $filter_id : '') : $bug_id)->absolute() : '',
			'BUG_ID'				=> $bug_id
		));
		
		return $body_view;
	}
	
	public static function build_filters($current_page, $nbr_bugs = 0)
	{
		$lang = LangLoader::get('bugtracker_common', 'bugtracker');
		$object = new self();
		
		$request = AppContext::get_request();
		$page = $request->get_int('page', 1);
		$filter = $request->get_value('filter', '');
		$filter_id = $request->get_value('filter_id', '');
		
		if (!empty($filter) && empty($filter_id))
		{
			$filter = $filter_id = '';
		}
		
		$filters_tmp = $filters = !empty($filter) ? explode('-', $filter) : array();
		$filters_ids_tmp = $filters_ids = !empty($filter_id) ? explode('-', $filter_id) : array();
		
		$display_save_button = AppContext::get_current_user()->check_level(User::MEMBER_LEVEL) && sizeof($filters) >= 1 ? true : false;
		
		$config = BugtrackerConfig::load();
		$types = $config->get_types();
		$categories = $config->get_categories();
		$severities = $config->get_severities();
		$versions = $config->get_versions_detected();
		$all_versions = $config->get_versions();
		
		$display_types = sizeof($types) > 1 ? true : false;
		$display_categories = sizeof($categories) > 1 ? true : false;
		$display_severities = sizeof($severities) > 1 ? true : false;
		$display_versions = sizeof($versions) > 1 ? true : false;
		
		$filters_number = 1;
		if ($display_types == true) $filters_number = $filters_number + 1;
		if ($display_categories == true) $filters_number = $filters_number + 1;
		if ($display_severities == true) $filters_number = $filters_number + 1;
		if ($display_versions == true) $filters_number = $filters_number + 1;
		if (!empty($filters)) $filters_number = $filters_number + 1;
		
		$filters_view = new FileTemplate('bugtracker/BugtrackerFilter.tpl');
		$filters_view->add_lang($lang);
		
		$result = PersistenceContext::get_querier()->select("SELECT *
		FROM " . BugtrackerSetup::$bugtracker_users_filters_table . "
		WHERE page = :page AND user_id = :user_id",
			array(
				'page' => $current_page,
				'user_id' => AppContext::get_current_user()->get_id()
			), SelectQueryResult::FETCH_ASSOC
		);
		
		$saved_filters = false;
		while ($row = $result->fetch())
		{
			$row_filters_tmp = $row_filters = !empty($row['filters']) ? explode('-', $row['filters']) : array();
			$row_filters_ids_tmp = $row_filters_ids = !empty($row['filters_ids']) ? explode('-', $row['filters_ids']) : array();
			
			sort($filters_tmp, SORT_STRING);
			sort($row_filters_tmp, SORT_STRING);
			sort($filters_ids_tmp, SORT_STRING);
			sort($row_filters_ids_tmp, SORT_STRING);
			if (implode('-', $filters_tmp) == implode('-', $row_filters_tmp) && implode('-', $filters_ids_tmp) == implode('-', $row_filters_ids_tmp))
				$display_save_button = false;
			
			$filter_not_saved_value = '-------------';
			
			$filters_view->assign_block_vars('filters', array(
				'ID'					=> $row['id'],
				'FILTER'				=> '|	' . (in_array('type', $row_filters) && $row_filters_ids[array_search('type', $row_filters)] ? $types[$row_filters_ids[array_search('type', $row_filters)]] : $filter_not_saved_value) . '	|	' . (in_array('category', $row_filters) && $row_filters_ids[array_search('category', $row_filters)] ? $categories[$row_filters_ids[array_search('category', $row_filters)]] : $filter_not_saved_value) . '	|	' . (in_array('severity', $row_filters) && $row_filters_ids[array_search('severity', $row_filters)] ? $severities[$row_filters_ids[array_search('severity', $row_filters)]]['name'] : $filter_not_saved_value) . '	|	' . (in_array('status', $row_filters) && $row_filters_ids[array_search('status', $row_filters)] ? $lang['bugs.status.' . $row_filters_ids[array_search('status', $row_filters)]] : $filter_not_saved_value) . '	|	' . ($current_page == 'unsolved' ? (in_array('detected_in', $row_filters) && $row_filters_ids[array_search('detected_in', $row_filters)] ? $versions[$row_filters_ids[array_search('detected_in', $row_filters)]]['name'] : $filter_not_saved_value) : (in_array('fixed_in', $row_filters) && $row_filters_ids[array_search('fixed_in', $row_filters)] ? $all_versions[$row_filters_ids[array_search('fixed_in', $row_filters)]]['name'] : $filter_not_saved_value)) . '	|',
				'LINK_FILTER'			=> ($current_page == 'unsolved' ? BugtrackerUrlBuilder::unsolved('name/desc/1/' . $row['filters'] . '/' . $row['filters_ids'])->absolute() : BugtrackerUrlBuilder::solved('name/desc/1/' . $row['filters'] . '/' . $row['filters_ids'])->absolute()),
				'LINK_FILTER_DELETE'	=> BugtrackerUrlBuilder::delete_filter($row['id'] . '/' . $current_page . '/' . $page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : ''))->absolute()
			));
			$saved_filters = true;
		}
		
		$filters_view->put_all(array(
			'L_FILTERS'				=> $filters_number > 1 ? $lang['bugs.titles.filters'] : $lang['bugs.titles.filter'],
			'L_DELETE' 				=> LangLoader::get_message('delete', 'main'),
			'C_FILTER'				=> sizeof($filters) == 1 ? true : false,
			'C_FILTERS'				=> sizeof($filters) > 1 ? true : false,
			'C_DISPLAY_TYPES'		=> $display_types,
			'C_DISPLAY_CATEGORIES'	=> $display_categories,
			'C_DISPLAY_SEVERITIES'	=> $display_severities,
			'C_DISPLAY_VERSIONS'	=> $display_versions,
			'C_DISPLAY_SAVE_BUTTON'	=> $display_save_button,
			'C_SAVED_FILTERS'		=> $saved_filters,
			'FILTERS_NUMBER'		=> $filters_number,
			'BUGS_NUMBER'			=> $nbr_bugs,
			'LINK_FILTER_SAVE'		=> BugtrackerUrlBuilder::add_filter($current_page . '/' . $page . (!empty($filter) ? '/' . $filter . '/' . $filter_id : ''))->absolute(),
			'SELECT_TYPE'			=> $object->build_types_form($current_page,($filter == 'type') ? $filter_id : (in_array('type', $filters) ? $filters_ids[array_search('type', $filters)] : 0), $filters, $filters_ids)->display(),
			'SELECT_CATEGORY'		=> $object->build_categories_form($current_page, ($filter == 'category') ? $filter_id : (in_array('category', $filters) ? $filters_ids[array_search('category', $filters)] : 0), $filters, $filters_ids)->display(),
			'SELECT_SEVERITY'		=> $object->build_severities_form($current_page, ($filter == 'severity') ? $filter_id : (in_array('severity', $filters) ? $filters_ids[array_search('severity', $filters)] : 0), $filters, $filters_ids)->display(),
			'SELECT_STATUS'			=> $object->build_status_form($current_page, ($filter == 'status') ? $filter_id : (in_array('status', $filters) ? $filters_ids[array_search('status', $filters)] : 0), $filters, $filters_ids, $lang)->display(),
			'SELECT_VERSION'		=> $object->build_versions_form($current_page, ($current_page == 'unsolved' ? (($filter == 'detected_in') ? $filter_id : (in_array('detected_in', $filters) ? $filters_ids[array_search('detected_in', $filters)] : 0)) : (($filter == 'fixed_in') ? $filter_id : (in_array('fixed_in', $filters) ? $filters_ids[array_search('fixed_in', $filters)] : 0))), $filters, $filters_ids)->display()
		));
		
		return $filters_view;
	}
	
	public static function build_legend($list, $current_page)
	{
		$lang = LangLoader::get('bugtracker_common', 'bugtracker');
		
		$config = BugtrackerConfig::load();
		$severities = $config->get_severities();
		
		$legend_view = new FileTemplate('bugtracker/BugtrackerLegend.tpl');
		$legend_view->add_lang($lang);
		
		$legend_colspan = 0;
		
		while ($row = $list->fetch())
		{
			if (($current_page == 'solved') || (!empty($row['severity']) && isset($severities[$row['severity']])))
			{
				$legend_view->assign_block_vars('legend', array(
					'COLOR'	=> 'style="background-color:' . ($current_page == 'solved' ? ($row['status'] == 'fixed' ? $config->get_fixed_bug_color() : $config->get_rejected_bug_color()) : stripslashes($severities[$row['severity']]['color'])) . ';"',
					'NAME'	=> ($current_page == 'solved' ? $lang['bugs.status.' . $row['status']] : stripslashes($severities[$row['severity']]['name']))
				));
				
				$legend_colspan = $legend_colspan + 3;
			}
		}
		
		$legend_view->put_all(array(
			'LEGEND_COLSPAN'	=> $legend_colspan
		));
		
		return ($legend_colspan ? $legend_view : new StringTemplate(''));
	}
	
	public static function build_progress_bar()
	{
		return new FileTemplate('bugtracker/BugtrackerProgressBar.tpl');
	}
	
	private function build_types_form($current_page, $requested_type, $filters, $filters_ids)
	{
		if (in_array('type', $filters))
		{
			$key = array_search('type', $filters);
			unset($filters[$key]);
			unset($filters_ids[$key]);
		}
		
		$filter = implode('-', $filters);
		$filter_id = implode('-', $filters_ids);
		
		$form = new HTMLForm('type-form');
		$fieldset = new FormFieldsetHorizontal('filter-type');
		
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('filter_type', '', $requested_type, $this->build_select_types(),
			array('events' => array('change' => 'if (HTMLForms.getField("filter_type").getValue() > 0) {
				document.location = "'. ($current_page == 'unsolved' ? BugtrackerUrlBuilder::unsolved('name/desc/1/' . (!empty($filter) ? $filter . '-' : '') . 'type/' . (!empty($filter_id) ? $filter_id . '-' : ''))->absolute() : BugtrackerUrlBuilder::solved('name/desc/1/' . (!empty($filter) ? $filter . '-' : '') . 'type/' . (!empty($filter_id) ? $filter_id . '-' : ''))->absolute()) .'" + HTMLForms.getField("filter_type").getValue();
			} else {
				document.location = "'. ($current_page == 'unsolved' ? BugtrackerUrlBuilder::unsolved('name/desc/1/' . (!empty($filter) ? $filter : '') . '/' . (!empty($filter_id) ? $filter_id : ''))->absolute() : BugtrackerUrlBuilder::solved('name/desc/1/' . (!empty($filter) ? $filter : '') . '/' . (!empty($filter_id) ? $filter_id : ''))->absolute()) .'";
			}')
		)));
		
		return $form;
	}
	
	private function build_select_types()
	{
		$types = BugtrackerConfig::load()->get_types();
		
		$array_types = array();
		$array_types[] = new FormFieldSelectChoiceOption('', 0);
		foreach ($types as $key => $type)
		{
			$array_types[] = new FormFieldSelectChoiceOption(stripslashes($type), $key);
		}
		return $array_types;
	}
	
	private function build_categories_form($current_page, $requested_category, $filters, $filters_ids)
	{
		if (in_array('category', $filters))
		{
			$key = array_search('category', $filters);
			unset($filters[$key]);
			unset($filters_ids[$key]);
		}
		
		$filter = implode('-', $filters);
		$filter_id = implode('-', $filters_ids);
		
		$form = new HTMLForm('category-form');
		$fieldset = new FormFieldsetHorizontal('filter-category');
		
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('filter_category', '', $requested_category, $this->build_select_categories(),
			array('events' => array('change' => 'if (HTMLForms.getField("filter_category").getValue() > 0) {
				document.location = "'. ($current_page == 'unsolved' ? BugtrackerUrlBuilder::unsolved('name/desc/1/' . (!empty($filter) ? $filter . '-' : '') . 'category/' . (!empty($filter_id) ? $filter_id . '-' : ''))->absolute() : BugtrackerUrlBuilder::solved('name/desc/1/' . (!empty($filter) ? $filter . '-' : '') . 'category/' . (!empty($filter_id) ? $filter_id . '-' : ''))->absolute()) .'" + HTMLForms.getField("filter_category").getValue();
			} else {
				document.location = "'. ($current_page == 'unsolved' ? BugtrackerUrlBuilder::unsolved('name/desc/1/' . (!empty($filter) ? $filter : '') . '/' . (!empty($filter_id) ? $filter_id : ''))->absolute() : BugtrackerUrlBuilder::solved('name/desc/1/' . (!empty($filter) ? $filter : '') . '/' . (!empty($filter_id) ? $filter_id : ''))->absolute()) .'";
			}')
		)));
		
		return $form;
	}
	
	private function build_select_categories()
	{
		$categories = BugtrackerConfig::load()->get_categories();
		
		$array_categories = array();
		$array_categories[] = new FormFieldSelectChoiceOption('', 0);
		foreach ($categories as $key => $category)
		{
			$array_categories[] = new FormFieldSelectChoiceOption(stripslashes($category), $key);
		}
		return $array_categories;
	}
	
	private function build_severities_form($current_page, $requested_severity, $filters, $filters_ids)
	{
		if (in_array('severity', $filters))
		{
			$key = array_search('severity', $filters);
			unset($filters[$key]);
			unset($filters_ids[$key]);
		}
		
		$filter = implode('-', $filters);
		$filter_id = implode('-', $filters_ids);
		
		$form = new HTMLForm('severity-form');
		$fieldset = new FormFieldsetHorizontal('filter-severity');
		
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('filter_severity', '', $requested_severity, $this->build_select_severities(),
			array('events' => array('change' => 'if (HTMLForms.getField("filter_severity").getValue() > 0) {
				document.location = "'. ($current_page == 'unsolved' ? BugtrackerUrlBuilder::unsolved('name/desc/1/' . (!empty($filter) ? $filter . '-' : '') . 'severity/' . (!empty($filter_id) ? $filter_id . '-' : ''))->absolute() : BugtrackerUrlBuilder::solved('name/desc/1/' . (!empty($filter) ? $filter . '-' : '') . 'severity/' . (!empty($filter_id) ? $filter_id . '-' : ''))->absolute()) .'" + HTMLForms.getField("filter_severity").getValue();
			} else {
				document.location = "'. ($current_page == 'unsolved' ? BugtrackerUrlBuilder::unsolved('name/desc/1/' . (!empty($filter) ? $filter : '') . '/' . (!empty($filter_id) ? $filter_id : ''))->absolute() : BugtrackerUrlBuilder::solved('name/desc/1/' . (!empty($filter) ? $filter : '') . '/' . (!empty($filter_id) ? $filter_id : ''))->absolute()) .'";
			}')
		)));
		
		return $form;
	}
	
	private function build_select_severities()
	{
		$severities = BugtrackerConfig::load()->get_severities();
		
		$array_categories = array();
		$array_severities[] = new FormFieldSelectChoiceOption('', 0);
		foreach ($severities as $key => $severity)
		{
			$array_severities[] = new FormFieldSelectChoiceOption(stripslashes($severity['name']), $key);
		}
		return $array_severities;
	}
	
	private function build_status_form($current_page, $requested_status, $filters, $filters_ids, $lang)
	{
		if (in_array('status', $filters))
		{
			$key = array_search('status', $filters);
			unset($filters[$key]);
			unset($filters_ids[$key]);
		}
		
		$filter = implode('-', $filters);
		$filter_id = implode('-', $filters_ids);
		
		$form = new HTMLForm('status-form');
		$fieldset = new FormFieldsetHorizontal('filter-status');
		
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('filter_status', '', $requested_status, $this->build_select_status($current_page, $lang),
			array('events' => array('change' => 'if (HTMLForms.getField("filter_status").getValue()) {
				document.location = "'. ($current_page == 'unsolved' ? BugtrackerUrlBuilder::unsolved('name/desc/1/' . (!empty($filter) ? $filter . '-' : '') . 'status/' . (!empty($filter_id) ? $filter_id . '-' : ''))->absolute() : BugtrackerUrlBuilder::solved('name/desc/1/' . (!empty($filter) ? $filter . '-' : '') . 'status/' . (!empty($filter_id) ? $filter_id . '-' : ''))->absolute()) .'" + HTMLForms.getField("filter_status").getValue();
			} else {
				document.location = "'. ($current_page == 'unsolved' ? BugtrackerUrlBuilder::unsolved('name/desc/1/' . (!empty($filter) ? $filter : '') . '/' . (!empty($filter_id) ? $filter_id : ''))->absolute() : BugtrackerUrlBuilder::solved('name/desc/1/' . (!empty($filter) ? $filter : '') . '/' . (!empty($filter_id) ? $filter_id : ''))->absolute()) .'";
			}')
		)));
		
		return $form;
	}
	
	private function build_select_status($current_page, $lang)
	{
		$status_list = BugtrackerConfig::load()->get_status_list();
		
		$array_status = array();
		$array_status[] = new FormFieldSelectChoiceOption('', '');
		foreach ($status_list as $status => $progress)
		{
			if (($current_page == 'unsolved' && !in_array($status, array('fixed', 'rejected'))) || ($current_page == 'solved' && in_array($status, array('fixed', 'rejected'))))
				$array_status[] = new FormFieldSelectChoiceOption($lang['bugs.status.' . $status], $status);
		}
		return $array_status;
	}
	
	private function build_versions_form($current_page, $requested_version, $filters, $filters_ids)
	{
		$search_field = ($current_page == 'unsolved' ? 'detected_in' : 'fixed_in');
		
		if (in_array($search_field , $filters))
		{
			$key = array_search($search_field , $filters);
			unset($filters[$key]);
			unset($filters_ids[$key]);
		}
		
		$filter = implode('-', $filters);
		$filter_id = implode('-', $filters_ids);
		
		$form = new HTMLForm('version-form');
		$fieldset = new FormFieldsetHorizontal('filter-version');
		
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('filter_version', '', $requested_version, $this->build_select_versions($current_page),
			array('events' => array('change' => 'if (HTMLForms.getField("filter_version").getValue() > 0) {
				document.location = "'. ($current_page == 'unsolved' ? BugtrackerUrlBuilder::unsolved('name/desc/1/' . (!empty($filter) ? $filter . '-' : '') . 'detected_in/' . (!empty($filter_id) ? $filter_id . '-' : ''))->absolute() : BugtrackerUrlBuilder::solved('name/desc/1/' . (!empty($filter) ? $filter . '-' : '') . 'fixed_in/' . (!empty($filter_id) ? $filter_id . '-' : ''))->absolute()) .'" + HTMLForms.getField("filter_version").getValue();
			} else {
				document.location = "'. ($current_page == 'unsolved' ? BugtrackerUrlBuilder::unsolved('name/desc/1/' . (!empty($filter) ? $filter : '') . '/' . (!empty($filter_id) ? $filter_id : ''))->absolute() : BugtrackerUrlBuilder::solved('name/desc/1/' . (!empty($filter) ? $filter : '') . '/' . (!empty($filter_id) ? $filter_id : ''))->absolute()) .'";
			}')
		)));
		
		return $form;
	}
	
	private function build_select_versions($current_page)
	{
		$versions = ($current_page == 'unsolved' ? BugtrackerConfig::load()->get_versions_detected() : BugtrackerConfig::load()->get_versions());
		$versions = array_reverse($versions, true);
		
		$array_versions = array();
		$array_versions[] = new FormFieldSelectChoiceOption('', '');
		foreach ($versions as $key => $version)
		{
			$array_versions[] = new FormFieldSelectChoiceOption(stripslashes($version['name']), $key);
		}
		return $array_versions;
	}
}
?>