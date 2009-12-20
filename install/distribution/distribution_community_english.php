<?php
/*##################################################
*                          distribution_english.php
*                            -------------------
*   begin                : November 22, 2008
*   copyright            :(C) 2008 Benoit Sautel
*   email                : ben.popeye@phpboost.com
*
*
 ###################################################
*
*  This program is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
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

//Name of the distribution (localized)
define('DISTRIBUTION_NAME', 'Community');

//Description of the distribution (localized)
define('DISTRIBUTION_DESCRIPTION', '<img src="distribution/community.png" alt="" style="float:right;padding-right:35px"/>
<p>You are going to install the <strong>Community</strong> distribution of PHPBoost.</p>
<p>This distribution is ideal to create and manage a community. Some discussion tools (such as the forum or the shoutbox) and contribution tools (wiki for instance) will enable the community members to participate.</p>');

//Distribution default theme
define('DISTRIBUTION_THEME', 'extends');

//Home page
define('DISTRIBUTION_START_PAGE', '/news/news.php');

//Can people register?
define('DISTRIBUTION_ENABLE_USER', true);

//Debug mode?
define('DISTRIBUTION_ENABLE_DEBUG_MODE', false);

//Modules list
$DISTRIBUTION_MODULES = array('articles', 'connect', 'contact', 'database', 'news', 'pages', 'search', 'web', 'download', 'wiki', 'shoutbox', 'faq', 'forum', 'guestbook', 'online', 'poll');

?>