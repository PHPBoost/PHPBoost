<?php
/*##################################################
*                          distribution_french.php
*                            -------------------
*   begin                : October 12, 2008
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
define('DISTRIBUTION_NAME', 'Development');

//Description of the distribution (localized)
define('DISTRIBUTION_DESCRIPTION', 'You are about to install the development version of PHPBoost.
<p>This version is not stable and mustn\'t be used in production for a website.</p>');

//Distribution default theme
define('DISTRIBUTION_THEME', 'base');

//Home page
define('DISTRIBUTION_START_PAGE', '/news/news.php');

//Can people register?
define('DISTRIBUTION_ENABLE_USER', true);

//Debug mode?
define('DISTRIBUTION_ENABLE_DEBUG_MODE', true);

//Modules list
$DISTRIBUTION_MODULES = array('articles', 'calendar', 'contact', 'connect', 'database', 'download', 'faq', 'forum', 'gallery', 'guestbook', 'media', 'news', 'newsletter', 'online', 'pages', 'poll', 'search', 'sitemap', 'shoutbox', 'stats', 'test', 'web', 'wiki');

?>
