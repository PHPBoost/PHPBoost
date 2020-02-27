<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 07 31
 * @since       PHPBoost 3.0 - 2012 05 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxGraphicsCSSController extends ModuleController
{
	private $tpl;
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
		$this->lang = LangLoader::get('css', 'sandbox');
		$this->tpl = new FileTemplate('sandbox/SandboxGraphicsCSSController.tpl');
		$this->tpl->add_lang($this->common_lang);
		$this->tpl->add_lang($this->lang);
	}

	private static function get_sub_tpl()
	{
		$sub_lang = LangLoader::get('submenu', 'sandbox');
		$sub_tpl = new FileTemplate('sandbox/SandboxSubMenu.tpl');
		$sub_tpl->add_lang($sub_lang);
		$sub_tpl->put('C_SANDBOX_CSS', true);
		return $sub_tpl;
	}

	private function build_view()
	{
		// code source
		$this->tpl->put_all(array(
			'PAGE'         => file_get_contents('html/css/page.tpl'),
			'FORM_OPTION'  => file_get_contents('html/css/form-option.tpl'),
			'DIV_OPTION'   => file_get_contents('html/css/div-option.tpl'),
			'PROGRESS_BAR' => file_get_contents('html/css/progress-bar.tpl'),
			'EXPLORER'     => file_get_contents('html/css/explorer.tpl'),
			'BUTTON'       => file_get_contents('html/css/button.tpl'),
			'SORTABLE'     => file_get_contents('html/css/sortable.tpl'),
			'TABLE'        => file_get_contents('html/css/table.tpl'),
			'MESSAGE'      => file_get_contents('html/css/message.tpl'),
			'ALERT'        => file_get_contents('html/css/alert.tpl'),
			'BLOCK'        => file_get_contents('html/css/block.tpl'),
			'SANDBOX_SUB_MENU' => self::get_sub_tpl()
		));

		$messages = array(
			MessageHelper::display($this->lang['css.message.notice'], MessageHelper::NOTICE),
			MessageHelper::display($this->lang['css.message.question'], MessageHelper::QUESTION),
			MessageHelper::display($this->lang['css.message.success'], MessageHelper::SUCCESS),
			MessageHelper::display($this->lang['css.message.warning'], MessageHelper::WARNING),
			MessageHelper::display($this->lang['css.message.error'], MessageHelper::ERROR),
			MessageHelper::display($this->lang['css.message.member'], MessageHelper::MEMBER_ONLY),
			MessageHelper::display($this->lang['css.message.modo'], MessageHelper::MODERATOR_ONLY),
			MessageHelper::display($this->lang['css.message.admin'], MessageHelper::ADMIN_ONLY)
		);

		foreach ($messages as $message)
		{
			$this->tpl->assign_block_vars('messages', array('VIEW' => $message));
		}

		$pagination = new ModulePagination(2, 15, 5);
		$pagination->set_url(new Url('#%d'));
		$this->tpl->put('PAGINATION', $pagination->display());

		$this->build_floating_messages();
		if ($this->floating_messages_button->has_been_submited() && $this->floating_messages->validate()) {
			$this->tpl->put_all(array(
				'FLOATING_SUCCESS'  => MessageHelper::display($this->lang['css.message.float-unlimited'], MessageHelper::SUCCESS, -1),
				'FLOATING_NOTICE'   => MessageHelper::display($this->lang['css.message.float-limited'], MessageHelper::NOTICE, 3),
				'FLOATING_WARNING'  => MessageHelper::display($this->lang['css.message.float-unlimited'], MessageHelper::WARNING, -1),
				'FLOATING_ERROR'    => MessageHelper::display($this->lang['css.message.float-limited'], MessageHelper::ERROR, 6)
			));
		}
		$this->tpl->put('FLOATING_MESSAGES', $this->floating_messages->display());
	}

	private function build_floating_messages()
	{
		$floating_messages = new HTMLForm('floating_messages', '', false);
		$this->floating_messages_button = new FormButtonDefaultSubmit($this->lang['css.message.float-display'], 'floating_messages');
		$floating_messages->add_button($this->floating_messages_button);
		$this->floating_messages = $floating_messages;
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
		$graphical_environment->set_page_title($this->common_lang['title.framework'], $this->common_lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->common_lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->common_lang['title.framework'], SandboxUrlBuilder::css()->rel());

		return $response;
	}
}
?>
