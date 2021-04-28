<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 28
 * @since       PHPBoost 4.1 - 2015 02 25
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    English                       #
####################################################

$lang['forum.module.title'] = 'Forum';

$lang['forum.my.items'] = 'My messages';
$lang['forum.member.items'] = 'Messages published by';

// Authorizations
$lang['forum.authorizations.read.topics.content']       = 'Display topics content authorization';
$lang['forum.authorizations.flood']                     = 'Flood authorization';
$lang['forum.authorizations.hide.edition.mark']         = 'Hide last edited time information';
$lang['forum.authorizations.unlimited.topics.tracking'] = 'Deactivate topics subscription limit';
$lang['forum.authorizations.multiple.posts']            = 'Users authorized to post several consecutive messages';

// Categories
$lang['forum.category.status.locked'] = 'Locked';

// Email
$lang['forum.email.title.new.post'] = 'New post on the forum';
$lang['forum.email.new.post'] = 'Dear %s

You track the topic: %s

You asked a notify in case of an answer on it.

%s has reply:
%s

[Rest of the message : %s]




If you no longer want to be informed on the answers of this topic, click on the link below:
' . HOST . DIR . '/forum/action.php?ut=%d&trt=%d

' . MailServiceConfig::load()->get_mail_signature();

// Extended fields
$lang['forum.extended.field.skype']      = 'Skype';
$lang['forum.extended.field.skype.clue'] = '';

$lang['forum.extended.field.signing']      = 'Signature';
$lang['forum.extended.field.signing.clue'] = 'The signature appears beyond all your messages';

// Forum table (index | categories | forums | specials)
$lang['forum.post.new.topic']  = 'Post a new topic';
$lang['forum.forum']           = 'Forum';
$lang['forum.forums']          = 'Forums';
$lang['forum.topic.status']    = 'Topic status';
$lang['forum.popular.topic']   = 'Popular topic';
$lang['forum.new.topic']       = 'New topic';
$lang['forum.topic.options']   = 'Topic options';
$lang['forum.topic.author']    = 'Topic author';
$lang['forum.topics.number']   = 'Topics number';
$lang['forum.messages.number'] = 'Messages number';
$lang['forum.answers.number']  = 'Answers number';
$lang['forum.views.number']    = 'Views number';
$lang['forum.sub.forums']      = 'Sub-forums';
$lang['forum.topic']           = 'Topic';
$lang['forum.topics']          = 'Topics';
$lang['forum.last.message']    = 'Last message';
$lang['forum.last.messages']   = 'Last messages';
$lang['forum.see.message']     = 'See this message';
    // bottom
$lang['forum.distributed']    = 'distributed in';
$lang['forum.online.users']   = 'Online user.s';
$lang['forum.no.online.user'] = 'No online user';
$lang['forum.statistics']     = 'Forum statistics';
    // no item
$lang['forum.no.topic']          = 'There are no topics to display';
$lang['forum.no.message.now']    = 'There are no messages by now';
$lang['forum.no.unread.message'] = 'There are no unread messages';

// History
$lang['forum.history']          = 'Actions history';
$lang['forum.concerned.user']   = 'Concerned Member ';
$lang['forum.no.action']        = 'No action in database';
$lang['forum.delete.message']   = 'Delete message';
$lang['forum.delete.topic']     = 'Delete topic';
$lang['forum.lock.topic']       = 'Lock topic';
$lang['forum.unlock.topic']     = 'Unlock topic';
$lang['forum.move.topic']       = 'Move topic';
$lang['forum.cut.topic']        = 'Cut topic';
$lang['forum.warning.on.user']  = '+10% to member';
$lang['forum.warning.off.user'] = '-10% to member';
$lang['forum.set.warning.user'] = 'Warning percent modification';
$lang['forum.more.action']      = 'Show 100 more action ';
$lang['forum.ban.user']         = 'Ban member';
$lang['forum.edit.message']     = 'Edit member\'s message ';
$lang['forum.edit.topic']       = 'Edit member\'s topic';
$lang['forum.solve.alert']      = 'Set alert status to solve';
$lang['forum.wait.alert']       = 'Set alert status to standby';
$lang['forum.del.alert']        = 'Delete alert';

// Links (top | bottom)
$lang['forum.links']                  = 'Links';
$lang['forum.index']                  = 'Index';
$lang['forum.unanswered.topics']      = 'Unanswered topics';
$lang['forum.tracked.topics']         = 'Tracked topics';
$lang['forum.last.read.messages']     = 'Last messages read';
$lang['forum.unread.messages']        = 'Unread messages';
$lang['forum.reload.unread.messages'] = 'Reload unread messages';
$lang['forum.mark.topics.as.read']    = 'Mark all topics as read';

