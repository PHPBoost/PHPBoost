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

class SandboxComponentController extends DefaultModuleController
{
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $floating_messages_button;

	protected function get_template_to_use()
	{
		return new FileTemplate('sandbox/SandboxComponentController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view();

		return $this->generate_response();
	}

	private function build_view()
	{
		$this->view->put_all(array(
			'TYPOGRAPHY'      => self::build_markup('sandbox/pagecontent/components/typography.tpl'),
			'COLOR'           => self::build_markup('sandbox/pagecontent/components/color.tpl'),
			'MEDIA'           => self::build_markup('sandbox/pagecontent/components/media.tpl'),
			'PROGRESSBAR'     => self::build_markup('sandbox/pagecontent/components/progressbar.tpl'),
			'LIST'            => self::build_markup('sandbox/pagecontent/components/list.tpl'),
			'EXPLORER'        => self::build_markup('sandbox/pagecontent/components/explorer.tpl'),
			'NOTATION'        => self::build_markup('sandbox/pagecontent/components/notation.tpl'),
			'TOOLTIP'         => self::build_markup('sandbox/pagecontent/components/tooltip.tpl'),
			'MODAL'			  => self::build_markup('sandbox/pagecontent/components/modal.tpl'),
			'PAGINATION'      => self::build_markup('sandbox/pagecontent/components/pagination.tpl'),
			'TABLE'           => self::build_markup('sandbox/pagecontent/components/table.tpl'),
			'MESSAGE_HELPER'  => self::build_alert_markup(),
			'SANDBOX_SUBMENU' => SandboxSubMenu::get_submenu()
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
		$view->add_lang(array_merge($this->lang, $this->lang));

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
		$graphical_environment->set_page_title($this->lang['sandbox.components'],$this->lang['sandbox.components']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['sandbox.components']);

		return $response;
	}
}
?>
