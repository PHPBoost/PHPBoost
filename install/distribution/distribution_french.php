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

//Nom de la distribution
define('DISTRIBUTION_NAME', 'Développement');

//Description de la distribution
define('DISTRIBUTION_DESCRIPTION', 'Vous êtes sur le point d\'installer la version de développement de PHPBoost.
<p>Cette version n\'est pas stable et n\'est donc pas à utiliser en production sur un site.</p>');

//Thème de la distribution
define('DISTRIBUTION_THEME', 'base');

//Page de démarrage de la distribution (commencer à la racine du site avec /)
define('DISTRIBUTION_START_PAGE', '/news/news.php');

//Espace membre activé ? (Est-ce que les membres peuvent s'inscrire et participer au site ?)
define('DISTRIBUTION_ENABLE_USER', true);

//Mode debug ?
define('DISTRIBUTION_ENABLE_DEBUG_MODE', true);

//Enable bench?
define('DISTRIBUTION_ENABLE_BENCH', true);

//Liste des modules
$DISTRIBUTION_MODULES = array('articles', 'calendar', 'contact', 'connect', 'database', 'download', 'faq', 'forum', 'gallery', 'guestbook', 'media', 'news', 'newsletter', 'online', 'pages', 'poll', 'sandbox', 'search', 'sitemap', 'shoutbox', 'stats', 'test', 'web', 'wiki');

?>