// Moderation
$lang['forum.moderation.forum'] = 'Forum moderation';
$lang['forum.for.selection'] = 'For the selection';
$lang['forum.change.issue.status.to'] = 'Set status: %s';
$lang['forum.default.issue.status'] = 'Set default status';
$lang['forum.no.moderation'] = 'No action';
    // Warnings

    // Reports
        // User reports
$lang['forum.reports.management']        = 'Report management';
$lang['forum.report.topic']              = 'Report this topic';
$lang['forum.report.concerned.topic']    = 'Concerned topic';
$lang['forum.report.concerned.cat']      = 'Concerned topic\'s category';
$lang['forum.report.author']             = 'Report postor';
$lang['forum.report.message']            = 'Precisions';
$lang['forum.report.unsolved']           = 'Waiting for treatement';
$lang['forum.report.solved']             = 'Resolve by ';
$lang['forum.report.change.to.unsolved'] = 'Set in waiting for treatment';
$lang['forum.report.change.to.solved']   = 'Set in resolved';
$lang['forum.report.not.auth']           = 'This alert has been posted in a forum in which you haven\'t the moderator\'s rights.';
$lang['forum.delete.several.reports']    = 'Are you sure, delete all this reports?';
$lang['forum.new.report']                = 'new report';
$lang['forum.new.reports']               = 'new reports';
$lang['forum.report.clue'] = '
    You are about to declare a report to the moderators.
    <br />You are helping the moderation team by informing us about topic which don\'t comply with certain rules,
    but do know that when you report a topic to a moderator, your username is recorded.
    <br />Be sure that your request is justified or you will risk sanctions on behalf of the moderators team and administrators in the event of abuse.
    <br />In order to help the team, please explain what does not observe the conditions in this topic.
    <br /><br />
    You wish to report to the moderators about a problem on the following topic:
';
$lang['forum.report.title'] = 'Short description';
$lang['forum.report.content'] = 'Thanks for detailing the problem more in order to help the moderating team';
$lang['forum.report.success'] = 'You successfully reported the nonconformity of the topic <em>%title</em>, the moderating team thanks you for helping it.';
$lang['forum.report.topic.already.done'] = 'We thank you for taking the initiative to help the moderating team, but a member already reported a nonconformity of this topic.';
$lang['forum.report.back'] = 'Back to topic';
        // Report moderation

// Poll
$lang['forum.poll']               = 'Poll(s)';
$lang['forum.mini.poll']          = 'Mini Poll';
$lang['forum.poll.main']          = 'This is the place of polls for the site, use it to deliver your opinion, or to simply answer the polls.';
$lang['forum.poll.back']          = 'Return to the poll(s)';
$lang['forum.redirect.none']      = 'No polls available';
$lang['forum.confirm.vote']       = 'Your vote was taken into account';
$lang['forum.already.vote']       = 'You have already voted';
$lang['forum.no.vote']            = 'Your null vote has been considered';
$lang['forum.poll.vote']          = 'Vote';
$lang['forum.poll.votes']         = 'Votes';
$lang['forum.poll.result']        = 'Results';
$lang['forum.alert.delete.poll']  = 'Delete this poll ?';
$lang['forum.unauthorized.poll']  = 'You aren\'t authorized to vote !';
$lang['forum.question']           = 'Question';
$lang['forum.answers']            = 'Answers';
$lang['forum.poll.type']          = 'Kind of poll';
$lang['forum.open.menu.poll']     = 'Open poll menu';
$lang['forum.simple.answer']      = 'Single answer';
$lang['forum.multiple.answer']    = 'Multiple answer';
$lang['forum.delete.poll']        = 'Delete poll';
$lang['forum.require.title.poll'] = 'Please set a title for the poll!';

// Ranks
$lang['forum.ranks.management']             = 'Gestion des rangs du forum';
$lang['forum.rank.add']                     = 'Add a rank';
$lang['forum.upload.rank.thumbnail']        = 'Upload icon rank';
$lang['forum.upload.rank.thumbnail.clue']   = 'JPG, GIF, PNG, BMP authorized';
$lang['forum.rank']                         = 'Rank';
$lang['forum.special.rank']                 = 'Special rank';
$lang['forum.rank.name']                    = 'Rank name';
$lang['forum.rank.messages.number']         = 'Messages number needed to reach this rank';
$lang['forum.rank.thumbnail']               = 'Associated image';
$lang['forum.require.rank.name']            = 'Please enter a name for the rank !';
$lang['forum.require.rank.messages.number'] = 'Please enter a number of message for the rank !';

