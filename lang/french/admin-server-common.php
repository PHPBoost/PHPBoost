<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 08 02
 * @since       PHPBoost 4.1 - 2015 07 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                     French                      #
####################################################

$lang['advises'] = 'Conseils';
$lang['advises.modules_management'] = '<a href="' . AdminModulesUrlBuilder::list_installed_modules()->rel() . '">Désactivez ou désinstallez les modules</a> que vous n\'utilisez pas pour économiser les ressources du site.';
$lang['advises.check_modules_authorizations'] = 'Vérifiez les autorisations d\'accès de tous vos modules et menus avant de mettre le site en ligne pour éviter que les visiteurs ou des utilisateurs non autorisés aient accès à des sections protégées du site.';
$lang['advises.disable_debug_mode'] = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Désactivez le mode debug</a> pour ne pas afficher les erreurs aux utilisateurs (les erreurs sont quand même logguées dans les <a href="' . AdminErrorsUrlBuilder::logged_errors()->rel() . '">Erreurs archivées</a>).';
$lang['advises.disable_maintenance'] = '<a href="' . AdminMaintainUrlBuilder::maintain()->rel() . '">Désactivez la maintenance</a> pour que les utilisateurs puissent afficher le site.';
$lang['advises.enable_url_rewriting'] = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Activez la réécriture des URL</a> pour que les URL de votre site soient plus lisibles (très utile pour le référencement).';
$lang['advises.enable_output_gz'] = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Activez la compression des pages</a> pour gagner en performance.';
$lang['advises.enable_apcu_cache'] = '<a href="' . AdminCacheUrlBuilder::configuration()->rel() . '">Activez le cache APCu</a> pour permettre de charger le cache en RAM sur le serveur et non sur le disque-dur et ainsi gagner d\'avantage en performance.';
$lang['advises.upgrade_php_version'] = 'La version PHP ' . ServerConfiguration::get_phpversion() . ' configurée sur votre serveur est obsolète, elle ne reçoit plus de mise à jour de sécurité et peut potentiellement contenir des vulnérabilités permettant à une personne mal intentionnée d\'attaquer votre site.
<br />Mettez à jour votre version PHP pour passer en ' . ServerConfiguration::RECOMMENDED_PHP_VERSION . ' minimum si votre hébergeur le permet, les nouvelles versions sont plus rapides et sécurisées.';
$lang['advises.save_database'] = 'Pensez à sauvegarder votre base de données régulièrement.';
$lang['advises.optimize_database_tables'] = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Activez l\'optimisation automatique des tables</a> ou optimisez de temps en temps vos tables dans le module <strong>Base de données</strong> (s\'il est installé) ou dans votre outil de gestion de base de données pour récupérer de la place perdue en base.';
$lang['advises.password_security'] = 'Augmentez la complexité et la longueur des mots de passe dans la <as href="' . AdminMembersUrlBuilder::configuration()->rel() . '">configuration des membres</a> pour renforcer la sécurité.';
?>
