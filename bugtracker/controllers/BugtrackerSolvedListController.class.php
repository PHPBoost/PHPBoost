<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 05
 * @since       PHPBoost 3.0 - 2012 11 13
*/

class BugtrackerSolvedListController extends ModuleController
{
	private $lang;
	private $view;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_view($request);

		return $this->build_response($this->view);
	}

	private function build_view($request)
	{
		//Configuration load
		$config = BugtrackerConfig::load();

		$field = $request->get_value('field', BugtrackerUrlBuilder::DEFAULT_SORT_FIELD);
		$sort = $request->get_value('sort', BugtrackerUrlBuilder::DEFAULT_SORT_MODE);
		$current_page = $request->get_getint('page', 1);
		$filter = $request->get_value('filter', '');
		$filter_id = $request->get_value('filter_id', '');

		if (!empty($filter) && empty($filter_id))
		{
			$filter = $filter_id = '';
		}

		$filters = !empty($filter) ? explode('-', $filter) : array();
		$nb_filters = count($filters);
		$filters_ids = !empty($filter_id) ? explode('-', $filter_id) : array();
		$nb_filters_ids = count($filters_ids);

		if ($nb_filters != $nb_filters_ids)
		{
			for ($i = $nb_filters_ids; $i < $nb_filters; $i++)
			{
				$filters_ids[] = 0;
			}
		}

		$mode = ($sort == 'top') ? 'ASC' : 'DESC';

		switch ($field)
		{
			case 'id' :
				$field_bdd = 'id';
				break;
			case 'title' :
				$field_bdd = 'title';
				break;
			case 'status' :
				$field_bdd = 'status';
				break;
			default :
				$field_bdd = 'fix_date';
				break;
		}

		$select_filters = '';
		foreach ($filters as $key => $f)
		{
			$select_filters .= in_array($f, array('type', 'category', 'severity', 'status', 'fixed_in')) && $filters_ids[$key] ? "AND " . $f . " = '" . $filters_ids[$key] . "'" : '';
		}

		$stats_cache = BugtrackerStatsCache::load();
		$bugs_number = BugtrackerService::count("WHERE (status = '" . Bug::FIXED . "' OR status = '" . Bug::REJECTED . "')" . $select_filters);

		$pagination = $this->get_pagination($bugs_number, $current_page, $field, $sort, $filter, $filter_id);

		$result = PersistenceContext::get_querier()->select("SELECT b.*, member.*
		FROM " . BugtrackerSetup::$bugtracker_table . " b
		LEFT JOIN " . DB_TABLE_MEMBER . " member ON member.user_id = b.author_id
		WHERE (status = '" . Bug::FIXED . "' OR status = '" . Bug::REJECTED . "')" .
		($config->is_restrict_display_to_own_elements_enabled() && !BugtrackerAuthorizationsService::check_authorizations()->moderation() ? "AND b.author_id = :user_id " : "") .
		$select_filters . "
		ORDER BY " . $field_bdd . " " . $mode . "
		LIMIT :number_items_per_page OFFSET :display_from",
			array(
				'user_id' => AppContext::get_current_user()->get_id(),
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
			)
		);

		$displayed_status = array();

		while ($row = $result->fetch())
		{
			$bug = new Bug();
			$bug->set_properties($row);

			if (!in_array($bug->get_status(), $displayed_status)) $displayed_status[] = $bug->get_status();

			$this->view->assign_block_vars('bug', array_merge($bug->get_array_tpl_vars(), array(
				'C_LINE_COLOR'		=> true,
				'LINE_COLOR' 		=> $bug->is_fixed() ? $config->get_fixed_bug_color() : $config->get_rejected_bug_color(),
				'U_CHANGE_STATUS'	=> BugtrackerUrlBuilder::change_status($bug->get_id())->rel(),
				'U_EDIT'			=> BugtrackerUrlBuilder::edit($bug->get_id(), 'solved', $current_page, $filter, $filter_id)->rel(),
				'U_DELETE'			=> BugtrackerUrlBuilder::delete($bug->get_id(), 'solved', $current_page, $filter, $filter_id)->rel(),
			)));
		}
		$result->dispose();

		$bugs_colspan = BugtrackerAuthorizationsService::check_authorizations()->moderation() ? 5 : 4;
		if ($config->is_type_column_displayed()) $bugs_colspan++;
		if ($config->is_category_column_displayed()) $bugs_colspan++;
		if ($config->is_priority_column_displayed()) $bugs_colspan++;
		if ($config->is_detected_in_column_displayed()) $bugs_colspan++;

		$this->view->put_all(array(
			'C_IS_ADMIN'					=> BugtrackerAuthorizationsService::check_authorizations()->moderation(),
			'C_BUGS' 						=> $result->get_rows_count() > 0,
			'C_DISPLAY_TYPE_COLUMN'			=> $config->is_type_column_displayed(),
			'C_DISPLAY_CATEGORY_COLUMN'		=> $config->is_category_column_displayed(),
			'C_DISPLAY_PRIORITY_COLUMN'		=> $config->is_priority_column_displayed(),
			'C_DISPLAY_DETECTED_IN_COLUMN'	=> $config->is_detected_in_column_displayed(),
			'C_PAGINATION'					=> $pagination->has_several_pages(),
			'PAGINATION' 					=> $pagination->display(),
			'BUGS_COLSPAN' 					=> $bugs_colspan,
			'L_NO_BUG' 						=> empty($filters) ? $this->lang['notice.no_bug_solved'] : (count($filters) > 1 ? $this->lang['notice.no_bug_matching_filters'] : $this->lang['notice.no_bug_matching_filter']),
			'FILTER_LIST'					=> BugtrackerViews::build_filters('solved', $bugs_number),
			'LEGEND'						=> BugtrackerViews::build_legend($displayed_status, 'solved'),
			'LINK_BUG_ID_TOP' 				=> BugtrackerUrlBuilder::solved('id', 'top', $current_page, $filter, $filter_id)->rel(),
			'LINK_BUG_ID_BOTTOM' 			=> BugtrackerUrlBuilder::solved('id', 'bottom', $current_page, $filter, $filter_id)->rel(),
			'LINK_BUG_TITLE_TOP' 			=> BugtrackerUrlBuilder::solved('title', 'top', $current_page, $filter, $filter_id)->rel(),
			'LINK_BUG_TITLE_BOTTOM' 		=> BugtrackerUrlBuilder::solved('title', 'bottom', $current_page, $filter, $filter_id)->rel(),
			'LINK_BUG_STATUS_TOP'			=> BugtrackerUrlBuilder::solved('status', 'top', $current_page, $filter, $filter_id)->rel(),
			'LINK_BUG_STATUS_BOTTOM'		=> BugtrackerUrlBuilder::solved('status', 'bottom', $current_page, $filter, $filter_id)->rel(),
			'LINK_BUG_DATE_TOP' 			=> BugtrackerUrlBuilder::solved('date', 'top', $current_page, $filter, $filter_id)->rel(),
			'LINK_BUG_DATE_BOTTOM' 			=> BugtrackerUrlBuilder::solved('date', 'bottom', $current_page, $filter, $filter_id)->rel()
		));

		if ($config->is_restrict_display_to_own_elements_enabled() && !BugtrackerAuthorizationsService::check_authorizations()->moderation() && !AppContext::get_current_user()->is_guest())
			$this->view->put('MSG', MessageHelper::display($this->lang['warning.restrict_display_to_own_elements_enabled'], MessageHelper::WARNING));

		return $this->view;
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'bugtracker');
		$this->view = new FileTemplate('bugtracker/BugtrackerListController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function check_authorizations()
	{
		if (!BugtrackerAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function get_pagination($bugs_number, $page, $field, $sort, $filter, $filter_id)
	{
		$pagination = new ModulePagination($page, $bugs_number, (int)BugtrackerConfig::load()->get_items_per_page());
		$pagination->set_url(BugtrackerUrlBuilder::solved($field, $sort, '%d', $filter, $filter_id));

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

		$field = $request->get_value('field', 'date');
		$sort = $request->get_value('sort', 'desc');
		$page = $request->get_int('page', 1);
		$filter = $request->get_value('filter', '');
		$filter_id = $request->get_value('filter_id', '');

		$body_view = BugtrackerViews::build_body_view($view, 'solved');

		$response = new SiteDisplayResponse($body_view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['titles.solved'], $this->lang['module_title'], $page);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['seo.solved'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(BugtrackerUrlBuilder::solved($field, $sort, $page, $filter, $filter_id));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], BugtrackerUrlBuilder::home());
		$breadcrumb->add($this->lang['titles.solved'], BugtrackerUrlBuilder::solved($field, $sort, $page, $filter, $filter_id));

		return $response;
	}
}
?>
