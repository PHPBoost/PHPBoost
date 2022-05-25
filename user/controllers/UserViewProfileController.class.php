<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 25
 * @since       PHPBoost 3.0 - 2011 10 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UserViewProfileController extends AbstractController
{
	private $lang;
	private $user_infos;
	private $view;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$user_id = $request->get_getint('user_id', AppContext::get_current_user()->get_id());

		try {
			$this->user_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . 'member', array('*'), 'WHERE user_id=:user_id', array('user_id' => $user_id));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		$this->build_view($this->user_infos['user_id']);

		return $this->build_response($this->view, $user_id);
	}

	private function init()
	{
		$this->lang = LangLoader::get_all_langs();
		$this->view = new FileTemplate('user/UserViewProfileController.tpl');
		$this->view->add_lang($this->lang);
		$this->user = AppContext::get_current_user();
	}

	private function build_view($user_id)
	{
		$registration_date = !empty($this->user_infos['registration_date']) ? Date::to_format($this->user_infos['registration_date'], Date::FORMAT_DAY_MONTH_YEAR) : $this->lang['common.unknown'];
		$last_connection_date = !empty($this->user_infos['last_connection_date']) ? Date::to_format($this->user_infos['last_connection_date'], Date::FORMAT_DAY_MONTH_YEAR) : $this->lang['common.never'];
		$has_groups = $this->build_groups(explode('|', $this->user_infos['user_groups']));
		$extended_fields_number = 0;
		$user_additional_informations = HooksService::execute_hook_display_user_additional_informations_action('user', $this->user_infos);

		foreach (MemberExtendedFieldsService::display_profile_fields($user_id) as $field)
		{
			$this->view->assign_block_vars('extended_fields', array(
				'NAME'          => $field['name'],
				'REWRITED_NAME' => Url::encode_rewrite($field['name']),
				'VALUE'         => $field['value'],
				'C_AVATAR'		=> $field['field_name'] == 'user_avatar'
			));
			$extended_fields_number++;
		}

		foreach ($user_additional_informations as $info)
		{
			$this->view->assign_block_vars('additional_informations', array(
				'VALUE' => $info
			));
		}

		$modules = AppContext::get_extension_provider_service()->get_extension_point(UserExtensionPoint::EXTENSION_POINT);
		$contributions_number = 0;
		foreach ($modules as $module)
		{
			$contributions_number += $module->get_publications_number($user_id);
		}

		$this->view->put_all(array(
			'C_DISPLAY_EDIT_LINK'          => $this->user_infos['user_id'] == AppContext::get_current_user()->get_id() || AppContext::get_current_user()->check_level(User::ADMINISTRATOR_LEVEL),
			'C_IS_BANNED'                  => $this->user->is_banned(),
			'C_GROUPS'                     => $has_groups,
			'C_DISPLAY_MAIL_LINK'          => AppContext::get_current_user()->check_auth(UserAccountsConfig::load()->get_auth_read_members(), UserAccountsConfig::AUTH_READ_MEMBERS_BIT) && $this->user_infos['show_email'],
			'C_DISPLAY_PM_LINK'            => !$this->same_user_view_profile($user_id) && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
			'C_DISPLAY_OTHER_INFORMATIONS' => $extended_fields_number || $user_additional_informations,

			'TITLE_PROFILE'       => $this->user_infos['user_id'] == AppContext::get_current_user()->get_id() ? $this->lang['user.profile'] : StringVars::replace_vars($this->lang['user.profile.of'], array('name' => $this->user_infos['display_name'])),
			'DISPLAY_NAME'        => $this->user_infos['display_name'],
			'LEVEL'               => UserService::get_level_lang($this->user_infos['level']),
			'LEVEL_CLASS'         => UserService::get_level_class($this->user_infos['level']),
			'REGISTRATION_DATE'   => $registration_date,
			'PUBLICATIONS_NUMBER' => $contributions_number,
			'LAST_CONNECTION_DATE'=> $last_connection_date,
			'EMAIL'               => $this->user_infos['email'],

			'U_EDIT_PROFILE'      => UserUrlBuilder::edit_profile($user_id)->rel(),
			'U_USER_PUBLICATIONS' => UserUrlBuilder::publications($user_id)->rel(),
			'U_DISPLAY_USER_PM'   => UserUrlBuilder::personnal_message($user_id)->rel()
		));
	}

	private function same_user_view_profile($user_id)
	{
		return $user_id == AppContext::get_current_user()->get_id();
	}

	private function build_groups($user_groups)
	{
		$groups_cache = GroupsCache::load();
		$has_groups = false;
		foreach ($user_groups as $key => $group_id)
		{
			if ($group_id > 0 && $groups_cache->group_exists($group_id))
			{
				$group = $groups_cache->get_group($group_id);
				$this->view->assign_block_vars('groups', array(
					'ID'              => $group_id,
					'C_PICTURE'       => !empty($group['img']),
					'NAME'            => $group['name'],
					'U_GROUP_PICTURE' => TPL_PATH_TO_ROOT .'/images/group/' . $group['img'],
					'U_GROUP'         => UserUrlBuilder::group($group_id)->rel()
				));
				$has_groups = true;
			}
		}

		return $has_groups;
	}

	private function build_response(View $view, $user_id)
	{
		$response = new SiteDisplayResponse($view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->user_infos['user_id'] == AppContext::get_current_user()->get_id() ? $this->lang['user.profile'] : StringVars::replace_vars($this->lang['user.profile.of'], array('name' => $this->user_infos['display_name']), $this->lang['user.user']));
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['user.seo.profile'], array('name' => $this->user_infos['display_name'])));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(UserUrlBuilder::profile($user_id));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['user.user'], UserUrlBuilder::home()->rel());
		$breadcrumb->add($this->user_infos['user_id'] == AppContext::get_current_user()->get_id() ? $this->lang['user.profile'] : StringVars::replace_vars($this->lang['user.profile.of'], array('name' => $this->user_infos['display_name'])), UserUrlBuilder::profile($user_id)->rel());

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
