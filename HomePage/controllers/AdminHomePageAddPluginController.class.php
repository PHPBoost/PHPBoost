<?php
/*##################################################
 *                      AdminHomePageAddPluginController.class.php
 *                            -------------------
 *   begin                : March 14, 2012
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

class AdminHomePageAddPluginController extends AdminController
{
	private $lang;
	private $view;
	private $form;
	private $submit_button;

	public function execute(HTTPRequest $request)
	{
		$column_selected = $request->get_int('column', 1);
		$this->init();
		$this->build_form($column_selected);
		$this->view->put('FORM', $this->form->display());

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->view->put('MSG', MessageHelper::display($this->lang['success'], MessageHelper::SUCCESS));
		}

		return $this->response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'HomePage');
		$this->view = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
	}
	
	private function build_form($column_selected)
	{
		$this->form = new HTMLForm('HomePage');

		$fieldset = new FormFieldsetHTML('AddPlugin', $this->lang['plugin.add']);
		$this->form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('column', $this->lang['column'], $column_selected, array(
			new FormFieldSelectChoiceOption('1', '1'),
			new FormFieldSelectChoiceOption('2', '2'),
			new FormFieldSelectChoiceOption('3', '3')
		)));

		$fieldset->add_field(new FormFieldHomePagePluginSelect('PluginSelect', $this->lang['plugin.type']));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$this->form->add_button($this->submit_button);
		$this->form->add_button(new FormButtonReset());
	}
	
	private function save()
	{
		
	}
	
	private function response()
	{
		$response = new AdminMenuDisplayResponse($this->view);
		$response->set_title($this->lang['home_page']);

		$env = $response->get_graphical_environment();
		$env->set_page_title($this->lang['plugin.add']);
		return $response;
	}
}
?>