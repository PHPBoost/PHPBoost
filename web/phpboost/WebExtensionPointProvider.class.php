<?php
/*##################################################
 *                              webExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : July 7, 2008
 *   copyright            : (C) 2008 Régis Viarre
 *   email                : crowkait@phpboost.com
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

class WebExtensionPointProvider extends ExtensionPointProvider
{
   public function __construct()
    {
		$this->sql_querier = PersistenceContext::get_sql();
        parent::__construct('web');
    }

	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('web.css');
		return $module_css_files;
	}
    
	public function get_cache()
	{
		$code = 'global $CAT_WEB;' . "\n";
		
		$result = $this->sql_querier->query_while("SELECT id, name, secure
		FROM " . PREFIX . "web_cat
		WHERE aprob = 1", __LINE__, __FILE__);
		while ($row = $this->sql_querier->fetch_assoc($result))
		{		
			$code .= '$CAT_WEB[\'' . $row['id'] . '\'][\'secure\'] = ' . var_export($row['secure'], true) . ';' . "\n";
			$code .= '$CAT_WEB[\'' . $row['id'] . '\'][\'name\'] = ' . var_export($row['name'], true) . ';' . "\n";
		}
		
		return $code;	
	}
	
	public function home_page()
	{
		return new WebHomePageExtensionPoint();
	}
	
	public function comments()
	{
		return new CommentsTopics(array(
			new WebCommentsTopic()
		));
	}
}
?>