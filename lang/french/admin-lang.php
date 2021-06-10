<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 10
 * @since       PHPBoost 3.0 - 2010 08 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

####################################################
#                     French                      #
####################################################

$lang['admin.administration']     = 'Administration';
$lang['admin.kernel']             = 'Noyau';
$lang['admin.warning']            = 'Attention';
$lang['admin.priority']           = 'Priorité';
$lang['admin.priority.very.high'] = 'Immédiat';
$lang['admin.priority.high']      = 'Urgent';
$lang['admin.priority.medium']    = 'Moyenne';
$lang['admin.priority.low']       = 'Faible';
$lang['admin.priority.very.low']  = 'Très faible';

// Advice
$lang['admin.advice'] = 'Conseils';
$lang['admin.advice.modules.management']           = '<a href="' . AdminModulesUrlBuilder::list_installed_modules()->rel() . '">Désactivez ou désinstallez les modules</a> que vous n\'utilisez pas pour économiser les ressources du site.';
$lang['admin.advice.check.modules.authorizations'] = 'Vérifiez les autorisations d\'accès de tous vos modules et menus avant de mettre le site en ligne pour éviter que les visiteurs ou des utilisateurs non autorisés aient accès à des sections protégées du site.';
$lang['admin.advice.disable.debug.mode']           = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Désactivez le mode debug</a> pour ne pas afficher les erreurs aux utilisateurs (les erreurs sont quand même logguées dans les <a href="' . AdminErrorsUrlBuilder::logged_errors()->rel() . '">Erreurs archivées</a>).';
$lang['admin.advice.disable.maintenance']          = '<a href="' . AdminMaintainUrlBuilder::maintain()->rel() . '">Désactivez la maintenance</a> pour que les utilisateurs puissent afficher le site.';
$lang['admin.advice.enable.url.rewriting']         = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Activez la réécriture des URL</a> pour que les URL de votre site soient plus lisibles (très utile pour le référencement).';
$lang['admin.advice.enable.output.gz']             = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Activez la compression des pages</a> pour gagner en performance.';
$lang['admin.advice.enable.apcu.cache']            = '<a href="' . AdminCacheUrlBuilder::configuration()->rel() . '">Activez le cache APCu</a> pour permettre de charger le cache en RAM sur le serveur et non sur le disque-dur et ainsi gagner d\'avantage en performance.';
$lang['admin.advice.save.database']                = 'Pensez à sauvegarder votre base de données régulièrement.';
$lang['admin.advice.optimize.database.tables']     = '<a href="' . AdminConfigUrlBuilder::advanced_config()->rel() . '">Activez l\'optimisation automatique des tables</a> ou optimisez de temps en temps vos tables dans le module <strong>Base de données</strong> (s\'il est installé) ou dans votre outil de gestion de base de données pour récupérer de la place perdue en base.';
$lang['admin.advice.password.security']            = 'Augmentez la complexité et la longueur des mots de passe dans la <a href="' . AdminMembersUrlBuilder ::configuration()->rel() . '">configuration des membres</a> pour renforcer la sécurité.';
$lang['admin.advice.upgrade.php.version']          = '
    La version PHP ' . ServerConfiguration::get_phpversion() . ' configurée sur votre serveur est obsolète, elle ne reçoit plus de mise à jour de sécurité et peut potentiellement contenir des vulnérabilités permettant à une personne mal intentionnée d\'attaquer votre site.
    <br />Mettez à jour votre version PHP pour passer en ' . ServerConfiguration::RECOMMENDED_PHP_VERSION . ' minimum si votre hébergeur le permet, les nouvelles versions sont plus rapides et sécurisées.
';

// Alerts
$lang['admin.alerts']               = 'Alertes';
$lang['admin.alerts.list']          = 'Liste des alertes';
$lang['admin.no.unread.alert']      = 'Aucune alerte en attente';
$lang['admin.unread.alerts']        = 'Des alertes non traitées sont en attente.';
$lang['admin.no.alert']             = 'Aucune alerte existante';
$lang['admin.display.all.alerts']   = 'Voir toutes les alertes';
$lang['admin.fix.alert']            = 'Passer l\'alerte en réglée';
$lang['admin.unfix.alert']          = 'Passer l\'alerte en non réglée';
$lang['admin.warning.delete.alert'] = 'Etes-vous sûr de vouloir supprimer cette alerte ?';
$lang['admin.unread.alerts']        = 'Des alertes non traitées sont en attente.';
$lang['admin.see.all.alerts']       = 'Voir toutes les alertes';

// Cache
$lang['admin.empty.cache'] = 'Vider le cache';

// Configuration
    // General
$lang['admin.general.configuration'] = 'Configuration générale';

