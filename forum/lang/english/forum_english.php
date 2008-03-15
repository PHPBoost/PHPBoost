<?php
/*##################################################
 *                              forum_english.php
 *                            -------------------
 *   begin                : November 21, 2006
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *  
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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
#                                                           English                                                                             #
####################################################

//Admin
$LANG['parent_category'] = 'Parent category';
$LANG['subcat'] = 'Subcategory';
$LANG['lock'] = 'Lock';
$LANG['unlock'] = 'Unlock';
$LANG['cat_edit'] = 'Edit category';
$LANG['del_cat'] = 'Tool for subcategory suppression';
$LANG['explain_topic'] = 'The forum that you wish delete contain <strong>1</strong> of topic, do you want to preserve it by transferring in another forum, or delete this topic?';
$LANG['explain_topics'] = 'The forum that you wish delete contain <strong>%d</strong> of topics, do you want to preserve them by transferring in another forum, or delete all topics?';
$LANG['explain_subcat'] = 'The forum that you wish delete contain <strong>1</strong> sub-forum, do you want to preserve it by transferring in another forum, or delete it and his content?';
$LANG['explain_subcats'] = 'The forum that you wish delete contain <strong>%d</strong> sub-forums, do you want to preserve them by transferring in another forum, or delete all this subforums and their content?';
$LANG['keep_topic'] = 'Keep topic(s)';
$LANG['keep_subforum'] = 'Keep subforum(s)';
$LANG['move_topics_to'] = 'Move topic(s) to';
$LANG['move_forums_to'] = 'Move sub-forum(s) to';
$LANG['cat_target'] = 'Category target';
$LANG['del_all'] = 'Complete supression';
$LANG['del_cat'] = 'Delete forum "<strong>%s</strong>", <strong>sub-forums</strong> and <strong>all</strong> his content';
$LANG['forum_config'] = 'Forum configuration';
$LANG['forum_management'] = 'Forum management';
$LANG['forum_name'] = 'Forum name';
$LANG['nbr_topic_p'] = 'Number of topic by page';
$LANG['nbr_topic_p_explain'] = 'Default 20';
$LANG['nbr_msg_p'] = 'Number of messages by page';
$LANG['nbr_msg_p_explain'] = 'Default 15';
$LANG['time_new_msg'] = 'Time for which the messages read by the members are stored';
$LANG['time_new_msg_explain'] = 'A to regulate according to the number of messages per day, by default 30 days';
$LANG['edit_mark'] = 'Markers of messages edition '.
$LANG['no_left_column'] = 'Hide left column menu on forum';
$LANG['no_right_column'] = 'Hide right column menu on forum';
$LANG['topic_track_max'] = 'Maximum number of tracked topics';
$LANG['topic_track_max_explain'] = 'Par défaut 40';
$LANG['activ_display_msg'] = 'Active message in front of topic';
$LANG['display_msg'] = 'Message in front of topic';
$LANG['explain_display_msg'] = 'Message explinations for members';
$LANG['explain_display_msg_explain'] = 'If status unchanged';
$LANG['explain_display_msg_bis_explain'] = 'If status changed';
$LANG['icon_display_msg'] = 'Associated icon';
$LANG['update_data_cached'] = 'Recount number of topics and messages';
$LANG['forum_groups_config'] = 'Groups config';
$LANG['explain_forum_groups'] = 'These configuration are only on the forum';
$LANG['flood_auth'] = 'Allowed flood';
$LANG['edit_mark_auth'] = 'Unactiv edit mark';
$LANG['track_topic_auth'] = 'Unactiv tracked topics limit';
	
//Require
$LANG['require_topic_p'] = 'Please enter the number of topics per page!';
$LANG['require_nbr_msg_p'] = 'Please enter the number of messages per page!';
$LANG['require_time_new_msg'] = 'Please enter one duration for the sight of the new messages!';
$LANG['require_topic_track_max'] = 'Please enter the maximum number of tracked topics!';
	
//Error
$LANG['e_topic_lock_forum'] = 'Locked topic, you can\'t post';
$LANG['e_cat_lock_forum'] = 'Locked category, you can\'t post new topic or message';
$LANG['e_unexist_topic_forum'] = 'This topic doesn\'t exist';
$LANG['e_unexist_cat_forum'] = 'This category doesn\'t exist';
$LANG['e_unable_cut_forum'] = 'You can\'t divide this topic starting the first message';
$LANG['e_cat_write'] = 'You aren\'t allowed to write in this category';

//Alerts
$LANG['alert_delete_topic'] = 'Delete this Topic ?';
$LANG['alert_lock_topic'] = 'Lock this Topic ?';
$LANG['alert_unlock_topic'] = 'Unlock this Topic ?';
$LANG['alert_move_topic'] = 'Move this Topic ?';
$LANG['alert_warning'] = 'Warning this member?';
$LANG['alert_history'] = 'Delete history?';
$LANG['confirm_mark_as_read'] = 'Mark all topics as read?';
$LANG['confirm_mark_as_read_forum'] = 'Mark topics of this forum as read';
$LANG['confirm_mark_as_read_favorite'] = 'Mark tracked topics as read';

//Titres
$LANG['title_forum'] = 'Forum';
$LANG['title_topic'] = 'Threads';
$LANG['title_post'] = 'Post';
$LANG['title_search'] = 'Search';

//Forum
$LANG['forum_index'] = 'Index';
$LANG['forum'] = 'Forum';
$LANG['forum_s'] = 'Forums';
$LANG['subforum_s'] = 'Sub-forums';
$LANG['topic'] = 'Topic';
$LANG['topic_s'] = 'Topics';
$LANG['author'] = 'Author';
$LANG['advanced_search'] = 'Advanced search';
$LANG['distributed'] = 'Distributed in';
$LANG['mark_as_read'] = 'Mark all topics as read';
$LANG['show_topic_track'] = 'Tracked topics';
$LANG['no_msg_not_read'] = 'No message not read';
$LANG['show_not_reads'] = 'Unread messages';
$LANG['show_last_read'] = 'Last messages read';
$LANG['no_last_read'] = 'message read';
$LANG['last_message'] = 'Last message';
$LANG['last_messages'] = 'Last messages';
$LANG['forum_new_subject'] = 'New subject';
$LANG['post_new_subject'] = 'Post a new subject';
$LANG['forum_edit_subject'] = 'Edit Topic';
$LANG['forum_announce'] = 'Announce';
$LANG['forum_postit'] = 'Post it';
$LANG['forum_lock'] = 'Lock';
$LANG['forum_unlock'] = 'Unlock';
$LANG['forum_move'] = 'Move';
$LANG['forum_move_subject'] = 'Move subject';
$LANG['forum_quote_last_msg'] = 'Repost of the preceding message ';
$LANG['edit_message'] = 'Edit Message';
$LANG['edit_by'] = 'Edit by';
$LANG['no_message'] = 'No message';
$LANG['group'] = 'Group';
$LANG['cut_topic'] = 'Divide this topic starting from this message';
$LANG['forum_cut_subject'] = 'Divide topic';
$LANG['alert_cut_topic'] = 'Divide this topic starting from this message?';
$LANG['track_topic'] = 'Track this topic';
$LANG['untrack_topic'] = 'Don\'t track this topic anymore';
$LANG['alert_topic'] = 'Alert moderators';
$LANG['banned'] = 'Banned';
$LANG['xml_forum_desc'] = 'Last forum\'s subject';
$LANG['alert_modo_explain'] = 'You are about to alert the moderators. You help the moderating team by announcing topics to him which do not comply with certain rules, but will know that when you alert a moderator your pseudo is recorded, it is thus necessary that your request is justified without what you risk sanctions on behalf of the team of the regulators and administrators in the event of abuse. In order to help the team, thank you to explain what does not observe the conditions in this subject.

You wish to alert the regulators of a problem on the following subject';
$LANG['alert_title'] = 'Short description';
$LANG['alert_contents'] = 'Thanks for detailing the problem more in order to help the moderating team';
$LANG['alert_success'] = 'You announced successfully the nonconformity of the subject <em>%title</em>, the moderating team thanks you for having helped it.';
$LANG['alert_topic_already_done'] = 'We thank you for having taken the initiative to help the moderating team, but a member already announced a nonconformity of this subject.';
$LANG['alert_back'] = 'Back to topic';
$LANG['explain_track'] = 'Check Pm to receive a private message, Mail for an email in case of answers in this tracked topic. Check delete box for untrack topic';
$LANG['sub_forums'] = 'Sub-forums';
$LANG['moderation_forum'] = 'Forum moderation';
$LANG['no_topics'] = 'No topics';
$LANG['for_selection'] = 'For the selection';
$LANG['change_status_to'] = 'Set status: %s';
$LANG['change_status_to_default'] = 'Set default status';
$LANG['move_to'] = 'Move to...';

//Recherche
$LANG['search_forum'] = 'Search on the forum';
$LANG['relevance'] = 'Pertinance';
$LANG['no_result'] = 'No result';
$LANG['invalid_req'] = 'Invalid request';
$LANG['keywords'] = 'Key Words (4 characters minimum)';
$LANG['colorate_result'] = 'Colorate results';

//Stats
$LANG['stats'] = 'Statistics';
$LANG['nbr_topics_day'] = 'Number topics per day';
$LANG['nbr_msg_day'] = 'Number messages per day';
$LANG['nbr_topics_today'] = 'Number topics today';
$LANG['nbr_msg_today'] = 'Number messages today';
$LANG['forum_last_msg'] = 'The 10 last topics';
$LANG['forum_popular'] = 'The 10 most famous topics';
$LANG['forum_nbr_answers'] = 'The 10 topics with the highest number of answers';

//History
$LANG['history'] = 'Actions history';
$LANG['history_member_concern'] = 'Member concern';
$LANG['no_action'] = 'No action in database';
$LANG['delete_msg'] = 'Delete message';
$LANG['delete_topic'] = 'Delete topic';
$LANG['lock_topic'] = 'Lock topic';
$LANG['unlock_topic'] = 'Unlock topic';
$LANG['move_topic'] = 'Move topic';
$LANG['cut_topic'] = 'Cut topic';
$LANG['warning_on_user'] = '+10% to member';
$LANG['warning_off_user'] = '-10% to member';
$LANG['set_warning_user'] = 'Warning pourcent modification';
$LANG['more_action'] = 'Show 100 action moreover';
$LANG['ban_user'] = 'Ban member';
$LANG['edit_msg'] = 'Edit message member';
$LANG['edit_topic'] = 'Edit topic member';
$LANG['solve_alert'] = 'Set alert statute to solve';
$LANG['wait_alert'] = 'Set alert statute to standby';
$LANG['del_alert'] = 'Delete alert';

//Member messages
$LANG['show_member_msg'] = 'Show all member\'s messages';

//Poll
$LANG['poll'] = 'Poll(s)';
$LANG['mini_poll'] = 'Mini Poll';
$LANG['poll_main'] = 'This is the place of polls of the site, profit in to deliver your opinion, or simply to answer the polls.';
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
$LANG['poll_type'] = 'Type de sondage';
$LANG['open_menu_poll'] = 'Open poll menu';
$LANG['simple_answer'] = 'Single answer';
$LANG['multiple_answer'] = 'Multiple answer';
$LANG['delete_poll'] = 'Delete poll';

//Post
$LANG['next'] = 'Next';
$LANG['forum_mail_title_new_post'] = 'New post on the forum';
$LANG['forum_mail_new_post'] = 'You track the subject: %s 

You asked a notify in case of answer on it.

%s has reply: 
%s... %s

If you do not wish any more to be informed answers of this subject, click on the unsubscribed link (in bottom of the subject). If you do not want to be informed any more of the answers of the subjects which you subscribed, desactivate it in topics track page.

' . $CONFIG['sign'];

//Alerts
$LANG['alert_management'] = 'Alert management';
$LANG['alert_concerned_topic'] = 'Concerned topic';
$LANG['alert_concerned_cat'] = 'Concerned topic\'s category';
$LANG['alert_login'] = 'Alert postor';
$LANG['alert_msg'] = 'Precisions';
$LANG['alert_not_solved'] = 'Waiting for treatement';
$LANG['alert_solved'] = 'Resolve by ';
$LANG['change_status_to_0'] = 'Set in waiting for treatement';
$LANG['change_status_to_1'] = 'Set in resolve';
$LANG['no_alert'] = 'There is no alert';
$LANG['alert_not_auth'] = 'This alert has been post in a forum in which you haven\'t the moderator rights.';
$LANG['delete_several_alerts'] = 'Are you sure, delete all this alerts?';
$LANG['new_alerts'] = 'new alert';
$LANG['new_alerts_s'] = 'new alerts';
$LANG['action'] = 'Action';

?>