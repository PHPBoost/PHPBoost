<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 16
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

	/**
	 * @var DatabaseConfig
	 */
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$view = new StringTemplate('# INCLUDE MESSAGE_HELPER # # INCLUDE FORM #');
		$view->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('database_tables_optimization_day')->set_hidden(!$this->config->is_database_tables_optimization_enabled());
			$view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.success.config', 'warning-lang'), MessageHelper::SUCCESS, 5));
		}

		$view->put('FORM', $this->form->display());

		return new AdminDatabaseDisplayResponse($view, $this->lang['database.config.module.title']);
	}

	private function init()
	{
		$this->config = DatabaseConfig::load();
		$this->lang = LangLoader::get('common', 'database');
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('config', $this->lang['database.config.module.title']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('database_tables_optimization_enabled', $this->lang['database.config.enable.tables.optimization'], $this->config->is_database_tables_optimization_enabled(),
			array(
				'class' => 'half-field top-field custom-checkbox',
				'events' => array('change' => '
					if (HTMLForms.getField("database_tables_optimization_enabled").getValue()) {
						HTMLForms.getField("database_tables_optimization_day").enable();
					} else {
						HTMLForms.getField("database_tables_optimization_day").disable();
					}'
				)
			)
		));

		$date_lang = LangLoader::get('date-lang');
		$fieldset->add_field(new FormFieldSimpleSelectChoice('database_tables_optimization_day', $this->lang['database.config.tables.optimization.day'], $this->config->get_database_tables_optimization_day(),
			array(
				new FormFieldSelectChoiceOption($date_lang['date.sunday'], 0),
				new FormFieldSelectChoiceOption($date_lang['date.monday'], 1),
				new FormFieldSelectChoiceOption($date_lang['date.tuesday'], 2),
				new FormFieldSelectChoiceOption($date_lang['date.wednesday'], 3),
				new FormFieldSelectChoiceOption($date_lang['date.thursday'], 4),
				new FormFieldSelectChoiceOption($date_lang['date.friday'], 5),
				new FormFieldSelectChoiceOption($date_lang['date.saturday'], 6),
				new FormFieldSelectChoiceOption($date_lang['date.every.month'], 7)
			),
			array(
				'description' => $this->lang['database.config.tables.optimization.day.clue'],
				'hidden' => !$this->config->is_database_tables_optimization_enabled()
			)
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