// S.E.O.
$lang['forum.member.messages.seo']  = 'All :author\'s messages.';
$lang['forum.root.description.seo'] = 'All site\'s :site forum catégories.';
$lang['forum.show.no.answer.seo']   = 'Messages list without answer';
$lang['forum.stats.seo']            = 'All forum statistics';
$lang['forum.topic.title.seo']      = 'Topic :title of forum :forum';

// Search
$lang['forum.no.result'] = 'No result';

//Stats
$lang['forum.stats']                   = 'Forum statistics';
$lang['forum.topics.number.per.day']   = 'Number of topics per day';
$lang['forum.messages.number.per.day'] = 'Number of messages per day';
$lang['forum.topics.number.today']     = 'Number of topics today';
$lang['forum.messages.number.today']   = 'Number of messages today';
$lang['forum.last.10.active.topics']   = 'The last 10 topics';
$lang['forum.10.most.popular.topics']  = 'The 10 most famous topics';
$lang['forum.10.most.active.topics']   = 'The 10 topics with the highest number of answers';

// Topics
$lang['forum.last.forum.topics']    = 'Last forum topics';
$lang['forum.connected.member']     = 'Connected member';
$lang['forum.not.connected.member'] = 'Not connected member';

$lang['forum.message.options'] = 'Message options';
$lang['forum.forum.message']   = 'Forum message';
$lang['forum.forum.messages']  = 'Forum messages';

$lang['forum.quote.last.message'] = 'Resuming the previous message ';
$lang['forum.show.member.messages'] = 'Show all member\'s messages';
$lang['forum.new.topic'] = 'New topic';
$lang['forum.edit.topic'] = 'Edit topic';
$lang['forum.move.topic'] = 'Move topic';
$lang['forum.edit.message'] = 'Edit Message';
$lang['forum.edit.by'] = 'Edit by';
$lang['forum.edit.on'] = 'Edit on';
$lang['forum.cut.topic'] = 'Divide this topic starting from this message';
$lang['forum.cut.topic.warning'] = 'Do you want to cut the topic from this message?';
$lang['forum.add.to.favorites'] = 'Add to favorite';
$lang['forum.link.to.topic'] = 'Link to topic';

// Track
$lang['forum.track.topic']         = 'Set to favorite';
$lang['forum.untrack.topic']       = 'Remove from favorites';
$lang['forum.no.tracked.topic']    = 'There are no tracked topic by now';
$lang['forum.track.topic.pm']      = 'Track by private message';
$lang['forum.untrack.topic.pm']    = 'Stop private messsage tracking';
$lang['forum.track.topic.email']   = 'Track by email';
$lang['forum.untrack.topic.email'] = 'Stop email tracking';
$lang['forum.track.clue'] = '
    Check the PM box far a private message,
     the Email box (<i class="fa iboost fa-iboost-email"></i>) for an email, in case of reply in this topic.
    <br />Check the Delete box (<i class="far fa-trash-alt"></i>) to stop tracking this topic.
';

// Types
$lang['forum.announce'] = 'Announce';
$lang['forum.pinned']   = 'Pinned';
$lang['forum.lock']     = 'Lock';
$lang['forum.locked']   = 'Locked';
$lang['forum.unlock']   = 'Unlock';

// Warnings
    // errors
$lang['forum.error.locked.topic']       = 'Locked topic, you can\'t post';
$lang['forum.error.non.existent.topic'] = 'This topic doesn\'t exist';
$lang['forum.error.non.cuttable.topic'] = 'You can\'t split this topic from this post';
$lang['forum.error.locked.category']    = 'Locked forum, you can\'t post new topic/post';
$lang['forum.error.category.right']     = 'You aren\'t allowed to write in this category';
    // alerts
$lang['forum.alert.delete.topic']   = 'Are you sure you want to delete this topic ?';
$lang['forum.alert.lock.topic']     = 'Are you sure you want to lock this topic ?';
$lang['forum.alert.unlock.topic']   = 'Are you sure you want to unlock this topic ?';
$lang['forum.alert.move.topic']     = 'Are you sure you want to move this topic ?';
$lang['forum.alert.warning']        = 'Do you want to warn this member ?';
$lang['forum.alert.history']        = 'Are you sure you want to delete history ?';
$lang['forum.confirm.mark.as.read'] = 'Mark all topics as read ?';
$lang['forum.non.compliant.topic']  = 'topic not complying with the forum rules: %s';
?>
