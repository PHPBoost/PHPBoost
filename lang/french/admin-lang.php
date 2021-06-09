<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 02
 * @since       PHPBoost 3.0 - 2010 08 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

####################################################
#                     French                      #
####################################################

$lang['admin.administration']     = 'Administration';
$lang['admin.author']             = 'Auteur';
$lang['admin.authors']            = 'Auteurs';
$lang['admin.kernel']             = 'Noyau';
$lang['admin.warning']            = 'Attention';
$lang['admin.priority']           = 'Priorité';
$lang['admin.priority.very.high'] = 'Immédiat';
$lang['admin.priority.high']      = 'Urgent';
$lang['admin.priority.medium']    = 'Moyenne';
$lang['admin.priority.low']       = 'Faible';
$lang['admin.priority.very.low']  = 'Très faible';

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

// Content
$lang['admin.forbidden.module']                  = 'Modules interdits';
$lang['admin.comments.forbidden.module.clue']    = 'Sélectionnez les modules dans lesquels vous ne souhaitez pas activer les commentaires';
$lang['admin.notation.forbidden.module.clue']    = 'Sélectionnez les modules dans lesquels vous ne souhaitez pas activer la notation';
$lang['admin.new.content.forbidden.module.clue'] = 'Sélectionnez les modules dans lesquels vous ne souhaitez pas activer les tags de nouveau contenu';

// Errors
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

// Groups
$lang['admin.groups.management']    = 'Gestion des groupes';
$lang['admin.edit.group']           = 'Modifier le groupe';
$lang['admin.add.group']            = 'Ajouter un groupe';
$lang['admin.flood']                = 'Autorisation de flooder';
$lang['admin.pm.limit']             = 'Limite de messages privés';
$lang['admin.pm.limit.clue']        = 'Mettre -1 pour illimité';
$lang['admin.data.limit']           = 'Taille de l\'espace de stockage des fichiers';
$lang['admin.data.limit.clue']      = 'En Mo. Mettre -1 pour illimité';
$lang['admin.group.color']          = 'Couleur associée au groupe';
$lang['admin.delete.group.color']   = 'Supprimer la couleur associée au groupe';
$lang['admin.group.thumbnail']      = 'Image associée au groupe';
$lang['admin.group.thumbnail.clue'] = 'Mettre dans le dossier images/group/';
$lang['admin.add.group.member']     = 'Ajouter un membre au groupe';
$lang['admin.group.members']        = 'Membres du groupe';
$lang['admin.upload.thumbnail']     = 'Uploader une image';

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
