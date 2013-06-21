<?php
/*##################################################
 *                              bugtracker_config.php
 *                            -------------------
 *   begin                : April 16, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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
# French                                           #
####################################################

$lang = array();

//Types
$lang['bugtracker.config.types.anomaly'] = 'Anomalie';
$lang['bugtracker.config.types.evolution_demand'] = 'Demande d\'évolution';

//Catégories
$lang['bugtracker.config.categories.kernel'] = 'Noyau';
$lang['bugtracker.config.categories.module'] = 'Module';
$lang['bugtracker.config.categories.graphism'] = 'Graphisme';
$lang['bugtracker.config.categories.installation'] = 'Installation';

//Importance
$lang['bugtracker.config.severities.minor'] = 'Mineur';
$lang['bugtracker.config.severities.major'] = 'Majeur';
$lang['bugtracker.config.severities.critical'] = 'Bloquant';

//Priorités
$lang['bugtracker.config.priorities.none'] = 'Aucune';
$lang['bugtracker.config.priorities.low'] = 'Basse';
$lang['bugtracker.config.priorities.normal'] = 'Normale';
$lang['bugtracker.config.priorities.high'] = 'Elevée';
$lang['bugtracker.config.priorities.urgent'] = 'Urgente';

//Message de contenu par défaut
$lang['bugtracker.config.contents_value'] = '
Système d\'exploitation :<br />
Navigateur Web :<br />
Version de phpboost :<br />
Installation locale ou sur un serveur web ? :<br />
Lien :<br />
----------------------------------------------------------------------<br />
';

?>