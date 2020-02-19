<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 09
 * @since       PHPBoost 3.0 - 2011 10 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UserEditProfileController extends AbstractController
{
	private $lang;
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;

	private $user;
	private $internal_auth_infos;
	private $user_auth_types;

	private $member_extended_fields_service;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$user_id = $request->get_getint('user_id', AppContext::get_current_user()->get_id());

		try {
			$this->user = UserService::get_user($user_id);
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		try {
			$this->internal_auth_infos = PHPBoostAuthenticationMethod::get_auth_infos($user_id);
		} catch (RowNotFoundException $e) {
		}

		if (!$this->check_authorizations($user_id))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		$this->user_auth_types = AuthenticationService::get_user_types_authentication($user_id);

		$associate_type = $request->get_getvalue('associate', false);
		if ($associate_type)
		{
			if (AuthenticationService::external_auth_is_activated($associate_type))
			{
				$authentication_method = AuthenticationService::get_external_auth_activated($associate_type)->get_authentication();
				AuthenticationService::associate($authentication_method, $user_id);
				AppContext::get_response()->redirect(UserUrlBuilder::edit_profile($user_id));
			}
		}

		$dissociate_type = $request->get_getvalue('dissociate', false);
		if ($dissociate_type)
		{
			if (AuthenticationService::external_auth_is_activated($dissociate_type) && count(AuthenticationService::get_external_auths_activated()) > 1)
			{
				$authentication_method = AuthenticationService::get_external_auth_activated($dissociate_type)->get_authentication();
				AuthenticationService::dissociate($authentication_method, $user_id);
				AppContext::get_response()->redirect(UserUrlBuilder::edit_profile($user_id));
			}
		}

		$this->build_form();

		if ($this->user->get_level() != User::ADMIN_LEVEL || ($this->user->get_level() == User::ADMIN_LEVEL && $this->user->get_id() != AppContext::get_current_user()->get_id()) || ($this->user->get_level() == User::ADMIN_LEVEL && $this->user->get_id() == AppContext::get_current_user()->get_id() && UserService::count_admin_members() > 1))
		{
			if ($request->has_getparameter('delete-account') && $request->get_getvalue('delete-account'))
				$this->delete_account();
		}

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save($request);
		}

		$this->tpl->put('FORM', $this->form->display());

		return $this->build_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('user-common');
		$this->tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$this->tpl->add_lang($this->lang);
		$this->user_accounts_config = UserAccountsConfig::load();
	}

	private function check_authorizations()
	{
		return AppContext::get_current_user()->get_id() == $this->user->get_id() || AppContext::get_current_user()->check_level(User::ADMIN_LEVEL);
	}

	private function build_form()
	{
		$security_config = SecurityConfig::load();

		$form = new HTMLForm(__CLASS__);
		$this->member_extended_fields_service = new MemberExtendedFieldsService($form);

		$fieldset = new FormFieldsetHTMLHeading('edit_profile', $this->lang['profile.edit']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field($display_name = new FormFieldTextEditor('display_name', $this->lang['display_name'], $this->user->get_display_name(),
			array('maxlength' => 100, 'required' => true, 'description'=> $this->lang['display_name.explain'], 'disabled' => !AppContext::get_current_user()->is_admin() && !$this->user_accounts_config->are_users_allowed_to_change_display_name() && $this->user->get_display_name(), 'events' => array('blur' => '
				if (!HTMLForms.getField("login").getValue() && HTMLForms.getField("display_name").validate() == "") {
					HTMLForms.getField("login").setValue(HTMLForms.getField("display_name").getValue().replace(/\s/g, \'\'));
				}')
			),
			array(new FormFieldConstraintLengthRange(3, 100), new FormFieldConstraintDisplayNameExists($this->user->get_id()))
		));

		$fieldset->add_field($email = new FormFieldMailEditor('email', $this->lang['email'], $this->user->get_email(),
			array('required' => true, 'disabled' => !AppContext::get_current_user()->is_admin() && !$this->user_accounts_config->are_users_allowed_to_change_email() && $this->user->get_email()),
			array(new FormFieldConstraintMailExist($this->user->get_id()))
		));

		$fieldset->add_field(new FormFieldCheckbox('user_hide_mail', $this->lang['email.hide'], !$this->user->get_show_email()));

		if (AppContext::get_current_user()->is_admin())
		{
			$manage_fieldset = new FormFieldsetHTML('member_management', $this->lang['member-management']);
			$form->add_fieldset($manage_fieldset);

			if ($this->internal_auth_infos)
				$manage_fieldset->add_field(new FormFieldCheckbox('approbation', $this->lang['approbation'], $this->internal_auth_infos['approved']));

			$manage_fieldset->add_field(new FormFieldRanksSelect('rank', $this->lang['rank'], $this->user->get_level()));

			$manage_fieldset->add_field(new FormFieldGroups('groups', $this->lang['groups'], $this->user->get_groups()));
		}

		$connect_fieldset = new FormFieldsetHTML('connect', $this->lang['connection']);
		$form->add_fieldset($connect_fieldset);

		$activated_external_authentication = AuthenticationService::get_external_auths_activated();
		$more_than_one_authentication_type = count($activated_external_authentication) >= 1;
		$internal_auth_connected = in_array(PHPBoostAuthenticationMethod::AUTHENTICATION_METHOD, $this->user_auth_types);

		$has_custom_login = $this->internal_auth_infos['login'] && $this->user->get_email() !== $this->internal_auth_infos['login'];

		if ($more_than_one_authentication_type)
		{
			if ($internal_auth_connected)
			{
				$connect_fieldset->add_field(new FormFieldFree('internal_auth', $this->lang['internal_connection'] . ' <i class="fa fa-check success"></i>', LangLoader::get_message('edit_internal_connection', 'user-common')));
			}
			else
			{
				$connect_fieldset->add_field(new FormFieldFree('internal_auth', $this->lang['internal_connection'] . ' <i class="fa fa-times error"></i>', '<a  href="#" onclick="javascript:HTMLForms.getField(\'custom_login\').setValue(false);HTMLForms.getField(\'custom_login\').enable();HTMLForms.getField(\'password\').enable();HTMLForms.getField(\'password_bis\').enable();return false;">' . LangLoader::get_message('create_internal_connection', 'user-common') . '</a>'));
			}
		}

		$connect_fieldset->add_field($custom_login_checked = new FormFieldCheckbox('custom_login', $this->lang['login.custom'], $has_custom_login,
			array(
				'description'=> $this->lang['login.custom.explain'],
				'hidden' => !$internal_auth_connected,
				'events' => array('click' => '
					if (HTMLForms.getField("custom_login").getValue()) {
						HTMLForms.getField("login").enable();
					} else {
						HTMLForms.getField("login").disable();
					}'
				)
			)
		));

		$connect_fieldset->add_field($login = new FormFieldTextEditor('login', $this->lang['login'], ($has_custom_login ? $this->internal_auth_infos['login'] : preg_replace('/\s+/u', '', $this->user->get_display_name())),
			array(	'required' => true,
					'hidden' => !$internal_auth_connected || !$has_custom_login,
					'maxlength' => 25
				),
			array(new FormFieldConstraintLengthRange(3, 25), new FormFieldConstraintPHPBoostAuthLoginExists($this->user->get_id()))
		));

		if ($this->user->get_id() == AppContext::get_current_user()->get_id())
		{
			$connect_fieldset->add_field(new FormFieldPasswordEditor('old_password', $this->lang['password.old'], '', array(
				'description' => $this->lang['password.old.explain'], 'autocomplete' => false, 'hidden' => !$internal_auth_connected))
			);
		}

		$connect_fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->lang['password'], '',
			array('description' => StringVars::replace_vars($this->lang['password.explain'], array('number' => $security_config->get_internal_password_min_length())), 'autocomplete' => false, 'hidden' => !$internal_auth_connected),
			array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()), new FormFieldConstraintPasswordStrength())
		));

		$connect_fieldset->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['password.confirm'], '',
			array('autocomplete' => false, 'hidden' => !$internal_auth_connected),
			array(new FormFieldConstraintLengthMin($security_config->get_internal_password_min_length()), new FormFieldConstraintPasswordStrength())
		));

		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));

		if ($security_config->are_login_and_email_forbidden_in_password())
		{
			$form->add_constraint(new FormConstraintFieldsNotIncluded($email, $password));
			$form->add_constraint(new FormConstraintFieldsNotIncluded($custom_login_checked == 'on' ? $login : $display_name, $password));
		}

		foreach ($activated_external_authentication as $id => $authentication)
		{
			if (in_array($id, $this->user_auth_types))
			{
				$connect_fieldset->add_field(new FormFieldFree($id .'_auth', $authentication->get_authentication_name() . ' <i class="fa fa-check success"></i>', '<a href="'. UserUrlBuilder::edit_profile($this->user->get_id(), 'dissociate', $id)->absolute() . '">' . ($this->user->get_id() != AppContext::get_current_user()->get_id() ? $this->lang['dissociate_account_admin'] : $this->lang['dissociate_account']) . '</a>'));
			}
			else
			{
				$connect_fieldset->add_field(new FormFieldFree($id .'_auth', $authentication->get_authentication_name() . ' <i class="fa fa-times"></i>', '<a href="'. UserUrlBuilder::edit_profile($this->user->get_id(), 'associate', $id)->absolute() . '">' . ($this->user->get_id() != AppContext::get_current_user()->get_id() ? $this->lang['associate_account_admin'] : $this->lang['associate_account']) . '</a>'));
			}
		}

		$options_fieldset = new FormFieldsetHTML('options', LangLoader::get_message('options', 'main'));
		$form->add_fieldset($options_fieldset);

		$options_fieldset->add_field(new FormFieldTimezone('timezone', $this->lang['timezone.choice'],
			$this->user->get_timezone(), array('description' => $this->lang['timezone.choice.explain'])
		));

		if (count(ThemesManager::get_activated_and_authorized_themes_map()) > 1)
		{
			$options_fieldset->add_field(new FormFieldThemesSelect('theme', $this->lang['theme'], $this->user->get_theme(),
				array('check_authorizations' => true, 'events' => array('change' => $this->build_javascript_picture_themes()))
			));
			$options_fieldset->add_field(new FormFieldFree('preview_theme', $this->lang['theme.preview'], '<img id="img_theme" src="'. $this->get_picture_theme($this->user->get_theme()) .'" alt="' . $this->lang['theme.preview'] . '" class="preview-img" />'));
		}

		$options_fieldset->add_field(new FormFieldEditors('text-editor', $this->lang['text-editor'], $this->user->get_editor()));

		$options_fieldset->add_field(new FormFieldLangsSelect('lang', $this->lang['lang'], $this->user->get_locale(), array('check_authorizations' => true)));

		if (AppContext::get_current_user()->is_admin())
		{
			$fieldset_punishment = new FormFieldsetHTML('punishment_management', $this->lang['punishment-management']);
			$form->add_fieldset($fieldset_punishment);

			$fieldset_punishment->add_field(new FormFieldMemberCaution('user_warning', $this->lang['caution'], $this->user->get_warning_percentage()));

			$fieldset_punishment->add_field(new FormFieldMemberSanction('user_readonly', $this->lang['readonly'], $this->user->get_delay_readonly()));

			$fieldset_punishment->add_field(new FormFieldMemberSanction('user_ban', $this->lang['banned'], $this->user->get_delay_banned()));
		}

		$this->member_extended_fields_service->display_form_fields($this->user->get_id());

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		if ($this->user->get_level() != User::ADMIN_LEVEL || ($this->user->get_level() == User::ADMIN_LEVEL && $this->user->get_id() != AppContext::get_current_user()->get_id()) || ($this->user->get_level() == User::ADMIN_LEVEL && $this->user->get_id() == AppContext::get_current_user()->get_id() && UserService::count_admin_members() > 1))
		{
			$form->add_button(new FormButtonLink($this->lang['delete-account'], UserUrlBuilder::edit_profile($this->user->get_id(), 'delete-account')->relative(), '', 'delete-account warning', ($this->user->get_id() != AppContext::get_current_user()->get_id() ? $this->lang['delete-account.confirmation.admin'] : $this->lang['delete-account.confirmation.member'])));
		}

		$this->form = $form;
	}

	private function delete_account()
	{
		if ($this->user->get_level() != User::ADMIN_LEVEL || ($this->user->get_level() == User::ADMIN_LEVEL && $this->user->get_id() != AppContext::get_current_user()->get_id()) || ($this->user->get_level() == User::ADMIN_LEVEL && $this->user->get_id() == AppContext::get_current_user()->get_id() && UserService::count_admin_members() > 1))
		{
			UserService::delete_by_id($this->user->get_id());
		}

		if ($this->user->get_id() == AppContext::get_current_user()->get_id())
			AppContext::get_response()->redirect(Environment::get_home_page(), $this->lang['user.message.success.delete.member']);
		else
			AppContext::get_response()->redirect(UserUrlBuilder::home(), StringVars::replace_vars($this->lang['user.message.success.delete'], array('name' => $this->user->get_display_name())));
	}

	private function save(HTTPRequestCustom $request)
	{
		$has_error = false;

		$user_id = $this->user->get_id();

		$approbation = $this->internal_auth_infos ? $this->internal_auth_infos['approved'] : true;
		if (AppContext::get_current_user()->is_admin())
		{
			$old_approbation = $approbation;
			if ($this->internal_auth_infos)
				$approbation = $this->form->get_value('approbation');

			$groups = array();
			foreach ($this->form->get_value('groups') as $field => $option)
			{
				$groups[] = $option->get_raw_value();
			}

			GroupsService::edit_member($user_id, $groups);
			$this->user->set_groups($groups);
			$this->user->set_level($this->form->get_value('rank')->get_raw_value());
		}

		if ($this->form->has_field('theme'))
		{
			$this->user->set_theme($this->form->get_value('theme')->get_raw_value());
		}

		$this->user->set_locale($this->form->get_value('lang')->get_raw_value());
		$this->form->get_field_by_id('display_name')->enable();
		$this->user->set_display_name($this->form->get_value('display_name'));
		$this->form->get_field_by_id('email')->enable();
		$this->user->set_email($this->form->get_value('email'));
		$this->user->set_locale($this->form->get_value('lang')->get_raw_value());
		$this->user->set_editor($this->form->get_value('text-editor')->get_raw_value());
		$this->user->set_show_email(!$this->form->get_value('user_hide_mail'));
		$this->user->set_timezone($this->form->get_value('timezone')->get_raw_value());

		try {
			UserService::update($this->user, $this->member_extended_fields_service);
		} catch (MemberExtendedFieldErrorsMessageException $e) {
			$has_error = true;
			$this->tpl->put('MSG', MessageHelper::display($e->getMessage(), MessageHelper::NOTICE));
		}

		$login = $this->form->get_value('email');
		$custom_login_hidden = $this->form->get_field_by_id('custom_login')->is_hidden();

		if ($custom_login_hidden)
			$this->form->get_field_by_id('custom_login')->set_hidden(false);

		if ($this->form->get_value('custom_login', false))
			$login = $this->form->get_value('login');

		if ($custom_login_hidden)
			$this->form->get_field_by_id('custom_login')->set_hidden(true);

		$password = $this->form->get_value('password');
		if ($this->internal_auth_infos === null && !empty($password))
		{
			$authentication_method = new PHPBoostAuthenticationMethod($login, $password);
			AuthenticationService::associate($authentication_method, $user_id);
		}
		elseif (!empty($password))
		{
			$old_password = $this->form->get_value('old_password');
			if (!empty($old_password) || (AppContext::get_current_user()->is_admin() && $user_id != AppContext::get_current_user()->get_id()))
			{
				$old_password_hashed = KeyGenerator::string_hash($old_password);

				if ($old_password_hashed == $this->internal_auth_infos['password'] || (AppContext::get_current_user()->is_admin() && $user_id != AppContext::get_current_user()->get_id()))
				{
					PHPBoostAuthenticationMethod::update_auth_infos($user_id, $login, $approbation, KeyGenerator::string_hash($password));
					$has_error = false;
				}
				else
				{
					$has_error = true;
					$this->tpl->put('MSG', MessageHelper::display($this->lang['profile.edit.password.error'], MessageHelper::NOTICE));
				}
			}
		}
		else
		{
			PHPBoostAuthenticationMethod::update_auth_infos($user_id, $login, $approbation);
		}

		if (AppContext::get_current_user()->is_admin())
		{
			if ($old_approbation != $approbation && $old_approbation == 0)
			{
				//Recherche de l'alerte correspondante
				$matching_alerts = AdministratorAlertService::find_by_criteria($user_id, 'member_account_to_approbate');

				//L'alerte a été trouvée
				if (count($matching_alerts) == 1)
				{
					$alert = $matching_alerts[0];
					$alert->set_status(AdministratorAlert::ADMIN_ALERT_STATUS_PROCESSED);
					AdministratorAlertService::save_alert($alert);

					$site_name = GeneralConfig::load()->get_site_name();
					$subject = StringVars::replace_vars($this->lang['registration.subject-mail'], array('site_name' => $site_name));
					$content = StringVars::replace_vars($this->lang['registration.email.mail-administrator-validation'], array(
						'pseudo' => $this->user->get_display_name(),
						'site_name' => $site_name,
						'signature' => MailServiceConfig::load()->get_mail_signature()
					));
					AppContext::get_mail_service()->send_from_properties($this->user->get_email(), $subject, $content);
				}
			}

			$user_warning = $this->form->get_value('user_warning')->get_raw_value();
			if (!empty($user_warning) && $user_warning != $this->user->get_warning_percentage())
			{
				MemberSanctionManager::caution($user_id, $user_warning, MemberSanctionManager::SEND_MP, str_replace('%level%', $user_warning, LangLoader::get_message('user_warning_level_changed', 'main')));
			}
			elseif (empty($user_warning))
			{
				MemberSanctionManager::cancel_caution($user_id);
			}

			$user_readonly = $this->form->get_value('user_readonly')->get_raw_value();
			if (!empty($user_readonly) && $user_readonly != $this->user->get_delay_readonly())
			{
				MemberSanctionManager::remove_write_permissions($user_id, time() + $user_readonly, MemberSanctionManager::SEND_MP, str_replace('%date%', $this->form->get_value('user_readonly')->get_label(), LangLoader::get_message('user_readonly_changed', 'main')));
			}
			elseif (empty($user_readonly))
			{
				MemberSanctionManager::restore_write_permissions($user_id);
			}

			$user_ban = $this->form->get_value('user_ban')->get_raw_value();
			if (!empty($user_ban) && $user_ban != $this->user->get_delay_banned())
			{
				MemberSanctionManager::banish($user_id, time() + $user_ban, MemberSanctionManager::SEND_MAIL);
			}
			elseif ($user_ban != $this->user->get_delay_banned())
			{
				MemberSanctionManager::cancel_banishment($user_id);
			}
		}
		SessionData::recheck_cached_data_from_user_id($user_id);

		if (!$has_error)
		{
			AppContext::get_response()->redirect(($request->get_url_referrer() ? $request->get_url_referrer() : UserUrlBuilder::edit_profile($user_id)), $this->lang['user.message.success.edit']);
		}
	}

	private function build_response()
	{
		$response = new SiteDisplayResponse($this->tpl);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['profile.edit'], $this->lang['user']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['user'], UserUrlBuilder::home()->rel());
		$breadcrumb->add(StringVars::replace_vars($this->lang['profile_of'], array('name' => $this->user->get_display_name())), UserUrlBuilder::profile($this->user->get_id())->rel());
		$breadcrumb->add($this->lang['profile.edit'], UserUrlBuilder::edit_profile($this->user->get_id())->rel());

		return $response;
	}

	private function build_javascript_picture_themes()
	{
		$text = 'var theme = new Array;' . "\n";
		foreach (ThemesManager::get_activated_themes_map() as $theme)
		{
			$picture = $theme->get_configuration()->get_first_picture();
			$text .= 'theme["' . $theme->get_id() . '"] = "' . TPL_PATH_TO_ROOT .'/templates/' . $theme->get_id() . '/' . $picture . '";' . "\n";
		}
		$text .= 'var theme_id = HTMLForms.getField("theme").getValue(); document.images[\'img_theme\'].src = theme[theme_id];';
		return $text;
	}

	private function get_picture_theme($user_theme)
	{
		$picture = ThemesManager::get_theme($user_theme)->get_configuration()->get_first_picture();
		return TPL_PATH_TO_ROOT .'/templates/' . $user_theme . '/' . $picture;
	}
}
?>
