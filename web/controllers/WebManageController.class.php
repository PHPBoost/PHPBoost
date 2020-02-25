<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 25
 * @since       PHPBoost 4.1 - 2014 08 21
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WebManageController extends AdminModuleController
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
		$this->lang = LangLoader::get('common', 'web');
		$this->view = new StringTemplate('# INCLUDE table #');
	}

	private function build_table()
	{
		$display_categories = CategoriesService::get_categories_manager()->get_categories_cache()->has_categories();

		$columns = array(
			new HTMLTableColumn(LangLoader::get_message('form.name', 'common'), 'name'),
			new HTMLTableColumn(LangLoader::get_message('category', 'categories-common'), 'id_category'),
			new HTMLTableColumn(LangLoader::get_message('author', 'common'), 'display_name'),
			new HTMLTableColumn(LangLoader::get_message('form.date.creation', 'common'), 'creation_date'),
			new HTMLTableColumn(LangLoader::get_message('status', 'common'), 'approbation_type'),
			new HTMLTableColumn(LangLoader::get_message('actions', 'admin-common'), '', array('sr-only' => true))
		);

		if (!$display_categories)
			unset($columns[1]);

		$table_model = new SQLHTMLTableModel(WebSetup::$web_table, 'table', $columns, new HTMLTableSortingRule('creation_date', HTMLTableSortingRule::DESC));

		$table_model->set_caption($this->lang['web.management']);

		$table = new HTMLTable($table_model);

		$results = array();
		$result = $table_model->get_sql_results('web
			LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = web.id AND com.module_id = \'web\'
			LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = web.id AND notes.module_name = \'web\'
			LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = web.id AND note.module_name = \'web\' AND note.user_id = ' . AppContext::get_current_user()->get_id() . '
			LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = web.author_user_id',
			array('*', 'web.id')
		);
		foreach ($result as $row)
		{
			$weblink = new WebLink();
			$weblink->set_properties($row);
			$category = $weblink->get_category();
			$user = $weblink->get_author_user();

			$this->elements_number++;
			$this->ids[$this->elements_number] = $weblink->get_id();

			$edit_link = new EditLinkHTMLElement(WebUrlBuilder::edit($weblink->get_id()));
			$delete_link = new DeleteLinkHTMLElement(WebUrlBuilder::delete($weblink->get_id()));

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(WebUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $weblink->get_id(), $weblink->get_rewrited_title()), $weblink->get_title()), 'left'),
				new HTMLTableRowCell(new LinkHTMLElement(WebUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()), $category->get_name())),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($weblink->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($weblink->get_status()),
				new HTMLTableRowCell($edit_link->display() . $delete_link->display())
			);

			if (!$display_categories)
				unset($row[1]);

			$results[] = new HTMLTableRow($row);
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('table', $table->display());

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
                        WebService::delete($this->ids[$i]);
                    }
                }
            }
            WebService::clear_cache();

            AppContext::get_response()->redirect(WebUrlBuilder::manage(), LangLoader::get_message('process.success', 'status-messages-common'));
        }
    }

	private function check_authorizations()
	{
		if (!CategoriesAuthorizationsService::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response($page = 1)
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['web.management'], $this->lang['module_title'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(WebUrlBuilder::manage());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], WebUrlBuilder::home());

		$breadcrumb->add($this->lang['web.management'], WebUrlBuilder::manage());

		return $response;
	}
}
?>
