<?php
/*##################################################
 *		                         AdminPatternsConfigController.class.php
 *                            -------------------
 *
 *   begin                : July 26, 2018
 *   copyright            : (C) 2018 Arnaud Genet
 *   email                : elenwii@phpboost.com
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

/**
 * @author Arnaud Genet <elenwii@phpboost.com>
 */
class AdminPatternsConfigController extends AdminModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $lang;
	private $admin_common_lang;

	/**
	 * @var PatternsConfig
	 */
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('patterns_unauthorized_modules')->set_hidden(!$this->config->is_patterns_enabled());
			$this->form->get_field_by_id('patterns_unauthorized_modules')->set_selected_options($this->config->get_patterns_unauthorized_modules());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminPatternsDisplayResponse($tpl, $this->lang['config.patterns.title']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'patterns');
		$this->admin_common_lang = LangLoader::get('admin-common');
		$this->config = PatternsConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('config', $this->admin_common_lang['configuration']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('patterns_enabled', $this->lang['config.patterns.enabled'], $this->config->is_patterns_enabled(),
			array('class' => 'top-field', 'events' => array('click' => '
				if (HTMLForms.getField("patterns_enabled").getValue()) {
					HTMLForms.getField("patterns_unauthorized_modules").enable();
				} else {
					HTMLForms.getField("patterns_unauthorized_modules").disable();
				}')
			)
		));


		$fieldset->add_field(new FormFieldMultipleSelectChoice('patterns_unauthorized_modules', $this->admin_common_lang['config.forbidden-module'], $this->config->get_patterns_unauthorized_modules(), ModulesManager::generate_unauthorized_module_option('patterns'),
			array('size' => 12, 'description' => $this->lang['config.patterns.explain'], 'hidden' => !$this->config->is_patterns_enabled())
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{

		if ($this->form->get_value('patterns_enabled'))
		{
			$this->config->set_patterns_enabled(true);
			$unauthorized_modules = array();
			foreach ($this->form->get_value('patterns_unauthorized_modules') as $field => $option)
			{
				$unauthorized_modules[] = $option->get_raw_value();
			}
			$this->config->set_patterns_unauthorized_modules($unauthorized_modules);
		}
		else
		{
			$this->config->set_patterns_enabled(false);
			$this->config->set_patterns_unauthorized_modules(array());
		}

		PatternsConfig::save();
	}
}
?>
