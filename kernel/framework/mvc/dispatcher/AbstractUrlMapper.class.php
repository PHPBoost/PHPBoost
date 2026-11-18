<?php
/**
 * Call the controller method matching an url
 * @package     MVC
 * @subpackage  Dispatcher
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 03
 * @since       PHPBoost 3.0 - 2009 10 17
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractUrlMapper implements UrlMapper
{
	private string $capture_regex = '';
	private array $captured_parameters = [];

	public function __construct(string $capture_regex)
	{
		$this->capture_regex = $capture_regex;
	}

	public function match(string $url): bool
	{
		$match = preg_match($this->capture_regex, $url, $this->captured_parameters);
		if ($match === false)
		{
			throw new MalformedUrlMapperRegexException($this->capture_regex, $url);
		}
		return $match > 0;
	}

	protected function get_captured_parameters(): array
	{
		return $this->captured_parameters;
	}
}
?>
