<?php
/*##################################################
 *                           url_dispatcher_item.class.php
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
class UrlDispatcherItem
{
	/**
	 * @desc build a new UrlDispatcherItem
	 * @param string $controller_name the controller classname
	 * @param string $method_name the controller method name
	 * @param string $capture_regex the regular expression matching the url
	 * and capturing the controller method parameters
	 * @throws NoSuchControllerException
	 */
	public function __construct($controller_name, $method_name, $capture_regex)
	{
		if (!implements_interface($controller_name, CONTROLLER__INTERFACE))
		{
			throw new NoSuchControllerException($controller_name);
		}
		$this->controller_name =& $controller_name;
		$this->method_name = $method_name;
		$this->capture_regex = $capture_regex;
	}

	/**
	 * @desc Returns true if the UrlDispatcherItem match the url
	 * @param string $url the to match
	 * @return boolean true if the UrlDispatcherItem match the url
	 */
	public function match(&$url)
	{
		$this->params = array();
		$match = preg_match($this->capture_regex, $url, $this->params);
		// Remove the global url from the parameters that the controller will receive
		unset($this->params[0]);
		return $match;
	}

	/**
	 * @desc Call the controller method if the url match and if the method exists
	 * @param string $url the url
	 * @throws NoUrlMatchException
	 * @throws NoSuchControllerMethodException
	 */
	public function call(&$url)
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
            $this->response = call_user_func_array(array($this->controller, $this->method_name), $this->params);
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
			if ($this->controller->get_bread_crumb() !== null)
			{
				global $Bread_crumb;
				$Bread_crumb = $this->controller->get_bread_crumb();
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
	private $params_capture_regex;
	private $params;
	private $response;
}
?>