<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2014 05 09
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminQaptchaConfig extends DefaultAdminModuleController
{
	public static function __static()
	{
		self::$module_id = 'qaptcha';
	}

	public function __construct($locale = '')
	{
		self::$module_id = 'qaptcha';

		$this->init_parameters($locale);
		$this->init_view();
	}

	protected function init_parameters($locale = '')
	{
		$this->request = AppContext::get_request();
		$this->config = QaptchaConfig::load();
		$this->lang = LangLoader::get_all_langs(self::$module_id, $locale = '');
	}

	protected function init_view()
	{
		$this->view = $this->get_template_to_use();

		$this->view->add_lang($this->lang);
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->execute_edit_config_hook();
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return $this->build_response($this->view);
	}

	private function build_form()
	{
		$form = new HTMLForm(self::class);

		$fieldset = new FormFieldsetHTML('configuration', StringVars::replace_vars($this->lang['form.module.title'], ['module_name' => self::get_module()->get_configuration()->get_name()]));
		$form->add_fieldset($fieldset);

		$this->display_fields($fieldset);

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function display_fields(FormFieldset $fieldset)
	{
		$fieldset->add_field(new QaptchaFormFieldQuestions('items', $this->lang['questioncaptcha.config.label'], $this->config->get_items(),
			['description' => $this->lang['questioncaptcha.config.label.description'], 'class' => 'full-field']
		));
	}

	private function save()
	{
		$this->config->set_items($this->form->get_value('items'));

		QaptchaConfig::save();
	}

	protected function execute_edit_config_hook()
	{
		HooksService::execute_hook_action('edit_config', self::$module_id, ['title' => StringVars::replace_vars($this->lang['form.module.title'], ['module_name' => self::get_module_configuration()->get_name()]), 'url' => ModulesUrlBuilder::configuration()->rel()]);
	}

	private function build_response(View $view)
	{
		$title = $this->lang['form.configuration'];

		$response = new AdminMenuDisplayResponse($view);
		$response->set_title($title);
		$response->add_link($title, QaptchaUrlBuilder::configuration());
		$env = $response->get_graphical_environment();
		$env->set_page_title($title);

		return $response;
	}

	public static function get_form_fields(FormFieldset $fieldset, $locale = '')
	{
		$object = new self($locale);
		return $object->display_fields($fieldset);
	}

	public static function save_config(HTMLForm $form)
	{
		$object = new self();
		$object->form = $form;
		$object->save();
	}

	public static function get_module()
	{
		self::$module = ModulesManager::get_module('qaptcha');

		return self::$module;
	}
}
?>
