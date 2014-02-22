<?php
/*##################################################
 *		                         BugtrackerTreeLinks.class.php
 *                            -------------------
 *   begin                : November 25, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */
class BugtrackerTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'bugtracker');
		$tree = new ModuleTreeLinks();
		
		$tree->add_link(new AdminModuleLink($lang['titles.admin.config'], BugtrackerUrlBuilder::configuration()));
		$tree->add_link(new AdminModuleLink($lang['titles.admin.authorizations.manage'], BugtrackerUrlBuilder::authorizations()));
		
		$tree->add_link(new ModuleLink($lang['titles.add'], BugtrackerUrlBuilder::add(), BugtrackerAuthorizationsService::check_authorizations()->write()));
	
		return $tree;
	}
}
?>