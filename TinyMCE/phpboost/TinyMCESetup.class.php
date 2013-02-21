<?php
/*##################################################
 *                             TinyMCESetup.class.php
 *                            -------------------
 *   begin                : January 17, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class TinyMCESetup extends DefaultModuleSetup
{
	public function uninstall()
	{
		$editors = AppContext::get_content_formatting_service()->get_available_editors();
		if (count($editors) > 1)
		{
			$default_editor = ContentFormattingConfig::load()->get_default_editor();
			if ($default_editor !== 'tinymce')
			{
				PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('user_editor' => $default_editor), 
					'WHERE user_editor=:old_user_editor', array('old_user_editor' => 'tinymce'
				));
				return null;
			}
			else
			{
				return LangLoader::get_message('is_default_editor', 'editor-common');
			}
		}
		return LangLoader::get_message('last_editor_installed', 'editor-common');
	}
}
?>