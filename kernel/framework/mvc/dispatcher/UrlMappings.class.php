<?php
/**
 * @package     MVC
 * @subpackage  Dispatcher
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 03
 * @since       PHPBoost 3.0 - 2010 10 06
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UrlMappings implements UrlMappingsExtensionPoint
{
	private array $mappings;

	/** @param UrlMapping[] $mappings */
	public function __construct(array $mappings)
	{
		$this->mappings = $mappings;
	}

	public function list_mappings(): array
	{
		return $this->mappings;
	}
}
?>
