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
class ModuleTreeLinks implements ModuleTreeLinksExtensionPoint
{
	private $actions_tree_links = array();
	private $tree_links = array();
	
	public function add_actions_tree_links(ModuleLink $actions_links)
	{
		$this->actions_tree_links[] = $actions_links;
	}
	
	public function add_tree_links(ModuleLink $links)
	{
		$this->tree_links[] = $links;
	}
	
	public function get_actions_tree_links()
	{
		return $this->actions_tree_links;
	}
	
	public function get_tree_links()
	{
		return $this->tree_links;
	}
}
?>