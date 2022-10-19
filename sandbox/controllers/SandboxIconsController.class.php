<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 01
 * @since       PHPBoost 3.0 - 2012 05 05
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxIconsController extends DefaultModuleController
{
	protected function get_template_to_use()
	{
		return new FileTemplate('sandbox/SandboxIconsController.tpl');
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
			'FAS'              => self::get_fa_list(PATH_TO_ROOT . '/sandbox/templates/fas.css', 'fas'),
			'FAB'              => self::get_fa_list(PATH_TO_ROOT . '/sandbox/templates/fab.css', 'fab'),
			'ICOMOON'         => self::build_markup('sandbox/pagecontent/icons/icomoon.tpl'),
			'SANDBOX_SUBMENU' => SandboxSubMenu::get_submenu()
		));
	}

	public function get_fa_list($file, $prefix)
	{
		$view = new FileTemplate('sandbox/pagecontent/icons/fa_list.tpl');
		$view->add_lang($this->lang);

		$css_file = file_get_contents($file);
		$properties_list = explode('}', $css_file);
        foreach($properties_list as $property)
        {
			$icon = $code = '';
			$contents = explode('{', $property);
			foreach($contents as $pseudo_class)
			{
				if (TextHelper::strpos($pseudo_class, 'content') !== false)
				{
					$content = explode('"', $pseudo_class);
					foreach($content as $row)
					{
						if (TextHelper::strpos($row, '\\') !== false)
						$code = $row;
					}
				}
				else
				{
					$classes = explode(':', $pseudo_class);
					foreach($classes as $class)
					{
						if (TextHelper::strpos($class, 'fa-') !== false) {
							$icon = str_replace('.', '',$class);
							if (TextHelper::strpos($class, 'fa-') !== false) {
								$fa = $icon;
							}
						}
					}
				}
			}
			$view->assign_block_vars('fa_icons', array(
				'PREFIX' => $prefix,
				'FA'     => $icon,
				'CODE'   => $code
			));
        }
		return $view;
	}

	private function build_markup($tpl)
	{
		$view = new FileTemplate($tpl);
		$view->add_lang($this->lang);

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
		$graphical_environment->set_page_title($this->lang['sandbox.icons'], $this->lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['sandbox.icons'], SandboxUrlBuilder::icons()->rel());

		return $response;
	}
}
?>
