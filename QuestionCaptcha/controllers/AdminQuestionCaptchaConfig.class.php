<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 20
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
		$view->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$view->put('FORM', $this->form->display());

		return $this->build_response($view);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'QuestionCaptcha');
		$this->config = QuestionCaptchaConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('configuration', StringVars::replace_vars(LangLoader::get_message('configuration.module.title', 'admin-common'), array('module_name' => self::get_module()->get_configuration()->get_name())));
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
	}

	private function build_response(View $view)
	{
		$title = LangLoader::get_message('configuration', 'admin');

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
