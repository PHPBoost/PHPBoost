<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 01 22
 * @since       PHPBoost 1.5 - 2006 06 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     English                      #
####################################################

$lang['warning.success'] = 'Succès';
$lang['warning.error']   = 'Error';

$lang['warning.fatal']    = 'Fatal';
$lang['warning.notice']   = 'Notice';
$lang['warning.warning']  = 'Warning';
$lang['warning.question'] = 'Question';
$lang['warning.unknown']  = 'Unknown';

$lang['warning.username']     = 'Please enter a username !';
$lang['warning.password']     = 'Please enter a password !';
$lang['warning.title']        = 'Please enter a title !';
$lang['warning.text']         = 'Please enter a text !';
$lang['warning.email']        = 'Please enter an valid email address !';
$lang['warning.subcat']       = 'Please select a sub-category !';
$lang['warning.url']          = 'Please enter a valid URL !';
$lang['warning.recipient']    = 'Please enter the recipient !';
$lang['warning.integer']      = 'Please enter an integer number !';
$lang['warning.items.number'] = 'Please enter an item number !';
$lang['warning.irreversible'] = 'This action is irreversible !';

// BBcode
$lang['warning.bbcode.member']       = 'This frame hides content for members only';
$lang['warning.bbcode.moderator']    = 'This frame hides content for moderators only';
$lang['warning.bbcode.teaser']       = 'Only members can read the whole following content.';
$lang['warning.bbcode.is.member']    = 'The following content is only displayed to members';
$lang['warning.bbcode.is.moderator'] = 'The following content is only displayed to moderators';
$lang['warning.bbcode.is.teaser']    = 'The following content, the beginning of which is displayed to visitors, is for members only.';

// Captcha
$lang['warning.captcha.validation.error'] = 'The visual confirmation field has not been properly filled!';
$lang['warning.captcha.is.default']       = 'The captcha you want to uninstall or disable is set on your site, you must select another captcha in the content management first.';
$lang['warning.captcha.last.installed']   = 'Last captcha, you can not delete or disable it. Please install another one first.';

// Content
$lang['warning.locked.content.description']  = 'The item is currently open by :user_display_name, you can not access it, try again later.';
$lang['warning.locked.content.another.user'] = 'another user';

// Contributions
$lang['warning.delete.contribution'] = 'Do you really want to delete this contribution?';

// Editor
$lang['warning.code.too.long.error']   = 'The code you want to highlight is too large and would use too much resources to be highlighted. Please reduce its size or split it.';
$lang['warning.feed.tag.error']        = 'The feed of the module <em>:module</em> that you want to display couldn\'t be found or the options you entered aren\'t correct.';
$lang['warning.is.default.editor']     = 'The editor you want to uninstall or disable, is set by default, you must select another default editor first';
$lang['warning.last.editor.installed'] = 'Last text editor, you can not delete or disable it. Please install another one first.';

// Element
$lang['warning.element.already.exists'] = 'The item already exists.';
$lang['warning.element.unexists']       = 'The item you requested does not exist.';
$lang['warning.element.not.visible']    = 'This element is not yet or no more approved, it is not displayed for the other users.';

// Errors
$lang['warning.incomplete']              = 'All the required fields must be filled!';
$lang['warning.readonly']                = 'You can\'t perform this action because your account is read-only!';
$lang['warning.flood']                   = 'You can\'t post yet, retry in a few moments';
$lang['warning.link.flood']              = 'You can\'t post more than %d links in your message';
$lang['warning.auth']                    = 'You don\'t have the required level!';
$lang['warning.auth.guest']              = 'Protected content. Please suscribe or connect to access this page.';
$lang['warning.registration.disabled']   = 'Registration is disabled on the site.';
$lang['warning.page.forbidden']          = 'This folder access is forbidden!';
$lang['warning.page.unexists']           = 'This page doesn\'t exist!';
$lang['warning.unauthorized.action']     = 'Unauthorized action!';
$lang['warning.module.uninstalled']      = 'This module isn\'t installed!';
$lang['warning.module.disabled']         = 'This module isn\'t activated!';
$lang['warning.invalid.archive.content'] = 'The content of the archive is invalid!';
$lang['warning.404.message']             = 'It seems that a tornado has passed through here. <br />Unfortunately nothing more to see.';
$lang['warning.403.message']             = 'It seems that a tornado has passed through here. <br />Access is forbidden to the public.';
$lang['warning.csrf.invalid.token']      = 'Invalid session token. Please try to reload the page because the operation has not been performed.';

