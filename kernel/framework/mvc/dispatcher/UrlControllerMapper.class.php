<?php
/**
 * Call the controller method matching an url
 * @package     MVC
 * @subpackage  Dispatcher
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 05
 * @since       PHPBoost 3.0 - 2009 06 08
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class UrlControllerMapper extends AbstractUrlMapper
{
	private $classname;
	private $parameters_names;
	private $module_id;

	/**
	 * build a new UrlDispatcherItem
	 * @param string $classpath the controller classname
	 * @param string $capture_regex the regular expression matching the url
	 * and capturing the controller method parameters. By default, match the empty url <code>/</code>
     * @param string $parameters_names the names of the parameters in the capture order
	 * @param string $module_id id of the current module (optional).
	 * @throws NoSuchControllerException
	 */
	public function __construct($classname, $capture_regex = '`^/?$`u', $parameters_names = array(), $module_id = '')
	{
		$this->classname =& $classname;
		$this->parameters_names = $parameters_names;
		$this->module_id = $module_id;
		parent::__construct($capture_regex);
	}

	/**
	 * Call the controller method if the url match and if the method exists
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
		$controller = $this->module_id ? new $this->classname($this->module_id) : new $this->classname();
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
