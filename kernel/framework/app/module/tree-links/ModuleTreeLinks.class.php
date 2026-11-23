<?php
/**
 * @package     PHPBoost
 * @subpackage  Module\tree-links
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 23
 * @since       PHPBoost 4.1 - 2013 11 15
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ModuleTreeLinks
{
	private array $links = [];

	public function add_link(ModuleLink $link): void
	{
		$this->links[] = $link;
	}

	public function get_links(): array
	{
		return $this->links;
	}

	public function has_links(): bool
	{
		return !empty($this->links);
	}

	public function has_visible_links(): bool
	{
		if (!empty($this->links))
		{
			foreach ($this->links as $link)
			{
				if ($link->is_visible())
					return true;
			}
			return false;
		}
		else
			return false;
	}
}
?>
