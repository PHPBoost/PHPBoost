<?php
/*##################################################
 *                           ErrorController.class.php
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

class ErrorController implements Controller
{
	const LEVEL = 'error_level';
	const TITLE = 'error_title';
	const CODE = 'error_code';
	const MESSAGE = 'error_message';
	const CORRECTION_LINK = 'error_correction_link';
	const CORRECTION_LINK_NAME = 'error_correction_link_name';
	
	/**
	 * @var string[string]
	 */
	private $lang;
	
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
		
		$title = $request->get_value(self::TITLE, $this->lang['error']);
		
		$env = $this->response->get_graphical_environment();
		$env->set_page_title($title);
		
		$level = 'question';
		switch ($request->get_string(self::LEVEL, E_ERROR))
		{
			case E_USER_NOTICE:
			case E_NOTICE:
			case E_STRICT:
				$level = 'notice';
				break;
				//Warning utilisateur.
			case E_USER_WARNING:
			case E_WARNING:
				$level = 'important';
				break;
				//Erreur fatale.
			case E_USER_ERROR:
			case E_ERROR:
				$level = 'stop';
				break;
				//Erreur inconnue.
			default:
				$level = 'question';
		}
		
		$this->view->assign_vars(array(
            'TITLE' => $title,
            'CODE' => $request->get_string(self::CODE, ''),
			'MESSAGE' => $request->get_string(self::MESSAGE, $this->lang['unexpected_error_occurs']),
			'LINK_NAME' => $request->get_string(self::CORRECTION_LINK_NAME, $this->lang['back']),
			'U_LINK' => $request->get_value(self::CORRECTION_LINK, 'javascript:history.back(1);'),
			'LEVEL' => $level
		));
		
		return $this->response;
	}

	private function load_env()
	{
		import('mvc/response/SiteDisplayResponse');

		$this->view = new View('member/error_controller.tpl');
		$this->response = new SiteDisplayResponse($this->view);
		$this->lang = LangLoader::get(get_class());
		$this->view->add_lang($this->lang);
	}
}
?>