<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 19
 * @since       PHPBoost 3.0 - 2011 10 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class UserViewProfileController extends AbstractController
{
	private $lang;
	private $user_infos;
	private $tpl;

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

		return $this->build_response($this->tpl, $user_id);
	}

	private function init()
	{
		$this->lang = LangLoader::get('user-common');
		$this->tpl = new FileTemplate('user/UserViewProfileController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->user = AppContext::get_current_user();
	}

	private function build_view($user_id)
	{
		$registration_date = !empty($this->user_infos['registration_date']) ? Date::to_format($this->user_infos['registration_date'], Date::FORMAT_DAY_MONTH_YEAR) : LangLoader::get_message('unknown', 'main');
		$last_connection_date = !empty($this->user_infos['last_connection_date']) ? Date::to_format($this->user_infos['last_connection_date'], Date::FORMAT_DAY_MONTH_YEAR) : LangLoader::get_message('never', 'main');
		$has_groups = $this->build_groups(explode('|', $this->user_infos['groups']));
		$extended_fields_number = 0;

		foreach (MemberExtendedFieldsService::display_profile_fields($user_id) as $field)
		{
			$this->tpl->assign_block_vars('extended_fields', array(
				'NAME' => $field['name'],
				'REWRITED_NAME' => Url::encode_rewrite($field['name']),
				'VALUE' => $field['value']
			));
			$extended_fields_number++;
		}

		$this->tpl->put_all(array(
			'C_DISPLAY_EDIT_LINK' => $this->user_infos['user_id'] == AppContext::get_current_user()->get_id() || AppContext::get_current_user()->check_level(User::ADMIN_LEVEL),
			'C_IS_BANNED' => $this->user->is_banned(),
			'C_GROUPS' => $has_groups,
			'C_DISPLAY_MAIL_LINK' => AppContext::get_current_user()->check_auth(UserAccountsConfig::load()->get_auth_read_members(), UserAccountsConfig::AUTH_READ_MEMBERS_BIT) && $this->user_infos['show_email'],
			'C_DISPLAY_PM_LINK' => !$this->same_user_view_profile($user_id) && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
			'C_EXTENDED_FIELDS' => $extended_fields_number,
			'TITLE_PROFILE' => $this->user_infos['user_id'] == AppContext::get_current_user()->get_id() ? $this->lang['profile'] : StringVars::replace_vars($this->lang['profile_of'], array('name' => $this->user_infos['display_name'])),
			'DISPLAY_NAME' => $this->user_infos['display_name'],
			'LEVEL' => UserService::get_level_lang($this->user_infos['level']),
			'LEVEL_CLASS' => UserService::get_level_class($this->user_infos['level']),
			'REGISTRATION_DATE' => $registration_date,
			'MESSAGES_NUMBER' => $this->user_infos['posted_msg'],
			'LAST_CONNECTION_DATE' => $last_connection_date,
			'EMAIL' => $this->user_infos['email'],
			'U_EDIT_PROFILE' => UserUrlBuilder::edit_profile($user_id)->rel(),
			'U_DISPLAY_USER_MESSAGES' => UserUrlBuilder::messages($user_id)->rel(),
			'U_DISPLAY_USER_PM' => UserUrlBuilder::personnal_message($user_id)->rel()
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
				$this->tpl->assign_block_vars('groups', array(
					'ID' => $group_id,
					'C_PICTURE' => !empty($group['img']),
					'NAME' => $group['name'],
					'U_GROUP_PICTURE' => TPL_PATH_TO_ROOT .'/images/group/' . $group['img'],
					'U_GROUP' => UserUrlBuilder::group($group_id)->rel()
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
		$graphical_environment->set_page_title($this->user_infos['user_id'] == AppContext::get_current_user()->get_id() ? $this->lang['profile'] : StringVars::replace_vars($this->lang['profile_of'], array('name' => $this->user_infos['display_name']), $this->lang['user']));
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['seo.user.profile'], array('name' => $this->user_infos['display_name'])));
		$graphical_environment->get_seo_meta_data()->set_canonical_url(UserUrlBuilder::profile($user_id));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['user'], UserUrlBuilder::home()->rel());
		$breadcrumb->add($this->user_infos['user_id'] == AppContext::get_current_user()->get_id() ? $this->lang['profile'] : StringVars::replace_vars($this->lang['profile_of'], array('name' => $this->user_infos['display_name'])), UserUrlBuilder::profile($user_id)->rel());

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
