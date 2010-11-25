<?php
/*##################################################
 *                       RegisterFormUtil.class.php
 *                            -------------------
 *   begin                : September 18, 2010 2009
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
 
class RegisterFormUtil
{

	public static function return_array_lang_for_formbuilder()
	{
		$array = array();
		$langs_cache = LangsCache::load();
		foreach($langs_cache->get_installed_langs() as $lang => $properties)
		{
			if ($properties['auth'] == -1)
			{
				$info_lang = load_ini_file('../lang/', $lang);

				$array[] = new FormFieldSelectChoiceOption($info_lang['name'], $lang);
			}
			
		}
		
		return $array;
	}
	
	public static function return_array_editor_for_formubuilder()
	{
		$choices_list = array();
		$editors = array('bbcode' => 'BBCode', 'tinymce' => 'Tinymce');
		foreach ($editors as $code => $name)
		{
			$choices_list[] = new FormFieldSelectChoiceOption($name, $code);
		}
		return $choices_list;
	}
	
	public static function return_array_timezone_for_formubuilder()
	{
		$choices_list = array();
		$timezone = GeneralConfig::load()->get_site_timezone();
		for ($i = -12; $i <= 14; $i++)
		{
			$name = ($i !== 0 ? ($i > 0 ? ' + ' . $i : ' - ' . -$i) : '');
			$choices_list[] = new FormFieldSelectChoiceOption('[GMT' . $name . ']', $i);
		}
		return $choices_list;
	
	}
	
	public static function return_array_theme_for_formubuilder()
	{
		$choices_list = array();
		
		$user_accounts_config = UserAccountsConfig::load();
		
		if (!$user_accounts_config->is_users_theme_forced()) // If users can choose the theme they use
		{
			foreach(ThemesCache::load()->get_installed_themes() as $theme => $theme_properties)
			{
				if (UserAccountsConfig::load()->get_default_theme() == $theme || ($theme_properties['auth'] == -1 && $theme != 'default'))
				{				
					$info_theme = load_ini_file('../templates/' . $theme . '/config/', UserAccountsConfig::load()->get_default_lang());
					$choices_list[] = new FormFieldSelectChoiceOption( $info_theme['name'], $theme);
				}
			}
		}
		else
		{
			$choices_list[] = new FormFieldSelectChoiceOption('Base', 'base');
		}
		return $choices_list;
	
	}
}

?>