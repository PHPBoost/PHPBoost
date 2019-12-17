<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 10 29
 * @since       PHPBoost 4.1 - 2015 09 30
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminDatabaseConfigController extends AdminModuleController
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
	 * @var DatabaseConfig
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
			$this->form->get_field_by_id('database_tables_optimization_day')->set_hidden(!$this->config->is_database_tables_optimization_enabled());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminDatabaseDisplayResponse($tpl, $this->lang['module_config_title']);
	}

	private function init()
	{
		$this->config = DatabaseConfig::load();
		$this->lang = LangLoader::get('common', 'database');
		$this->admin_common_lang = LangLoader::get('admin-common');
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('config', $this->admin_common_lang['configuration']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('database_tables_optimization_enabled', $this->lang['config.database-tables-optimization-enabled'], $this->config->is_database_tables_optimization_enabled(),
			array(
				'class' => 'half-field custom-checkbox', 
				'events' => array('change' => '
					if (HTMLForms.getField("database_tables_optimization_enabled").getValue()) {
						HTMLForms.getField("database_tables_optimization_day").enable();
					} else {
						HTMLForms.getField("database_tables_optimization_day").disable();
					}'
				)
			)
		));

		$date_lang = LangLoader::get('date-common');
		$fieldset->add_field(new FormFieldSimpleSelectChoice('database_tables_optimization_day', $this->lang['config.database-tables-optimization-day'], $this->config->get_database_tables_optimization_day(),
			array(
				new FormFieldSelectChoiceOption($date_lang['sunday'], 0),
				new FormFieldSelectChoiceOption($date_lang['monday'], 1),
				new FormFieldSelectChoiceOption($date_lang['tuesday'], 2),
				new FormFieldSelectChoiceOption($date_lang['wednesday'], 3),
				new FormFieldSelectChoiceOption($date_lang['thursday'], 4),
				new FormFieldSelectChoiceOption($date_lang['friday'], 5),
				new FormFieldSelectChoiceOption($date_lang['saturday'], 6),
				new FormFieldSelectChoiceOption($date_lang['every_month'], 7)
			),
			array('description' => $this->lang['config.database-tables-optimization-day.explain'], 'hidden' => !$this->config->is_database_tables_optimization_enabled())
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		$this->config->set_database_tables_optimization_enabled($this->form->get_value('database_tables_optimization_enabled'));

		if (!$this->form->field_is_disabled('database_tables_optimization_day'))
		{
			$this->config->set_database_tables_optimization_day($this->form->get_value('database_tables_optimization_day')->get_raw_value());
		}

		DatabaseConfig::save();
	}
}
?>
