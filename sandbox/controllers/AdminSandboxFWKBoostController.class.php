<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 15
 * @since       PHPBoost 3.0 - 2012 05 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminSandboxFWKBoostController extends DefaultAdminModuleController
{
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $floating_messages_button;

	protected function get_template_to_use()
	{
		return new FileTemplate('sandbox/AdminSandboxFWKBoostController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view();

		return new AdminSandboxDisplayResponse($this->view, $this->lang['sandbox.module.title'] . ' - ' . $this->lang['sandbox.components']);
	}

	private function build_view()
	{
		$this->view->put_all(array(
			'BASIC'          => self::build_basic_markup(),
			'ACCORDION'      => self::build_accordion_markup(),
			'TABS'           => self::build_tabs_markup(),
			'WIZARD'         => self::build_wizard_markup(),
			'MODAL'			 => self::build_markup('sandbox/pagecontent/components/modal.tpl'),
			'LIST'           => self::build_markup('sandbox/pagecontent/components/list.tpl'),
			'PAGINATION'     => self::build_markup('sandbox/pagecontent/components/pagination.tpl'),
			'TABLE'          => self::build_markup('sandbox/pagecontent/components/table.tpl'),
			'MESSAGE_HELPER' => self::build_alert_markup()
		));
	}

	private function build_floating_messages()
	{
		$floating_messages = new HTMLForm('floating_messages', '', false);
		$this->floating_messages_button = new FormButtonDefaultSubmit($this->lang['sandbox.component.message.float.display'], 'floating_messages');
		$floating_messages->add_button($this->floating_messages_button);
		$this->floating_messages = $floating_messages;
	}

	private function build_markup($tpl)
	{
		$view = new FileTemplate($tpl);
		$view->add_lang($this->lang);

		$pagination_full = new ModulePagination(2, 15, 5);
		$pagination_full->set_url(new Url('#%d'));
		$pagination_light = new ModulePagination(2, 15, 5, Pagination::LIGHT_PAGINATION);
		$pagination_light->set_url(new Url('#%d'));
		$pagination = new ModulePagination(2, 15, 5);
		$pagination->set_url(new Url('#%d'));

		$view->put_all(array(
			'MODAL_FORM'       => $this->build_modal_form()->display(),
			'PAGINATION_FULL'  => $pagination_full->display(),
			'PAGINATION_LIGHT' => $pagination_light->display(),
			'PAGINATION_TABLE' => $pagination->display()
		));
		return $view;
	}

	private function build_modal_form()
	{
		$modal_form = new HTMLForm('Sandbox_Modal');
		$modal_form->set_css_class('modal-container fieldset-content');

		$modal_menu = new FormFieldMenuFieldset('modal_menu', '');
			$modal_form->add_fieldset($modal_menu);
			$modal_menu->set_css_class('modal-nav');

			$modal_menu->add_field(new FormFieldMultitabsLinkList('modal_menu_list',
				array(
					new FormFieldMultitabsLinkElement($this->lang['sandbox.component.link.icon'], 'modal', 'Sandbox_Modal_modal_01', 'fa fa-cog'),
					new FormFieldMultitabsLinkElement($this->lang['sandbox.component.link.img'], 'modal', 'Sandbox_Modal_modal_02', '', '/templates/__default__/theme/images/logo.svg', '', 'sandbox-svg-icon'),
					new FormFieldMultitabsLinkElement($this->lang['sandbox.component.link'].' 3', 'modal', 'Sandbox_Modal_modal_03'),
					new FormFieldMultitabsLinkElement($this->lang['sandbox.component.link'].' 4', 'modal', 'Sandbox_Modal_modal_04', '', '', '','button d-inline-block')
				)
			));

			$modal_01 = new FormFieldsetMultitabsHTML('modal_01', $this->lang['sandbox.component.panel'].' 1',
				array('css_class' => 'modal modal-animation first-tab', 'modal' => true)
			);
			$modal_form->add_fieldset($modal_01);

			$modal_01->set_description($this->lang['sandbox.lorem.short.content']);

			$modal_02 = new FormFieldsetMultitabsHTML('modal_02', $this->lang['sandbox.component.panel'].' 2',
				array('css_class' => 'modal modal-animation', 'modal' => true)
			);
			$modal_form->add_fieldset($modal_02);

			$modal_02->set_description($this->lang['sandbox.lorem.medium.content']);

			$modal_03 = new FormFieldsetMultitabsHTML('modal_03', $this->lang['sandbox.component.panel'].' 3',
				array('css_class' => 'modal modal-animation', 'modal' => true)
			);
			$modal_form->add_fieldset($modal_03);

			$modal_03->set_description($this->lang['sandbox.lorem.large.content']);

			$modal_04 = new FormFieldsetMultitabsHTML('modal_04', $this->lang['sandbox.component.panel'].' 4',
				array('css_class' => 'modal modal-animation', 'modal' => true)
			);
			$modal_form->add_fieldset($modal_04);

			$modal_04->set_description($this->lang['sandbox.lorem.short.content']);

		return $modal_form;
	}

	private function build_alert_markup()
	{
		$view = new FileTemplate('sandbox/pagecontent/components/alert.tpl');
		$view->add_lang($this->lang);
		$view->add_lang($this->lang);

		$messages = array(
			MessageHelper::display($this->lang['sandbox.component.message.notice'], MessageHelper::NOTICE),
			MessageHelper::display($this->lang['sandbox.component.message.question'], MessageHelper::QUESTION),
			MessageHelper::display($this->lang['sandbox.component.message.success'], MessageHelper::SUCCESS),
			MessageHelper::display($this->lang['sandbox.component.message.warning'], MessageHelper::WARNING),
			MessageHelper::display($this->lang['sandbox.component.message.error'], MessageHelper::ERROR),
			MessageHelper::display($this->lang['sandbox.component.message.member'], MessageHelper::MEMBER_ONLY),
			MessageHelper::display($this->lang['sandbox.component.message.modo'], MessageHelper::MODERATOR_ONLY),
			MessageHelper::display($this->lang['sandbox.component.message.admin'], MessageHelper::ADMIN_ONLY)
		);

		foreach ($messages as $message)
		{
			$view->assign_block_vars('messages', array('VIEW' => $message));
		}

		$this->build_floating_messages();
		if ($this->floating_messages_button->has_been_submited() && $this->floating_messages->validate()) {
			$view->put_all(array(
				'FLOATING_SUCCESS'  => MessageHelper::display($this->lang['sandbox.component.message.float.unlimited'], MessageHelper::SUCCESS, -1),
				'FLOATING_NOTICE'   => MessageHelper::display($this->lang['sandbox.component.message.float.limited'], MessageHelper::NOTICE, 3),
				'FLOATING_WARNING'  => MessageHelper::display($this->lang['sandbox.component.message.float.unlimited'], MessageHelper::WARNING, -1),
				'FLOATING_ERROR'    => MessageHelper::display($this->lang['sandbox.component.message.float.limited'], MessageHelper::ERROR, 6)
			));
		}
		$view->put('FLOATING_MESSAGES', $this->floating_messages->display());

		return $view;
	}

	private function build_basic_markup()
	{
		$basic_tpl = new FileTemplate('sandbox/pagecontent/menus/basic.tpl');
		$basic_tpl->add_lang($this->lang);
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

			$accordion_02->add_field(new FormFieldHTML('accordion_content_02_a', $this->lang['sandbox.lorem.short.content']));
			$accordion_02->add_field(new FormFieldHTML('accordion_content_02_b', $this->lang['sandbox.lorem.short.content']));

			$accordion_03 = new FormFieldsetMultitabsHTML('accordion_03', $this->lang['sandbox.menu.panel'].' 3',
				array('css_class' => 'accordion accordion-animation')
			);
			$accordion_form->add_fieldset($accordion_03);

			$accordion_03->add_field(new FormFieldHTML('accordion_content_03_a', $this->lang['sandbox.lorem.short.content']));
			$accordion_03->add_field(new FormFieldHTML('accordion_content_03_b', $this->lang['sandbox.lorem.short.content']));
			$accordion_03->add_field(new FormFieldHTML('accordion_content_03_c', $this->lang['sandbox.lorem.short.content']));

			$accordion_04 = new FormFieldsetMultitabsHTML('accordion_04', $this->lang['sandbox.menu.panel'].' 4',
				array('css_class' => 'accordion accordion-animation')
			);
			$accordion_form->add_fieldset($accordion_04);

			$accordion_04->add_field(new FormFieldHTML('accordion_content_04_a', $this->lang['sandbox.lorem.short.content']));
			$accordion_04->add_field(new FormFieldHTML('accordion_content_04_b', $this->lang['sandbox.lorem.short.content']));
			$accordion_04->add_field(new FormFieldHTML('accordion_content_04_c', $this->lang['sandbox.lorem.short.content']));
			$accordion_04->add_field(new FormFieldHTML('accordion_content_04_d', $this->lang['sandbox.lorem.short.content']));

		return $accordion_form;
	}

	private function build_tabs_markup()
	{
		$tabs_tpl = new FileTemplate('sandbox/pagecontent/menus/tabs.tpl');
		$tabs_tpl->add_lang($this->lang);
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

			$tabs_02->add_field(new FormFieldHTML('tabs_content_02_a', $this->lang['sandbox.lorem.short.content']));
			$tabs_02->add_field(new FormFieldHTML('tabs_content_02_b', $this->lang['sandbox.lorem.short.content']));

			$tabs_03 = new FormFieldsetMultitabsHTML('tabs_03', $this->lang['sandbox.menu.panel'].' 3',
				array('css_class' => 'tabs tabs-animation')
			);
			$tabs_form->add_fieldset($tabs_03);

			$tabs_03->add_field(new FormFieldHTML('tabs_content_03_a', $this->lang['sandbox.lorem.short.content']));
			$tabs_03->add_field(new FormFieldHTML('tabs_content_03_b', $this->lang['sandbox.lorem.short.content']));
			$tabs_03->add_field(new FormFieldHTML('tabs_content_03_c', $this->lang['sandbox.lorem.short.content']));

			$tabs_04 = new FormFieldsetMultitabsHTML('tabs_04', $this->lang['sandbox.menu.panel'].' 4',
				array('css_class' => 'tabs tabs-animation')
			);
			$tabs_form->add_fieldset($tabs_04);

			$tabs_04->add_field(new FormFieldHTML('tabs_content_04_a', $this->lang['sandbox.lorem.short.content']));
			$tabs_04->add_field(new FormFieldHTML('tabs_content_04_b', $this->lang['sandbox.lorem.short.content']));
			$tabs_04->add_field(new FormFieldHTML('tabs_content_04_c', $this->lang['sandbox.lorem.short.content']));
			$tabs_04->add_field(new FormFieldHTML('tabs_content_04_d', $this->lang['sandbox.lorem.short.content']));

		return $tabs_form;
	}

	private function build_wizard_markup()
	{
		$tpl = new FileTemplate('sandbox/pagecontent/menus/wizard.tpl');
		$tpl->add_lang($this->lang);
		$tpl->add_lang($this->lang);
		$tpl->put('WIZARD_FORM', $this->build_wizard_form()->display());
		return $tpl;
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
				new FormFieldActionLinkElement($this->lang['sandbox.menu.panel'] . ' 01', '#', '', '', '', 'fa fa-cog'),
				new FormFieldActionLinkElement($this->lang['sandbox.menu.panel'] . ' 02', '#', '', '', '', 'fa fa-image'),
				new FormFieldActionLinkElement($this->lang['sandbox.menu.panel'] . ' 03', '#', 'sandbox-svg-icon', PATH_TO_ROOT . 'templates/__default__/theme/images/logo.svg', 'articles'),
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
}
?>
