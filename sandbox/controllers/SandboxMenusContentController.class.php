<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 003 15
 * @since       PHPBoost 5.2 - 2019 07 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SandboxMenusContentController extends DefaultModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $tabs_form;
	private $wizard_form;

	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_tabs_button;
	private $submit_wizard_button;

	protected function get_template_to_use()
	{
		return new FileTemplate('sandbox/SandboxMenusContentController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view();

		return $this->generate_response();
	}

	private function build_view()
	{
		$this->build_wizard_form();

		$this->view->put_all(array(
			'BASIC'           => self::build_basic_markup(),
			'ACCORDION'       => self::build_accordion_markup(),
			'TABS'            => self::build_tabs_markup(),
			'WIZARD'          => self::build_wizard_markup(),
			'NO_AVATAR_URL'   => Url::to_rel(FormFieldThumbnail::get_default_thumbnail_url(UserAccountsConfig::NO_AVATAR_URL)),
			'SANDBOX_SUBMENU' => SandboxSubMenu::get_submenu()
		));
	}

	private function build_basic_markup()
	{
		$basic_tpl = new FileTemplate('sandbox/pagecontent/menus/basic.tpl');
		$basic_tpl->add_lang($this->lang);
		$basic_tpl->put('BASIC_FORM', $this->build_basic_form()->display());
		return $basic_tpl;
	}

	private function build_basic_form()
	{
		$basic_form = new HTMLForm('Basic_menu');

		$link_list = new FormFieldsetHTML('links_list', '');
			$basic_form->add_fieldset($link_list);

			$link_list->add_field(new FormFieldActionLinkList('actionlink_list',
				array(
					new FormFieldActionLinkElement($this->lang['sandbox.menu.link.icon'], '#', '', '', '', 'far fa-edit'),
					new FormFieldActionLinkElement($this->lang['sandbox.menu.link.img'], '#', 'sandbox-svg-icon', '/templates/__default__/theme/images/logo.svg'),
					new FormFieldActionLinkElement($this->lang['sandbox.menu.link'].' 3', '#', ''),
					new FormFieldActionLinkElement($this->lang['sandbox.menu.link'].' 4', '#', '')
				),
				array('class' => 'css-class')
			));

		return $basic_form;
	}

	private function build_accordion_markup()
	{
		$accordion_tpl = new FileTemplate('sandbox/pagecontent/menus/accordion.tpl');
		$accordion_tpl->add_lang($this->lang);
		$accordion_tpl->put('ACCORDION_FORM', $this->build_accordion_form()->display());
		return $accordion_tpl;
	}

	private function build_accordion_form()
	{
		$accordion_form = new HTMLForm('Sandbox_Accordion');
		$accordion_form->set_css_class('accordion-container basic fieldset-content'); // basic|siblings

		$accordion_controls = new FormFieldsetAccordionControls('accordion_controls_basic', '');
			$accordion_form->add_fieldset($accordion_controls);

		$accordion_menu = new FormFieldMenuFieldset('accordion_menu', '');
			$accordion_form->add_fieldset($accordion_menu);
			$accordion_menu->set_css_class('accordion-nav');

			$accordion_menu->add_field(new FormFieldMultitabsLinkList('accordion_menu_list',
				array(
					new FormFieldMultitabsLinkElement($this->lang['sandbox.menu.link.icon'], 'accordion', 'Sandbox_Accordion_accordion_01', 'fa fa-cog'),
					new FormFieldMultitabsLinkElement($this->lang['sandbox.menu.link.img'], 'accordion', 'Sandbox_Accordion_accordion_02', '', '/templates/__default__/theme/images/logo.svg', '', 'sandbox-svg-icon'),
					new FormFieldMultitabsLinkElement($this->lang['sandbox.menu.link'].' 3', 'accordion', 'Sandbox_Accordion_accordion_03'),
					new FormFieldMultitabsLinkElement($this->lang['sandbox.menu.link'].' 4', 'accordion', 'Sandbox_Accordion_accordion_04', '', '', '', 'bgc warning')
				)
			));

			$accordion_01 = new FormFieldsetMultitabsHTML('accordion_01', $this->lang['sandbox.menu.panel'].' 1',
				array('css_class' => 'accordion accordion-animation first-tab')
			);
			$accordion_form->add_fieldset($accordion_01);

			$accordion_01->add_field(new FormFieldHTML('accordion_content_01', $this->lang['sandbox.lorem.short.content']));

			$accordion_02 = new FormFieldsetMultitabsHTML('accordion_02', $this->lang['sandbox.menu.panel'].' 2',
				array('css_class' => 'accordion accordion-animation')
			);
			$accordion_form->add_fieldset($accordion_02);

			$accordion_02->add_field(new FormFieldHTML('accordion_content_02', $this->lang['sandbox.lorem.medium.content']));

			$accordion_03 = new FormFieldsetMultitabsHTML('accordion_03', $this->lang['sandbox.menu.panel'].' 3',
				array('css_class' => 'accordion accordion-animation')
			);
			$accordion_form->add_fieldset($accordion_03);

			$accordion_03->add_field(new FormFieldHTML('accordion_content_03', $this->lang['sandbox.lorem.large.content']));

			$accordion_04 = new FormFieldsetMultitabsHTML('accordion_04', $this->lang['sandbox.menu.panel'].' 4',
				array('css_class' => 'accordion accordion-animation')
			);
			$accordion_form->add_fieldset($accordion_04);

			$accordion_04->add_field(new FormFieldHTML('accordion_content_04', $this->lang['sandbox.lorem.short.content']));

		return $accordion_form;
	}

	private function build_tabs_markup()
	{
		$tabs_tpl = new FileTemplate('sandbox/pagecontent/menus/tabs.tpl');
		$tabs_tpl->add_lang($this->lang);
		$tabs_tpl->put('TABS_FORM', $this->build_tabs_form()->display());
		return $tabs_tpl;
	}

	private function build_tabs_form()
	{
		$tabs_form = new HTMLForm('Sandbox_Accordion');
		$tabs_form->set_css_class('tabs-container fieldset-content');

		$tabs_menu = new FormFieldMenuFieldset('tabs_menu', '');
			$tabs_form->add_fieldset($tabs_menu);
			$tabs_menu->set_css_class('tabs-nav');

			$tabs_menu->add_field(new FormFieldMultitabsLinkList('tabs_menu_list',
				array(
					new FormFieldMultitabsLinkElement($this->lang['sandbox.menu.link.icon'], 'tabs', 'Sandbox_Accordion_tabs_01', 'fa fa-cog'),
					new FormFieldMultitabsLinkElement($this->lang['sandbox.menu.link.img'], 'tabs', 'Sandbox_Accordion_tabs_02', '', '/templates/__default__/theme/images/logo.svg', '', 'sandbox-svg-icon'),
					new FormFieldMultitabsLinkElement($this->lang['sandbox.menu.link'].' 3', 'tabs', 'Sandbox_Accordion_tabs_03'),
					new FormFieldMultitabsLinkElement($this->lang['sandbox.menu.link'].' 4', 'tabs', 'Sandbox_Accordion_tabs_04', '', '', '', 'bgc warning')
				)
			));

			$tabs_01 = new FormFieldsetMultitabsHTML('tabs_01', $this->lang['sandbox.menu.panel'].' 1',
				array('css_class' => 'tabs tabs-animation first-tab')
			);
			$tabs_form->add_fieldset($tabs_01);

			$tabs_01->add_field(new FormFieldHTML('tabs_content_01', $this->lang['sandbox.lorem.short.content']));

			$tabs_02 = new FormFieldsetMultitabsHTML('tabs_02', $this->lang['sandbox.menu.panel'].' 2',
				array('css_class' => 'tabs tabs-animation')
			);
			$tabs_form->add_fieldset($tabs_02);

			$tabs_02->add_field(new FormFieldHTML('tabs_content_02', $this->lang['sandbox.lorem.medium.content']));

			$tabs_03 = new FormFieldsetMultitabsHTML('tabs_03', $this->lang['sandbox.menu.panel'].' 3',
				array('css_class' => 'tabs tabs-animation')
			);
			$tabs_form->add_fieldset($tabs_03);

			$tabs_03->add_field(new FormFieldHTML('tabs_content_03', $this->lang['sandbox.lorem.large.content']));

			$tabs_04 = new FormFieldsetMultitabsHTML('tabs_04', $this->lang['sandbox.menu.panel'].' 4',
				array('css_class' => 'tabs tabs-animation')
			);
			$tabs_form->add_fieldset($tabs_04);

			$tabs_04->add_field(new FormFieldHTML('tabs_content_04', $this->lang['sandbox.lorem.short.content']));

		return $tabs_form;
	}

	private function build_wizard_markup()
	{
		$wizard_tpl = new FileTemplate('sandbox/pagecontent/menus/wizard.tpl');
		$wizard_tpl->add_lang($this->lang);
		$wizard_tpl->put('WIZARD_FORM', $this->build_wizard_form()->display());
		return $wizard_tpl;
	}

	private function build_wizard_form()
	{
		$wizard_form = new WizardHTMLForm('wizard_form');
		$wizard_form->set_css_class('wizard-container fieldset-content');

		// Wizard menu
		$fieldset_tab_menu = new FormFieldMenuFieldset('tab_menu', '');
		$wizard_form->add_fieldset($fieldset_tab_menu);

		$fieldset_tab_menu->add_field(new WizardActionLinkList('tab_menu_list',
			array(
				new FormFieldActionLinkElement($this->lang['sandbox.menu.panel'] . ' 01', '#', '', '', '', 'fa-cog'),
				new FormFieldActionLinkElement($this->lang['sandbox.menu.panel'] . ' 02', '#', '', '', '', 'fa-image'),
				new FormFieldActionLinkElement($this->lang['sandbox.menu.panel'] . ' 03', '#', 'sandbox-svg-icon', PATH_TO_ROOT . '/templates/__default__/theme/images/logo.svg', 'articles'),
			)
		));

		$fieldset_tab_one = new FormFieldsetHTML('tab-04', $this->lang['sandbox.menu.panel.title'] . ' 01');
		$wizard_form->add_fieldset($fieldset_tab_one);
		$fieldset_tab_one->set_css_class('wizard-step');

		$fieldset_tab_one->set_description($this->lang['sandbox.lorem.large.content']);

		$fieldset_tab_one->add_field(new FormFieldSubTitle('tab_01b', $this->lang['sandbox.menu.panel.subtitle'],''));

		$fieldset_tab_one->add_field(new FormFieldTextEditor('text', $this->lang['sandbox.menu.panel.input'], ''));

		$fieldset_tab_two = new FormFieldsetHTML('tab-05', $this->lang['sandbox.menu.panel.title'] . ' 02');
		$wizard_form->add_fieldset($fieldset_tab_two);
		$fieldset_tab_two->set_css_class('wizard-step');

		$fieldset_tab_two->set_description($this->lang['sandbox.lorem.medium.content']);

		if (ModulesManager::is_module_installed('news') & ModulesManager::is_module_activated('news'))
		{
			$fieldset_tab_three = new FormFieldsetHTML('tab-06', $this->lang['sandbox.menu.panel.title'] . ' 03');
			$wizard_form->add_fieldset($fieldset_tab_three);
			$fieldset_tab_three->set_css_class('wizard-step');

			$fieldset_tab_three->set_description($this->lang['sandbox.lorem.short.content']);
		}

		$this->submit_wizard_button = new FormButtonDefaultSubmit();
		$wizard_form->add_button($this->submit_wizard_button);

		return $wizard_form;
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
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['sandbox.menus.content'], $this->lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['sandbox.menus.content'], SandboxUrlBuilder::menus_content()->rel());

		return $response;
	}
}
?>
