<?php
/*##################################################
 *                          SandboxController.class.php
 *                            -------------------
 *   begin                : December 20, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

class SandboxController extends ModuleController
{
	public function execute(HTTPRequest $request)
	{
		$view = new View('sandbox/SandboxController.tpl');
		$form = $this->build_form($request);
		if ($request->is_post_method())
		{
			if ($form->validate())
			{
				echo $form->get_value('pseudo') . ' poste ' . $form->get_value('content');
			}
		}
		$view->add_subtemplate('form', $form->export());
		return new SiteDisplayResponse($view);
	}

	private function build_form(HTTPRequest $request)
	{
		$form = new Form('sandboxForm');
		$fieldset = new FormFieldset('This is a fieldset');

		$fieldset->add_field(new FormTextEdit('pseudo', 'This is a text field', 'toto', array(
			'class' => 'text', 'required' => 'Le pseudo est obligatoire',
			'maxlength' => 25),
			array(new RegexFormFieldConstraint('`^[a-z0-9_]+$`i'))
		));
		$fieldset->add_field(new FormTextArea('content', 'This is a textarea', 'toto', array(
			'rows' => 10, 'cols' => 47
		)));
		
//		$fieldset->add_field(new FormTextArea('rich_content', 'This is a rich text editor', 'toto', array(
//			'rows' => 10, 'cols' => 47, 'editor' => ContentFormattingFactory::get_editor())
//		));
		
//		$fieldset->add_field(new FormRadioChoice('choice', array('title' => 'Answer'),
//			array(
//				new FormRadioChoiceOption('Choix1', 1),
//				new FormRadioChoiceOption('Choix2', 2, FormRadioChoiceOption::CHECKED)
//			)
//		));
		$fieldset->add_field(new FormCheckbox('checkbox', 'This is a checkbox', FormCheckbox::CHECKED));
		
//		$fieldset->add_field(new FormSelect('sex', array('title' => 'Sex'),
//			array(
//				new FormSelectOption('Men', 1),
//				new FormSelectOption('Women', 2),
//				new FormSelectOption('?', -1, FormSelectOption::SELECTED)
//			)
//		));
		
		//Select field
//		$fieldset->add_field(new FormSelect('sex2', array('title' => 'Sex', 'multiple' => true),
//			array(
//				new FormSelectOption('Men', 1),
//				new FormSelectOption('Women', 2),
//				new FormSelectOption('?', -1, FormSelectOption::SELECTED)
//			)
//		));
		
		$form->add_fieldset($fieldset);
		
//		$fieldset_up = new FormFieldset('Upload file');
//		//File field
//		$fieldset_up->add_field(new FormFileUploader('avatar', array('title' => 'Avatar', 'subtitle' => 'Upload a file', 'class' => 'file', 'size' => 30)));
//		//Radio button field
//		$fieldset_up->add_field(new FormHiddenField('test', 1));
		
//		//Captcha
//		$captcha = new Captcha();
//		$fieldset->add_field(new FormCaptchaField('verif_code', $captcha));
//		
//		$form->add_fieldset($fieldset_up);
//				
//		$form->display_preview_button('contents');

		return $form;
	}
}
?>
