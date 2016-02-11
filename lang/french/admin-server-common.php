<?php
/*##################################################
 *                           admin-server-common.php
 *                            -------------------
 *   begin                : July 8, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 
$lang['advises'] = 'Conseils';
$lang['advises.modules_management'] = '<a href="' . AdminModulesUrlBuilder::list_installed_modules()->rel() . '">Désactivez ou désinstallez les modules</a> que vous n\'utilisez pas pour économiser les ressources du site.';
$lang['advises.check_modules_authorizations'] = 'Vérifiez les autorisations d\'accès de tous vos modules et menus avant de mettre le site en ligne pour éviter que les visiteurs ou des utilisateurs non autorisés aient accès à des sections protégées du site.';
$lang['advises.disable_debug_mode'] = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Désactivez le mode debug</a> pour ne pas afficher les erreurs aux utilisateurs (les erreurs sont quand même logguées dans les <a href="' . AdminErrorsUrlBuilder::logged_errors()->rel() . '">Erreurs archivées</a>).';
$lang['advises.enable_url_rewriting'] = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Activez la réécriture des URL</a> pour que les URL de votre site soient plus lisibles (très utile pour le référencement).';
$lang['advises.enable_output_gz'] = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Activez la compression des pages</a> pour gagner en performance.';
$lang['advises.enable_apcu_cache'] = '<a href="' . AdminCacheUrlBuilder::configuration()->rel() . '">Activez le cache APCu</a> pour permettre de charger le cache en RAM sur le serveur et non sur le disque-dur et ainsi gagner d\'avantage en performance.';
$lang['advises.upgrade_php_version'] = 'Mettez à jour votre version PHP pour passer en 5.6 (qui est la dernière version stable) si votre hébergeur le permet.';
$lang['advises.save_database'] = 'Pensez à sauvegarder votre base de données régulièrement.';
$lang['advises.optimize_database_tables'] = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Activez l\'optimisation automatique des tables</a> ou optimisez de temps en temps vos tables dans le module <strong>Base de données</strong> (s\'il est installé) ou dans votre outil de gestion de base de donnée pour récupérer de la place perdue en base.';
$lang['advises.password_security'] = 'Augmentez la complexité et la longueur des mots de passe dans la <a href="' . AdminMembersUrlBuilder::configuration()->rel() . '">configuration des membres</a> pour renforcer la sécurité.';
?>
