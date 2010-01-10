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
				$view->assign_vars(array(
					'C_RESULT' => true, 
					'TEXT' => $form->get_value('text'),
					'MULTI_LINE_TEXT' => $form->get_value('multi_line_text'),
					'RICH_TEXT' => $form->get_value('rich_text'),
					'RADIO' => $form->get_value('radio')->get_label(),
					'CHECKBOX' => var_export($form->get_value('checkbox'), true)
				));
			}
		}
		$view->add_subtemplate('form', $form->export());
		return new SiteDisplayResponse($view);
	}

	private function build_form(HTTPRequest $request)
	{
		$form = new Form('sandboxForm');
		
		// FIELDSET
		$fieldset = new FormFieldset('Fieldset');

		// SINGLE LINE TEXT
		$fieldset->add_field(new FormFieldTextEditor('text', 'Champ texte', 'toto', array(
			'class' => 'text', 'maxlength' => 25, 'description' => 'nom'),
			array(new RegexFormFieldConstraint('`^[a-z0-9_]+$`i'))
		));
		
		// MULTI LINE TEXT
		$fieldset->add_field(new FormFieldMultiLineTextEditor('multi_line_text', 'Champ text multi lignes', 'toto', array(
			'rows' => 6, 'cols' => 47, 'description' => 'Description'
		)));
		
		// RICH TEXT
		$fieldset->add_field(new FormFieldRichTextEditor('rich_text', 'Champ texte riche', 'toto <strong>tata</strong>'));
		
		// RADIO
		$default_option = new FormFieldRadioChoiceOption('Choix 1', '1');
		$fieldset->add_field(new FormFieldRadioChoice('radio', 'Choix énumération', $default_option, array(
				$default_option,
				new FormFieldRadioChoiceOption('Choix 2', '2')
			)
		));
		
		// CHECKBOX
		$fieldset->add_field(new FormFieldCheckbox('checkbox', 'Case à cocher', FormFieldCheckbox::CHECKED));
		
//		$fieldset->add_field(new FormFieldSelect('sex', array('title' => 'Sex'),
//			array(
//				new FormFieldSelectOption('Men', 1),
//				new FormFieldSelectOption('Women', 2),
//				new FormFieldSelectOption('?', -1, FormFieldSelectOption::SELECTED)
//			)
//		));
		
		//Select field
//		$fieldset->add_field(new FormFieldSelect('sex2', array('title' => 'Sex', 'multiple' => true),
//			array(
//				new FormFieldSelectOption('Men', 1),
//				new FormFieldSelectOption('Women', 2),
//				new FormFieldSelectOption('?', -1, FormFieldSelectOption::SELECTED)
//			)
//		));
		
		$form->add_fieldset($fieldset);
		
//		$fieldset_up = new FormFieldset('Upload file');
//		//File field
//		$fieldset_up->add_field(new FormFieldFilePicker('avatar', array('title' => 'Avatar', 'subtitle' => 'Upload a file', 'class' => 'file', 'size' => 30)));
//		//Radio button field
//		$fieldset_up->add_field(new FormFieldHidden('test', 1));
		
//		//Captcha
//		$captcha = new Captcha();
//		$fieldset->add_field(new FormFieldCaptcha('verif_code', $captcha));
//		
//		$form->add_fieldset($fieldset_up);
//				
//		$form->display_preview_button('contents');

		return $form;
	}
}
?>
