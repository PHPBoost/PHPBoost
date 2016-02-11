<?php
/*##################################################
 *		                         DatabaseTreeLinks.class.php
 *                            -------------------
 *   begin                : November 26, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
class DatabaseTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		$lang = LangLoader::get('common', 'database');
		$tree = new ModuleTreeLinks();
		
		$tree->add_link(new AdminModuleLink($lang['database.actions.database_management'], DatabaseUrlBuilder::database_management()));
		$tree->add_link(new AdminModuleLink($lang['database.actions.db_sql_queries'], DatabaseUrlBuilder::db_sql_queries()));
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin-common'), DatabaseUrlBuilder::configuration()));
		
		return $tree;
	}
}
?>