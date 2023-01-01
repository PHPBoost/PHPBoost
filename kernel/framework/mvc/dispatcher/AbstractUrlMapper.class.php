<?php
/**
 * Call the controller method matching an url
 * @package     MVC
 * @subpackage  Dispatcher
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 17
*/

abstract class AbstractUrlMapper implements UrlMapper
{
	/**
	 * @var string
	 */
	private $capture_regex;

	/**
	 * @var string[]
	 */
	private $captured_parameters = array();

	public function __construct($capture_regex)
	{
		$this->capture_regex = $capture_regex;
	}

	public function match($url)
	{
		$match = preg_match($this->capture_regex, $url, $this->captured_parameters);
		if ($match === false)
		{
			throw new MalformedUrlMapperRegexException($this->capture_regex, $url);
		}
		return $match > 0;
	}

	protected function get_captured_parameters()
	{
		return $this->captured_parameters;
	}
}
?>
