<?php
/*##################################################
 *                           UrlControllerMapper.class.php
 *                            -------------------
 *   begin                : June 08 2009
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

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc Call the controller method matching an url
 * @package {@package}
 */
class UrlControllerMapper extends AbstractUrlMapper
{
	private $classname;
	private $parameters_names;

	/**
	 * @desc build a new UrlDispatcherItem
	 * @param string $classpath the controller classname
	 * @param string $capture_regex the regular expression matching the url
	 * and capturing the controller method parameters. By default, match the empty url <code>/</code>
     * @param string $parameters_names the names of the parameters in the capture order
	 * @throws NoSuchControllerException
	 */
	public function __construct($classname, $capture_regex = '`^/?$`', $parameters_names = array())
	{
		$this->classname =& $classname;
		$this->parameters_names = $parameters_names;
		parent::__construct($capture_regex);
	}

	/**
	 * @desc Call the controller method if the url match and if the method exists
	 */
	public function call()
	{
		$this->build_parameters();
		$this->do_call();
	}

	private function build_parameters()
	{
		$captured_parameters = $this->get_captured_parameters();

		$i = 1;
		foreach ($this->parameters_names as $parameter_name)
		{
			$value = null;
			if (isset($captured_parameters[$i]))
			{
				$value = $captured_parameters[$i];
			}
			AppContext::get_request()->set_getvalue($parameter_name, $value);
			$i++;
		}
	}

	private function do_call()
	{
		$controller = new $this->classname();
		if (!($controller instanceof Controller))
		{
			throw new NoSuchControllerException($this->classname);
		}
		$controller_to_execute = $controller->get_right_controller_regarding_authorizations();
		$response = $controller_to_execute->execute(AppContext::get_request());
		$response->send();
	}
}
?>