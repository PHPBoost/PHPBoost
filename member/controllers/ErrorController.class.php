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
	const EXCEPTION = 'error_exception';

	/**
	 * @var string[string]
	 */
	private $lang;

	/**
	 * @var View
	 */
	private $view;

	/**
	 * @var response
	 */
	private $response;

	public function execute(HTTPRequest $request)
	{
		$this->create_view($request);
		$this->load_env();

		$title = $request->get_value(self::TITLE, $this->lang['error']);

		$env = $this->response->get_graphical_environment();
		$env->set_page_title($title);

		return $this->response;
	}

	protected function load_env()
	{
		
		$this->lang = LangLoader::get('ErrorViewBuilder');
		$this->view->add_lang($this->lang);
		$this->set_response(new SiteDisplayResponse($this->view));
	}
	/**
	 * @return View
	 */
	protected function get_view()
	{
		return $this->view;
	}

	protected function set_response(Response $response)
	{
		return $this->response = $response;
	}

	private function create_view(HTTPRequest $request)
	{
		
		$view_builder = new ErrorViewBuilder($request);

		$exception = null;
		try
		{
			$exception = $request->get_value(self::EXCEPTION);
		}
		catch (UnexistingHTTPParameterException $exception)
		{
			Debug::fatal($exception);
		}

		$this->view = $view_builder->build($request->get_int(self::LEVEL, E_UNKNOWN),
		$request->get_string(self::TITLE, ''), $request->get_string(self::CODE, ''),
		$request->get_string(self::MESSAGE, ''), $request->get_string(self::CORRECTION_LINK, ''),
		$request->get_string(self::CORRECTION_LINK_NAME, ''), $exception);
	}
}
?>