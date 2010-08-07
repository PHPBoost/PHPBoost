<?php
/*##################################################
 *                           admin-cache-common.php
 *                            -------------------
 *   begin                : August 7, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
#                     French                       #
 ####################################################
 
$lang = array();
$lang['cache'] = 'Cache';
$lang['cache_cleared_successfully'] = 'Le cache a été vidé avec succès !';
$lang['clear_cache'] = 'Vider';
$lang['explain_data_cache'] = '<p>PHPBoost met en cache un certain nombre d\'informations, ce qui permet d\'améliorer considérablement ses performances.
Toutes les données manipulées par PHPBoost sont stockées en base de données mais chaque accès à la base de données coûte cher en temps. Les données qui sont accédées de façon régulière (notamment la configuration) sont ainsi conservées par le serveur
de façon à ne pas avoir à les demander à la base de données.</p>
<p>En contre partie, cela signifie que certaines données sont présentes à deux endroits : dans la base de données et sur le serveur web. Si vous modifiez des données dans la base de données, la modification ne se fera peut-être pas immédiatement car le fichier de cache contient encore les anciennes données.
Dans ce cas, il faut vider le cache à la main via cette page de configuration de façon à ce que PHPBoost soit obligé de générer de nouveaux fichiers de cache contenant les données à jour.
L\'emplacement de référence des données est la base de données. Si vous modifiez un fichier cache, dès qu\'il sera invalidé car la base de données aura changé, les modifications seront perdues.</p>';
$lang['syndication_cache'] = 'Syndication';
$lang['explain_syndication_cache'] = '<p>PHPBoost met en cache l\'ensemble des flux de données (RSS ou ATOM) qui lui sont demandés. En pratique, la première fois qu\'on lui demande un flux, il va le chercher en base de données et il l\'enregistre sur le serveur web et n\'accède plus à la base de données les fois suivantes pour
éviter des requêtes dans la base de données qui ralentissent considérablement l\'affichage des pages.</p>
<p>Via cette page de l\'administration de PHPBoost, vous pouvez vider le cache de façon à forcer PHPBoost à rechercher les données dans la base de données. C\'est particulièrement utile si vous avez modifié certaines choses manuellement dans la base de données. En effet, elles ne seront pas prises en compte car le cache aura toujours les valeurs précédentes.</p>';

?>