<?php
/*##################################################
 *                           admin-contents-common.php
 *                            -------------------
 *   begin                : August 10, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
#                     English                       #
 ####################################################
 
$lang = array();

$lang['content'] = 'Content';
$lang['content.config'] = 'Content configuration';
$lang['content.config.language'] = 'Formatting language';
$lang['content.config.default-formatting-language'] = 'Default formatting language on the website';
$lang['content.config.default-formatting-language-explain'] = 'Every user will be able to choose';
$lang['content.config.html-language'] = 'HTML language';
$lang['content.config.html-language-use-authorization'] = 'Authorization level to insert HTML langage in the content';
$lang['content.config.html-language-use-authorization-explain'] = 'Warning : if you can insert HTML tags, you can also insert some JavaScript and this code can be the source of vulnerabilities. People who can insert some HTML language must be people who you trust.';
$lang['content.config.post-management'] = 'Post Management';
$lang['content.config.max-pm-number'] = 'Maximum number of private messages';
$lang['content.config.max-pm-number-explain'] = 'Unlimited for administrators and moderators';
$lang['content.config.anti-flood-enabled'] = 'Anti-flood';
$lang['content.config.anti-flood-enabled-explain'] = 'Block too rapid repeat messages, except if the visitors are authorized';
$lang['content.config.delay-flood'] = 'Minimal interval of time between two messages';
$lang['content.config.delay-flood-explain'] = 'In seconds. 7 seconds per default.';

$lang['content.config.captcha'] = 'Captcha';
$lang['content.config.captcha-used'] = 'Captcha used on your site';
$lang['content.config.captcha-used-explain'] = 'The captcha allows you to protect yourself against spam on your site.';

$lang['comments'] = 'Comments';
$lang['comments.config'] = 'Comments configuration';
$lang['comments.management'] = 'Comments management';

$lang['comments.config.number-comments-display'] = 'Number of comments to display by default';
$lang['comments.config.order-display-comments'] = 'Ordre comments display';
$lang['comments.config.order-display-comments.asc'] = 'Oldest to newest';
$lang['comments.config.order-display-comments.desc'] = 'Newest to oldest';

$lang['comments.config.authorization'] = 'Authorizations';
$lang['comments.config.authorization-read'] = 'Authorization to see the comments';
$lang['comments.config.authorization-post'] = 'Authorization to post a comment';
$lang['comments.config.authorization-moderation'] = 'Authorization to manage comments';
$lang['comments.config.authorization-note'] = 'Authorization to note a comment';
$lang['comments.config.max-links-comment'] = 'Number of links allowed in a comment';
$lang['comments.config.forbidden-tags'] = 'Forbidden tags';
$lang['comments.config.approbation'] = 'Comments approbation';
$lang['comments.config.approbation.auto'] = 'Automatic';
$lang['comments.config.approbation.moderator'] = 'Moderator';
$lang['comments.config.approbation.administrator'] = 'Administrator';
?>
