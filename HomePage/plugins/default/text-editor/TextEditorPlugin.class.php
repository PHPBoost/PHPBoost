<?php
/*##################################################
 *                      TextEditorPlugin.class.php
 *                            -------------------
 *   begin                : February 22, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class TextEditorPlugin extends Plugin
{
	public function __construct($id = 0)
	{
		$this->set_id($id);
		parent::__construct('Text-editor', $this->build_view(), true);
	}
	
	private function build_view()
	{
		$tpl = new StringTemplate($this->get_configuration()->get_content());
		return $tpl;
	}
	
	public function get_configuration()
	{
		return TextEditorPluginConfiguration::load($this->get_id());
	}
	
	public function get_fieldset_configuration(HTMLForm $form)
	{
		return new TextEditorPluginFieldsetConfiguration($this->get_id(), $this->get_configuration(), $form);
	}
}
?>