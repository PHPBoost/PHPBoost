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
	private $view;
	private $lang;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_view();
		
		return new AdminErrorsDisplayResponse($this->view, $this->lang['404_list']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-errors-common');
		
		$this->view = new FileTemplate('admin/errors/AdminErrorsController404List.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_view()
	{
		$errors_404 = AdminError404Service::list_404_errors();
		$nb_errors = 0;
		
		foreach ($errors_404 as $error)
		{
			$this->view->assign_block_vars('errors', array(
				'REQUESTED_URL' => $error->get_requested_url(),
				'FROM_URL' => $error->get_from_url(),
				'TIMES' => $error->get_times(),
				'U_DELETE' => AdminErrorsUrlBuilder::delete_404_error($error->get_id())->rel(),
			));
			$nb_errors++;
		}
		
		$this->view->put_all(array(
			'C_ERRORS' => $nb_errors,
			'U_CLEAR_404_ERRORS' => AdminErrorsUrlBuilder::clear_404_errors()->rel()
		));
	}
}
?>