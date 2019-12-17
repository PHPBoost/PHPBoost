<?php
/**
 * Call the controller method matching an url
 * @package     MVC
 * @subpackage  Dispatcher
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 15
 * @since       PHPBoost 3.0 - 2009 06 08
 * @contributor mipel <mipel@phpboost.com>
*/

class UrlControllerMapper extends AbstractUrlMapper
{
	private $classname;
	private $parameters_names;

	/**
	 * build a new UrlDispatcherItem
	 * @param string $classpath the controller classname
	 * @param string $capture_regex the regular expression matching the url
	 * and capturing the controller method parameters. By default, match the empty url <code>/</code>
     * @param string $parameters_names the names of the parameters in the capture order
	 * @throws NoSuchControllerException
	 */
	public function __construct($classname, $capture_regex = '`^/?$`u', $parameters_names = array())
	{
		$this->classname =& $classname;
		$this->parameters_names = $parameters_names;
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
