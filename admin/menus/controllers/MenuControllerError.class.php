<?php
/*##################################################
 *                           MenuControllerError.class.php
 *                            -------------------
 *   begin                : October 27 2009
 *   copyright            : (C) 2009 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

class MenuControllerError implements Controller
{
	const TITLE = 'error_title';
	const CODE = 'error_code';
	const MESSAGE = 'error_message';
	const CORRECTION_LINK = 'error_correction_link';
	const CORRECTION_LINK_NAME = 'error_correction_link_name';
	
	/**
	 * @var int
	 */
	private $object_id;

	/**
	 * @var View
	 */
	private $view;

	/**
	 * @var AdminMenusDisplayResponse
	 */
	private $response;

	public function execute(HTTPRequest $request)
	{
		$this->load_env();
		
		$title = $request->get_value(self::TITLE, 'Error');
		
		$env = $this->response->get_graphical_environment();
		$env->set_page_title($title);
		
		$request->get_value(self::CORRECTION_LINK, 'javascript:history.back(1);');
		
		$this->view->assign_vars(array(
            'TITLE' => $title,
            'CODE' => $request->get_string(self::CODE, ''),
			'MESSAGE' => $request->get_string(self::MESSAGE, 'An unexpected error occurs'),
			'LINK_NAME' => $request->get_string(self::CORRECTION_LINK_NAME, 'Back'),
			'U_LINK' => $request->get_value(self::CORRECTION_LINK, 'javascript:history.back(1);'),
		));
		
		return $this->response;
	}

	private function load_env()
	{
		import('/admin/menus/util/AdminMenusDisplayResponse');

		global $LANG;

		$this->view = new View('admin/menus/error.tpl');
		$this->response = new AdminMenusDisplayResponse($this->view);
	}
}
?>