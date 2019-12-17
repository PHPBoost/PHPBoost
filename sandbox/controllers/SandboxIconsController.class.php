<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 05 05
 * @since       PHPBoost 3.0 - 2012 05 05
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxIconsController extends ModuleController
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
		$this->view = new FileTemplate('sandbox/SandboxIconsController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_view()
	{
		//Social
		$icons = array(
			array('fab', 'facebook-f', '\f39e'),
			array('fab', 'google-plus-g', '\f0d5'),
			array('fab', 'twitter', '\f099'),
			array('fas', 'hashtag', '\f292')
		);

		foreach ($icons as $icon)
		{
			$this->view->assign_block_vars('social', array(
					'PREFIX' => $icon[0],
					'FA'     => $icon[1],
					'CODE'   => $icon[2]
			));
		}

		//Responsive
		$icons = array(
			array('fas', 'tv', '\f26c'),
			array('fas', 'desktop', '\f108'),
			array('fas', 'laptop', '\f109'),
			array('fas', 'tablet-alt', '\f3fa'),
			array('fas', 'mobile-alt', '\f3cd')
		);

		foreach ($icons as $icon)
		{
			$this->view->assign_block_vars('responsive', array(
				'PREFIX' => $icon[0],
				'FA'     => $icon[1],
				'CODE'   => $icon[2]
			));
		}
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
		$graphical_environment->set_page_title($this->lang['title.icons'], $this->lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['title.icons'], SandboxUrlBuilder::icons()->rel());

		return $response;
	}
}
?>
