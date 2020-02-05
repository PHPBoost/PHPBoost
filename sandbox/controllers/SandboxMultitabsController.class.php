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
		$this->tpl->put_all(array(
			'PRE_ACCORDION_BASIC' => file_get_contents('html/multitabs/accordion-basic.tpl'),
			'PRE_ACCORDION_SIBLINGS' => file_get_contents('html/multitabs/accordion-siblings.tpl'),
			'PRE_MODAL_HTML' => file_get_contents('html/multitabs/modal-html.tpl'),
			'PRE_TABS_HTML' => file_get_contents('html/multitabs/tabs-html.tpl')
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
