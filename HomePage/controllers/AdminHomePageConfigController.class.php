<?php
/*##################################################
 *                      AdminHomePageConfigController.class.php
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

class AdminHomePageConfigController extends AdminController
{
	private $lang;

	public function execute(HTTPRequest $request)
	{
		$this->init();
		$this->build_view();

		return $this->response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'HomePage');
	}

	private function build_view()
	{
		$view = new FileTemplate('HomePage/AdminHomePageConfigController.tpl');
		$view->add_lang($this->lang);
		
		$view->put_all(array(
			'CONFIGURATION' => $this->build_form_configuration()->display()
		));
		
		return $view;
	}
	
	private function build_form_configuration()
	{
		$form = new HTMLForm('HomePageConfig');
		$fieldset = new FormFieldsetHorizontal('HomePageConfig');
		$form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldSimpleSelectChoice('number_columns', $this->lang['number_columns'] . ' : ', HomePageConfig::load()->get_number_columns(), array(
			new FormFieldSelectChoiceOption('1', '1'),
			new FormFieldSelectChoiceOption('2', '2'),
			new FormFieldSelectChoiceOption('3', '3')
		)));
		return $form;
	}
	
	private function response()
	{
		$response = new AdminMenuDisplayResponse($this->build_view());
		$response->set_title($this->lang['home_page']);
		
		$env = $response->get_graphical_environment();
		$env->set_page_title($this->lang['config']);
		return $response;
	}
}
?>