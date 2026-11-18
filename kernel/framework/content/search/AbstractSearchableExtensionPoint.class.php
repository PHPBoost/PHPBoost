<?php
/**
 * @package     Content
 * @subpackage  Search
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 10
 * @since       PHPBoost 3.0 - 2010 02 08
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractSearchableExtensionPoint implements SearchableExtensionPoint
{
	private bool $has_search_options = false;
	private bool $has_customized_results = false;

	public function __construct(bool $has_search_options = false, bool $has_customized_results = false)
	{
		$this->has_search_options = $has_search_options;
		$this->has_customized_results = $has_customized_results;
	}

	public function has_search_options(): bool
	{
		return $this->has_search_options;
	}

	public function has_customized_results(): bool
	{
		return $this->has_customized_results;
	}
}
?>
