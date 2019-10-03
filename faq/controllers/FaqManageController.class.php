<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 10 23
 * @since   	PHPBoost 4.0 - 2014 09 02
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FaqManageController extends AdminModuleController
{
	private $lang;
	private $view;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$current_page = $this->build_table();

		return $this->generate_response($current_page);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'faq');
		$this->view = new StringTemplate('# INCLUDE table #');
	}

	private function build_table()
	{
		$display_categories = FaqService::get_categories_manager()->get_categories_cache()->has_categories();

		$columns = array(
			new HTMLTableColumn($this->lang['faq.form.question'], 'question'),
			new HTMLTableColumn(LangLoader::get_message('category', 'categories-common'), 'id_category'),
			new HTMLTableColumn(LangLoader::get_message('author', 'common'), 'display_name'),
			new HTMLTableColumn(LangLoader::get_message('form.date.creation', 'common'), 'creation_date'),
			new HTMLTableColumn(LangLoader::get_message('status.approved', 'common'), 'approved'),
			new HTMLTableColumn('')
		);

		if (!$display_categories)
			unset($columns[1]);

		$table_model = new SQLHTMLTableModel(FaqSetup::$faq_table, 'table', $columns, new HTMLTableSortingRule('creation_date', HTMLTableSortingRule::DESC));

		$table_model->set_caption($this->lang['faq.management']);

		$table = new HTMLTable($table_model);

		$results = array();
		$result = $table_model->get_sql_results('faq LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = faq.author_user_id');
		foreach ($result as $row)
		{
			$faq_question = new FaqQuestion();
			$faq_question->set_properties($row);
			$category = $faq_question->get_category();
			$user = $faq_question->get_author_user();

			$edit_link = new LinkHTMLElement(FaqUrlBuilder::edit($faq_question->get_id()), '', array('aria-label' => LangLoader::get_message('edit', 'common')), 'fa fa-edit');
			$delete_link = new LinkHTMLElement(FaqUrlBuilder::delete($faq_question->get_id()), '', array('aria-label' => LangLoader::get_message('delete', 'common'), 'data-confirmation' => 'delete-element'), 'fa fa-delete');

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(FaqUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $faq_question->get_id()), $faq_question->get_question()), 'left'),
				new HTMLTableRowCell(new LinkHTMLElement(FaqUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()), $category->get_name())),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($faq_question->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($faq_question->is_approved() ? LangLoader::get_message('yes', 'common') : LangLoader::get_message('no', 'common')),
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

	private function check_authorizations()
	{
		if (!FaqAuthorizationsService::check_authorizations()->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response($page = 1)
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['faq.management'], $this->lang['module_title'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(FaqUrlBuilder::manage());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], FaqUrlBuilder::home());

		$breadcrumb->add($this->lang['faq.management'], FaqUrlBuilder::manage());

		return $response;
	}
}
?>
