<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 10
 * @since       PHPBoost 6.0 - 2021 10 22
*/

class AdminHistoryController extends DefaultAdminModuleController
{
	private $elements_number = 0;
	private $ids = array();

	public function execute(HTTPRequestCustom $request)
	{
		$this->build_table();

		return new AdminHistoryDisplayResponse($this->view, ModulesManager::get_module('history')->get_configuration()->get_name());
	}

	private function build_table()
	{
		$modules_specific_hooks = HooksService::get_specific_hooks_list_with_localized_names();
		
		$columns = array(
			new HTMLTableColumn($this->lang['common.creation.date'], 'creation_date'),
			new HTMLTableColumn($this->lang['common.author'], 'display_name'),
			new HTMLTableColumn($this->lang['common.module'], 'module_id'),
			new HTMLTableColumn($this->lang['history.action'], 'action'),
			new HTMLTableColumn($this->lang['common.link'], 'title'),
			new HTMLTableColumn($this->lang['common.description'], 'description')
		);

		$table_model = new SQLHTMLTableModel(HistorySetup::$history_table, 'history-table', $columns, new HTMLTableSortingRule('creation_date', HTMLTableSortingRule::DESC));

		$table_model->set_layout_title(ModulesManager::get_module('history')->get_configuration()->get_name());

		$table_model->set_filters_menu_title($this->lang['common.filters']);
		$table_model->add_filter(new HTMLTableDateGreaterThanOrEqualsToSQLFilter('creation_date', 'filter1', $this->lang['common.creation.date'] . ' ' . TextHelper::lcfirst($this->lang['common.minimum'])));
		$table_model->add_filter(new HTMLTableDateLessThanOrEqualsToSQLFilter('creation_date', 'filter2', $this->lang['common.creation.date'] . ' ' . TextHelper::lcfirst($this->lang['common.maximum'])));
		$table_model->add_filter(new HTMLTableAjaxUserAutoCompleteSQLFilter('display_name', 'filter3', $this->lang['common.author']));
		
		$result = PersistenceContext::get_querier()->select('SELECT DISTINCT module_id FROM ' . HistorySetup::$history_table);
		$modules_list = array();
		while ($row = $result->fetch())
		{
			$module = ModulesManager::get_module($row['module_id']);
			$modules_list[$row['module_id']] = ($row['module_id'] != 'kernel' && $module ? $module->get_configuration()->get_name() : ($row['module_id'] == 'kernel' ? LangLoader::get_message('admin.kernel', 'admin-lang') : $row['module_id']));
		}
		$result->dispose();
		asort($modules_list);
		$table_model->add_filter(new HTMLTableEqualsFromListSQLFilter('module_id', 'filter4', $this->lang['common.module'], $modules_list));
		
		$result = PersistenceContext::get_querier()->select('SELECT DISTINCT action FROM ' . HistorySetup::$history_table);
		$actions_list = array();
		while ($row = $result->fetch())
		{
			$actions_list[$row['action']] = (isset($modules_specific_hooks[$row['action']]) ? $modules_specific_hooks[$row['action']] : (isset($this->lang['history.action.' . $row['action']]) ? $this->lang['history.action.' . $row['action']] : $row['action']));
		}
		$result->dispose();
		asort($actions_list);
		$table_model->add_filter(new HTMLTableEqualsFromListSQLFilter('action', 'filter5', $this->lang['history.action'], $actions_list, true));
		
		$table_model->add_filter(new HTMLTableLikeTextSQLFilter('description', 'filter6', $this->lang['common.description']));

		$table = new HTMLTable($table_model);
		$table->set_filters_fieldset_class_HTML();
		$table->hide_multiple_delete();

		$results = array();
		$result = $table_model->get_sql_results('history
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = history.user_id',
			array('*', 'history.id')
		);
		foreach ($result as $row)
		{
			$creation_date = new Date($row['creation_date'], Timezone::SERVER_TIMEZONE);
			
			$user = new User();
			$user->set_properties($row);

			$this->elements_number++;
			$this->ids[$this->elements_number] = $row['id'];

			$title = str_replace("\'", "'", $row['title']);
			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();
			$module = ModulesManager::get_module($row['module_id']);
			
			$table_row = array(
				new HTMLTableRowCell($creation_date->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($row['module_id'] != 'kernel' && $row['module_id'] != 'user' && $module ? $module->get_configuration()->get_name() : ($row['module_id'] == 'kernel' ? LangLoader::get_message('admin.kernel', 'admin-lang') : ($row['module_id'] == 'user' ? LangLoader::get_message('user.user', 'user-lang') : $row['module_id']))),
				new HTMLTableRowCell(isset($modules_specific_hooks[$row['action']]) ? $modules_specific_hooks[$row['action']] : (isset($this->lang['history.action.' . $row['action']]) ? $this->lang['history.action.' . $row['action']] : $row['action'])),
				new HTMLTableRowCell(($row['url'] ? new LinkHTMLElement($row['url'], $title) : ($row['action'] == 'edit_config' && $module ? '' : $title)), 'left'),
				new HTMLTableRowCell($row['description'], 'left')
			);

			$results[] = new HTMLTableRow($table_row);
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('CONTENT', $table->display());

		return $table->get_page_number();
	}
}
?>
