<?php
/*##################################################
 *                      TextEditorPluginConfiguration.class.php
 *                            -------------------
 *   begin                : February 22, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class TextEditorPluginConfiguration extends PluginConfiguration
{
	private $content = 'cxwcxw';
	
	public function set_content($content)
	{
		$this->content = $content;
	}
	
	public function get_content()
	{
		return $this->content;
	}
	
	public static function load($id, $class = '')
	{
		return parent::load($id, __CLASS__);
	}
}
?>