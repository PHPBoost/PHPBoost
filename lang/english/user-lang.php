<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 19
 * @since       PHPBoost 3.0 - 2011 10 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

// Connexion panel
$lang['user.username']           = 'Username';
$lang['user.username.tooltip']   = 'If you didn\'t checked "Choose a login" in your account, you have to connect with your email address.';
$lang['user.password']           = 'Password';
$lang['user.sign.in']            = 'Sign in';
$lang['user.connect']            = 'Connect';
$lang['user.stay.connected']     = 'Stay connected';
$lang['user.auto.connect']       = 'Auto connect';
$lang['user.sign.out']           = 'Sign out';
$lang['user.sign.up']            = 'Sign up';
$lang['user.forgotten.password'] = 'Forgotten password';

// Forgotten password
$lang['user.change.password']            = 'Change password';
$lang['user.forgotten.password.select']  = 'Select the field you want to informat (email or login)';
$lang['user.forgotten.password.success'] = 'An email has been sent with a link to change your password';
$lang['user.forgotten.password.error']   = 'Information provided are not correct, please correct it and try again';
$lang['user.forgotten.password.email.content'] = 'Dear(e) :pseudo,

You are receiving this email because you (or someone claiming to be) have requested a new password to be sent to your account :host.
If you have not asked to change your password, please ignore it. If you continue to receive it, please contact the site administrator.

To change your password, click on the link below and follow the directions on the site.

:change_password_link

If you have problems, please contact the site administrator.

:signature';

// Dashboard
$lang['user.private.messaging']  = 'Private messaging';
$lang['user.admin.panel']        = 'Admin panel';
$lang['user.moderation.panel']   = 'Moderation panel';
$lang['user.contribution.panel'] = 'Contribution panel';
$lang['user.dashboard']          = 'Dashboard';
$lang['user.my.account']         = 'My account';
$lang['user.my.profile']         = 'My profile';

// Ranks
$lang['user.rank']               = 'Rank';
$lang['user.rank.robot']         = 'Robot';
$lang['user.rank.visitor']       = 'Visitor';
$lang['user.rank.member']        = 'Member';
$lang['user.rank.moderator']     = 'Moderator';
$lang['user.rank.administrator'] = 'Administrator';

// S.E.O.
$lang['user.seo.profile']            = 'All informations about :name.';
$lang['user.seo.list']               = 'Table of users list.';
$lang['user.seo.groups']             = 'Each group users.';
$lang['user.seo.comments']           = 'All comments.';
$lang['user.seo.comments.user']      = 'All comments of :name.';
$lang['user.seo.messages']           = 'All messages of :name.';
$lang['user.seo.registration']       = 'Fill all required informations to create an account.';
$lang['user.seo.login']              = 'Log to the site to reach the protected area.';
$lang['user.seo.forgotten.password'] = 'Fill all required informations to receive a link to change your password.';
$lang['user.seo.about.cookie']       = 'All informations about cookies.';

// Labels
$lang['user.user']           = 'User';
$lang['user.users']          = 'Users';
$lang['user.profile']        = 'Profile';
$lang['user.profile.of']     = 'Profile of :name';
$lang['user.profile.edit']   = 'Edit profile';
$lang['user.contact']        = 'Contact';
$lang['user.message']        = 'Message';
$lang['user.messages']       = 'Messages';
$lang['user.last.message']   = 'Last message';
$lang['user.user.messages']  = 'User messages';
$lang['user.welcome']        = 'Welcome';
$lang['user.about.author']   = 'About the author';
$lang['user.robot']          = 'Robot';
$lang['user.robots']         = 'Robots';
$lang['user.guest']          = 'Visitor';
$lang['user.guests']         = 'Visitors';
$lang['user.member']         = 'Member';
$lang['user.members']        = 'Members';
$lang['user.moderator']      = 'Moderator';
$lang['user.moderators']     = 'Moderators';
$lang['user.administrator']  = 'Administrator';
$lang['user.administrators'] = 'Administrators';
$lang['user.supervisor']     = 'Supervisor';
$lang['user.sex']            = 'Sex';
$lang['user.male']           = 'Male';
$lang['user.female']         = 'Female';

$lang['user.members.management'] = 'Members management';
$lang['user.members.config']     = 'Members configuration';
$lang['user.members.punishment'] = 'Members punishment';
$lang['user.add.member']         = 'Add a member';
$lang['user.members.all']        = 'All members';
$lang['user.members_list']       = 'Members list';
$lang['user.member.management']  = 'Member management';
$lang['user.search.member']      = 'Search member';

$lang['user.profile.edit.password.error']       = 'The new password is not correct';
$lang['user.external.auth.account.exists']      = 'You already have an account. To use this login method, please login to the site and go to editing of your profile';
$lang['user.external.auth.email.not.found']     = 'The email address of your account could not be retrieved, your account can not be associated.';
$lang['user.external.auth.user.data.not.found'] = 'The informations of your account could not be retrieved, your account can not be created.';

