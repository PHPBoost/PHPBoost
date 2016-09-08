<?php
/*##################################################
 *                            common.php
 *                            -------------------
 *   begin                : December 17, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 #						English						#
 ####################################################

//Title
$lang['module_title'] = 'Sandbox';

// --- Welcome

$lang['title.form_builder'] = 'Form builder';
$lang['title.table_builder'] = 'Table builder';
$lang['title.icons'] = 'Icons';
$lang['title.css'] = 'CSS';
$lang['title.cssmenu'] = 'Cssmenu';
$lang['title.mail_sender'] = 'Mail sender';
$lang['title.string_template'] = 'Template benchmark';

$lang['welcome_message'] = 'Welcome in the Sandbox module.<br /><br />
Here you can test several parts of the PHPBoost framework:<br />
<ul>
<li>Rendering of the different fields of the <a href="' . SandboxUrlBuilder::form()->absolute() . '">form builder</a></li>
<li>The <a href="' . SandboxUrlBuilder::table()->absolute() . '">dynamic table generation</a></li>
<li>The <a href="' . SandboxUrlBuilder::icons()->absolute() . '">icons list</a> of the Font Awesome library used in the  different modules</li>
<li>Rendering of the main <a href="' . SandboxUrlBuilder::css()->absolute() . '">CSS clsss</a></li>
<li>Rendering of cssmenu <a href="' . SandboxUrlBuilder::cssmenu()->absolute() . '">cssmenu menus</a></li>
<li><a href="' . SandboxUrlBuilder::mail()->absolute() . '">Mail sending</a></li>
<li>The <a href="' . SandboxUrlBuilder::template()->absolute() . '">template benchmark</a> with or without cache</li>
</ul>
<br />
';

// --- Form

$lang['form.title'] = 'Form';
$lang['form.desc'] = 'This is a description';
$lang['form.input.text'] = 'Text field';
$lang['form.input.text.desc'] = 'Constraints: letters, numbers and underscore';
$lang['form.input.text.lorem'] = 'Lorem ipsum';
$lang['form.input.text.disabled'] = 'Disabled field';
$lang['form.input.text.disabled.desc'] = 'Disabled';
$lang['form.input.url'] = 'Web site';
$lang['form.input.url.desc'] = 'Valid url';
$lang['form.input.url.placeholder'] = 'https://www.phpboost.com';
$lang['form.input.email'] = 'Email';
$lang['form.input.email.desc'] = 'Valide email';
$lang['form.input.email.placeholder'] = 'lorem@phpboost.com';
$lang['form.input.email.multiple'] = 'Multiple email';
$lang['form.input.email.multiple.desc'] = 'Valide emails, separated by comma';
$lang['form.input.email.multiple.placeholder'] = 'lorem@phpboost.com,ipsum@phpboost.com';
$lang['form.input.phone'] = 'Phone number';
$lang['form.input.phone.desc'] = 'Valid phone number';
$lang['form.input.phone.placeholder'] = '0123456789';
$lang['form.input.text.required'] = 'Required field';
$lang['form.input.text.required.filled'] = 'Required field filled';
$lang['form.input.text.required.empty'] = 'Required field empty';
$lang['form.input.number'] = 'Number';
$lang['form.input.number.desc'] = 'interval: from 10 to 100';
$lang['form.input.number.placeholder'] = '20';
$lang['form.input.number.decimal'] = 'Decimal number';
$lang['form.input.number.decimal.desc'] = 'Use comma';
$lang['form.input.number.decimal.placeholder'] = '5.5';
$lang['form.input.length'] = 'Slider';
$lang['form.input.length.desc'] = 'Clic and drag';
$lang['form.input.length.placeholder'] = '4';
$lang['form.input.password'] = 'Password';
$lang['form.input.password.desc'] = ' characters minimum';
$lang['form.input.password.placeholder'] = 'aaaaaa';
$lang['form.input.password.confirm'] = 'Confirm password';
$lang['form.input.multiline.medium'] = 'Medium multiline text field';
$lang['form.input.multiline'] = 'Multiline text field';
$lang['form.input.multiline.desc'] = 'Description';
$lang['form.input.multiline.lorem'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ut tempor lacus.';
$lang['form.input.rich.text'] = 'Text field with editor';
$lang['form.input.rich.text.placeholder'] = 'Create a website <strong>easily</strong>';
$lang['form.input.checkbox'] = 'Checkbox';
$lang['form.input.multiple.checkbox'] = 'Multiple checkbox';
$lang['form.input.radio'] = 'Radio buttons';
$lang['form.input.select'] = 'Select';
$lang['form.input.multiple.select'] = 'Select with serveral choices';
$lang['form.input.choice.1'] = 'Choice 1';
$lang['form.input.choice.2'] = 'Choice 2';
$lang['form.input.choice.3'] = 'Choice 3';
$lang['form.input.choice.4'] = 'Choice 4';
$lang['form.input.choice.5'] = 'Choice 5';
$lang['form.input.choice.6'] = 'Choice 6';
$lang['form.input.choice.7'] = 'Choice 7';
$lang['form.input.choice.group.1'] = 'Group 1';
$lang['form.input.choice.group.2'] = 'Group 2';
$lang['form.input.timezone'] = 'TimeZone';
$lang['form.input.user.completion'] = 'User completion';
$lang['form.send.button'] = 'Send';

$lang['form.title.2'] = 'Form 2';
$lang['form.input.hidden'] = 'hidden field';
$lang['form.free.html'] = 'Free field';
$lang['form.date'] = 'Date';
$lang['form.date.hm'] = 'Date/hour/minutes';
$lang['form.color'] = 'color';
$lang['form.search'] = 'Search';
$lang['form.file.picker'] = 'File picker';
$lang['form.multiple.file.picker'] = 'Multiple file picker';
$lang['form.file.upload'] = 'Link to file';

$lang['form.authorization'] = 'Authorization';
$lang['form.authorization.1'] = 'Action 1';
$lang['form.authorization.1.desc'] = 'Authorizations for l\'action 1';
$lang['form.authorization.2'] = 'Action 2';

$lang['form.vertical.desc'] = 'Vertical form';
$lang['form.horizontal.desc'] = 'Horizontal form';

$lang['form.preview'] = 'Preview';
$lang['form.button'] = 'Button';

// --- Cssmenu

$lang['css.menu.site.title'] = 'Cssmenu menus';
$lang['css.menu.site.slogan'] = 'Sandbox - Design of cssmenu';
$lang['css.menu.breadcrumb.index'] = 'Home';
$lang['css.menu.breadcrumb.sandbox'] = 'sandbox';
$lang['css.menu.breadcrumb.cssmenu'] = 'cssmenu';
$lang['css.menu.h2'] = 'All the cssmenu menus';
$lang['css.menu.element'] = 'Menu element';
$lang['css.menu.sub.element'] = 'Submenu';
$lang['css.menu.horizontal.sub.header'] = 'Menu in sub-header';
$lang['css.menu.sub.admin'] = 'Administration';
$lang['css.menu.horizontal.top'] = ' Header horizontal menu';
$lang['css.menu.horizontal.scrolling'] = 'Horizontal scrolling menu';
$lang['css.menu.vertical.scrolling'] = 'Vertical scrolling menu';
$lang['css.menu.vertical.img'] = 'Menu with icons';
$lang['css.menu.vertical.scrolling.left'] = 'Vert scroll menu on left';
$lang['css.menu.vertical.scrolling.right'] = 'Vert scroll menu on right';
$lang['css.menu.actionslinks.sandbox'] = 'Sandbox';
$lang['css.menu.actionslinks.index'] = 'Homepage';
$lang['css.menu.actionslinks.form'] = 'Forms';
$lang['css.menu.actionslinks.css'] = 'CSS';
$lang['css.menu.actionslinks.menu'] = 'Cssmenu';
$lang['css.menu.actionslinks.icons'] = 'Icons';
$lang['css.menu.actionslinks.table'] = 'Table';
$lang['css.menu.actionslinks.template'] = 'Templates';
$lang['css.menu.actionslinks.mail'] = 'Mail sending';
$lang['css.menu.actionslinks'] = 'Options menu of modules';
$lang['css.menu.group'] = 'Group menu';
$lang['css.menu.static'] = 'Static menu';
$lang['css.menu.static.footer'] = 'Footer static menu';

// --- lorem ipsum for Cssmenu

$lang['css.menu.content'] = 'This page has a specific design to display all the menu types in all fields can be filled';
$lang['lorem.ipsum'] = ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. In quis condimentum eros. Vestibulum fermentum eleifend consectetur.
Nulla efficitur molestie vulputate. Sed finibus dolor in est faucibus egestas. Nullam odio elit, rutrum ut tempor in, elementum ut nisi.
Nunc placerat convallis dolor, vitae semper justo placerat vel. Nulla porta quis nisl vitae commodo. Aliquam et tortor viverra, porttitor nulla nec,
pretium ligula. Sed eleifend consequat tincidunt.';

// --- Icons

$lang['css.icon.sample'] = 'Some samples';
$lang['css.icon.social'] = 'Social network';
$lang['css.icon.screen'] = 'Screens';
$lang['css.icon.icon'] = 'Icon';
$lang['css.icon.name'] = 'Name';
$lang['css.icon.code'] = 'Code';
$lang['css.icon.list'] = 'The complete list of icons and their own code : ';

$lang['css.icon.howto'] = 'How to ?';
$lang['css.icon.howto.explain'] = 'Font-Awesome is an icon-font, a font to simply display icons';
$lang['css.icon.howto.update'] = 'It\'s integrated since the 4.1 PHPBoost version. Each update of Font-Awesome is integrated in the next PHPBoost update.';
$lang['css.icon.howto.html'] = 'Html way';
$lang['css.icon.howto.html.class'] = 'Set the icon name as a class : ';
$lang['css.icon.howto.html.class.result.i'] = 'That gives the "edit" icon following by : ';
$lang['css.icon.howto.html.class.result.a'] = 'That gives the link with ths "globe" icon first : ';
$lang['css.icon.howto.html.class.result.all'] = 'You can play with all html tags.';
$lang['css.icon.howto.css'] = 'CSS way';
$lang['css.icon.howto.css.class'] = 'Set your class, then the icon code as content of ::before or ::after of the class :';
$lang['css.icon.howto.css.css.code'] = 'CSS :';
$lang['css.icon.howto.css.html.code'] = 'HTML :';
$lang['css.icon.howto.variants'] = 'Variants';
$lang['css.icon.howto.variants.explain'] = 'Font-Awesome is setted with some variants like sizing the icon, animation, rotation, stacking and somelse.';
$lang['css.icon.howto.variants.list'] = 'All variants are explained here : ';
$lang['css.icon.howto.variants.spinner'] = 'That gives the "spinner" icon, setted in pulse and twice bigger than its initial size : ';

// --- CSS

//Typography
$lang['css.typography'] = 'Typography';
$lang['css.titles'] = 'Titles';
$lang['css.title'] = 'Title';
$lang['css.specific_titles'] = 'Specific titles (BBCode)';

$lang['css.styles'] = 'Styles';
$lang['css.text_bold'] = 'Bold text';
$lang['css.text_italic'] = 'Italic text';
$lang['css.text_underline'] = 'Underline text';
$lang['css.text_strike'] = 'Strike text';

$lang['css.sizes'] = 'Tailles';
$lang['css.link'] = 'Link';
$lang['css.link_smaller'] = 'Smaller link';
$lang['css.link_small'] = 'Small link';
$lang['css.link_big'] = 'Big link';
$lang['css.link_bigger'] = 'Bigger link';
$lang['css.link_biggest'] = 'Biggest link';

$lang['css.rank_color'] = 'User rank color';
$lang['css.admin'] = 'Administrator';
$lang['css.modo'] = 'Moderator';
$lang['css.member'] = 'Member';

//Miscellaneous
$lang['css.miscellaneous'] = 'Miscellaneous';
$lang['css.main_actions_icons'] = 'Main actions icons';
$lang['css.rss_feed'] = 'RSS feed';
$lang['css.edit'] = 'Edit';
$lang['css.delete'] = 'Delete';
$lang['css.delete.confirm'] = 'Delete (automatic JS control with delete confirmation)';
$lang['css.delete.confirm.custom'] = 'Delete (automatic JS control with custom confirmation)';
$lang['css.delete.custom_message'] = 'Custom message';

$lang['css.lists'] = 'Lists';
$lang['css.element'] = 'Element';

$lang['css.progress_bar'] = 'Progress bar';
$lang['css.progress_bar.util_infos'] = 'Util informations';
$lang['css.progress_bar.votes'] = '3 votes';

$lang['css.explorer'] = 'Explorer';
$lang['css.root'] = 'Root';
$lang['css.tree'] = 'Tree';
$lang['css.cat'] = 'Category';
$lang['css.file'] = 'File';

$lang['css.options'] = 'Options';
$lang['css.options.sort_by'] = 'Sort by';
$lang['css.options.sort_by.alphabetical'] = 'Alphabetical';
$lang['css.options.sort_by.size'] = 'Size';
$lang['css.options.sort_by.date'] = 'Date';
$lang['css.options.sort_by.popularity'] = 'Popularity';
$lang['css.options.sort_by.note'] = 'Note';

$lang['css.modules_menus.direction.up'] = 'Up';
$lang['css.modules_menus.direction.down'] = 'Down';

$lang['css.button'] = 'Buttons';

$lang['css.sortable'] = 'Sortable Drag & Drop';
$lang['css.static.sortable'] = 'Sortable spoted';
$lang['css.moved.sortable'] = 'Sortable moving';
$lang['css.dropzone'] = 'Spot here';

//Blockquotes
$lang['css.quote'] = 'Quote';
$lang['css.code'] = 'Code';
$lang['css.code.php'] = 'PHP Code';
$lang['css.hidden'] = 'Hidden text';

//Pagination
$lang['css.pagination'] = 'Pagination';

//Tables
$lang['css.table'] = 'Table';
$lang['css.table_description'] = 'Table description';
$lang['css.table.name'] = 'Name';
$lang['css.table.description'] = 'Description';
$lang['css.table.author'] = 'Author';
$lang['css.table.test'] = 'Test';
$lang['css.specific.table'] = 'Specific table (bbcode)';
$lang['css.table.header'] = 'Header';

//Messages
$lang['css.messages_and_coms'] = 'Messages and comments';
$lang['css.messages.login'] = 'admin';
$lang['css.messages.level'] = 'Administrator';
$lang['css.messages.date'] = '09/05/2013 at 15h37';
$lang['css.messages.content'] = 'This is a comment';

$lang['css.message_success'] = 'This is a success message';
$lang['css.message_notice'] = 'This is a notice message';
$lang['css.message_warning'] = 'This is a warning message';
$lang['css.message_error'] = 'This is an error message';
$lang['css.message_question'] = 'This is a question, is the two-lines display working correctly?';
$lang['css.error_messages'] = 'Error messages';

//Pages
$lang['css.page'] = 'Page';
$lang['css.page.title'] = 'Page title';
$lang['css.page.subtitle'] = 'Subtitle';
$lang['css.page.subsubtitle'] = 'SubSubtitle';
$lang['css.blocks'] = 'Blocks';
$lang['css.block.title'] = 'Block title';
$lang['css.blocks.medium'] = 'Blocks (2 on one line)';
$lang['css.blocks.small'] = 'Blocks (3 on one line)';

$lang['mail.title'] = 'Mail';
$lang['mail.sender_mail'] = 'Sender mail';
$lang['mail.sender_name'] = 'Sender name';
$lang['mail.recipient_mail'] = 'Recipient mail';
$lang['mail.recipient_name'] = 'Recipient name';
$lang['mail.subject'] = 'Mail subject';
$lang['mail.content'] = 'Content';
$lang['mail.smtp_config'] = 'SMTP configuration';
$lang['mail.smtp_config.explain'] = 'If you want to use a direct SMTP connection to send the mail, check the box.';
$lang['mail.use_smtp'] = 'Use SMTP';
$lang['mail.smtp_configuration'] = 'SMTP parameters configuration';
$lang['mail.smtp.host'] = 'Host name';
$lang['mail.smtp.port'] = 'Port';
$lang['mail.smtp.login'] = 'Login';
$lang['mail.smtp.password'] = 'Password';
$lang['mail.smtp.secure_protocol'] = 'Secure protocol';
$lang['mail.smtp.secure_protocol.none'] = 'None';
$lang['mail.smtp.secure_protocol.tls'] = 'TLS';
$lang['mail.smtp.secure_protocol.ssl'] = 'SSL';
$lang['mail.success'] = 'The mail has been sent';

//Template
$lang['string_template.result'] = 'Template generation duration without cache : :non_cached_time seconds<br />Template generation duration with cache: :cached_time seconds<br />Lenght of the parsed string: :string_length chars.';

//Wiki
$lang['wiki.module'] = 'Wiki Module';
$lang['wiki.table.of.contents'] = 'Table of contents';
$lang['wiki.contents'] = 'Contents of Wiki';

?>
