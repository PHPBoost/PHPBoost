<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 26
 * @since       PHPBoost 4.0 - 2014 05 09
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminQuestionCaptchaConfig extends AdminModuleController
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
	 * @var QuestionCaptchaConfig
	 */
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$view = new StringTemplate('# INCLUDE MESSAGE_HELPER # # INCLUDE FORM #');

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.success.config', 'warning-lang'), MessageHelper::SUCCESS, 5));
		}

		$view->put('FORM', $this->form->display());

		return $this->build_response($view);
	}

	private function init()
	{
		$this->lang = array_merge(
			LangLoader::get('form-lang'),
			LangLoader::get('common', 'QuestionCaptcha')
		);
		$this->config = QuestionCaptchaConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset);

		$this->display_fields($fieldset);

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function display_fields(FormFieldset $fieldset)
	{
		$fieldset->add_field(new QuestionCaptchaFormFieldQuestions('items', $this->lang['questioncaptcha.config.label'], $this->config->get_items(),
			array('description' => $this->lang['questioncaptcha.config.label.description'], 'class' => 'full-field')
		));
	}

	private function save()
	{
		$this->config->set_items($this->form->get_value('items'));

		QuestionCaptchaConfig::save();

		HooksService::execute_hook_action('edit_config', self::$module_id, array('title' => StringVars::replace_vars($this->lang['form.module.title'], array('module_name' => self::get_module_configuration()->get_name())), 'url' => ModulesUrlBuilder::configuration()->rel()));
	}

	private function build_response(View $view)
	{
		$title = $this->lang['form.configuration'];

		$response = new AdminMenuDisplayResponse($view);
		$response->set_title($title);
		$response->add_link($title, QuestionCaptchaUrlBuilder::configuration());
		$env = $response->get_graphical_environment();
		$env->set_page_title($title);

		return $response;
	}

	public static function get_form_fields(FormFieldset $fieldset)
	{
		$object = new self();
		$object->init();
		return $object->display_fields($fieldset);
	}

	public static function save_config(HTMLForm $form)
	{
		$object = new self();
		$object->init();
		$object->form = $form;
		$object->save();
	}
}
?>
