<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.3 - last update: 2019 07 30
 * @since   	PHPBoost 5.2 - 2019 07 30
*/

class SandboxPluginsController extends ModuleController
{
	private $tpl;

	/**
	 * @var HTMLForm
	 */
	private $tabs_form;
	private $wizard_form;

	private $common_lang;
	private $lang;

	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_tabs_button;
	private $submit_wizard_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view();


		return $this->generate_response();
	}

	private function init()
	{
		$this->common_lang = LangLoader::get('common', 'sandbox');
		$this->lang = LangLoader::get('plugins', 'sandbox');
		$this->tpl = new FileTemplate('sandbox/SandboxPluginsController.tpl');
		$this->tpl->add_lang($this->common_lang);
		$this->tpl->add_lang($this->lang);
	}

	private function build_view()
	{

		$this->build_tabs_form();
		$this->build_wizard_form();

		$this->tpl->put_all(array(
			'PRE_TABS_FORM' => file_get_contents('html/plugins/tabs-form.tpl'),
			'PRE_TABS_HTML' => file_get_contents('html/plugins/tabs-html.tpl'),
			'PRE_WIZARD_FORM' => file_get_contents('html/plugins/wizard-form.tpl'),
			'PRE_WIZARD_HTML' => file_get_contents('html/plugins/wizard-html.tpl'),
			'TABS_PHP_FORM' => $this->tabs_form->display(),
			'WIZARD_PHP_FORM' => $this->wizard_form->display()
		));

	}

	private function build_tabs_form()
	{
		$tabs_form = new HTMLForm('easytabs_form');
		$tabs_form->set_css_class('tab-container fieldset-content');

		//Configuration
		$fieldset_tab_menu = new FormFieldMenuFieldset('tab_menu', '');
		$tabs_form->add_fieldset($fieldset_tab_menu);

		$fieldset_tab_menu->add_field(new FormFieldActionLinkList('tab_menu_list',
			array(
				new FormFieldActionLinkElement($this->lang['plugins.menu.title'] . ' 01', '#easytabs_form_tab-01', 'fa-cog'),
				new FormFieldActionLinkElement($this->lang['plugins.menu.title'] . ' 02', '#easytabs_form_tab-02', 'fa-image'),
				new FormFieldActionLinkElement($this->lang['plugins.menu.title'] . ' 03', '#easytabs_form_tab-03', '', '../../articles/articles_mini.png', 'articles'),
			)
		));

		$fieldset_tab_one = new FormFieldsetHTML('tab-01', $this->lang['plugins.form.title'] . ' 01');
		$tabs_form->add_fieldset($fieldset_tab_one);

		$fieldset_tab_one->set_description($this->lang['plugins.short.content']);

		$fieldset_tab_one->add_field(new FormFieldSubTitle('tab_01b', $this->lang['plugins.form.subtitle'],''));

		$fieldset_tab_one->add_field(new FormFieldTextEditor('text', $this->lang['plugins.form.input'], ''));

		$fieldset_tab_two = new FormFieldsetHTML('tab-02', $this->lang['plugins.form.title'] . ' 02');
		$tabs_form->add_fieldset($fieldset_tab_two);

		$fieldset_tab_two->set_description($this->lang['plugins.large.content']);

		if (ModulesManager::is_module_installed('articles') & ModulesManager::is_module_activated('articles'))
		{
			$fieldset_tab_three = new FormFieldsetHTML('tab-03', $this->lang['plugins.form.title'] . ' 03');
			$tabs_form->add_fieldset($fieldset_tab_three);

			$fieldset_tab_three->set_description($this->lang['plugins.short.content']);
		}


		$this->submit_tabs_button = new FormButtonDefaultSubmit();
		$tabs_form->add_button($this->submit_tabs_button);
		$tabs_form->add_button(new FormButtonReset());

		$this->tabs_form = $tabs_form;
	}

	private function build_wizard_form()
	{
		$wizard_form = new WizardHTMLForm('wizard_form');
		$wizard_form->set_css_class('wizard-container fieldset-content');

		//Configuration
		$fieldset_tab_menu = new FormFieldMenuFieldset('tab_menu', '');
		$wizard_form->add_fieldset($fieldset_tab_menu);

		$fieldset_tab_menu->add_field(new WizardActionLinkList('tab_menu_list',
			array(
				new FormFieldActionLinkElement($this->lang['plugins.menu.title'] . ' 01', '#', 'fa-cog'),
				new FormFieldActionLinkElement($this->lang['plugins.menu.title'] . ' 02', '#', 'fa-image'),
				new FormFieldActionLinkElement($this->lang['plugins.menu.title'] . ' 03', '#', '', '../../articles/articles_mini.png', 'articles'),
			)
		));

		$fieldset_tab_one = new FormFieldsetHTML('tab-04', $this->lang['plugins.form.title'] . ' 01');
		$wizard_form->add_fieldset($fieldset_tab_one);
		$fieldset_tab_one->set_css_class('wizard-step');

		$fieldset_tab_one->set_description($this->lang['plugins.short.content']);

		$fieldset_tab_one->add_field(new FormFieldSubTitle('tab_01b', $this->lang['plugins.form.subtitle'],''));

		$fieldset_tab_one->add_field(new FormFieldTextEditor('text', $this->lang['plugins.form.input'], ''));

		$fieldset_tab_two = new FormFieldsetHTML('tab-05', $this->lang['plugins.form.title'] . ' 02');
		$wizard_form->add_fieldset($fieldset_tab_two);
		$fieldset_tab_two->set_css_class('wizard-step');

		$fieldset_tab_two->set_description($this->lang['plugins.large.content']);

		if (ModulesManager::is_module_installed('news') & ModulesManager::is_module_activated('news'))
		{
			$fieldset_tab_three = new FormFieldsetHTML('tab-06', $this->lang['plugins.form.title'] . ' 03');
			$wizard_form->add_fieldset($fieldset_tab_three);
			$fieldset_tab_three->set_css_class('wizard-step');

			$fieldset_tab_three->set_description($this->lang['plugins.short.content']);
		}

		$this->submit_wizard_button = new FormButtonDefaultSubmit();
		$wizard_form->add_button($this->submit_wizard_button);

		$this->wizard_form = $wizard_form;
	}

	private function check_authorizations()
	{
		if (!SandboxAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->common_lang['title.plugins'], $this->common_lang['module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->common_lang['module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->common_lang['title.plugins'], SandboxUrlBuilder::plugins()->rel());

		return $response;
	}
}
?>