// Forbidden
$lang['warning.file.forbidden.chars'] = 'File name can\'t contain the following characters: \\\ / . | ? < > \"';

// Groups
$lang['warning.already.group'] = 'Member is already in this group';

// Members
$lang['warning.display.name.auth']  = 'The entered display name is already used!';
$lang['warning.pseudo.auth']        = 'The entered username is already used!';
$lang['warning.email.auth']         = 'The entered email address is already used!';
$lang['warning.member.ban']         = 'You have been banned! You can retry to connect in';
$lang['warning.member.ban.contact'] = 'You have been banned for your behaviour! Contact the administator if you think it\'s an warning.';
$lang['warning.unactive.member']    = 'Your account has not been activated yet!';

// Private messages
$lang['warning.delete.message'] = 'Delete message.s ?';
$lang['warning.pm.full']        = 'Your private message box is full, You have <strong>%d</strong> waiting conversation(s), delete old posts to read it/them';
$lang['warning.pm.full.post']   = 'Your private message box is full, delete old messages to create new ones';
$lang['warning.unexist.user']   = 'The selected user doesn\'t exist!';
$lang['warning.pm.del']         = 'The recipient has deleted the conversation, you can\'t post anymore';
$lang['warning.pm.no.edit']     = 'The recipient has already read your message, you can\'t edit it anymore';
$lang['warning.pm.no.del']      = 'The recipient has already read your message, you can\'t delete it anymore';

// Process
$lang['warning.process.success'] = 'The operation is a success';
$lang['warning.process.error']   = 'An error occurred during the operation';

$lang['warning.confirm.delete']          = 'Do you really want to delete this item?';
$lang['warning.confirm.delete.elements'] = 'Do you really want to delete these items?';

$lang['warning.success.config']          = 'The configuration has been modified';
$lang['warning.success.position.update'] = 'The position of the items has been updated';

$lang['warning.download.file.error'] = 'File :filename download failed';

$lang['warning.delete.install.and.update.folders'] = 'For security reasons we recommand you to delete the <b>install</b> and <b>update</b> folders and all their contents, hackers could manage to run the installation script and you could lose data !';
$lang['warning.delete.install.or.update.folders']  = 'For security reasons we recommand you to delete the <b>:folder</b> folder and all its contents, hackers could manage to run the installation script and you could lose data !';

// Regex
$lang['warning.regex']                 = 'The entered value does not fit the proper format';
$lang['warning.regex.date']            = 'The entered value has to be a valid date';
$lang['warning.regex.url']             = 'The entered value has to be a valid url';
$lang['warning.regex.email']           = 'The entered value has to be a valid email';
$lang['warning.regex.tel']             = 'The entered value has to be a valid phone number';
$lang['warning.regex.letters.numbers'] = 'The value entered must be a serie of letters and numbers';
$lang['warning.regex.number']          = 'The value entered must be a number';
$lang['warning.regex.picture.file']    = 'The value entered must correspond to a picture';
$lang['warning.length.intervall']      = 'The entered value does not fit the specified length (:lower_bound <= value <= :upper_bound)';
$lang['warning.length.min']            = 'The entered value must be at least :lower_bound characters';
$lang['warning.length.max']            = 'The entered value must be max :upper_bound characters';
$lang['warning.integer.intervall']     = 'The entered value does not fit the specified interval (:lower_bound <= value <= :upper_bound)';
$lang['warning.integer.min']           = 'The entered value must be superior or equal to :lower_bound';
$lang['warning.integer.max']           = 'The entered value must be inferior or equal to :upper_bound';
$lang['warning.regex.authorized.extensions'] = 'File extension is not authorized. Valid extensions : :extensions.';

