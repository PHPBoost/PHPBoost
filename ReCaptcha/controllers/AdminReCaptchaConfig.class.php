<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 25
 * @since       PHPBoost 4.1 - 2015 09 18
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminReCaptchaConfig extends AdminModuleController
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
	 * @var ReCaptchaConfig
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
		$this->lang = LangLoader::get('common', 'ReCaptcha');
		$this->config = ReCaptchaConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('config', $this->lang['config.title']);
		$form->add_fieldset($fieldset);

		$this->display_fields($fieldset);

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function display_fields(FormFieldset $fieldset)
	{
		$fieldset->add_field(new FormFieldFree('explain', '', $this->lang['config.recaptcha-explain']));

		$fieldset->add_field(new FormFieldTextEditor('site_key', $this->lang['config.site_key'], $this->config->get_site_key(),
			array('required' => true),
			array(new FormFieldConstraintLengthMin(30))
		));

		$fieldset->add_field(new FormFieldPasswordEditor('secret_key', $this->lang['config.secret_key'], $this->config->get_secret_key(),
			array('required' => true),
			array(new FormFieldConstraintLengthMin(30))
		));

		$fieldset->add_field(new FormFieldCheckbox('invisible_mode_enabled', $this->lang['config.invisible_mode_enabled'], $this->config->is_invisible_mode_enabled(),
			array(
				'class' => 'custom-checkbox',
				'description' => $this->lang['config.invisible_mode_enabled.explain']
			)
		));
	}

	private function save()
	{
		$this->config->set_site_key($this->form->get_value('site_key'));
		$this->config->set_secret_key($this->form->get_value('secret_key'));
		if ($this->form->get_value('invisible_mode_enabled'))
			$this->config->enable_invisible_mode();
		else
			$this->config->disable_invisible_mode();

		ReCaptchaConfig::save();
	}

	private function build_response(View $view)
	{
		$title = LangLoader::get_message('form.configuration', 'form-lang');

		$response = new AdminMenuDisplayResponse($view);
		$response->set_title($title);
		$response->add_link($this->lang['config.title'], DispatchManager::get_url('/ReCaptcha', '/admin/config/'));
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
