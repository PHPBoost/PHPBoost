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
	/**
	 * @var View
	 */
	private $view;

	/**
	 * @var SiteDisplayResponse
	 */
	private $response;

	public function execute(HTTPRequest $request)
	{
		$this->load_env();
		$errors_404 = AdminError404Service::list_404_errors();

		foreach ($errors_404 as $error)
		{
			$this->view->assign_block_vars('errors', array(
                'REQUESTED_URL' => $error->get_requested_url(),
                'FROM_URL' => $error->get_from_url(),
                'TIMES' => $error->get_times(),
                'U_DELETE' => AdminErrorsUrlBuilder::delete_404_error($error->get_id())->absolute(),
			));
		}
		$this->view->put_all(array('U_CLEAR_404_ERRORS' => AdminErrorsUrlBuilder::clear_404_errors()->absolute()));

		return $this->response;
	}

	private function load_env()
	{
        $lang = LangLoader::get_class(__CLASS__);
        
		$this->view = new FileTemplate('admin/errors/AdminErrorsController404List.tpl');
        $this->view->add_lang($lang);
        
		$this->response = new AdminErrorsDisplayResponse($this->view);
        
        $env = $this->response->get_graphical_environment();
		$env->set_page_title($lang['404_list']);
	}
}
?>