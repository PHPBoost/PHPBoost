<?php
/*##################################################
 *		                         ModuleTreeLinks.class.php
 *                            -------------------
 *   begin                : November 15, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
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