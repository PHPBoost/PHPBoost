<?php
/*##################################################
 *                               common.php
 *                            -------------------
 *   begin                : February 25, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/


 ####################################################
 #						English						#
 ####################################################

$lang['module_title'] = 'Forum';
$lang['module_config_title'] = 'Forum module configuration';

$lang['forum.actions.add_rank'] = 'Add a rank';
$lang['forum.manage_ranks'] = 'Manage ranks';
$lang['forum.ranks_management'] = 'Ranks management';

$lang['xml_forum_desc'] = 'Last forum thread';
$lang['go_top'] = 'Go top';
$lang['go_bottom'] = 'Go bottom';

$lang['forum.links'] = 'Links';
$lang['forum.message_options'] = 'Message options';

//config
$lang['config.forum_name'] = 'Forum name';
$lang['config.number_topics_per_page'] = 'Number of topics per page';
$lang['config.number_messages_per_page'] = 'Number of posts per page';
$lang['config.read_messages_storage_duration'] = 'Number of days unread posts are stored';
$lang['config.read_messages_storage_duration.explain'] = 'In days. Adjust according to the number of posts per day.';
$lang['config.max_topic_number_in_favorite'] = 'Topics subscriptions limit for each member';
$lang['config.edit_mark_enabled'] = 'Display last edited time information';
$lang['config.multiple_posts_allowed'] = 'Allow members to post multiple consecutive messages';
$lang['config.multiple_posts_allowed.explain'] = 'If the option is unchecked, the last message of the user will automatically be completed with the new posted content';
$lang['config.connexion_form_displayed'] = 'Display login form';
$lang['config.left_column_disabled'] = 'Hide left column while reading the forum';
$lang['config.right_column_disabled'] = 'Hide right column while reading the forum';
$lang['config.message_before_topic_title_displayed'] = 'Activate title prefix';
$lang['config.message_before_topic_title'] = 'Prefix title';
$lang['config.message_when_topic_is_unsolved'] = 'Message explanation to members if topic status is unsolved';
$lang['config.message_when_topic_is_solved'] = 'Message explanation to members if topic status is solved';
$lang['config.message_before_topic_title_icon_displayed'] = 'Display associated icon';
$lang['config.message_before_topic_title_icon_displayed.explain'] = '<i class="fa fa-msg-display"></i> / <i class="fa fa-msg-not-display"></i>';

//Categories
$lang['category.status.locked'] = 'Locked';

//Extended Field
$lang['extended-field.field.website'] = 'Website';
$lang['extended-field.field.website-explain'] = 'Please enter a valid url (ex : http://www.phpboost.com)';

$lang['extended-field.field.skype'] = 'Skype';
$lang['extended-field.field.skype-explain'] = '';

$lang['extended-field.field.signing'] = 'Signature';
$lang['extended-field.field.signing-explain'] = 'The signature appears beyond all your messages';

//authorizations
$lang['authorizations.read_topics_content'] = 'Display topics content authorization';
$lang['authorizations.flood'] = 'Flood authorization';
$lang['authorizations.hide_edition_mark'] = 'Hide last edited time information';
$lang['authorizations.unlimited_topics_tracking'] = 'Deactivate topics subscription limit';
?>
