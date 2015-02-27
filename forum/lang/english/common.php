<?php
/*##################################################
 *                               common.php
 *                            -------------------
 *   begin                : February 25, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
$lang['module_config_title'] = 'Configuration du module forum';

$lang['forum.actions.add_rank'] = 'Add a rank';
$lang['forum.manage_ranks'] = 'Manage ranks';
$lang['forum.ranks_management'] = 'Ranks management';

//config
$lang['config.forum_name'] = 'Forum name';
$lang['config.number_topics_per_page'] = 'Number of topics per page';
$lang['config.number_messages_per_page'] = 'Number of posts per page';
$lang['config.read_messages_storage_duration'] = 'Number of days unread posts are stored';
$lang['config.read_messages_storage_duration.explain'] = 'In days. Adjust according to the number of posts per day.';
$lang['config.max_topic_number_in_favorite'] = 'Topics subscriptions limit for each member';
$lang['config.edit_mark_enabled'] = 'Display last edited time information';
$lang['config.connexion_form_displayed'] = 'Display login form';
$lang['config.left_column_disabled'] = 'Hide left column while reading the forum';
$lang['config.right_column_disabled'] = 'Hide right column while reading the forum';
$lang['config.message_before_topic_title_displayed'] = 'Activate title prefix';
$lang['config.message_before_topic_title'] = 'Prefix title';
$lang['config.message_when_topic_is_unsolved'] = 'Message explanation to members if topic status is unsolved';
$lang['config.message_when_topic_is_solved'] = 'Message explanation to members if topic status is solved';
$lang['config.message_before_topic_title_icon_displayed'] = 'Display associated icon';
$lang['config.message_before_topic_title_icon_displayed.explain'] = '<i class="fa fa-msg-display"></i> / <i class="fa fa-msg-not-display"></i>';

//authorizations
$lang['authorizations.flood'] = 'Flood authorization';
$lang['authorizations.hide_edition_mark'] = 'Hide last edited time information';
$lang['authorizations.unlimited_topics_tracking'] = 'Deactivate topics subscription limit';
?>
