<?php
/*##################################################
 *                      TextEditorPlugin.class.php
 *                            -------------------
 *   begin                : February 22, 2012
 *   copyright            : (C) 2012 Kévin MASSY
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

class TextEditorPlugin extends Plugin
{
	public function __construct()
	{
		parent::__construct('Text-editor', $this->build_view(), true);
		$this->set_configuration(HomePageConfig::load()->get_plugin($this->get_id()));
		$this->set_form_configuration(new TextEditorPluginFormConfiguration($this->get_id()));
	}
	
	private function build_view()
	{
		$tpl = new StringTemplate($this->get_configuration()->get_content());
		return $tpl;
	}
}
?>