// User fields
$lang['user.display.name']         = 'Displayed name';
$lang['user.display.name.clue']    = 'Displayed name on each item you add.';
$lang['user.username.clue']        = 'Email address or your customized username if you chose one.';
$lang['user.username.custom']      = 'Choose a username';
$lang['user.username.custom.clue'] = '<span class="error">Default, you must log in with your email address.</span>';
$lang['user.password.custom']      = 'Define a password';
$lang['user.password.custom.clue'] = 'By default, a password is automatically generated';
$lang['user.password.new']         = 'New password';
$lang['user.password.old']         = 'Old password';
$lang['user.password.old.clue']    = 'Complete only if amended';
$lang['user.password.confirm']     = 'Confirm the password';
$lang['user.password.clue']        = 'Minimum length of password: :number characters';
$lang['user.email']                = 'Email';
$lang['user.email.hide']           = 'Hide email';
$lang['user.theme']                = 'Theme';
$lang['user.theme.preview']        = 'Theme preview';
$lang['user.text.editor']          = 'Text editor';
$lang['user.lang']                 = 'Lang';
$lang['user.timezone']             = 'Timezone';
$lang['user.timezone.choice']      = 'Timezone choice';
$lang['user.timezone.choice.clue'] = 'Adjusts the time to your location';
$lang['user.level']                = 'Level';
$lang['user.approbation']          = 'Approbation';
$lang['user.unlimited']            = 'Unlimited';

$lang['user.avatar']              = 'Avatar';
$lang['user.registration_date']   = 'Registration date';
$lang['user.last_connection']     = 'Last connection';
$lang['user.my.publications']     = 'My publications';
$lang['user.publications']        = 'Publications';
$lang['user.view.publications']   = 'View user\'s publications';
$lang['user.private_message']     = 'Private message';
$lang['user.delete.account']      = 'Delete account';
$lang['user.delete.account.confirmation.member'] = 'Are you sure you want to delete your account?';
$lang['user.delete.account.confirmation.admin']  = 'Are you sure you want to delete the account?';

//Groups
$lang['user.groups']         = 'Groups';
$lang['user.groups.list']    = 'Groups list';
$lang['user.groups.select']  = 'Group select';
$lang['user.groups.all']     = 'All groups';
$lang['user.group.of_group'] = 'of group :';
$lang['user.admins.list']    = 'Administrators list';
$lang['user.modos.list']     = 'Moderators list';
$lang['user.no_member']      = 'No member in this group';
$lang['user.group.view_list_members'] = 'View members of group';
$lang['user.group.hide_list_members'] = 'Hide members of group';

//Other
$lang['user.caution']  = 'Caution';
$lang['user.readonly'] = 'Read only';
$lang['user.banned']   = 'Banned';

$lang['user.internal_connection']        = 'Internal connection';
$lang['user.create_internal_connection'] = 'Create internal connection';
$lang['user.edit_internal_connection']   = 'Edit your internal connection';
$lang['user.associate_account']          = 'Associate your account';
$lang['user.associate_account_admin']    = 'Associate an account';
$lang['user.dissociate_account']         = 'Dissociate your account';
$lang['user.dissociate_account_admin']   = 'Dissociate the account';

$lang['user.share']      = 'Share';
$lang['user.share_on']   = 'Share on';
$lang['user.share_by']   = 'Share by';
$lang['user.share.menu'] = 'Social network menu';
$lang['user.share.sms']  = 'SMS';

// Registration
$lang['user.register']     = 'Register';
$lang['user.registration'] = 'Registration';

$lang['user.registration.validation.email.clue']         = 'You will need to activate your account in the email sent to you before you can log in';
$lang['user.registration.validation.administrator.clue'] = 'An administrator will need to activate your account before you can log in';

$lang['user.registration.confirm.success'] = 'Your account has successfully been validated';
$lang['user.registration.confirm.error']   = 'A problem occurred during your activation, make sure your key is valid';

$lang['user.registration.success.administrator.validation'] = 'You have registered successfully. However, an administrator must validate your account before you can use it';
$lang['user.registration.success.email.validation']         = 'You have registered successfully. However you must click the activation link in the email you were sent';

$lang['user.registration.email.automatic.validation']     = 'You can now connect to your account directly on the site.';
$lang['user.registration.email.validation.link']          = 'You have to click on this link to activate your account: :validation_link';
$lang['user.registration.email.administrator.validation'] = 'Warning: Your account must be activated by an administrator. Thanks for your patience.';
$lang['user.registration.email.administrator.validation.content'] = 'Dear :pseudo,

We are pleased to inform you that your account :site_name has been validated by an administrator.

You can now login to the site using the credentials provided in the previous email.