$lang['warning.medium.password.regex']          = 'The password must contain at least one lower case letter and one upper case letter or one lower case letter and a digit';
$lang['warning.strong.password.regex']          = 'The password must contain at least one lower case letter, one upper case letter and a digit';
$lang['warning.very.strong.password.regex']     = 'The password must contain at least one lower case letter, one upper case letter, one digit and a special character';
$lang['warning.email.authorized.domains.regex'] = 'This mail domain address is not authorized, please choose another mail address';

$lang['warning.invalid.url']            = 'The url is not valid';
$lang['warning.invalid.picture']        = 'The file is not a picture';
$lang['warning.unexisting.file']        = 'The file has not been found, its url must be incorrect';
$lang['warning.has.to.be.filled']       = 'The field ":name" has to be filled';
$lang['warning.must.contain.min.input'] = 'The field ":name" must contain at least :min_input values';
$lang['warning.must.contain.max.input'] = 'The field ":name" must not contain more :max_input values';
$lang['warning.unique.input.value']     = 'The field ":name" must not contain same values';
$lang['warning.validation.error']       = 'Please correct the form errors';

$lang['warning.fields.must.be.equal']                                  = 'Fields ":field1" and ":field2" must be equal';
$lang['warning.fields.must.not.be.equal']                              = 'Fields ":field1" and ":field2" must have different values';
$lang['warning.first.field.must.be.inferior.to.second.field']          = 'Field ":field2" must have a value inferior to field ":field1"';
$lang['warning.first.field.must.be.superior.to.second.field']          = 'Field ":field2" must have a value superior to field ":field1"';
$lang['warning.first.field.must.not.be.contained.in.second.field']     = 'Value of field ":field1" must not be present in field ":field2"';
$lang['warning.login.and.email.must.not.be.contained.in.second.field'] = 'Your mail or your login must not be present in field ":field2"';

// Upload
$lang['warning.file.max.size.exceeded'] = 'The maximum file size must not exceed :max_file_size.';
$lang['warning.file.max.dimension']     = 'Max file dimensions exceeded';
$lang['warning.file.max.weight']        = 'Maximum file size exceeded';
$lang['warning.file.invalid.format']    = 'Invalid file format';
$lang['warning.file.php.code']          = 'Invalid file content, php code is forbidden';
$lang['warning.file.upload.error']      = 'Error while uploading file';
$lang['warning.unlink.disabled']        = 'File deleting function not supported by your server';
$lang['warning.folder.unwritable']      = 'Impossible to upload because writing in this directory is not allowed';
$lang['warning.file.already.exists']    = 'File already exists, overwrite is not allowed';
$lang['warning.folder.already.exists']  = 'The folder already exists.';
$lang['warning.no.selected.file']       = 'No file has been selected';
$lang['warning.max.data.reach']         = 'Max size reached, delete old files';
$lang['warning.del.file']               = 'Delete this file?';
$lang['warning.empty.folder']           = 'Empty folder?';
$lang['warning.empty.folder.content']   = 'Empty all folders contents?';
$lang['warning.del.folder']             = 'Delete folder?';
$lang['warning.del.folder.content']     = 'Delete this folder, and all his contents?';
$lang['warning.file.forbidden.chars']   = 'File name can\'t contain the following characters : \\\ / . | ? < > \"';
$lang['warning.folder.forbidden.chars'] = 'Folder name can\'t contain the following characters: \\\ / . | ? < > \"';
$lang['warning.files.del.failed']       = 'Delete files procedure has failed, please manually remove the files';
$lang['warning.success.upload']         = 'Your file has been uploaded successfully !';
$lang['warning.folder.contains.folder'] = 'You try to put this directory in one of its sub-directory or in itself, that\'s impossible !';

// User
$lang['warning.user.not.authorized.during.maintenance'] = 'You are not authorized to connect during the maintenance';
$lang['warning.user.not.exists'] = 'User not exists !';
$lang['warning.user.auth.password.flood'] = ':remaining_tries tries are remaining. After that, you\'ll have to wait 5 minutes to have 2 more tries (10min for 5)!';
$lang['warning.user.auth.password.flood.max'] = 'You have failed, too many authentication attempts, your account is locked for 5 minutes.';

// Version
$lang['warning.misfit.php'] = 'Inadequate PHP version';
$lang['warning.misfit.phpboost'] = 'Inadequate PHPBoost version';
?>
