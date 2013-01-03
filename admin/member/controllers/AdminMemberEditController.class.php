<?php
/*##################################################
 *                       AdminMemberEditController.class.php
 *                            -------------------
 *   begin                : February 28, 2010
 *   copyright            : (C) 2010 Kï¿½vin MASSY
 *   email                : soldier.weasel@gmail.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class AdminMemberEditController extends AdminController
{
	private $user_lang;
	private $admin_user_lang;
	private $main_lang;
	
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;
	
	private $user;

	public function execute(HTTPRequestCustom $request)
	{
		$user_id = $request->get_getint('id');
		$this->init();
		
		try {
			$this->user = UserService::get_user('WHERE user_id=:id', array('id' => $user_id));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_member();
			DispatchManager::redirect($error_controller);
		}
		
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->user_lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminMembersDisplayResponse($tpl, $this->admin_user_lang['members.edit-member']);
	}

	private function init()
	{
		$this->user_lang = LangLoader::get('user-common');
		$this->admin_user_lang = LangLoader::get('admin-members-common');
		$this->main_lang = LangLoader::get('main');
	}

	private function build_form()
	{
		$form = new HTMLForm('member-edit');
		
		$fieldset = new FormFieldsetHTML('edit_member', $this->admin_user_lang['members.edit-member']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('login', $this->user_lang['pseudo'], $this->user->get_pseudo(), array(
			'class' => 'text', 'maxlength' => 25, 'size' => 25, 'required' => true),
			array(new FormFieldConstraintLengthRange(3, 25), new FormFieldConstraintLoginExist($this->user->get_id()))
		));		
		
		$fieldset->add_field(new FormFieldTextEditor('mail', $this->user_lang['email'], $this->user->get_email(), array(
			'class' => 'text', 'maxlength' => 255, 'required' => true),
			array(new FormFieldConstraintMailAddress(), new FormFieldConstraintMailExist($this->user->get_id()))
		));
		
		$fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->user_lang['password'], ''));
		
		$fieldset->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->user_lang['password.confirm'], ''));
		
		$fieldset->add_field(new FormFieldCheckbox('user_hide_mail', $this->user_lang['email.hide'], FormFieldCheckbox::CHECKED));

		$fieldset = new FormFieldsetHTML('member_management', $this->admin_user_lang['members.member-management']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldCheckbox('approbation', $this->user_lang['approbation'], (bool)$this->user->get_approbation()));
		
		$fieldset->add_field(new FormFieldRanksSelect('rank', $this->user_lang['rank'], $this->user->get_level()));
		
		$fieldset->add_field(new FormFieldGroups('groups', $this->user_lang['groups'], $this->user->get_groups()));
		
		$options_fieldset = new FormFieldsetHTML('options', LangLoader::get_message('options', 'main'));
		$form->add_fieldset($options_fieldset);
		
		$options_fieldset->add_field(new FormFieldTimezone('timezone', $this->user_lang['timezone.choice'], 
			$this->user->get_timezone(), array('description' => $this->user_lang['timezone.choice.explain'])
		));
		
		if (count(ThemeManager::get_activated_and_authorized_themes_map()) > 1)
		{
			$options_fieldset->add_field(new FormFieldThemesSelect('theme', $this->user_lang['theme'], $this->user->get_theme(),
					array('check_authorizations' => true, 'events' => array('change' => $this->build_javascript_picture_themes()))
			));
			$options_fieldset->add_field(new FormFieldFree('preview_theme', $this->user_lang['theme.preview'], '<img id="img_theme" src="'. $this->get_picture_theme($this->user->get_theme()) .'" alt="" style="vertical-align:top; max-height:180px;" />'));
		}
		
		$options_fieldset->add_field(new FormFieldEditors('text-editor', $this->user_lang['text-editor'], $this->user->get_editor()));
		
		$options_fieldset->add_field(new FormFieldLangsSelect('lang',$this->user_lang['lang'], $this->user->get_locale(), array('check_authorizations' => true)));
		
		$fieldset_punishment = new FormFieldsetHTML('punishment_management', $this->admin_user_lang['members.punishment-management']);
		$form->add_fieldset($fieldset_punishment);

		$fieldset_punishment->add_field(new FormFieldCheckbox('delete_account', $this->user_lang['delete-account'], FormFieldCheckbox::UNCHECKED));
		
		$fieldset_punishment->add_field(new FormFieldMemberCaution('user_warning', $this->user_lang['caution'], $this->user->get_warning_percentage()));
		
		$fieldset_punishment->add_field(new FormFieldMemberSanction('user_readonly', $this->user_lang['readonly'], $this->user->get_delay_readonly()));
		
		$fieldset_punishment->add_field(new FormFieldMemberSanction('user_ban', $this->user_lang['banned'], $this->user->get_delay_banned()));
		
		$member_extended_field = new MemberExtendedField();
		$member_extended_field->set_template($form);
		$member_extended_field->set_user_id($this->user->get_id());
		$member_extended_field->set_is_admin(true);
		MemberExtendedFieldsService::display_form_fields($member_extended_field);
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}

	private function save()
	{
		$redirect = true;
		
		$user_id = $this->user->get_id();
		$old_approbation = (int)$this->user->get_approbation();
		
		GroupsService::edit_member($user_id, $this->form->get_value('groups'));
		
		$this->user->set_pseudo($this->form->get_value('login'));
		$this->user->set_level($this->form->get_value('rank')->get_raw_value());
		$this->user->set_groups($this->form->get_value('groups'));
		$this->user->set_email($this->form->get_value('mail'));
		$this->user->set_show_email(!$this->form->get_value('user_hide_mail'));
		$this->user->set_approbation($this->form->get_value('approbation'));
		
		$this->user->set_theme($this->form->get_value('theme')->get_raw_value());
		$this->user->set_locale($this->form->get_value('lang')->get_raw_value());
		$this->user->set_timezone($this->form->get_value('timezone')->get_raw_value());
		$this->user->set_editor($this->form->get_value('text-editor')->get_raw_value());
		UserService::update($this->user, 'WHERE user_id=:id', array('id' => $user_id));
		
		if ($old_approbation != $this->user->get_approbation() && $old_approbation == 0)
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
				$subject = StringVars::replace_vars($this->user_lang['registration.subject-mail'], array('site_name' => $site_name));
				$content = StringVars::replace_vars($this->user_lang['registration.email.mail-administrator-validation'], array(
					'pseudo' => $this->user->get_pseudo(),
					'site_name' => $site_name,
					'signature' => MailServiceConfig::load()->get_mail_signature()
				));
				$mail = new Mail();
				$mail->add_recipient($this->user->get_email(), $this->user->get_pseudo());
				$mail->set_sender(MailServiceConfig::load()->get_default_mail_sender());
				$mail->set_subject($subject);
				$mail->set_content($content);
				AppContext::get_mail_service()->try_to_send($mail);
			}
		}

		try {
			MemberExtendedFieldsService::register_fields($this->form, $user_id);
		} catch (MemberExtendedFieldErrorsMessageException $e) {
			$redirect = false;
			$this->tpl->put('MSG', MessageHelper::display($e->getMessage(), MessageHelper::NOTICE));
		}
			
		if ($this->form->get_value('delete_account'))
		{
			UserService::delete_account('WHERE user_id=:user_id', array('user_id' => $user_id));
		}
		
		$password = $this->form->get_value('password');
		$password_bis = $this->form->get_value('password_bis');
		if (!empty($password) && !empty($password_bis))
		{
			UserService::change_password(KeyGenerator::string_hash($password), 'WHERE user_id=:user_id', array('user_id' => $user_id));
		}
		
		$user_warning = $this->form->get_value('user_warning')->get_raw_value();
		if (!empty($user_warning) && $user_warning != $this->user->get_warning_percentage())
		{
			MemberSanctionManager::caution($user_id, $user_warning, MemberSanctionManager::SEND_MP, str_replace('%level%', $user_warning, $this->main_lang['user_warning_level_changed']));
		}
		
		$user_readonly = $this->form->get_value('user_readonly')->get_raw_value();
		if (!empty($user_readonly) && $user_readonly != $this->user->get_delay_readonly())
		{
			MemberSanctionManager::remove_write_permissions($user_id, time() + $user_readonly, MemberSanctionManager::SEND_MP, str_replace('%date%', $this->form->get_value('user_readonly')->get_label(), $this->main_lang['user_readonly_changed']));
		}
		elseif ($user_readonly != $this->user->get_delay_readonly())
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
		
		StatsCache::invalidate();
		GroupsCache::invalidate(); //On régénère le fichier de cache des groupes
		
		if ($redirect)
		{
			AppContext::get_response()->redirect(PATH_TO_ROOT . '/admin/admin_members.php');
		}
	}
	
	private function build_javascript_picture_themes()
	{
		$text = 'var theme = new Array;' . "\n";
		foreach (ThemeManager::get_activated_themes_map() as $theme)
		{
			$pictures = $theme->get_configuration()->get_pictures();
			$text .= 'theme["' . $theme->get_id() . '"] = "' . TPL_PATH_TO_ROOT .'/templates/' . $theme->get_id() . '/' . $pictures[0] . '";' . "\n";
		}
		$text .= 'var theme_id = HTMLForms.getField("theme").getValue(); document.images[\'img_theme\'].src = theme[theme_id];';
		return $text;
	}
	
	private function get_picture_theme($user_theme)
	{
		$pictures = ThemeManager::get_theme($user_theme)->get_configuration()->get_pictures();
		return TPL_PATH_TO_ROOT .'/templates/' . $user_theme . '/' . $pictures[0];
	}
}
?>