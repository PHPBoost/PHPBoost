<?php
/*##################################################
 *		                         PollTreeLinks.class.php
 *                            -------------------
 *   begin                : November 23, 2013
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
class PollTreeLinks implements ModuleTreeLinksExtensionPoint
{
	public function get_actions_tree_links()
	{
		global $LANG;
		load_module_lang('poll'); //Chargement de la langue du module.
		
		$tree = new ModuleTreeLinks();
		
		$manage_poll_link = new AdminModuleLink($LANG['poll.manage'], new Url('/poll/admin_poll.php'));
		$manage_poll_link->add_sub_link(new AdminModuleLink($LANG['poll.manage'], new Url('/poll/admin_poll.php')));
		$manage_poll_link->add_sub_link(new AdminModuleLink($LANG['poll_add'], new Url('/poll/admin_poll_add.php')));
		$tree->add_link($manage_poll_link);
		
		$tree->add_link(new AdminModuleLink(LangLoader::get_message('configuration', 'admin'), new Url('/poll/admin_poll_config.php')));
		
		return $tree;
	}
}
?>