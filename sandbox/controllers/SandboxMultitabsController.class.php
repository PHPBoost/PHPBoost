<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 07 31
 * @since       PHPBoost 5.2 - 2019 07 30
*/

class SandboxMultitabsController extends ModuleController
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
		$this->lang = LangLoader::get('multitabs', 'sandbox');
		$this->tpl = new FileTemplate('sandbox/SandboxMultitabsController.tpl');
		$this->tpl->add_lang($this->common_lang);
		$this->tpl->add_lang($this->lang);
	}

	private function build_view()
	{

		$this->build_accordion_form();
		$this->build_modal_form();
		$this->build_tabs_form();

		$this->tpl->put_all(array(
			'PRE_ACCORDION_FORM' => file_get_contents('html/multitabs/accordion-form.tpl'),
			'PRE_ACCORDION_HTML' => file_get_contents('html/multitabs/accordion-html.tpl'),
			'ACCORDION_PHP_FORM' => $this->accordion_form->display(),
			'PRE_MODAL_FORM' => file_get_contents('html/multitabs/modal-form.tpl'),
			'PRE_MODAL_HTML' => file_get_contents('html/multitabs/modal-html.tpl'),
			'MODAL_PHP_FORM' => $this->modal_form->display(),
			'PRE_TABS_FORM' => file_get_contents('html/multitabs/tabs-form.tpl'),
			'PRE_TABS_HTML' => file_get_contents('html/multitabs/tabs-html.tpl'),
			'TABS_PHP_FORM' => $this->tabs_form->display(),
		));
	}

	private function build_accordion_form()
	{
		$accordion_form = new HTMLForm('accordion_form');
		$accordion_form->set_css_class('accordion-container siblings fieldset-content');

		$fieldset_accordion_controls = new FormFieldsetAccordionControls('accordion_controls', '');
		$accordion_form->add_fieldset($fieldset_accordion_controls);

		// Accordion menu
		$fieldset_accordion_menu = new FormFieldMenuFieldset('accordion_menu', '');
		$accordion_form->add_fieldset($fieldset_accordion_menu);

		$fieldset_accordion_menu->add_field(new FormFieldMultitabsLinkList('tab_menu_list',
			array(
				new FormFieldMultitabsLinkElement($this->lang['multitabs.menu.title'] . ' 04', 'accordion', 'accordion_form_accordion-04', 'fa-cog'),
				new FormFieldMultitabsLinkElement($this->lang['multitabs.menu.title'] . ' 05', 'accordion', 'accordion_form_accordion-05', 'fa-image'),
				new FormFieldMultitabsLinkElement($this->lang['multitabs.menu.title'] . ' 06', 'accordion', 'accordion_form_accordion-06', '', '../../articles/articles_mini.png', 'articles'),
			)
		));

		// Accordion panels
		$fieldset_accordion_one = new FormFieldsetMultitabsHTML('accordion-04', $this->lang['multitabs.panel.title'] . ' 04', array('css_class' => 'accordion accordion-animation'));
		$accordion_form->add_fieldset($fieldset_accordion_one);

		$fieldset_accordion_one->set_description($this->common_lang['lorem.large.content']);

		$fieldset_accordion_one->add_field(new FormFieldSubTitle('accordion_01b', $this->lang['multitabs.form.subtitle'],''));

		$fieldset_accordion_one->add_field(new FormFieldTextEditor('text', $this->lang['multitabs.form.input'], ''));

		$fieldset_accordion_two = new FormFieldsetMultitabsHTML('accordion-05', $this->lang['multitabs.panel.title'] . ' 05', array('css_class' => 'accordion accordion-animation'));
		$accordion_form->add_fieldset($fieldset_accordion_two);

		$fieldset_accordion_two->set_description($this->common_lang['lorem.medium.content']);

		if (ModulesManager::is_module_installed('articles') & ModulesManager::is_module_activated('articles'))
		{
			$fieldset_accordion_three = new FormFieldsetMultitabsHTML('accordion-06', $this->lang['multitabs.panel.title'] . ' 06', array('css_class' => 'accordion accordion-animation'));
			$accordion_form->add_fieldset($fieldset_accordion_three);

			$fieldset_accordion_three->set_description($this->common_lang['lorem.short.content']);
		}

		$this->submit_accordion_button = new FormButtonDefaultSubmit();
		$accordion_form->add_button($this->submit_accordion_button);
		$accordion_form->add_button(new FormButtonReset());

		$this->accordion_form = $accordion_form;
	}

	private function build_modal_form()
	{
		$modal_form = new HTMLForm('modal_form');
		$modal_form->set_css_class('modal-container fieldset-content');

		// Modal triggers
		$fieldset_modal_menu = new FormFieldMenuFieldset('modal_menu', '');
		$modal_form->add_fieldset($fieldset_modal_menu);

		$fieldset_modal_menu->add_field(new FormFieldMultitabsLinkList('modal_menu_list',
			array(
				new FormFieldMultitabsLinkElement($this->lang['multitabs.menu.title'] . ' modal', 'modal', 'modal_form_modal-10', 'fa-cog'),
			)
		));

		// Modal window
		$fieldset_modal_one = new FormFieldsetMultitabsHTML('modal-10', $this->lang['multitabs.panel.title'] . ' 10', array('css_class' => 'modal modal-animation', 'modal' => true));
		$modal_form->add_fieldset($fieldset_modal_one);

		$fieldset_modal_one->set_description($this->common_lang['lorem.large.content']);

		$this->submit_modal_button = new FormButtonDefaultSubmit();
		$modal_form->add_button($this->submit_modal_button);
		$modal_form->add_button(new FormButtonReset());

		$this->modal_form = $modal_form;
	}

	private function build_tabs_form()
	{
		$tabs_form = new HTMLForm('tabs_form');
		$tabs_form->set_css_class('tabs-container fieldset-content');

		// Tabs menu
		$fieldset_tab_menu = new FormFieldMenuFieldset('tab_menu', '');
		$tabs_form->add_fieldset($fieldset_tab_menu);

		$fieldset_tab_menu->add_field(new FormFieldMultitabsLinkList('tab_menu_list',
			array(
				new FormFieldMultitabsLinkElement($this->lang['multitabs.menu.title'] . ' 10', 'tabs', 'tabs_form_tab-10', 'fa-cog'),
				new FormFieldMultitabsLinkElement($this->lang['multitabs.menu.title'] . ' 11', 'tabs', 'tabs_form_tab-11', 'fa-image'),
				new FormFieldMultitabsLinkElement($this->lang['multitabs.menu.title'] . ' 12', 'tabs', 'tabs_form_tab-12', '', '../../articles/articles_mini.png', 'articles'),
			)
		));

		// Tabs panels
		$fieldset_tab_one = new FormFieldsetMultitabsHTML('tab-10', $this->lang['multitabs.panel.title'] . ' 10', array('css_class' => 'tabs tabs-animation first-tab'));
		$tabs_form->add_fieldset($fieldset_tab_one);

		$fieldset_tab_one->set_description($this->common_lang['lorem.large.content']);

		$fieldset_tab_one->add_field(new FormFieldSubTitle('tab_01b', $this->lang['multitabs.form.subtitle'],''));

		$fieldset_tab_one->add_field(new FormFieldTextEditor('text', $this->lang['multitabs.form.input'], ''));

		$fieldset_tab_two = new FormFieldsetMultitabsHTML('tab-11', $this->lang['multitabs.panel.title'] . ' 11', array('css_class' => 'tabs tabs-animation'));
		$tabs_form->add_fieldset($fieldset_tab_two);

		$fieldset_tab_two->set_description($this->common_lang['lorem.medium.content']);

		if (ModulesManager::is_module_installed('articles') & ModulesManager::is_module_activated('articles'))
		{
			$fieldset_tab_three = new FormFieldsetMultitabsHTML('tab-12', $this->lang['multitabs.panel.title'] . ' 12', array('css_class' => 'tabs tabs-animation'));
			$tabs_form->add_fieldset($fieldset_tab_three);

			$fieldset_tab_three->set_description($this->common_lang['lorem.short.content']);
		}

		$this->submit_tabs_button = new FormButtonDefaultSubmit();
		$tabs_form->add_button($this->submit_tabs_button);
		$tabs_form->add_button(new FormButtonReset());

		$this->tabs_form = $tabs_form;
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
		$graphical_environment->set_page_title($this->common_lang['title.multitabs'], $this->common_lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->common_lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->common_lang['title.multitabs'], SandboxUrlBuilder::multitabs()->rel());

		return $response;
	}
}
?>
