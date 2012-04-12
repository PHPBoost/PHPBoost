<?php
/*##################################################
 *                      TextEditorPluginFormConfiguration.class.php
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

class TextEditorPluginFormConfiguration extends PluginFormConfiguration
{
	protected function create_form()
	{
		$form = new HTMLForm(__CLASS__);
		$fieldset = new FormFieldsetHTML('config', 'config');
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('contents', 'contents', $this->get_plugin_configuration()->get_content()));
	
		$form->add_button($submit_button = new FormButtonDefaultSubmit());
		$form->add_button(new FormButtonReset());
		
		$this->set_form($form);
		$this->set_submit_button($submit_button);

		return $form;
	}
	
	protected function handle_submit()
	{
		$this->get_plugin_configuration()->set_content($this->get_form()->get_value('contents'));
		$this->save();
	}
}
?>