// Content
$lang['admin.forbidden.module']                  = 'Modules interdits';
$lang['admin.comments.forbidden.module.clue']    = 'Sélectionnez les modules dans lesquels vous ne souhaitez pas activer les commentaires';
$lang['admin.notation.forbidden.module.clue']    = 'Sélectionnez les modules dans lesquels vous ne souhaitez pas activer la notation';
$lang['admin.new.content.forbidden.module.clue'] = 'Sélectionnez les modules dans lesquels vous ne souhaitez pas activer les tags de nouveau contenu';

// Errors lists
$lang['admin.errors'] = 'Erreurs';
$lang['admin.clear.list'] = 'Vider la liste';
$lang['admin.warning.clear'] = 'Effacer toutes les erreurs ?';
    // Logged
$lang['admin.logged.errors'] = 'Erreurs archivées';
$lang['admin.logged.errors.list'] = 'Liste des erreurs archivées';
    // 404
$lang['admin.404.errors'] = 'Erreurs 404';
$lang['admin.404.errors.list'] = 'Liste des erreurs 404';
$lang['admin.404.requested.url'] = 'Url demandée';
$lang['admin.404.from.url'] = 'Url de provenance';

// Index
$lang['admin.quick.access']        = 'Accès rapide';
$lang['admin.welcome.title']       = 'Bienvenue sur le tableau de bord d\'administration de votre site';
$lang['admin.welcome.description'] = 'Ce tableau de bord vous permet de gérer le contenu et la configuration de votre site<br />La page d\'accueil recense les actions les plus courantes<br />Prenez le temps de lire les conseils afin d\'optimiser la sécurité de votre site';
$lang['admin.website.management']  = 'Administrer le site';
$lang['admin.customize.website']   = 'Personnaliser le site';
$lang['admin.add.content']         = 'Ajouter du contenu';
$lang['admin.customize.theme']     = 'Personnaliser un thème';
$lang['admin.add.article']         = 'Ajouter un article';
$lang['admin.add.news']            = 'Ajouter une actualité';
$lang['admin.last.comments']       = 'Derniers commentaires';
$lang['admin.writing.pad']         = 'Bloc-note';
$lang['admin.writing.pad.clue']    = 'Cet emplacement est destiné à la saisie vos notes personnelles.';

// Maintenance
$lang['admin.maintenance']         = 'Maintenance';
$lang['admin.maintenance.title']   = 'Site en maintenance';
$lang['admin.maintenance.content'] = 'Le site est actuellement en maintenance. Merci de votre patience.';
$lang['admin.maintenance.delay']   = 'Délai estimé avant réouverture du site :';
$lang['admin.disable.maintenance'] = 'Désactiver la maintenance';
    // Form
$lang['admin.maintenance.type']                   = 'Mettre le site en maintenance';
$lang['admin.maintenance.type.during']            = 'Moins d\'une journée';
$lang['admin.maintenance.type.until']             = 'Plusieurs jours';
$lang['admin.maintenance.type.unlimited']         = 'Pour une durée non spécifiée';
$lang['admin.maintenance.display.duration']       = 'Afficher la durée de la maintenance';
$lang['admin.maintenance.admin.display.duration'] = 'Afficher la durée de la maintenance à l\'administrateur';
$lang['admin.maintenance.text']                   = 'Texte à afficher lorsque la maintenance du site est en cours';
$lang['admin.maintenance.authorization']          = 'Autorisation d\'accès au site durant la maintenance';

// Updates
$lang['admin.updates']                = 'Mises à jour';
$lang['admin.available.updates']      = 'Mises à jour disponibles';
$lang['admin.available.updates.clue'] = 'Des mises à jours sont disponibles.<br />Veuillez les effectuer au plus vite.';
$lang['admin.available.version']      = 'Le %1$s %2$s est disponible dans sa version %3$s';
$lang['admin.kernel.update']          = 'PHPBoost est disponible dans sa nouvelle version %s';
$lang['admin.download.app']           = 'Téléchargement';
$lang['admin.download.pack']          = 'Pack complet';
$lang['admin.update.pack']            = 'Pack de mise à jour';
$lang['admin.new.features']           = 'Nouvelles Fonctionnalités';
$lang['admin.improvements']           = 'Améliorations';
$lang['admin.security.improvements']  = 'Améliorations de sécurité';
$lang['admin.fixed.bugs']             = 'Corrections de bugs';
$lang['admin.details']                = 'Détails';
$lang['admin.more.details']           = 'Plus de détails';
$lang['admin.download.full.pack']     = 'Télécharger le pack complet';
$lang['admin.download.update.pack']   = 'Télécharger le pack de mise à jour';
$lang['admin.no.available.update']    = 'Aucune mise à jour n\'est disponible pour l\'instant.';
$lang['admin.updates.check']          = 'Vérifier la présence de mises à jour';
$lang['admin.php.version']            = '
    Impossible de vérifier la présence de mise à jour.<br />
    Veuillez utiliser la version %s ou ultérieure de PHP.<br />
    Si vous ne pouvez utiliser PHP5, veuillez vérifier la présence de ces mises à jour sur notre <a href="https://www.phpboost.com">site officiel</a>.
';

?>
