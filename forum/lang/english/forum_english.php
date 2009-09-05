<?php
/*##################################################
 *                              forum_english.php
 *                            -------------------
 *   begin                : November 21, 2006
 *   last modified		: August 30, 2009 - Forensic 
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *  
 ###################################################
 *
 *   This program is a free software. You can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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
#                                                           English                                                                             #
####################################################

global $CONFIG;

//Admin
$LANG['parent_category'] = 'Parent category';
$LANG['subcat'] = 'Subcategory';
$LANG['url_explain'] = 'Transform the forum into weblink (http://...)';
$LANG['lock'] = 'Lock';
$LANG['unlock'] = 'Unlock';
$LANG['cat_edit'] = 'Edit category';
$LANG['del_cat'] = 'Subcategory suppression tool';
$LANG['explain_topic'] = 'The forum you wish to delete contains <strong>1</strong> post, do you want to preserve it by moving it in another forum, or delete this post?';
$LANG['explain_topics'] = 'The forum you wish to delete contains <strong>%d</strong> posts, do you want to preserve them by transferring them in another forum, or delete all posts?';
$LANG['explain_subcat'] = 'The forum you wish to delete contains <strong>1</strong> subforum, do you want to preserve it by transferring it in another forum, or delete it and its content?';
$LANG['explain_subcats'] = 'The forum you wish to delete contains <strong>%d</strong> subforums, do you want to preserve them by transferring them in another forum, or delete all those subforums and their contents?';
$LANG['keep_topic'] = 'Keep post(s)';
$LANG['keep_subforum'] = 'Keep subforum(s)';
$LANG['move_topics_to'] = 'Move post(s) to';
$LANG['move_forums_to'] = 'Move subforum(s) to';
$LANG['cat_target'] = 'Category target';
$LANG['del_all'] = 'Delete all';
$LANG['del_forum_contents'] = 'Delete the forum "<strong>%s</strong>", <strong>subforums</strong> and <strong>all</strong> its content <span class="text_small">(irreversible)</span>';
$LANG['forum_config'] = 'Forum configuration';
$LANG['forum_management'] = 'Forum management';
$LANG['forum_name'] = 'Forum name';
$LANG['nbr_topic_p'] = 'Number of topics per page';
$LANG['nbr_topic_p_explain'] = 'Default 20';
$LANG['nbr_msg_p'] = 'Number of posts per page';
$LANG['nbr_msg_p_explain'] = 'Default 15';
$LANG['time_new_msg'] = 'Number of days unread posts are stored';
$LANG['time_new_msg_explain'] = 'Adjust according to the number of posts per day, default\'s 30 days';
$LANG['topic_track_max'] = 'Topics subscriptions limit';
$LANG['topic_track_max_explain'] = 'Default 40';
$LANG['edit_mark'] = 'Display last edited time information';
$LANG['forum_display_connexion'] = 'Display login form';
$LANG['no_left_column'] = 'Hide left column while reading the forum';
$LANG['no_right_column'] = 'Hide right column while reading the forum';
$LANG['activ_display_msg'] = 'Activate title prefix';
$LANG['display_msg'] = 'Prefix title';
$LANG['explain_display_msg'] = 'Message explanation to members';
$LANG['explain_display_msg_explain'] = 'If topic status\'s unsolved';
$LANG['explain_display_msg_bis_explain'] = 'If topic status\'s solved';
$LANG['icon_display_msg'] = 'Associated icon';
$LANG['update_data_cached'] = 'Recount topics and posts';
$LANG['explain_forum_groups'] = 'These configurations apply only on the forum';
$LANG['forum_groups_config'] = 'Groups configs';
$LANG['flood_auth'] = 'Allow flood';
$LANG['edit_mark_auth'] = 'Hide last edited time information';
$LANG['track_topic_auth'] = 'Deactivate topics subscription limit';
$LANG['forum_read_feed'] = 'Read the topic';
	
//Require
$LANG['require_topic_p'] = 'Please enter the number of topics per page !';
$LANG['require_nbr_msg_p'] = 'Please enter the number of posts per page !';
$LANG['require_time_new_msg'] = 'Please enter a duration for the sighting of new messages !';
$LANG['require_topic_track_max'] = 'Please enter the topics subscriptions limit !';
	
//Error
$LANG['e_topic_lock_forum'] = 'Locked topic, you can\'t post';
$LANG['e_cat_lock_forum'] = 'Locked category, you can\'t post new topic/post';
$LANG['e_unexist_topic_forum'] = 'This topic doesn\'t exist';
$LANG['e_unexist_cat_forum'] = 'This category doesn\'t exist';
$LANG['e_unable_cut_forum'] = 'You can\'t split this topic from this post';
$LANG['e_cat_write'] = 'You aren\'t allowed to write in this category';

//Alerts
$LANG['alert_delete_topic'] = 'Are you sure you want to delete this topic ?';
$LANG['alert_lock_topic'] = 'Are you sure you want to lock this topic ?';
$LANG['alert_unlock_topic'] = 'Are you sure you want to unlock this topic ?';
$LANG['alert_move_topic'] = 'Are you sure you want to move this topic ?';
$LANG['alert_warning'] = 'Do you want to warn this member ?';
$LANG['alert_history'] = 'Are you sure you want to delete history ?';
$LANG['confirm_mark_as_read'] = 'Mark all topics as read ?';
$LANG['contribution_alert_moderators_for_topics'] = 'thread not complying with the forum rules: %s';

//Titres
$LANG['title_forum'] = 'Forum';
$LANG['title_topic'] = 'Threads';
$LANG['title_post'] = 'Post';
$LANG['title_search'] = 'Search';

//Forum
$LANG['forum_index'] = 'Index';
$LANG['forum'] = 'Forum';
$LANG['forum_s'] = 'Forums';
$LANG['subforum_s'] = 'Subforums';
$LANG['topic'] = 'Thread';
$LANG['topic_s'] = 'Threads';
$LANG['author'] = 'Author';
$LANG['advanced_search'] = 'Advanced search';
$LANG['distributed'] = 'Distributed in';
$LANG['mark_as_read'] = 'Mark all threads as read';
$LANG['show_topic_track'] = 'Tracked threads';
$LANG['no_msg_not_read'] = 'No message unread';
$LANG['show_not_reads'] = 'Unread messages';
$LANG['show_last_read'] = 'Last messages read';
$LANG['no_last_read'] = 'message read';
$LANG['last_message'] = 'Last message';
$LANG['last_messages'] = 'Last messages';
$LANG['forum_new_subject'] = 'New thread';
$LANG['post_new_subject'] = 'Post a new thread';
$LANG['forum_edit_subject'] = 'Edit thread';
$LANG['forum_announce'] = 'Announce';
$LANG['forum_postit'] = 'Pinned';
$LANG['forum_lock'] = 'Lock';
$LANG['forum_unlock'] = 'Unlock';
$LANG['forum_move'] = 'Move';
$LANG['forum_move_subject'] = 'Move thread';
$LANG['forum_quote_last_msg'] = 'Repost of the preceding message ';
$LANG['edit_message'] = 'Edit Message';
$LANG['edit_by'] = 'Edit by';
$LANG['no_message'] = 'No message';
$LANG['group'] = 'Group';
$LANG['cut_topic'] = 'Divide this thread starting from this message';
$LANG['forum_cut_topic'] = 'Divide thread';
$LANG['alert_cut_topic'] = 'Divide this thread starting from this message?';
$LANG['track_topic'] = 'Add to favorite';
$LANG['untrack_topic'] = 'Remove from favorites';
$LANG['track_topic_pm'] = 'Track by private message';
$LANG['untrack_topic_pm'] = 'Stop private messsage tracking';
$LANG['track_topic_mail'] = 'Track by email';
$LANG['untrack_topic_mail'] = 'Stop email tracking';
$LANG['alert_topic'] = 'Alert moderators';
$LANG['banned'] = 'Banned';
$LANG['xml_forum_desc'] = 'Last forum thread';
$LANG['alert_modo_explain'] = 'You are about to alert the moderators. You are helping the moderation team by informing us about threads which don\'t comply with certain rules, but do know that when you alert a moderator, your pseudo is recorded. Be sure that your request is justified or you will risk sanctions on behalf of the moderators team and administrators in the event of abuse. In order to help the team, please explain what does not observe the conditions in this thread.

You wish to alert the moderators about a problem on the following thread';
$LANG['alert_title'] = 'Short description';
$LANG['alert_contents'] = 'Thanks for detailing the problem more in order to help the moderating team';
$LANG['alert_success'] = 'You announced successfully the nonconformity of the thread <em>%title</em>, the moderating team thanks you for helping it.';
$LANG['alert_topic_already_done'] = 'We thank you for taking the initiative to help the moderating team, but a member already announced a nonconformity of this thread.';
$LANG['alert_back'] = 'Back to thread';
$LANG['explain_track'] = 'Check Pm to receive a private message, Mail for an email in case of answers in this tracked thread. Check delete box for untrack thread';
$LANG['sub_forums'] = 'Sub-forums';
$LANG['moderation_forum'] = 'Forum moderation';
$LANG['no_topics'] = 'No threads';
$LANG['for_selection'] = 'For the selection';
$LANG['change_status_to'] = 'Set status: %s';
$LANG['change_status_to_default'] = 'Set default status';
$LANG['move_to'] = 'Move to...';

//Recherche
$LANG['search_forum'] = 'Search on the forum';
$LANG['relevance'] = 'Relevance';
$LANG['no_result'] = 'No result';
$LANG['invalid_req'] = 'Invalid request';
$LANG['keywords'] = 'Key Words (4 characters minimum)';
$LANG['color_result'] = 'Color results';

//Stats
$LANG['stats'] = 'Statistics';
$LANG['nbr_topics_day'] = 'Number of threads per day';
$LANG['nbr_msg_day'] = 'Number of messages per day';
$LANG['nbr_topics_today'] = 'Number of threads today';
$LANG['nbr_msg_today'] = 'Number of messages today';
$LANG['forum_last_msg'] = 'The last 10 threads';
$LANG['forum_popular'] = 'The 10 most famous threads';
$LANG['forum_nbr_answers'] = 'The 10 threads with the highest number of answers';

//History
$LANG['history'] = 'Actions history';
$LANG['history_member_concern'] = 'Member concern';
$LANG['no_action'] = 'No action in database';
$LANG['delete_msg'] = 'Delete message';
$LANG['delete_topic'] = 'Delete thread';
$LANG['lock_topic'] = 'Lock thread';
$LANG['unlock_topic'] = 'Unlock thread';
$LANG['move_topic'] = 'Move thread';
$LANG['cut_topic'] = 'Cut thread';
$LANG['warning_on_user'] = '+10% to member';
$LANG['warning_off_user'] = '-10% to member';
$LANG['set_warning_user'] = 'Warning percent modification';
$LANG['more_action'] = 'Show 100 more action ';
$LANG['ban_user'] = 'Ban member';
$LANG['edit_msg'] = 'Edit member\'s message ';
$LANG['edit_topic'] = 'Edit member\'s thread';
$LANG['solve_alert'] = 'Set alert status to solve';
$LANG['wait_alert'] = 'Set alert status to standby';
$LANG['del_alert'] = 'Delete alert';

//Member messages
$LANG['show_member_msg'] = 'Show all member\'s messages';

//Poll
$LANG['poll'] = 'Poll(s)';
$LANG['mini_poll'] = 'Mini Poll';
$LANG['poll_main'] = 'This is the place of polls for the site, use it to deliver your opinion, or to simply answer the polls.';
$LANG['poll_back'] = 'Return to the poll(s)';
$LANG['redirect_none'] = 'No polls available';
$LANG['confirm_vote'] = 'Your vote was taken into account';
$LANG['already_vote'] = 'You have already voted';
$LANG['no_vote'] = 'Your null vote has been considered';
$LANG['poll_vote'] = 'Vote';
$LANG['poll_vote_s'] = 'Votes';
$LANG['poll_result'] = 'Results';
$LANG['alert_delete_poll'] = 'Delete this poll ?';
$LANG['unauthorized_poll'] = 'You aren\'t authorized to vote !';
$LANG['question'] = 'Question';
$LANG['answers'] = 'Answers';
$LANG['poll_type'] = 'Kind of poll';
$LANG['open_menu_poll'] = 'Open poll menu';
$LANG['simple_answer'] = 'Single answer';
$LANG['multiple_answer'] = 'Multiple answer';
$LANG['delete_poll'] = 'Delete poll';
$LANG['require_title_poll'] = 'Please set a title for the poll!';

//Post
$LANG['forum_mail_title_new_post'] = 'New post on the forum';
$LANG['forum_mail_new_post'] = 'Dear %s

You track the thread: %s 

You asked a notify in case of an answer on it.

%s has reply: 
%s

[Rest of the message : %s]




If you no longer want to be informed on the answers of this thread, click on the link below:
' . HOST . DIR . '/forum/action.php?ut=%d&trt=%d

' . $CONFIG['sign'];

//Alerts
$LANG['alert_management'] = 'Alert management';
$LANG['alert_concerned_topic'] = 'Concerned thread';
$LANG['alert_concerned_cat'] = 'Concerned thread\'s category';
$LANG['alert_login'] = 'Alert postor';
$LANG['alert_msg'] = 'Precisions';
$LANG['alert_not_solved'] = 'Waiting for treatement';
$LANG['alert_solved'] = 'Resolve by ';
$LANG['change_status_to_0'] = 'Set in waiting for treatement';
$LANG['change_status_to_1'] = 'Set in resolve';
$LANG['no_alert'] = 'There is no alert';
$LANG['alert_not_auth'] = 'This alert has been posted in a forum in which you haven\'t the moderator\'s rights.';
$LANG['delete_several_alerts'] = 'Are you sure, delete all this alerts?';
$LANG['new_alerts'] = 'new alert';
$LANG['new_alerts_s'] = 'new alerts';
$LANG['action'] = 'Action';

?>
