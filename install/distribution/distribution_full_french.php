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
define('DISTRIBUTION_NAME', 'Pack complet');

//Description de la distribution
define('DISTRIBUTION_DESCRIPTION', '<img src="distribution/publication.png" alt="" style="float:right;padding-right:35px"/>
<p>Vous allez installer la distribution <strong>Pack complet</strong> de PHPBoost.</p>
<p>Cette distribution contient l\'ensemble des modules officiels publiés par l\'équipe de développement de PHPBoost. Elle devrait répondre à une grande variété de besoins grâce à la diversité de ses modules.</p>');

//Thème de la distribution
define('DISTRIBUTION_THEME', 'base');

//Page de démarrage de la distribution (commencer à la racine du site avec /)
define('DISTRIBUTION_START_PAGE', '/news/news.php');

//Espace membre activé ? (Est-ce que les membres peuvent s'inscrire et participer au site ?)
define('DISTRIBUTION_ENABLE_USER', true);

//Liste des modules
$DISTRIBUTION_MODULES = array('articles', 'calendar', 'contact', 'connect', 'database', 'download', 'faq', 'forum', 'gallery', 'guestbook', 'media', 'news', 'newsletter', 'online', 'pages', 'poll', 'search', 'shoutbox', 'stats', 'web', 'wiki');

?>
