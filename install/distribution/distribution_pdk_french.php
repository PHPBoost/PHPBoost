<?php
/*##################################################
*                          distribution_french.php
*                            -------------------
*   begin                : June 6, 2009
*   copyright            :(C) 2009 Benoit Sautel
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
define('DISTRIBUTION_NAME', 'PDK');

//Description de la distribution
define('DISTRIBUTION_DESCRIPTION', '<p>Vous allez installer la distribution <strong><acronym title="PHPBoost Development Kit">PDK</acronym></strong> de PHPBoost.</p>
<p>Cette distribution est parfaitement adaptée aux développeurs qui souhaitent développer un module afin de l\'intégrer à PHPBoost. Elle contient un outil de gestion de la base de données ainsi que la documentation du framework de PHPBoost.</p>');

//Thème de la distribution
define('DISTRIBUTION_THEME', 'extends');

//Page de démarrage de la distribution (commencer à la racine du site avec /)
define('DISTRIBUTION_START_PAGE', '/doc/3.0/index.php');

//Espace membre activé ? (Est-ce que les membres peuvent s'inscrire et participer au site ?)
define('DISTRIBUTION_ENABLE_USER', true);

//Liste des modules
$DISTRIBUTION_MODULES = array('connect', 'database', 'doc');

?>