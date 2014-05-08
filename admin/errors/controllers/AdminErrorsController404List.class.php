<?php
/*##################################################
 *                         AdminErrorsController404List.class.php
 *                            -------------------
 *   begin                : December 13 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class AdminErrorsController404List extends AdminController
{
	const NUMBER_ITEMS_PER_PAGE = 20;
	
	private $view;
	private $lang;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_view($request);
		
		return new AdminErrorsDisplayResponse($this->view, $this->lang['404_list']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-errors-common');
		
		$this->view = new FileTemplate('admin/errors/AdminErrorsController404List.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$page = $request->get_getint('page', 1);
		$pagination = $this->get_pagination($page);
		
		$result = PersistenceContext::get_querier()->select("SELECT *
		FROM " . PREFIX . "errors_404
		ORDER BY times DESC
		LIMIT :number_items_per_page OFFSET :display_from",
			array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
			)
		);
		
		$nb_errors = 0;
		
		while($row = $result->fetch())
		{
			$this->view->assign_block_vars('errors', array(
				'REQUESTED_URL' => $row['requested_url'],
				'FROM_URL' => $row['from_url'],
				'TIMES' => $row['times'],
				'U_DELETE' => AdminErrorsUrlBuilder::delete_404_error($row['id'])->rel(),
			));
			$nb_errors++;
		}
		$result->dispose();
		
		$this->view->put_all(array(
			'C_ERRORS' => $nb_errors,
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display(),
			'U_CLEAR_404_ERRORS' => AdminErrorsUrlBuilder::clear_404_errors()->rel()
		));
	}
	
	private function get_pagination($page)
	{
		$errors_number = PersistenceContext::get_querier()->count(PREFIX . 'errors_404');
		
		$pagination = new ModulePagination($page, $errors_number, self::NUMBER_ITEMS_PER_PAGE);
		$pagination->set_url(AdminErrorsUrlBuilder::list_404_errors('%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		return $pagination;
	}
}
?>