<?php
/*##################################################
 *                       SandboxAddExtendedFieldController.class.php
 *                            -------------------
 *   begin                : May 2, 2010
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

class SandboxAddExtendedFieldController extends ModuleController
{
	private $lang;

	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;

	public function execute(HTTPRequest $request)
	{
		$this->init();
		$form = $this->build_form();

		$tpl = new StringTemplate('<script type="text/javascript">
				<!--
					Event.observe(window, \'load\', function() {
						HTMLForms.getField("possible_values").disable();
						HTMLForms.getField("regex").disable();
					});
				-->		
				</script># INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submitted() && $form->validate())
		{
			$this->save($form);
		}

		$tpl->put('FORM', $form->display());

		return new SiteDisplayResponse($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin');
	}

	private function build_form()
	{
		$form = new HTMLForm('extended_fields');
		
		$fieldset = new FormFieldsetHTML('add_fields', $this->lang['extend_field_add']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('field_type', $this->lang['type'], '1',
			array(
				new FormFieldSelectChoiceOption($this->lang['short_text'], '1'),
				new FormFieldSelectChoiceOption($this->lang['long_text'], '2'),
				new FormFieldSelectChoiceOption($this->lang['sel_uniq'], '3'),
				new FormFieldSelectChoiceOption($this->lang['sel_mult'], '4'),
				new FormFieldSelectChoiceOption($this->lang['check_uniq'], '5'),
				new FormFieldSelectChoiceOption($this->lang['check_mult'], '6'),
			),
			array('events' => array('change' => '
				if (HTMLForms.getField("field_type").getValue() < 3 ){ 
					HTMLForms.getField("regex_type").enable(); 
					HTMLForms.getField("possible_values").disable(); 
				} else { 
					HTMLForms.getField("regex_type").disable(); 
					HTMLForms.getField("regex").disable(); 
					HTMLForms.getField("possible_values").enable();
					}'))
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('regex_type', $this->lang['predef_regexp'], '0',
			array(
				new FormFieldSelectChoiceOption('--', '0'),
				new FormFieldSelectChoiceOption($this->lang['personnal_regex'], '1'),
				new FormFieldSelectChoiceOption($this->lang['figures'], '2'),
				new FormFieldSelectChoiceOption($this->lang['letters'], '3'),
				new FormFieldSelectChoiceOption($this->lang['figures_letters'], '4'),
				new FormFieldSelectChoiceOption($this->lang['mail'], '5'),
				new FormFieldSelectChoiceOption($this->lang['website'], '6'),
			),
			array('events' => array('change' => '
				if (HTMLForms.getField("regex_type").getValue() == 1) { 
					HTMLForms.getField("regex").enable(); 
				} else { 
					HTMLForms.getField("regex").disable(); 
				}'))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('regex', $this->lang['personnal_regex'], '', array(
			'class' => 'text', 'maxlength' => 25, 'description' => $this->lang['regex_explain'])
		));
		
		$fieldset->add_field(new FormFieldRadioChoice('field_required', $this->lang['require_field'], '1',
			array(
				new FormFieldRadioChoiceOption('Oui', '1'),
				new FormFieldRadioChoiceOption('Non', '2')
			)
		));
		
		$fieldset->add_field(new FormFieldTextEditor('name', $this->lang['name'], '', array(
			'class' => 'text', 'maxlength' => 25)
		));
		
		$fieldset->add_field(new FormFieldMultiLineTextEditor('description', $this->lang['description'], '',
		array('rows' => 4, 'cols' => 47)
		));
		
		$fieldset->add_field(new FormFieldTextEditor('possible_values', $this->lang['possible_values'], '', array(
			'class' => 'text', 'size' => 60, 'description' => $this->lang['possible_values_explain'])
		));
		
		$fieldset->add_field(new FormFieldTextEditor('default_values', $this->lang['default_values'], '', array(
			'class' => 'text', 'size' => 60, 'description' => $this->lang['default_values_explain'])
		));

		$fieldset->add_field(new FormFieldRadioChoice('display', LangLoader::get_message('display', 'main'), '1',
			array(
				new FormFieldRadioChoiceOption($this->lang['yes'], '1'),
				new FormFieldRadioChoiceOption($this->lang['no'], '2')
			)
		));
		
		/*$auth_settings = new AuthorizationsSettings(array(new ActionAuthorization(LangLoader::get_message('authorizations', 'main'), 2)));
		$auth_settings->build_from_auth_array(array('r1' => 3, 'r0' => 2, 'm1' => 1, '1' => 2));
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$fieldset->add_field($auth_setter);
		*/
		
		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		return $form;
	}

	private function save(HTMLForm $form)
	{
		$extended_field = new ExtendedField();
		$extended_field->set_name($form->get_value('name'));
		$extended_field->set_field_name(ExtendedField::rewrite_field_name($form->get_value('name')));
		$extended_field->set_position(PersistenceContext::get_sql()->query("SELECT MAX(position) + 1 FROM " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . "", __LINE__, __FILE__));
		$extended_field->set_description($form->get_value('description'));
		$extended_field->set_field_type($form->get_value('field_type')->get_raw_value());
		$extended_field->set_possible_values($form->get_value('possible_values'));
		$extended_field->set_default_values($form->get_value('default_values'));
		$extended_field->set_is_required($form->get_value('field_required')->get_raw_value());
		$extended_field->set_display($form->get_value('display')->get_raw_value());
		$extended_field->set_regex($form->get_value('regex_type')->get_raw_value());

		ExtendedFieldsService::add($extended_field);
	}
}

?>
