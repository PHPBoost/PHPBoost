<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 28
 * @since       PHPBoost 4.1 - 2014 10 11
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    English                       #
####################################################

$lang['forum.config.forum.name']            = GeneralConfig::load()->get_site_name() . ' forum';
$lang['forum.config.issue.status']          = '[Solved]';
$lang['forum.config.issue.status.unsolved'] = 'Solved topic?';
$lang['forum.config.issue.status.solved']   = 'Unsolved topic?';

$lang['forum.config.forum.name']                   = 'Forum name';
$lang['forum.config.topics.per.page']              = 'Number of topics per page';
$lang['forum.config.messages.per.page']            = 'Number of posts per page';
$lang['forum.config.read.messages.storage']        = 'Storage duration of read messages ';
$lang['forum.config.read.messages.storage.clue']   = 'In days. Adjust according to the number of daily posts.';
$lang['forum.config.favorite.topics.number']       = 'Max favorite topics number per member';
$lang['forum.config.enable.edit.marker']           = 'Display last edited time information';
$lang['forum.config.enable.multiple.posts']        = 'Allow members to post multiple consecutive messages';
$lang['forum.config.enable.multiple.posts.clue']   = 'If the option is unchecked, the last message of the user will automatically be completed with the new posted content';
$lang['forum.config.display.connexion.form']       = 'Display login form';
$lang['forum.config.display.message.before.topic'] = 'Display message before topic title';
$lang['forum.config.message.before.topic']         = 'Message before topic title';
$lang['forum.config.status.message.unsolved']      = 'Message explanation to members if topic status is unsolved';
$lang['forum.config.status.message.solved']        = 'Message explanation to members if topic status is solved';
$lang['forum.config.display.issue.status.icon']    = 'Display associated icon';

?>
