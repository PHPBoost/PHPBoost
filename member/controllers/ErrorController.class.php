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
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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



class ErrorController extends AbstractController
{
	private $level = E_UNKNOWN;
	private $title = '';
	private $code = '';
	private $message = '';
	private $correction_link = '';
	private $correction_link_name = '';
	private $exception = null;

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

	public function set_level($level)
	{
		$this->level = $level;
	}

	public function set_title($title)
	{
		$this->title = $title;
	}

	public function set_code($code)
	{
		$this->code = $code;
	}

	public function set_message($message)
	{
		$this->message = $message;
	}

	public function set_correction_link($correction_link)
	{
		$this->correction_link = $correction_link;
	}

	public function set_correction_link_name($correction_link_name)
	{
		$this->correction_link_name = $correction_link_name;
	}

	public function set_exception(Exception $exception)
	{
		$this->exception = $exception;
	}
	
	public function execute(HTTPRequest $request)
	{
		$this->create_view($request);
		$this->load_env();

		if (empty($this->title))
		{
			$this->title = $this->lang['error'];
		}

		$env = $this->response->get_graphical_environment();
		$env->set_page_title($this->title);

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
		$view_builder = new ErrorViewBuilder();
		$this->view = $view_builder->build($this->level, $this->title, $this->code, $this->message,
		$this->correction_link, $this->correction_link_name, $this->exception);
	}
}
?>