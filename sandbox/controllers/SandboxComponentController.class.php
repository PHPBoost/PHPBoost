<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 09
 * @since       PHPBoost 3.0 - 2012 05 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxComponentController extends ModuleController
{
	private $view;
	private $common_lang;
	private $lang;

	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $floating_messages_button;

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
		$this->lang = LangLoader::get('component', 'sandbox');
		$this->view = new FileTemplate('sandbox/SandboxComponentController.tpl');
		$this->view->add_lang($this->common_lang);
		$this->view->add_lang($this->lang);
	}

	private function build_view()
	{
		$this->view->put_all(array(
			'TYPOGRAPHY'      => self::build_typography_view(),
			'COLOR'           => self::build_color_view(),
			'MEDIA'           => self::build_media_view(),
			'PROGRESSBAR'     => self::build_progressbar_view(),
			'LIST'            => self::build_list_view(),
			'MODAL'			  => self::build_modal_view(),
			'EXPLORER'        => self::build_explorer_view(),
			'NOTATION'        => self::build_notation_view(),
			'PAGINATION'      => self::build_pagination_view(),
			'SORTABLE'        => self::build_sortable_view(),
			'TABLE'           => self::build_table_view(),
			'MESSAGE_HELPER'  => self::build_alert_view(),
			'TOOLTIP'         => self::build_tooltip_view(),
			'SANDBOX_SUBMENU' => SandboxSubMenu::get_submenu()
		));
	}

	private function build_floating_messages()
	{
		$floating_messages = new HTMLForm('floating_messages', '', false);
		$this->floating_messages_button = new FormButtonDefaultSubmit($this->lang['component.message.float-display'], 'floating_messages');
		$floating_messages->add_button($this->floating_messages_button);
		$this->floating_messages = $floating_messages;
	}

	private function build_typography_view()
	{
		$this->lang = LangLoader::get('component', 'sandbox');
		$this->common_lang = LangLoader::get('common', 'sandbox');
		$typography_tpl = new FileTemplate('sandbox/pagecontent/components/typography.tpl');
		$typography_tpl->add_lang($this->lang);
		$typography_tpl->add_lang($this->common_lang);
		return $typography_tpl;
	}

	private function build_color_view()
	{
		$color_tpl = new FileTemplate('sandbox/pagecontent/components/color.tpl');
		$color_tpl->add_lang($this->lang);
		$color_tpl->add_lang($this->common_lang);
		return $color_tpl;
	}

	private function build_media_view()
	{
		$media_tpl = new FileTemplate('sandbox/pagecontent/components/media.tpl');
		$media_tpl->add_lang($this->lang);
		$media_tpl->add_lang($this->common_lang);
		return $media_tpl;
	}

	private function build_progressbar_view()
	{
		$progressbar_tpl = new FileTemplate('sandbox/pagecontent/components/progressbar.tpl');
		$progressbar_tpl->add_lang($this->lang);
		$progressbar_tpl->add_lang($this->common_lang);
		return $progressbar_tpl;
	}

	private function build_list_view()
	{
		$list_tpl = new FileTemplate('sandbox/pagecontent/components/list.tpl');
		$list_tpl->add_lang($this->lang);
		$list_tpl->add_lang($this->common_lang);
		return $list_tpl;
	}

	private function build_modal_view()
	{
		$modal_tpl = new FileTemplate('sandbox/pagecontent/components/modal.tpl');
		$modal_tpl->add_lang($this->lang);
		$modal_tpl->add_lang($this->common_lang);
		$modal_tpl->put('MODAL_FORM', $this->build_modal_form()->display());
		return $modal_tpl;
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
					new FormFieldMultitabsLinkElement($this->lang['component.link.icon'], 'modal', 'Sandbox_Modal_modal_01', 'fa-cog'),
					new FormFieldMultitabsLinkElement($this->lang['component.link.img'], 'modal', 'Sandbox_Modal_modal_02', '', '/sandbox/sandbox_mini.png'),
					new FormFieldMultitabsLinkElement($this->lang['component.link'].' 3', 'modal', 'Sandbox_Modal_modal_03'),
					new FormFieldMultitabsLinkElement($this->lang['component.link'].' 4', 'modal', 'Sandbox_Modal_modal_04', '', '', '','button d-inline-block')
				)
			));

			$modal_01 = new FormFieldsetMultitabsHTML('modal_01', $this->lang['component.panel'].' 1',
				array('css_class' => 'modal modal-animation first-tab', 'modal' => true)
			);
			$modal_form->add_fieldset($modal_01);

			$modal_01->set_description($this->common_lang['lorem.short.content']);

			$modal_02 = new FormFieldsetMultitabsHTML('modal_02', $this->lang['component.panel'].' 2',
				array('css_class' => 'modal modal-animation', 'modal' => true)
			);
			$modal_form->add_fieldset($modal_02);

			$modal_02->set_description($this->common_lang['lorem.medium.content']);

			$modal_03 = new FormFieldsetMultitabsHTML('modal_03', $this->lang['component.panel'].' 3',
				array('css_class' => 'modal modal-animation', 'modal' => true)
			);
			$modal_form->add_fieldset($modal_03);

			$modal_03->set_description($this->common_lang['lorem.large.content']);

			$modal_04 = new FormFieldsetMultitabsHTML('modal_04', $this->lang['component.panel'].' 4',
				array('css_class' => 'modal modal-animation', 'modal' => true)
			);
			$modal_form->add_fieldset($modal_04);

			$modal_04->set_description($this->common_lang['lorem.short.content']);

		return $modal_form;
	}

	private function build_explorer_view()
	{
		$explorer_tpl = new FileTemplate('sandbox/pagecontent/components/explorer.tpl');
		$explorer_tpl->add_lang($this->lang);
		$explorer_tpl->add_lang($this->common_lang);
		return $explorer_tpl;
	}

	private function build_notation_view()
	{
		$notation_tpl = new FileTemplate('sandbox/pagecontent/components/notation.tpl');
		$notation_tpl->add_lang($this->lang);
		$notation_tpl->add_lang($this->common_lang);
		return $notation_tpl;
	}

	private function build_pagination_view()
	{
		$pagination_tpl = new FileTemplate('sandbox/pagecontent/components/pagination.tpl');
		$pagination_tpl->add_lang($this->lang);
		$pagination_tpl->add_lang($this->common_lang);

		$pagination_full = new ModulePagination(2, 15, 5);
		$pagination_full->set_url(new Url('#%d'));
		$pagination_tpl->put('PAGINATION_FULL', $pagination_full->display());

		$pagination_light = new ModulePagination(2, 15, 5, Pagination::LIGHT_PAGINATION);
		$pagination_light->set_url(new Url('#%d'));
		$pagination_tpl->put('PAGINATION_LIGHT', $pagination_light->display());

		return $pagination_tpl;
	}

	private function build_sortable_view()
	{
		$sortable_tpl = new FileTemplate('sandbox/pagecontent/components/sortable.tpl');
		$sortable_tpl->add_lang($this->lang);
		$sortable_tpl->add_lang($this->common_lang);
		return $sortable_tpl;
	}

	private function build_table_view()
	{
		$table_tpl = new FileTemplate('sandbox/pagecontent/components/table.tpl');
		$table_tpl->add_lang($this->lang);
		$table_tpl->add_lang($this->common_lang);

		$pagination = new ModulePagination(2, 15, 5);
		$pagination->set_url(new Url('#%d'));
		$table_tpl->put('PAGINATION_TABLE', $pagination->display());
		return $table_tpl;
	}

	private function build_alert_view()
	{
		$alert_tpl = new FileTemplate('sandbox/pagecontent/components/alert.tpl');
		$alert_tpl->add_lang($this->lang);
		$alert_tpl->add_lang($this->common_lang);

		$messages = array(
			MessageHelper::display($this->lang['component.message.notice'], MessageHelper::NOTICE),
			MessageHelper::display($this->lang['component.message.question'], MessageHelper::QUESTION),
			MessageHelper::display($this->lang['component.message.success'], MessageHelper::SUCCESS),
			MessageHelper::display($this->lang['component.message.warning'], MessageHelper::WARNING),
			MessageHelper::display($this->lang['component.message.error'], MessageHelper::ERROR),
			MessageHelper::display($this->lang['component.message.member'], MessageHelper::MEMBER_ONLY),
			MessageHelper::display($this->lang['component.message.modo'], MessageHelper::MODERATOR_ONLY),
			MessageHelper::display($this->lang['component.message.admin'], MessageHelper::ADMIN_ONLY)
		);

		foreach ($messages as $message)
		{
			$alert_tpl->assign_block_vars('messages', array('VIEW' => $message));
		}

		$this->build_floating_messages();
		if ($this->floating_messages_button->has_been_submited() && $this->floating_messages->validate()) {
			$alert_tpl->put_all(array(
				'FLOATING_SUCCESS'  => MessageHelper::display($this->lang['component.message.float-unlimited'], MessageHelper::SUCCESS, -1),
				'FLOATING_NOTICE'   => MessageHelper::display($this->lang['component.message.float-limited'], MessageHelper::NOTICE, 3),
				'FLOATING_WARNING'  => MessageHelper::display($this->lang['component.message.float-unlimited'], MessageHelper::WARNING, -1),
				'FLOATING_ERROR'    => MessageHelper::display($this->lang['component.message.float-limited'], MessageHelper::ERROR, 6)
			));
		}
		$alert_tpl->put('FLOATING_MESSAGES', $this->floating_messages->display());

		return $alert_tpl;
	}

	private function build_tooltip_view()
	{
		$tooltip_tpl = new FileTemplate('sandbox/pagecontent/components/tooltip.tpl');
		$tooltip_tpl->add_lang($this->lang);
		$tooltip_tpl->add_lang($this->common_lang);
		return $tooltip_tpl;
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
		$graphical_environment->set_page_title($this->common_lang['title.component'],$this->common_lang['title.component'],  $this->common_lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->common_lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->common_lang['title.component']);
		$breadcrumb->add($this->common_lang['title.component']);

		return $response;
	}
}
?>