:signature';

$lang['user.registration.pending.approval']   = 'A new member has registered. His account must be approved before it can be used.';
$lang['user.registration.not.approved']       = 'Your account must be approved by an administrator before it can be used.';
$lang['user.registration.email.subject']      = 'Confirmation of registration :site_name';
$lang['user.registration.lost.password.link'] = 'If you loose your password, you can generate a new one from this link :lost_password_link';
$lang['user.registration.password']           = 'Password : :password';
$lang['user.registration.content.email'] = 'Dear(e) :pseudo,

First of all, thank you for being registered on :site_name. You are now part of the site members.
By registering on :site_name, you can access to the member\'s area witch offers you several advantages. You can, among other things, be automatically recognized throughout the site to post messages, change the language and / or the default theme, edit your profile, access to classes for members only ... In short you access the entire site community.

To connect, you must retain your username and password.

Here are your login credentials.

Login : :login
:lost_password_link

:accounts_validation_explain

See you on :host

:signature';
$lang['user.registration.content.email.admin'] = 'Dear :pseudo,

You have been registered on :site_name by an administrator. Now you are part of the site members.
You get access to the member area that offers several advantages. You can, among other things, be automatically recognized throughout the site to post messages, change the language and / or the default theme, edit your profile, access to classes for members only ... In short you access to all the community site.

To connect, you must retain your username and password.

Here are your identifiers.

Login : :login
:lost_password_link

:accounts_validation_explain

See you on :host

:signature';

$lang['user.agreement']                = 'Agreement';
$lang['user.agreement.agree']          = 'I agree this agreement';
$lang['user.agreement.agree.required'] = 'You must agree for registration';

// Messages
$lang['user.message.success.add']           = 'The user <b>:name</b> has been added';
$lang['user.message.success.edit']          = 'The profile has been modified';
$lang['user.message.success.delete']        = 'The user <b>:name</b> has been deleted';
$lang['user.message.success.delete.member'] = 'Your account has been deleted';

// Extended Fields
$lang['user.extended.field.sex']      = 'Sex';
$lang['user.extended.field.sex.clue'] = '';

$lang['user.extended.field.pmtomail']      = 'Receive email notification when receiving a private message';
$lang['user.extended.field.pmtomail.clue'] = '';

$lang['user.extended.field.date.birth']      = 'Birth date';
$lang['user.extended.field.date.birth.clue'] = '';

$lang['user.extended.field.avatar']                    = 'Avatar';
$lang['user.extended.field.avatar.clue']               = '';
$lang['user.extended.field.avatar.current_avatar']     = 'Current avatar';
$lang['user.extended.field.avatar.upload_avatar']      = 'Upload an avatar';
$lang['user.extended.field.avatar.upload_avatar.clue'] = 'Avatar hosted on the server';
$lang['user.extended.field.avatar.link']               = 'Avatar link';
$lang['user.extended.field.avatar.link.clue']          = 'Url of the avatar';
$lang['user.extended.field.avatar.delete']             = 'Delete current avatar';
$lang['user.extended.field.avatar.no_avatar']          = 'No avatar';

$lang['user.extended.field.location']      = 'Location';
$lang['user.extended.field.location.clue'] = '';

$lang['user.extended.field.job']      = 'Job';
$lang['user.extended.field.job.clue'] = '';

$lang['user.extended.field.entertainement']      = 'Hobbies';
$lang['user.extended.field.entertainement.clue'] = '';

$lang['user.extended.field.biography']      = 'Biography';
$lang['user.extended.field.biography.clue'] = 'A few lines describing you';
$lang['user.extended.field.no.biography']   = 'This member didn\'t fill his biography';
$lang['user.extended.field.no.member']      = 'This member is no longer registred';

$lang['user.extended.field.website']      = 'Website';
$lang['user.extended.field.website.clue'] = 'Please enter a valid url (ex : https://www.phpboost.com)';

// Moderation panel
$lang['user.contact.pm']          = 'Contact by private message';
$lang['user.alternative.pm']      = 'Private message sent to the member';
$lang['user.alternative.pm.clue'] = 'Leave empty for no private message. The member won\'t be able to reply to this message, he won\'t know who sent it';

// Punishment management
$lang['user.punishments']           = 'Punishment';
$lang['user.punishment.management'] = 'Punishment management';
$lang['user.punish.until']          = 'Punishment until';
$lang['user.no.punish']             = 'No punished user';
$lang['user.life']                  = 'Life';
$lang['user.readonly']              = 'Member on read only';
$lang['user.readonly.clue']         = 'He can read but can\'t post on the whole website (comments, etc...)';
$lang['user.read.only.title']       = 'Punishement';
$lang['user.readonly.changed']      = 'You have been set on read only status by a member of the moderator team, you can\'t post during %date%.


This is a semi-automatic message.';

