<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 26
 * @since       PHPBoost 4.0 - 2014 08 24
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
*/

class DownloadItemsManagerController extends ModuleController
{
	private $lang;
	private $view;

	private $elements_number = 0;
	private $ids = array();

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$current_page = $this->build_table();

		$this->execute_multiple_delete_if_needed($request);

		return $this->generate_response($current_page);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'download');
		$this->view = new StringTemplate('# INCLUDE TABLE #');
	}

	private function build_table()
	{
		$common_lang = LangLoader::get('common-lang');
		$display_categories = CategoriesService::get_categories_manager()->get_categories_cache()->has_categories();

		$columns = array(
			new HTMLTableColumn($common_lang['common.title'], 'title'),
			new HTMLTableColumn(LangLoader::get_message('category.category', 'category-lang'), 'id_category'),
			new HTMLTableColumn($common_lang['common.author'], 'display_name'),
			new HTMLTableColumn($common_lang['common.creation.date'], 'creation_date'),
			new HTMLTableColumn($common_lang['common.status.publication'], 'published'),
			new HTMLTableColumn($common_lang['common.actions'], '', array('sr-only' => true))
		);

		if (!$display_categories)
			unset($columns[1]);

		$table_model = new SQLHTMLTableModel(DownloadSetup::$download_table, 'items-manager', $columns, new HTMLTableSortingRule('creation_date', HTMLTableSortingRule::DESC));

		$table_model->set_layout_title($this->lang['download.items.management']);

		$table_model->set_filters_menu_title($this->lang['download.filter.items']);
		$table_model->add_filter(new HTMLTableDateGreaterThanOrEqualsToSQLFilter('creation_date', 'filter1', $common_lang['common.creation.date'] . ' ' . TextHelper::lcfirst($common_lang['common.minimum'])));
		$table_model->add_filter(new HTMLTableDateLessThanOrEqualsToSQLFilter('creation_date', 'filter2', $common_lang['common.creation.date'] . ' ' . TextHelper::lcfirst($common_lang['common.maximum'])));
		$table_model->add_filter(new HTMLTableAjaxUserAutoCompleteSQLFilter('display_name', 'filter3', $common_lang['common.author']));
		if ($display_categories)
			$table_model->add_filter(new HTMLTableCategorySQLFilter('filter4'));

		$status_list = array(Item::PUBLISHED => $common_lang['common.status.published'], Item::NOT_PUBLISHED => $common_lang['common.status.draft'], Item::DEFERRED_PUBLICATION => $common_lang['common.status.deffered.date']);
		$table_model->add_filter(new HTMLTableEqualsFromListSQLFilter('published', 'filter5', $common_lang['common.status.publication'], $status_list));

		$table = new HTMLTable($table_model);
		$table->set_filters_fieldset_class_HTML();

		$results = array();
		$result = $table_model->get_sql_results('download
			LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = download.id AND com.module_id = \'download\'
			LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = download.id AND notes.module_name = \'download\'
			LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = download.id AND note.module_name = \'download\' AND note.user_id = ' . AppContext::get_current_user()->get_id() . '
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = download.author_user_id',
			array('*', 'download.id')
		);
		foreach ($result as $row)
		{
			$item = new DownloadItem();
			$item->set_properties($row);
			$category = $item->get_category();
			$user = $item->get_author_user();

			$this->elements_number++;
			$this->ids[$this->elements_number] = $item->get_id();

			$edit_link = new EditLinkHTMLElement(DownloadUrlBuilder::edit($item->get_id()));
			$delete_link = new DeleteLinkHTMLElement(DownloadUrlBuilder::delete($item->get_id()));

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(DownloadUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()), $item->get_title()), 'left'),
				new HTMLTableRowCell(new LinkHTMLElement(DownloadUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()), ($category->get_id() == Category::ROOT_CATEGORY ? $common_lang['none_e'] : $category->get_name()))),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($item->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($item->get_status()),
				new HTMLTableRowCell($edit_link->display() . $delete_link->display(), 'controls')
			);

			if (!$display_categories)
				unset($row[1]);

			$results[] = new HTMLTableRow($row);
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('TABLE', $table->display());

		return $table->get_page_number();
	}

	private function execute_multiple_delete_if_needed(HTTPRequestCustom $request)
    {
        if ($request->get_string('delete-selected-elements', false))
        {
            for ($i = 1; $i <= $this->elements_number; $i++)
            {
                if ($request->get_value('delete-checkbox-' . $i, 'off') == 'on')
                {
                    if (isset($this->ids[$i]))
                    {
                        DownloadService::delete($this->ids[$i]);
                    }
                }
            }
            DownloadService::clear_cache();

            AppContext::get_response()->redirect(DownloadUrlBuilder::manage(), LangLoader::get_message('warning.process.success', 'warning-lang'));
        }
    }

	private function check_authorizations()
	{
		if (!DownloadAuthorizationsService::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response($page = 1)
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['download.items.management'], $this->lang['download.module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(DownloadUrlBuilder::manage());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['download.module.title'], DownloadUrlBuilder::home());

		$breadcrumb->add($this->lang['download.items.management'], DownloadUrlBuilder::manage());

		return $response;
	}
}
?>
