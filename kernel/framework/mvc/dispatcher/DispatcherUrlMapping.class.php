<?php
/**
 * @package     MVC
 * @subpackage  Dispatcher
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 06
 * @since       PHPBoost 3.0 - 2010 10 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class DispatcherUrlMapping extends UrlMapping
{
	private $high_priority = false;
	private $low_priority = false;

	/**
	 * @param UrlMapping[] $mappings
	 */
	public function __construct($dispatcher_name, $match = '([\w/_-]*)$', $from_path = '', $redirect_path = '', $high_priority = false)
	{
		if (!empty($from_path))
		{
			if ($from_path == 'root')
			{
				$from = '^' . $match;
				$to = $dispatcher_name . '?url=/' . ($redirect_path ? $redirect_path : '$1');
				$this->low_priority = true;
			}
			else
			{
				$dispatcher_path = ltrim(TextHelper::substr($from_path, 0, TextHelper::strrpos($from_path, '/') + 1), '/');
				$from = '^' . $dispatcher_path . $match;
				$to = $dispatcher_name . '?url=/' . $dispatcher_path . '$1';
				$this->high_priority = true;
			}
		}
		else if ($high_priority)
		{
			$dispatcher_path = ltrim(TextHelper::substr($dispatcher_name, 0, TextHelper::strrpos($dispatcher_name, '/') + 1), '/');
			$from = '^' . $dispatcher_path . $match;
			$to = $dispatcher_name . '?url=/' . ($redirect_path ? $redirect_path : '$1');
			$this->high_priority = true;
		}
		else
		{
			$dispatcher_path = ltrim(TextHelper::substr($dispatcher_name, 0, TextHelper::strrpos($dispatcher_name, '/') + 1), '/');
			$from = '^' . $dispatcher_path . $match;
			$to = $dispatcher_name . '?url=/$1';
		}
		parent::__construct($from, $to);
	}

	/**
	 * Check if the Url must be placed in high priority in the .htaccess file
	 */
	public function is_high_priority()
	{
		return $this->high_priority;
	}

	/**
	 * Check if the Url must be placed in low priority in the .htaccess file
	 */
	public function is_low_priority()
	{
		return $this->low_priority;
	}
}
?>
