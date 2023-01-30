<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 30
 * @since       PHPBoost 3.0 - 2011 10 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UserPublicationsController extends AbstractController
{
	private $lang;
	private $view;
	private $user;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$user_id = $request->get_getint('user_id', 0);

		if (empty($user_id))
		{
			AppContext::get_response()->redirect(UserUrlBuilder::home());
		}
		else if ($user_id != $this->user->get_id())
		{
			if (UserService::user_exists('WHERE user_id = :id', array('id' => $user_id)))
				$this->user = UserService::get_user($user_id);
			else
			{
				$error_controller = PHPBoostErrors::unexisting_element();
				DispatchManager::redirect($error_controller);
			}
		}

		$this->build_view();
		return $this->build_response($this->view);
	}

	private function init()
	{
		$this->user = AppContext::get_current_user();
		$this->view = new FileTemplate('user/UserPublicationsController.tpl');
		$this->lang = LangLoader::get_all_langs();
		$this->view->add_lang($this->lang);
	}

	private function build_view()
	{
		$modules_with_publications = AppContext::get_extension_provider_service()->get_extension_point(UserExtensionPoint::EXTENSION_POINT);

        $this->view->put_all(array(
            'C_CURRENT_USER' => $this->user->get_id() == AppContext::get_current_user()->get_id(),
            'USER_NAME' => $this->user->get_display_name()
        ));

        foreach (array_merge(array('user' => true), ModulesManager::get_activated_modules_map_sorted_by_localized_name()) as $id => $installed_module)
		{
			if (in_array($id, array_keys($modules_with_publications)))
			{
				$module = $modules_with_publications[$id];
				$module_icon = new File(PATH_TO_ROOT . '/' . $module->get_publications_module_id() . '/' . $module->get_publications_module_id() . '_mini.png');
				$is_picture = false;
				if($module->get_publications_module_icon() != '')
					$thumbnail = $module->get_publications_module_icon();
				else if($module_icon->exists())
				{
					$thumbnail = Url::to_rel($module_icon->get_path());
					$is_picture = true;
				}
				else if($module->get_publications_module_id() != '')
				{
					$thumbnail = ModulesManager::get_module($module->get_publications_module_id())->get_configuration()->get_fa_icon();
				}
				else
					$thumbnail = 'fa fa-cube';

				$this->view->assign_block_vars('user_publications', array(
					'C_ICON_IS_PICTURE'   => $is_picture,
					'MODULE_NAME'         => $module->get_publications_module_name(),
					'MODULE_THUMBNAIL'    => $thumbnail,
					'U_MODULE_VIEW'       => $module->get_publications_module_view($this->user->get_id()),
					'PUBLICATIONS_NUMBER' => (int)$module->get_publications_number($this->user->get_id())
				));
			}
		}
	}

	private function build_response(View $view)
	{
		$title = $this->lang['user.user.messages'];
		$response = new SiteDisplayResponse($view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($title);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['user.seo.messages'], array('name' => $this->user->get_display_name())));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(UserUrlBuilder::publications($this->user->get_id()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['user.user'], UserUrlBuilder::home()->rel());
		$breadcrumb->add($title, UserUrlBuilder::publications($this->user->get_id())->rel());

		return $response;
	}

	public function get_right_controller_regarding_authorizations()
	{
		if (!AppContext::get_current_user()->check_auth(UserAccountsConfig::load()->get_auth_read_members(), UserAccountsConfig::AUTH_READ_MEMBERS_BIT))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		return $this;
	}
}
?>
