<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 05
 * @since       PHPBoost 3.0 - 2012 11 11
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BugtrackerHistoryListController extends DefaultModuleController
{
	private $bug;

	protected function get_template_to_use()
	{
		return new FileTemplate('bugtracker/BugtrackerHistoryListController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->init($request);

		$this->check_authorizations();

		$this->build_view($request);

		return $this->build_response($this->view);
	}

	private function build_view($request)
	{
		$current_page = $request->get_getint('page', 1);

		//Configuration load
		$types = $this->config->get_types();
		$categories = $this->config->get_categories();
		$severities = $this->config->get_severities();
		$priorities = $this->config->get_priorities();
		$versions = $this->config->get_versions();

		$history_lines_number = BugtrackerService::count_history($this->bug->get_id());
		$pagination = $this->get_pagination($history_lines_number, $current_page);

		$this->view->put_all(array(
			'C_PAGINATION'	=> $pagination->has_several_pages(),
			'C_HISTORY'		=> $history_lines_number,
			'PAGINATION'	=> $pagination->display()
		));

		$result = PersistenceContext::get_querier()->select("SELECT *
		FROM " . BugtrackerSetup::$bugtracker_table . " b
		JOIN " . BugtrackerSetup::$bugtracker_history_table . " bh ON (bh.bug_id = b.id)
		LEFT JOIN " . DB_TABLE_MEMBER . " member ON (member.user_id = bh.updater_id)
		WHERE b.id = '" . $this->bug->get_id() . "'
		ORDER BY update_date DESC
		LIMIT :number_items_per_page OFFSET :display_from",
			array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
			)
		);

		while ($row = $result->fetch())
		{
			switch ($row['updated_field'])
			{
				case 'type':
					$old_value = !empty($row['old_value']) && isset($types[$row['old_value']]) ? stripslashes($types[$row['old_value']]) : $this->lang['notice.none'];
					$new_value = !empty($row['new_value']) && isset($types[$row['new_value']]) ? stripslashes($types[$row['new_value']]) : $this->lang['notice.none'];
					break;

				case 'category':
					$old_value = !empty($row['old_value']) && isset($categories[$row['old_value']]) ? stripslashes($categories[$row['old_value']]) : $this->lang['notice.none_e'];
					$new_value = !empty($row['new_value']) && isset($categories[$row['new_value']]) ? stripslashes($categories[$row['new_value']]) : $this->lang['notice.none_e'];
					break;

				case 'severity':
					$old_value = !empty($row['old_value']) ? stripslashes($severities[$row['old_value']]['name']) : $this->lang['notice.none'];
					$new_value = !empty($row['new_value']) ? stripslashes($severities[$row['new_value']]['name']) : $this->lang['notice.none'];
					break;

				case 'priority':
					$old_value = !empty($row['old_value']) ? stripslashes($priorities[$row['old_value']]) : $this->lang['notice.none_e'];
					$new_value = !empty($row['new_value']) ? stripslashes($priorities[$row['new_value']]) : $this->lang['notice.none_e'];
					break;

				case 'detected_in':
				case 'fixed_in':
					$old_value = !empty($row['old_value']) && isset($versions[$row['old_value']]) ? stripslashes($versions[$row['old_value']]['name']) : $this->lang['notice.none_e'];
					$new_value = !empty($row['new_value']) && isset($versions[$row['new_value']]) ? stripslashes($versions[$row['new_value']]['name']) : $this->lang['notice.none_e'];
					break;

				case 'status':
					$old_value = $this->lang['status.' . $row['old_value']];
					$new_value = $this->lang['status.' . $row['new_value']];
					break;

				case 'reproductible':
					$old_value = ($row['old_value']) ? $this->lang['common.yes'] : $this->lang['common.no'];
					$new_value = ($row['new_value']) ? $this->lang['common.yes'] : $this->lang['common.no'];
					break;

				default:
					$old_value = $row['old_value'];
					$new_value = $row['new_value'];
			}

			$update_date = new Date($row['update_date'], Timezone::SERVER_TIMEZONE);

			$user = new User();
			if (!empty($row['user_id']))
				$user->set_properties($row);
			else
				$user->init_visitor_user();

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);

			$this->view->assign_block_vars('history', array_merge(
				Date::get_array_tpl_vars($update_date,'update_date'),
				array(
				'C_UPDATER_GROUP_COLOR'	=> !empty($user_group_color),
				'C_UPDATER_EXIST'		=> $user->get_id() !== User::VISITOR_LEVEL,
				'UPDATED_FIELD'			=> (!empty($row['updated_field']) ? $this->lang['labels.fields.' . $row['updated_field']] : $this->lang['notice.none']),
				'OLD_VALUE'				=> stripslashes($old_value),
				'NEW_VALUE'				=> stripslashes($new_value),
				'COMMENT'				=> $row['change_comment'],
				'UPDATER'				=> $user->get_display_name(),
				'UPDATER_LEVEL_CLASS'	=> UserService::get_level_class($user->get_level()),
				'UPDATER_GROUP_COLOR'	=> $user_group_color,
				'LINK_UPDATER_PROFILE'	=> UserUrlBuilder::profile($user->get_id())->rel()
				)
			));
		}
		$result->dispose();
	}

	private function init($request)
	{
		$id = $request->get_int('id', 0);

		try {
			$this->bug = BugtrackerService::get_bug('WHERE id=:id', array('id' => $id));
		} catch (RowNotFoundException $e) {
			$controller = new UserErrorController($lang['warning.error'], $this->lang['error.e_unexist_bug']);
			DispatchManager::redirect($controller);
		}

		$this->config = BugtrackerConfig::load();
	}

	private function check_authorizations()
	{
		if (!BugtrackerAuthorizationsService::check_authorizations()->read() || ($this->config->is_restrict_display_to_own_elements_enabled() && !BugtrackerAuthorizationsService::check_authorizations()->moderation() && $this->bug->get_author_user()->get_id() != AppContext::get_current_user()->get_id() && !$this->bug->is_fixed()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function get_pagination($history_lines_number, $page)
	{
		$pagination = new ModulePagination($page, $history_lines_number, (int)$this->config->get_items_per_page());
		$pagination->set_url(BugtrackerUrlBuilder::history($this->bug->get_id(), '%d'));

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
		$page = $request->get_int('page', 1);

		$body_view = BugtrackerViews::build_body_view($view, 'history', $this->bug->get_id(), $this->bug->get_type());

		$response = new SiteDisplayResponse($body_view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['titles.history'] . ' #' . $this->bug->get_id(), $this->lang['bugtracker.module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['seo.history'], array('id' => $this->bug->get_id())), $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(BugtrackerUrlBuilder::history($this->bug->get_id(), $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['bugtracker.module.title'], BugtrackerUrlBuilder::home());
		$breadcrumb->add($this->lang['titles.detail'] . ' #' . $this->bug->get_id(), BugtrackerUrlBuilder::detail($this->bug->get_id() . '-' . $this->bug->get_rewrited_title()));
		$breadcrumb->add($this->lang['titles.history'], BugtrackerUrlBuilder::history($this->bug->get_id(), $page));

		return $response;
	}
}
?>
