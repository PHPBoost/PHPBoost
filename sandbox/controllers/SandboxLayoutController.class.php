<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 04 28
 * @since       PHPBoost 5.3 - 2020 03 04
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SandboxLayoutController extends ModuleController
{
	private $view;
	private $common_lang;
	private $lang;

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
		$this->lang = LangLoader::get('fwkboost', 'sandbox');
		$this->view = new FileTemplate('sandbox/SandboxLayoutController.tpl');
		$this->view->add_lang($this->common_lang);
		$this->view->add_lang($this->lang);
	}

	private function build_view()
	{
		$this->view->put_all(array(
			'NO_AVATAR_URL'   => Url::to_rel(FormFieldThumbnail::get_default_thumbnail_url(UserAccountsConfig::NO_AVATAR_URL)),
			'MESSAGE'         => self::build_message_view(),
			'MODAL'			  => $this->build_modal_view()->display(),
			'SANDBOX_SUBMENU' => SandboxSubMenu::get_submenu()
		));
	}

	private function build_message_view()
	{
		$this->lang = LangLoader::get('fwkboost', 'sandbox');
		$this->common_lang = LangLoader::get('common', 'sandbox');
		$message_tpl = new FileTemplate('sandbox/pagecontent/layout/message.tpl');
		$message_tpl->add_lang($this->lang);
		$message_tpl->add_lang($this->common_lang);
		$message_tpl->put('NO_AVATAR_URL', Url::to_rel(FormFieldThumbnail::get_default_thumbnail_url(UserAccountsConfig::NO_AVATAR_URL)));

		return $message_tpl;
	}

	private function build_modal_view()
	{
		$modal_form = new HTMLForm('Sandbox_Modal');
		$modal_form->set_css_class('modal-container fieldset-content');

		$modal_menu = new FormFieldMenuFieldset('modal_menu', '');
			$modal_form->add_fieldset($modal_menu);
			$modal_menu->set_css_class('modal-nav');

			$modal_menu->add_field(new FormFieldSubTitle('modal', $this->lang['builder.modal.menu'] . ' ' . $this->common_lang['pinned.php'] . ' ' . $this->common_lang['pinned.html'], ''));

			$modal_menu->add_field(new FormFieldMultitabsLinkList('modal_menu_list',
				array(
					new FormFieldMultitabsLinkElement($this->lang['builder.link.icon'], 'modal', 'Sandbox_Modal_modal_01', 'fa-cog'),
					new FormFieldMultitabsLinkElement($this->lang['builder.link.img'], 'modal', 'Sandbox_Modal_modal_02', '', '/sandbox/sandbox_mini.png'),
					new FormFieldMultitabsLinkElement($this->lang['builder.link'].' 3', 'modal', 'Sandbox_Modal_modal_03'),
					new FormFieldMultitabsLinkElement($this->lang['builder.link'].' 4', 'modal', 'Sandbox_Modal_modal_04', '', '', '','button d-inline-block')
				)
			));

			$modal_01 = new FormFieldsetMultitabsHTML('modal_01', $this->lang['builder.panel'].' 1',
				array('css_class' => 'modal modal-animation first-tab', 'modal' => true)
			);
			$modal_form->add_fieldset($modal_01);

			$modal_01->set_description($this->common_lang['lorem.short.content']);

			$modal_02 = new FormFieldsetMultitabsHTML('modal_02', $this->lang['builder.panel'].' 2',
				array('css_class' => 'modal modal-animation', 'modal' => true)
			);
			$modal_form->add_fieldset($modal_02);

			$modal_02->set_description($this->common_lang['lorem.medium.content']);

			$modal_03 = new FormFieldsetMultitabsHTML('modal_03', $this->lang['builder.panel'].' 3',
				array('css_class' => 'modal modal-animation', 'modal' => true)
			);
			$modal_form->add_fieldset($modal_03);

			$modal_03->set_description($this->common_lang['lorem.large.content']);

			$modal_04 = new FormFieldsetMultitabsHTML('modal_04', $this->lang['builder.panel'].' 4',
				array('css_class' => 'modal modal-animation', 'modal' => true)
			);
			$modal_form->add_fieldset($modal_04);

			$modal_04->set_description($this->common_lang['lorem.short.content']);

		return $modal_form;
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
		$graphical_environment->set_page_title($this->common_lang['title.fwkboost'], $this->common_lang['title.layout'], $this->common_lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->common_lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->common_lang['title.fwkboost']);
		$breadcrumb->add($this->common_lang['title.layout']);

		return $response;
	}
}
?>
