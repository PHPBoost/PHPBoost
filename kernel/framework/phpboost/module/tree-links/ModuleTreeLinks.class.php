<?php
/**
 * @package     PHPBoost
 * @subpackage  Module\tree-links
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 4.1 - 2013 11 15
*/

class ModuleTreeLinks
{
	private $links = array();

	public function add_link(ModuleLink $link)
	{
		$this->links[] = $link;
	}

	public function get_links()
	{
		return $this->links;
	}

	public function has_links()
	{
		return !empty($this->links);
	}

	public function has_visible_links()
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
