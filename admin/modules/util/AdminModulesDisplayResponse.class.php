<?php
/*##################################################
 *                       AdminModulesDisplayResponse.class.php
 *                            -------------------
 *   begin                : September 20, 2011
 *   copyright            : (C) 2011 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

class AdminModulesDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);
		
		$lang = LangLoader::get('admin-modules-common');
		$this->set_title($lang['modules.module_management']);
		
		$this->add_link($lang['modules.installed_modules'], AdminModulesUrlBuilder::list_installed_modules());
		$this->add_link($lang['modules.add_module'], AdminModulesUrlBuilder::add_module());
		$this->add_link($lang['modules.update_module'], AdminModulesUrlBuilder::update_module());
		
		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}


?>