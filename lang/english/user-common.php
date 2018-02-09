<?php
/*##################################################
 *                           user-common.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

 ####################################################
 #                     English                      #
 ####################################################

$lang['user'] = 'User';
$lang['users'] = 'Users';
$lang['profile'] = 'Profile';
$lang['profile_of'] = 'Profile of :name';
$lang['profile.edit'] = 'Edit profile';
$lang['dashboard'] = 'Dashboard';
$lang['messages'] = 'User messages';
$lang['maintain'] = 'Maintain';
$lang['welcome'] = 'Welcome';

$lang['members-list'] = 'Members list';
$lang['member-management'] = 'Member management';
$lang['punishment-management'] = 'Punishments management';

$lang['profile.edit.password.error'] = 'The new password is not correct';
$lang['external-auth.account-exists'] = 'To associate your account with external connection you must connect to the site and go to edit your profile';
$lang['external-auth.email-not-found'] = 'The email address of your account could not be recovered, your account can not be associated.';

//Contribution
$lang['contribution'] = 'Contribution';
$lang['contribution.explain'] = 'Your contribution will be treated in the contribution panel. <span class="color-alert text-strong">Amendment is possible until the contribution has been approved.</span> In the next field, you can justify your contribution to explain your demarche to a moderator.';
$lang['contribution.description'] = 'Contribution counterpart';
$lang['contribution.description.explain'] = 'Explain the reasons of your contribution. This field is not required but it may help the moderator to make his decision.';
$lang['contribution.confirmed'] = 'Your contribution has been saved.';
$lang['contribution.confirmed.messages'] = '<p>You can follow it into the <a href="' . UserUrlBuilder::contribution_panel()->rel() . '">contribution panel</a> 
and possibly discuss with the validators if their choice is not straightforward.</p><p>Thank you for participating in the life of the site!</p>';
$lang['contribution.pm.title'] = 'The contribution <strong>:title</strong> has been commented';
$lang['contribution.pm.contents'] = ':author add a comment to the contribution <strong>:title</strong>.<br />
<br />
Comment :<br />
:comment<br />
<br />
<a href=":contribution_url">See the contribution</a>';


//User fields
$lang['display_name'] = 'Display name';
$lang['display_name.explain'] = 'Display name on each item you add.';
$lang['login'] = 'Login';
$lang['login.explain'] = 'Email address or your customized login if you chose one.';
$lang['login.custom'] = 'Choose a login';
$lang['login.custom.explain'] = '<span class="color-alert">Default, you must log in with your email address.</span>';
$lang['password.custom'] = 'Define a password';
$lang['password.custom.explain'] = 'The password is automatically generated per default';
$lang['password'] = 'Password';
$lang['password.new'] = 'New password';
$lang['password.old'] = 'Old password';
$lang['password.old.explain'] = 'Complete only if amended';
$lang['password.confirm'] = 'Confirm the password';
$lang['password.explain'] = 'Minimum length of password: :number characters';
$lang['email'] = 'Email';
$lang['email.hide'] = 'Hide email';
$lang['theme'] = 'Theme';
$lang['theme.preview'] = 'Theme preview';
$lang['text-editor'] = 'Text editor';
$lang['lang'] = 'Lang';
$lang['timezone'] = 'Timezone';
$lang['timezone.choice'] = 'Timezone choice';
$lang['timezone.choice.explain'] = 'Adjusts the time at your location';
$lang['level'] = 'Level';
$lang['approbation'] = 'Approbation';

$lang['registration_date'] = 'Registration date';
$lang['last_connection'] = 'Last connection';
$lang['number_messages'] = 'Messages number';
$lang['private_message'] = 'Private message';
$lang['delete-account'] = 'Delete account';
$lang['avatar'] = 'Avatar';

//Groups
$lang['groups'] = 'Groups';
$lang['groups.select'] = 'Group select';
$lang['no_member'] = 'No member in this group';

//Other
$lang['caution'] = 'Caution';
$lang['readonly'] = 'Read only';
$lang['banned'] = 'Banned';
$lang['connection'] = 'Sign in';
$lang['autoconnect'] = 'Auto connect';
$lang['disconnect'] = 'Sign out';
$lang['facebook-connect'] = 'Sign in with Facebook';
$lang['google-connect'] = 'Sign in with Google+';
$lang['twitter-connect'] = 'Sign in with Twitter';

$lang['internal_connection'] = 'Internal connection';
$lang['create_internal_connection'] = 'Create internal connection';
$lang['edit_internal_connection'] = 'Edit your internal connection';
$lang['fb_connection'] = 'Facebook connection';
$lang['google_connection'] = 'Google connection';
$lang['associate_account'] = 'Associate your account';
$lang['dissociate_account'] = 'Dissociate your account';

// Ranks
$lang['rank'] = 'Rank';
$lang['visitor'] = 'Visitor';
$lang['member'] = 'Member';
$lang['moderator'] = 'Moderator';
$lang['administrator'] = 'Administrator';

//Forget password
$lang['forget-password'] = 'Forgotten password';
$lang['forget-password.select'] = 'Select the field you want to informat (email or login)';
$lang['forget-password.success'] = 'An email has been sent with a link to change your password';
$lang['forget-password.error'] = 'Information provided are not correct, please correct it and try again';
$lang['change-password'] = 'Change password';
$lang['forget-password.mail.content'] = 'Dear(e) :pseudo,

You are receiving this email because you (or someone claiming to be) have requested a new password to be sent to your account :host. 
If you have not asked to change your password, please ignore it. If you continue to receive it, please contact the site administrator.

To change your password, click on the link below and follow the directions on the site.

:change_password_link

If you have problems, please contact the site administrator.

:signature';

//Registration 
$lang['register'] = 'Register';
$lang['registration'] = 'Registration';

$lang['registration.validation.mail.explain'] = 'You will need to activate your account in the email sent to you before beeing able to connect to the site';
$lang['registration.validation.administrator.explain'] = 'An administrator will need to activate your account before beeing able to connect to the site';

$lang['registration.confirm.success'] = 'Your account has successfully been validated';
$lang['registration.confirm.error'] = 'A problem occurred during your activation, make sure your key is valid';

$lang['registration.success.administrator-validation'] = 'You have registered successfully. However, an administrator must validate your account before you can use it';
$lang['registration.success.mail-validation'] = 'You have registered successfully. However you must click the activation link in the email you were sent';

$lang['registration.email.automatic-validation'] = 'You can now connect to your account directly on the site.';
$lang['registration.email.mail-validation'] = 'You have to click on this link to activate your account: :validation_link';
$lang['registration.email.administrator-validation'] = 'Warning: Your account must be activated by an administrator. Thanks for your patience.';
$lang['registration.email.mail-administrator-validation'] = 'Dear(e) :pseudo,

We are pleased to inform you that your account :site_name has been validated by an administrator.

You can now login to the site using the credentials provided in the previous email.

:signature';

$lang['registration.pending-approval'] = 'A new member has registered. His account must be approved before it can be used.';
$lang['registration.not-approved'] = 'Your account must be approved by an administrator before it can be used.';
$lang['registration.subject-mail'] = 'Confirmation of registration :site_name';
$lang['registration.lost-password-link'] = 'If you loose your password, you can generate a new one from this link :lost_password_link';
$lang['registration.password'] = 'Password : :password';
$lang['registration.content-mail'] = 'Dear(e) :pseudo,

First of all, thank you for being registered on :site_name. Now you are part of the site members.
By registering on :site_name, you get access to the member area that offers several advantages. You can, among other things, be automatically recognized throughout the site to post messages, change the language and / or the default theme, edit your profile, access to classes for members only ... In short you access to all the community site.

To connect, you must retain your username and password.

Here are your identifiers.

Login : :login
:lost_password_link

:accounts_validation_explain

See you on :host

:signature';

$lang['agreement'] = 'Agreement';
$lang['agreement.agree'] = 'I agree this agreement';
$lang['agreement.agree.required'] = 'You must agree for registration';

//Messages
$lang['user.message.success.add'] = 'The user <b>:name</b> has been added';
$lang['user.message.success.edit'] = 'The profile has been modified';
$lang['user.message.success.delete'] = 'The user <b>:name</b> has been deleted';

############## Extended Field ##############

$lang['extended-field.field.sex'] = 'Sex';
$lang['extended-field.field.sex-explain'] = '';

$lang['extended-field.field.pmtomail'] = 'Receive mail notification when receiving a PM';
$lang['extended-field.field.pmtomail-explain'] = '';

$lang['extended-field.field.date-birth'] = 'Birth date';
$lang['extended-field.field.date-birth-explain'] = '';

$lang['extended-field.field.avatar'] = 'Avatar';
$lang['extended-field.field.avatar-explain'] = '';
$lang['extended-field.field.avatar.current_avatar'] = 'Current avatar';
$lang['extended-field.field.avatar.upload_avatar'] = 'Upload an avatar';
$lang['extended-field.field.avatar.upload_avatar-explain'] = 'Avatar on the server';
$lang['extended-field.field.avatar.link'] = 'Avatar link';
$lang['extended-field.field.avatar.link-explain'] = 'Url of the avatar';
$lang['extended-field.field.avatar.delete'] = 'Delete current avatar';
$lang['extended-field.field.avatar.no_avatar'] = 'No avatar';

$lang['extended-field.field.location'] = 'Location';
$lang['extended-field.field.location-explain'] = '';

$lang['extended-field.field.job'] = 'Job';
$lang['extended-field.field.job-explain'] = '';

$lang['extended-field.field.entertainement'] = 'Hobbies';
$lang['extended-field.field.entertainement-explain'] = '';

$lang['extended-field.field.biography'] = 'Biography';
$lang['extended-field.field.biography-explain'] = 'A few lines describing you';

//Scroll to
$lang['scroll-to.top'] = 'Scroll to Top';
$lang['scroll-to.bottom'] = 'Scroll to Bottom';

//Cookies Bar
$lang['cookiebar.cookie'] = 'Cookie';
$lang['cookiebar.cookie.management'] = 'Cookies management';
$lang['cookiebar-message.notracking']  = 'If you continue your visit to this website, you agree to use Cookies to manage your connection, your preferences, and to save anonymous visits statistics.';
$lang['cookiebar-message.tracking']  = 'If you continue your visit to this website, you agree to use cookies or other tracers to offer a suitable navigation (targeted advertising, social media sharing , more ...).';
$lang['cookiebar-message.aboutcookie.title']  = 'About Cookies';
$lang['cookiebar-message.aboutcookie']  = 'To make this site work properly, we sometimes place small data files called cookies on your device.<br />Most big websites do this too.

<h2 class="formatter-title">What are cookies ?</h2>
A cookie is a small text file that a website saves on your computer or mobile device when you visit the site.<br />
It enables the website to remember your actions and preferences (such as login, language, font size and other display preferences) over a period of time, so you don\'t have to keep re-entering them whenever you come back to the site or browse from one page to another.

<h2 class="formatter-title">Technical Cookies : </h2>
' . GeneralConfig::load()->get_site_name() . ' use technical cookie to :<br /> 
<ul class="formatter-ul">
<li class="formatter-li"> manage login (essential if you want to connect),
</li><li class="formatter-li"> save BBCode preferences (not essential, but you will have to re-open extend BBCode à each visit),
</li><li class="formatter-li"> record anonymous statistics for website (note essenial, but allows webmasters to know how visit website.)
</li></ul>
<h2 class="formatter-title">Others cookies :</h2>
' . GeneralConfig::load()->get_site_name() . ' does not use any tracking cookies. However, using google-analytics module or social-media buttons need some tracking cookies
<h2 class="formatter-title">How to control cookies ?</h2>
You can control and/or delete cookies as you wish – for details, see <a title="About Cookies" href="http://www.aboutcookies.org">aboutcookies.org</a>. <br />
You can delete all cookies that are already on your computer and you can set most browsers to prevent them from being placed. If you do this, however, you may have to manually adjust some preferences every time you visit a site and some services and functionalities may not work.
';
$lang['cookiebar.understand']  = 'Ok';
$lang['cookiebar.allowed']  = 'Allowed';
$lang['cookiebar.declined']  = 'Declined';
$lang['cookiebar.more-title']  = 'Explanations on the management of cookies and the "cookie-bar" (Learn more)';
$lang['cookiebar.more']  = 'Learn more';
$lang['cookiebar.cookies'] = 'Cookies';
$lang['cookiebar.change-choice'] = 'Change your preferences';

//Menu
$lang['menu.link-to'] = 'Link to page ';
?>
