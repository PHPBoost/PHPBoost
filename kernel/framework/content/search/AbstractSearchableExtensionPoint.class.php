<?php
/**
 * @package     Content
 * @subpackage  Search
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 02 08
*/

abstract class AbstractSearchableExtensionPoint implements SearchableExtensionPoint
{
	private $has_search_options;
	private $has_customized_results;

	public function __construct($has_search_options = false, $has_customized_results = false)
	{
		$this->has_search_options = $has_search_options;
		$this->has_customized_results = $has_customized_results;
	}

	/**
	 * {@inheritDoc}
	 */
	public function has_search_options()
	{
		return $this->has_search_options;
	}

	public function has_customized_results()
	{
		return $this->has_customized_results;
	}
}
?>
