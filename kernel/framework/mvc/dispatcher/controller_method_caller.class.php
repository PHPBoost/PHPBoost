<?php
/*##################################################
 *                           controller_method_callerclass.php
 *                            -------------------
 *   begin                : June 08 2009
 *   copyright         : (C) 2009 Loïc Rouchon
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

import('mvc/controller/controller');

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc Call the controller method matching an url
 */
class ControllerMethodCaller
{
	/**
	 * @desc call the controller method in the phpboost environment
	 * @param string $controller_name the controller classname
	 * @param string $method_name the controller method name
	 * @param string $parameters the regular expression matching the url
	 * and capturing the controller method parameters
	 * @throws NoSuchControllerException
	 */
	public function call($controller_name, $method_name, $parameters)
	{
		$this->controller_name =& $controller_name;
		$this->method_name = $method_name;
		$this->parameters = $parameters;
		
		$this->do_call();
	}

	private function do_call()
	{	
        $this->init_environment();
		$this->check_controller_method();
		$this->run();
		$this->destroy();
	}

	private function init_environment()
	{
        check_for_maintain_redirect();
		$this->init_controller();
	}
	
	private function check_controller_method()
	{
		if (!method_exists($this->controller, $this->method_name))
		{
			$this->destroy();
			throw new NoSuchControllerMethodException($this->controller, $this->method_name);
		}
	}
	
	private function run()
	{
		try
		{
            $this->response = call_user_func_array(array($this->controller, $this->method_name), $this->parameters);
			$this->header();
            $this->response->parse();
		}
		catch (Exception $ex)
		{
			$this->controller->exception_handler($ex);
		}
		$this->footer();
	}
	
	private function destroy()
	{
		$this->controller->destroy();
	}
	
	private function init_controller()
	{
        $this->controller = new $this->controller_name();
        $this->controller->init();
	}
	
	private function header()
	{
		if ($this->controller->is_display_enabled())
		{
			$title = $this->controller->get_title();
			if (!empty($title))
			{
				define('TITLE', $title);
			}
			require_once PATH_TO_ROOT . '/kernel/header.php';
		}
	}
	
	private function footer()
	{
		if ($this->controller->is_display_enabled())
		{
			require_once PATH_TO_ROOT . '/kernel/footer.php';
		}
		else
		{
			require_once PATH_TO_ROOT . '/kernel/footer_no_display.php';
		}
	}
	
	private $method_name;
	private $controller_name;
	private $controller;
	private $parameters;
	private $response;
}
?>