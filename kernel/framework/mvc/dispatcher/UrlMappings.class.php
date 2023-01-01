<?php
/**
 * @package     MVC
 * @subpackage  Dispatcher
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 10 06
*/

class UrlMappings implements UrlMappingsExtensionPoint
{
	private $mappings;

	/**
	 * @param UrlMapping[] $mappings
	 */
	public function __construct(array $mappings)
	{
		$this->mappings = $mappings;
	}

	/**
	 * {@inheritdoc}
	 */
	public function list_mappings()
	{
		return $this->mappings;
	}
}
?>
