<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2017 11 09
 * @since   	PHPBoost 3.0 - 2012 05 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxGraphicsCSSController extends ModuleController
{
	private $view;
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
		$this->lang = LangLoader::get('common', 'sandbox');
		$this->view = new FileTemplate('sandbox/SandboxGraphicsCSSController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_view()
	{
		$messages = array(
			MessageHelper::display($this->lang['css.message.success'], MessageHelper::SUCCESS),
			MessageHelper::display($this->lang['css.message.notice'], MessageHelper::NOTICE),
			MessageHelper::display($this->lang['css.message.warning'], MessageHelper::WARNING),
			MessageHelper::display($this->lang['css.message.error'], MessageHelper::ERROR),
			MessageHelper::display($this->lang['css.message.question'], MessageHelper::QUESTION),
			MessageHelper::display($this->lang['css.message.member'], MessageHelper::MEMBER_ONLY),
			MessageHelper::display($this->lang['css.message.modo'], MessageHelper::MODERATOR_ONLY),
			MessageHelper::display($this->lang['css.message.admin'], MessageHelper::ADMIN_ONLY)
		);

		foreach ($messages as $message)
		{
			$this->view->assign_block_vars('messages', array('VIEW' => $message));
		}

		$pagination = new ModulePagination(2, 15, 5);
		$pagination->set_url(new Url('#%d'));
		$this->view->put('PAGINATION', $pagination->display());

		// code source
		$this->view->put_all(array(
			'PAGE' => file_get_contents('html/css/page.tpl'),
			'FORM_OPTION' => file_get_contents('html/css/form-option.tpl'),
			'DIV_OPTION' => file_get_contents('html/css/div-option.tpl'),
			'PROGRESS_BAR' => file_get_contents('html/css/progress-bar.tpl'),
			'EXPLORER' => file_get_contents('html/css/explorer.tpl'),
			'BUTTON' => file_get_contents('html/css/button.tpl'),
			'SORTABLE' => file_get_contents('html/css/sortable.tpl'),
			'TABLE' => file_get_contents('html/css/table.tpl'),
			'MESSAGE' => file_get_contents('html/css/message.tpl'),
			'ALERT' => file_get_contents('html/css/alert.tpl'),
			'BLOCK' => file_get_contents('html/css/block.tpl')
		));
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
		$graphical_environment->set_page_title($this->lang['title.css'], $this->lang['module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['title.css'], SandboxUrlBuilder::css()->rel());

		return $response;
	}
}
?>
