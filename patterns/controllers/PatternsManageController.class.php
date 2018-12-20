<?php
/*##################################################
 *                      PatternsManageController.class.php
 *                            -------------------
 *
 *   begin                : July 26, 2018
 *   copyright            : (C) 2018 Arnaud Genet
 *   email                : elenwii@phpboost.com
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
 * @author Arnaud Genet <elenwii@phpboost.com>
 */
class PatternsManageController extends AdminModuleController
{
	private $lang;
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		
		$this->init();
		
		$this->build_table();
		
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'patterns');
		$this->view = new StringTemplate('# INCLUDE table #');
	}

	private function build_table()
	{
		$columns = array(
			new HTMLTableColumn(LangLoader::get_message('form.name', 'common'), 'name'),
			new HTMLTableColumn(LangLoader::get_message('author', 'common'), 'display_name'),
			new HTMLTableColumn(LangLoader::get_message('form.date.creation', 'common'), 'creation_date'),
			new HTMLTableColumn(LangLoader::get_message('status', 'common'), 'approbation_type')
		);
		
		$table_model = new SQLHTMLTableModel(PatternsSetup::$patterns_table, 'table', $columns, new HTMLTableSortingRule('creation_date', HTMLTableSortingRule::DESC));
		
		$table_model->set_caption($this->lang['news.management']);

        $table = new HTMLTable($table_model);
		
		$results = array();
		$result = $table_model->get_sql_results('patterns LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = patterns.author_user_id');

		foreach ($result as $row)
		{
			$patterns = new Patterns();
			$patterns->set_properties($row);

			$user = $patterns->get_author_user();

			$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
			$author = $user->get_id() !== User::VISITOR_LEVEL ? new LinkHTMLElement(UserUrlBuilder::profile($user->get_id()), $user->get_display_name(), (!empty($user_group_color) ? array('style' => 'color: ' . $user_group_color) : array()), UserService::get_level_class($user->get_level())) : $user->get_display_name();

			$row = array(
				new HTMLTableRowCell(new LinkHTMLElement(PatternsUrlBuilder::display_patterns($patterns->get_id(), $patterns->get_name()), $patterns->get_name()), 'left'),
				new HTMLTableRowCell($author),
				new HTMLTableRowCell($patterns->get_creation_date()->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE)),
				new HTMLTableRowCell($patterns->get_status())
			);
			
			$results[] = new HTMLTableRow($row);
		}
		$table->set_rows($table_model->get_number_of_matching_rows(), $results);

		$this->view->put('table', $table->display());
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['manage.patterns'], $this->lang['manage.patterns']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(PatternsUrlBuilder::manage_patterns());
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		
		$breadcrumb->add($this->lang['patterns'], PatternsUrlBuilder::manage_patterns());
		
		return $response;
	}
}
?>