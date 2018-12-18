<?php
/*##################################################
 *                       ForumModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : March 09, 2017
 *   copyright            : (C) 2017 Julien BRISWALTER
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

class ForumModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('forum');
	}
	
	public function execute()
	{
		if (ModulesManager::is_module_installed('forum'))
		{
			$this->update_content();
		}
	}
	
	public function update_content()
	{
		UpdateServices::update_table_content(PREFIX . 'forum_alerts');
		UpdateServices::update_table_content(PREFIX . 'forum_msg');
		UpdateServices::update_table_content(PREFIX . 'member_extended_fields', 'user_sign', 'user_id');
	}
}
?>