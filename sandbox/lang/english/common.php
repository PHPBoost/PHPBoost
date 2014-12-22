<?php
/*##################################################
 *                            common.php
 *                            -------------------
 *   begin                : December 17, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

$lang['title.form_builder'] = 'Form builder';
$lang['title.table_builder'] = 'Table builder';
$lang['title.icons'] = 'Icons';
$lang['title.css'] = 'CSS';
$lang['title.mail_sender'] = 'Mail sender';
$lang['title.string_template'] = 'Template benchmark';

$lang['welcome_message'] = 'Welcome in the Sandbox module.<br /><br />
Here you can test several parts of the PHPBoost framework:<br />
<ul>
<li>Rendering of the different fields of the <a href="' . SandboxUrlBuilder::form()->absolute() . '">form builder</a></li>
<li>The <a href="' . SandboxUrlBuilder::table()->absolute() . '">dynaic table generation</a></li>
<li>The <a href="' . SandboxUrlBuilder::icons()->absolute() . '">icons list</a> of the Font Awesome library used in the  different modules</li>
<li>Rendering of the main <a href="' . SandboxUrlBuilder::css()->absolute() . '">CSS clsss</a></li>
<li><a href="' . SandboxUrlBuilder::mail()->absolute() . '">Mail sending</a></li>
<li>The <a href="' . SandboxUrlBuilder::template()->absolute() . '">template benchmark</a> with or without cache</li>
</ul>
<br />
';

$lang['css.message_success'] = 'This is a success message';
$lang['css.message_notice'] = 'This is a notice message';
$lang['css.message_warning'] = 'This is a warning message';
$lang['css.message_error'] = 'This is an error message';
$lang['css.message_question'] = 'This is a question, is the two-lines display working correctly?';
$lang['css.typography'] = 'Typography';
$lang['css.titles'] = 'Titles';
$lang['css.title'] = 'Title';
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
$lang['css.modules_menus'] = 'Modules menus';
$lang['css.modules_menus.display'] = 'Display';
$lang['css.modules_menus.display.most_viewed'] = 'Most viewed';
$lang['css.modules_menus.display.top_rated'] = 'Top rated';
$lang['css.modules_menus.order_by'] = 'Order by';
$lang['css.modules_menus.order_by.name'] = 'Name';
$lang['css.modules_menus.order_by.date'] = 'Date';
$lang['css.modules_menus.order_by.views'] = 'Views';
$lang['css.modules_menus.order_by.notes'] = 'Notes';
$lang['css.modules_menus.order_by.coms'] = 'Comments';
$lang['css.modules_menus.direction'] = 'Direction';
$lang['css.modules_menus.direction.up'] = 'Up';
$lang['css.modules_menus.direction.down'] = 'Down';
$lang['css.modules_menus.unsolved_bugs'] = 'Unsolved bugs';
$lang['css.modules_menus.solved_bugs'] = 'Solved bugs';
$lang['css.modules_menus.roadmap'] = 'Roadmap';
$lang['css.modules_menus.stats'] = 'Statistics';
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
$lang['css.quote'] = 'Quote';
$lang['css.code'] = 'Code';
$lang['css.code.php'] = 'PHP Code';
$lang['css.hidden'] = 'Hidden text';
$lang['css.pagination'] = 'Pagination';
$lang['css.table'] = 'Table';
$lang['css.table_description'] = 'Table description';
$lang['css.table.name'] = 'Name';
$lang['css.table.description'] = 'Description';
$lang['css.table.author'] = 'Author';
$lang['css.table.test'] = 'Test';
$lang['css.messages_and_coms'] = 'Messages and comments';
$lang['css.messages.login'] = 'admin';
$lang['css.messages.level'] = 'Administrator';
$lang['css.messages.date'] = '09/05/2013 at 15h37';
$lang['css.messages.content'] = 'This is a comment';
$lang['css.specific_titles'] = 'Specific titles (BBCode)';
$lang['css.error_messages'] = 'Error messages';
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

$lang['string_template.result'] = 'Template generation duration without cache : :non_cached_time seconds<br />Template generation duration with cache: :cached_time seconds<br />Lenght of the parsed string: :string_length chars.';
?>