// Warning management
$lang['user.warning']            = 'Warning';
$lang['user.warnings']           = 'Warnings';
$lang['user.warning.management'] = 'Warning management';
$lang['user.warning.level']      = 'Warning level';
$lang['user.no.user.warning']    = 'No warned users';
$lang['user.warning.clue']       = 'Member warning level. You can update it, but at 100% the member is banned';
$lang['user.warning.user']       = 'Warn user';
$lang['user.warning.level.changed'] = 'You have been warned by a member of the moderation team, your warning level is now %level%%. Be careful with your behavior, if you reach 100% you will be permanently banned.


This is a semi-automatic message.';

// Ban management.
$lang['user.ban']             = 'Ban';
$lang['user.bans']            = 'Bans';
$lang['user.ban.management']  = 'Ban management';
$lang['user.ban.until']       = 'Banned until';
$lang['user.no.ban']          = 'No banned user';
$lang['user.ban.delay']       = 'Ban delay';
$lang['user.ban.title.email'] = 'Banned';
$lang['user.ban.email'] = 'Dear member,

You have been banned from : %s !
It may be an error, if you think it is, you can contact the administrator of the web site.


%s';

// Private messaging
$lang['user.private.message']       = 'Private message';
$lang['user.private.messages']      = 'Private messages';
$lang['user.pm']                    = 'PM';
$lang['user.pm.box']                = 'Private message box';
$lang['user.recipient']             = 'Recipient';
$lang['user.post.new.conversation'] = 'Create a new conversation';
$lang['user.new.pm']                = 'New private message';
$lang['user.pm.conversation.link']  = 'Read the full discussion';
$lang['user.pm.status']             = 'Message status';
$lang['user.pm.track']              = 'Unread by recipient';
$lang['user.not.read']              = 'Not read';
$lang['user.read']                  = 'Read';
$lang['user.last.message']          = 'Last message';
$lang['user.mark.pm.as.read']       = 'Mark all privates messages as read';
$lang['user.participants']          = 'Participant(s)';
$lang['user.quote.last.message']    = 'Repost of the preceding message';
$lang['user.select.all.messages']   = 'Select all messages';

//Cookies Bar
$lang['user.cookiebar.cookie']                    = 'Cookie';
$lang['user.cookiebar.cookie.management']         = 'Cookies management';
$lang['user.cookiebar.message.notracking']        = 'If you continue your visit to this website, you agree to use Cookies to manage your connection, your preferences, and to save anonymous visits statistics.';
$lang['user.cookiebar.message.tracking']          = 'If you continue your visit to this website, you agree to use cookies or other tracers to offer a suitable navigation (targeted advertising, social media sharing , more ...).';
$lang['user.cookiebar.message.aboutcookie.title'] = 'About Cookies';
$lang['user.cookiebar.message.aboutcookie']  = 'To make this site work properly, we sometimes need to save small data files called cookies on your device.<br />Most of websites do this too.

<h2 class="formatter-title">What are cookies ?</h2>
A cookie is a small text file that a website saves on your computer or mobile device when you visit the site.<br />
It enables the website to remember your actions and preferences (such as login, language, font size and other display preferences) over a period of time, so you don\'t have to keep re-entering them whenever you come back to the site or browse from one page to another.

<h2 class="formatter-title">Technical Cookies : </h2>
' . GeneralConfig::load()->get_site_name() . ' use technical cookie to :<br />
<ul class="formatter-ul">
    <li class="formatter-li"> manage login (essential if you want to connect),
    </li><li class="formatter-li"> record anonymous statistics for website (note essenial, but allows webmasters to know how visit website.)
    </li>
</ul>

<h2 class="formatter-title">Others cookies :</h2>
' . GeneralConfig::load()->get_site_name() . ' does not use any tracking cookies. However, using google-analytics module or social-media buttons need some tracking cookies.

<h2 class="formatter-title">How to control cookies ?</h2>
You can control and/or delete cookies as you wish – for details, see <a href="https://www.aboutcookies.org">aboutcookies.org</a>. <br />
You can delete all cookies that are already on your computer and you can set most browsers to prevent them from being placed. If you do this, however, you may have to manually adjust some preferences every time you visit a site and some services and functionalities may not work.
';
$lang['user.cookiebar.understand']    = 'Ok';
$lang['user.cookiebar.allowed']       = 'Allowed';
$lang['user.cookiebar.declined']      = 'Declined';
$lang['user.cookiebar.more.title']    = 'Explanations on the management of cookies and the "cookie-bar" (Learn more)';
$lang['user.cookiebar.more']          = 'Learn more';
$lang['user.cookiebar.cookies']       = 'Cookies';
$lang['user.cookiebar.change.choice'] = 'Change your preferences';

//Menu
$lang['user.menu.link.to'] = 'Link to page ';
?>
