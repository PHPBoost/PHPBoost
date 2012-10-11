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
# English                                          #
####################################################

$lang = array();

//Types
$lang['bugtracker.config.types.anomaly'] = 'Anomaly';
$lang['bugtracker.config.types.evolution_demand'] = 'Evolution demand';

//Categories
$lang['bugtracker.config.categories.kernel'] = 'Kernel';
$lang['bugtracker.config.categories.module'] = 'Module';
$lang['bugtracker.config.categories.graphism'] = 'Graphism';
$lang['bugtracker.config.categories.installation'] = 'Installation';

//Severities
$lang['bugtracker.config.severities.minor'] = 'Minor';
$lang['bugtracker.config.severities.major'] = 'Major';
$lang['bugtracker.config.severities.critical'] = 'Critical';

//Priorities
$lang['bugtracker.config.priorities.none'] = 'None';
$lang['bugtracker.config.priorities.low'] = 'Low';
$lang['bugtracker.config.priorities.normal'] = 'Normal';
$lang['bugtracker.config.priorities.high'] = 'High';
$lang['bugtracker.config.priorities.urgent'] = 'Urgent';

//Default content message
$lang['bugtracker.config.contents_value'] = 'Please fill the informations below, they will be useful to treat the bug
Operating system :
Web navigator :
Test version (test site, zip archive) :
Local installation or on a web server ? :
Link :
----------------------------------------------------------------------
';

?>