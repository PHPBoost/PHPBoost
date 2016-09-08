<?php
/*##################################################
 *                              common.php
 *                            -------------------
 *   begin                : November 09, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
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
# French                                           #
####################################################

//Titre du module
$lang['module_title'] = 'Rapport de bugs';

//Messages divers
$lang['notice.no_one'] = 'Personne';
$lang['notice.none'] = 'Aucun';
$lang['notice.none_e'] = 'Aucune';
$lang['notice.no_bug'] = 'Aucun bug n\'a été déclaré';
$lang['notice.no_bug_solved'] = 'Aucun bug n\'a été corrigé';
$lang['notice.no_bug_fixed'] = 'Aucun bug n\'a été corrigé dans cette version';
$lang['notice.no_bug_in_progress'] = 'Aucun bug n\'est en cours de correction dans cette version';
$lang['notice.no_bug_matching_filter'] = 'Aucun bug ne correspond au filtre sélectionné';
$lang['notice.no_bug_matching_filters'] = 'Aucun bug ne correspond aux filtres sélectionnés';
$lang['notice.no_version_roadmap'] = 'Veuillez ajouter au moins une version dans la configuration pour afficher la feuille de route.';
$lang['notice.no_history'] = 'Ce bug n\'a aucun historique';
$lang['notice.contents_update'] = 'Mise à jour du contenu';
$lang['notice.new_comment'] = 'Nouveau commentaire';
$lang['notice.reproduction_method_update'] = 'Mise à jour de la méthode de reproduction';
$lang['notice.not_defined'] = 'Non défini';
$lang['notice.not_defined_e_date'] = 'Date non définie';

//Actions
$lang['actions'] = 'Actions';
$lang['actions.add'] = 'Déclarer un bug';
$lang['actions.history'] = 'Historique';
$lang['actions.change_status'] = 'Changer l\'état du bug';
$lang['actions.confirm.del_default_value'] = 'Etes-vous sûr de vouloir la valeur par défaut ?';
$lang['actions.confirm.del_filter'] = 'Etes-vous sûr de vouloir supprimer ce filtre ?';

//Titres
$lang['titles.add'] = 'Déclaration d\'un bug';
$lang['titles.add_version'] = 'Ajout d\'une nouvelle version';
$lang['titles.add_type'] = 'Ajouter un nouveau type de bug';
$lang['titles.add_category'] = 'Ajouter une nouvelle catégorie';
$lang['titles.edit'] = 'Edition du bug';
$lang['titles.change_status'] = 'Changement d\'état du bug';
$lang['titles.delete'] = 'Suppression du bug';
$lang['titles.history'] = 'Historique du bug';
$lang['titles.detail'] = 'Bug';
$lang['titles.roadmap'] = 'Feuille de route';
$lang['titles.bugs_infos'] = 'Informations sur le bug';
$lang['titles.stats'] = 'Statistiques';
$lang['titles.bugs_treatment_state'] = 'Etat du traitement du bug';
$lang['titles.versions'] = 'Versions';
$lang['titles.types'] = 'Types';
$lang['titles.categories'] = 'Catégories';
$lang['titles.priorities'] = 'Priorités';
$lang['titles.severities'] = 'Niveaux';
$lang['titles.admin.authorizations.manage'] = 'Gérer les autorisations';
$lang['titles.admin.module_config'] = 'Configuration du module bugtracker';
$lang['titles.admin.module_authorizations'] = 'Configuration des autorisations du module bugtracker';
$lang['titles.choose_version'] = 'Version à afficher';
$lang['titles.solved'] = 'Bugs résolus';
$lang['titles.unsolved'] = 'Bugs non-résolus';
$lang['titles.contents_value_title'] = 'Description par défaut d\'un bug';
$lang['titles.contents_value'] = 'Description par défaut';
$lang['titles.filter'] = 'Filtre';
$lang['titles.filters'] = 'Filtres';
$lang['titles.informations'] = 'Informations';
$lang['titles.version_informations'] = 'Informations sur la version';

//Libellés
$lang['labels.fields.id'] = 'ID';
$lang['labels.fields.title'] = 'Titre';
$lang['labels.fields.contents'] = 'Description';
$lang['labels.fields.submit_date'] = 'Détecté le';
$lang['labels.fields.fix_date'] = 'Corrigé le';
$lang['labels.fields.status'] = 'Etat';
$lang['labels.fields.type'] = 'Type';
$lang['labels.fields.category'] = 'Catégorie';
$lang['labels.fields.reproductible'] = 'Reproductible';
$lang['labels.fields.reproduction_method'] = 'Méthode de reproduction';
$lang['labels.fields.severity'] = 'Niveau';
$lang['labels.fields.priority'] = 'Priorité';
$lang['labels.fields.progress'] = 'Avancement';
$lang['labels.fields.detected_in'] = 'Détecté dans la version';
$lang['labels.fields.fixed_in'] = 'Corrigé dans la version';
$lang['labels.fields.assigned_to_id'] = 'Assigné à';
$lang['labels.fields.updater_id'] = 'Modifié par';
$lang['labels.fields.update_date'] = 'Modifié le';
$lang['labels.fields.updated_field'] = 'Champ modifié';
$lang['labels.fields.old_value'] = 'Ancienne valeur';
$lang['labels.fields.new_value'] = 'Nouvelle valeur';
$lang['labels.fields.change_comment'] = 'Commentaire';
$lang['labels.fields.version'] = 'Version';
$lang['labels.fields.version_detected'] = 'Version détectée';
$lang['labels.fields.version_fixed'] = 'Version corrigée';
$lang['labels.fields.version_release_date'] = 'Date de parution';
$lang['labels.page'] = 'Page';
$lang['labels.color'] = 'Couleur';
$lang['labels.number_fixed'] = 'Nombre de bugs corrigés';
$lang['labels.number_in_progress'] = 'Nombre de bugs en cours de correction';
$lang['labels.top_posters'] = 'Top posteurs';
$lang['labels.login'] = 'Pseudo';
$lang['labels.default'] = 'Par défaut';
$lang['labels.default_value'] = 'Valeur par défaut';
$lang['labels.del_default_value'] = 'Supprimer la valeur par défaut';
$lang['labels.type_mandatory'] = 'Section <b>Type</b> obligatoire ?';
$lang['labels.category_mandatory'] = 'Section <b>Catégorie</b> obligatoire ?';
$lang['labels.severity_mandatory'] = 'Section <b>Niveau</b> obligatoire ?';
$lang['labels.priority_mandatory'] = 'Section <b>Priorité</b> obligatoire ?';
$lang['labels.detected_in_mandatory'] = 'Section <b>Détecté dans la version</b> obligatoire ?';
$lang['labels.detected'] = 'Détecté';
$lang['labels.detected_in'] = 'Détecté dans';
$lang['labels.fixed'] = 'Corrigé';
$lang['labels.fix_bugs_per_version'] = 'Nombre de bugs corrigés par version';
$lang['labels.not_yet_fixed'] = 'Pas encore corrigé';
$lang['labels.alert_fix'] = 'Passer l\'alerte en réglé';
$lang['labels.alert_delete'] = 'Supprimer l\'alerte';
$lang['labels.save_filters'] = 'Sauvegarder les filtres';
$lang['labels.version_name'] = 'Nom de la version';

//Etats
$lang['status.new'] = 'Nouveau';
$lang['status.pending'] = 'En attente';
$lang['status.assigned'] = 'Assigné';
$lang['status.in_progress'] = 'En cours';
$lang['status.fixed'] = 'Corrigé';
$lang['status.reopen'] = 'Réouvert';
$lang['status.rejected'] = 'Rejeté';

//Explications
$lang['explain.contents'] = 'Détails qui seront utiles pour la résolution du bug';
$lang['explain.roadmap'] = 'Permet d\'afficher la liste des bugs corrigés pour chaque version. Affichée s\'il y a au moins une version dans la liste.';
$lang['explain.type'] = 'Type des demandes. Exemples : Anomalie, Demande d\'évolution...';
$lang['explain.category'] = 'Catégorie des demandes. Exemples : Noyau, Module...';
$lang['explain.severity'] = 'Niveau des demandes. Exemples : Mineur, Majeur, Critique...';
$lang['explain.priority'] = 'Priorité des demandes. Exemples : Basse, Normale, Elevée...';
$lang['explain.version'] = 'Liste des versions du produit.';
$lang['explain.remarks'] = 'Remarques : <br />
- Si la liste est vide, cette option ne sera pas visible lors de la signalisation d\'un bug<br />
- Si la liste ne contient qu\'une seule valeur, cette option ne sera pas non plus visible et sera attribuée par défaut au bug<br /><br />';
$lang['explain.contents_value'] = 'Entrez ci-dessous la description par défaut à afficher lors de l\'ouverture d\'un nouveau bug. Laissez vide pour que la description ne soit pas pré-remplie.';
$lang['explain.delete_comment'] = 'Facultatif. Permet d\'ajouter un commentaire dans le Message Privé de suppression du bug.';
$lang['explain.change_status_select_fix_version'] = 'Vous pouvez sélectionner une version pour que le bug soit présent dans la feuille de route.';
$lang['explain.change_status_comments_message'] = 'Facultatif. Permet de commenter le bug et d\'ajouter ce commentaire dans le Message Privé si son envoi est activé.';

//MP
$lang['pm.with_comment'] = '<br />
<br />
Commentaire :<br />
:comment';
$lang['pm.edit_fields'] = '<br />
<br />
:fields';
$lang['pm.bug_link'] = '<br />
<br />
<a href=":link">Lien vers le bug</a>';

$lang['pm.assigned.title'] = '[Rapport de bugs] Le bug #:id vous a été assigné';
$lang['pm.assigned.contents'] = ':author vous a assigné le bug #:id.';

$lang['pm.comment.title'] = '[Rapport de bugs] Le bug #:id a été commenté';
$lang['pm.comment.contents'] = ':author a ajouté un commentaire au bug #:id.';

$lang['pm.edit.title'] = '[Rapport de bugs] Le bug #:id a été modifié';
$lang['pm.edit.contents'] = ':author a modifié les champs suivants dans le bug #:id :';

$lang['pm.fixed.title'] = '[Rapport de bugs] Le bug #:id a été corrigé';
$lang['pm.fixed.contents'] = ':author a corrigé le bug #:id.';

$lang['pm.reopen.title'] = '[Rapport de bugs] Le bug #:id a été ré-ouvert';
$lang['pm.reopen.contents'] = ':author a ré-ouvert le bug #:id.';

$lang['pm.rejected.title'] = '[Rapport de bugs] Le bug #:id a été rejeté';
$lang['pm.rejected.contents'] = ':author a rejeté le bug #:id.';

$lang['pm.pending.title'] = '[Rapport de bugs] Le bug #:id a été mis en attente';
$lang['pm.pending.contents'] = ':author a mis en attente le bug #:id.';

$lang['pm.in_progress.title'] = '[Rapport de bugs] Le bug #:id est en cours de correction';
$lang['pm.in_progress.contents'] = ':author a mis le bug #:id en cours de correction.';

$lang['pm.delete.title'] = '[Rapport de bugs] Le bug #:id a été supprimé';
$lang['pm.delete.contents'] = ':author a supprimé le bug #:id.';

//Configuration
$lang['config.rejected_bug_color_label'] = 'Couleur de la ligne d\'un bug Rejeté';
$lang['config.fixed_bug_color_label'] = 'Couleur de la ligne d\'un bug Fermé';
$lang['config.enable_roadmap'] = 'Activer la feuille de route';
$lang['config.enable_stats'] = 'Activer les statistiques';
$lang['config.enable_stats_top_posters'] = 'Afficher la liste des membres qui ont posté le plus de bugs';
$lang['config.stats_top_posters_number'] = 'Nombre d\'utilisateurs affichés';
$lang['config.progress_bar'] = 'Barre de progression';
$lang['config.enable_progress_bar'] = 'Afficher la barre de progression des bugs';
$lang['config.restrict_display_to_own_elements_enabled'] = 'Restreindre l\'affichage des bugs';
$lang['config.restrict_display_to_own_elements_enabled.explain'] = 'N\'affiche que les bugs déclarés par l\'utilisateur s\'il n\'a pas les droits de modération';
$lang['config.status.new'] = 'Pourcentage d\'un Nouveau bug';
$lang['config.status.pending'] = 'Pourcentage d\'un bug En attente';
$lang['config.status.assigned'] = 'Pourcentage d\'un bug Assigné';
$lang['config.status.in_progress'] = 'Pourcentage d\'un bug En cours';
$lang['config.status.fixed'] = 'Pourcentage d\'un bug Corrigé';
$lang['config.status.reopen'] = 'Pourcentage d\'un bug Réouvert';
$lang['config.status.rejected'] = 'Pourcentage d\'un bug Rejeté';
$lang['config.admin_alerts'] = 'Alertes administrateur';
$lang['config.enable_admin_alerts'] = 'Activer les alertes administrateur';
$lang['config.admin_alerts_levels'] = 'Niveau du bug pour déclencher l\'alerte';
$lang['config.admin_alerts_fix_action'] = 'Action à la fermeture d\'un bug';
$lang['config.pm'] = 'Messages Privés';
$lang['config.enable_pm'] = 'Activer l\'envoi de Messages Privés (MP)';
$lang['config.enable_pm.comment'] = 'Envoyer un MP lors de l\'ajout d\'un nouveau commentaire';
$lang['config.enable_pm.in_progress'] = 'Envoyer un MP lors du passage à l\'état En cours';
$lang['config.enable_pm.fix'] = 'Envoyer un MP lors de la correction d\'un bug';
$lang['config.enable_pm.pending'] = 'Envoyer un MP lors de la mise en attente d\'un bug';
$lang['config.enable_pm.assign'] = 'Envoyer un MP lors de l\'assignation d\'un bug';
$lang['config.enable_pm.edit'] = 'Envoyer un MP lors de l\'édition d\'un bug';
$lang['config.enable_pm.reject'] = 'Envoyer un MP lors du rejet d\'un bug';
$lang['config.enable_pm.reopen'] = 'Envoyer un MP lors de la réouverture d\'un bug';
$lang['config.enable_pm.delete'] = 'Envoyer un MP lors de la suppression d\'un bug';
$lang['config.delete_parameter.type'] = 'Suppression d\'un type';
$lang['config.delete_parameter.category'] = 'Suppression d\'une catégorie';
$lang['config.delete_parameter.version'] = 'Suppression d\'une version';
$lang['config.delete_parameter.description.type'] = 'Vous êtes sur le point de supprimer un type de bug. Deux solutions s\'offrent à vous. Vous pouvez soit affecter un autre type à l\'ensemble des bugs associés à ce type, soit supprimer l\'ensemble des bugs associés à ce type. Si aucune action n\'est choisie sur cette page, le type de bug sera supprimé et les bugs conservés (en supprimant leur type). <strong>Attention, cette action est irréversible !</strong>';
$lang['config.delete_parameter.description.category'] = 'Vous êtes sur le point de supprimer une catégorie de bug. Deux solutions s\'offrent à vous. Vous pouvez soit affecter une autre catégorie à l\'ensemble des bugs associés à cette catégorie, soit supprimer l\'ensemble des bugs associés à cette catégorie. Si aucune action n\'est choisie sur cette page, la catégorie sera supprimée et les bugs conservés (en supprimant leur catégorie). <strong>Attention, cette action est irréversible !</strong>';
$lang['config.delete_parameter.description.version'] = 'Vous êtes sur le point de supprimer une version. Deux solutions s\'offrent à vous. Vous pouvez soit affecter une autre version à l\'ensemble des bugs associés à cette version, soit supprimer l\'ensemble des bugs associés à cette version. Si aucune action n\'est choisie sur cette page, la version sera supprimée et les bugs conservés (en supprimant leur version). <strong>Attention, cette action est irréversible !</strong>';
$lang['config.delete_parameter.move_into_another'] = 'Déplacer les bugs associés dans :';
$lang['config.delete_parameter.parameter_and_content.type'] = 'Supprimer le type de bug et tous les bugs associés';
$lang['config.delete_parameter.parameter_and_content.category'] = 'Supprimer la catégorie et tous les bugs associés';
$lang['config.delete_parameter.parameter_and_content.version'] = 'Supprimer la version et tous les bugs associés';
$lang['config.display_type_column'] = 'Afficher la colonne <b>Type</b> dans les tableaux';
$lang['config.display_category_column'] = 'Afficher la colonne <b>Catégorie</b> dans les tableaux';
$lang['config.display_priority_column'] = 'Afficher la colonne <b>Priorité</b> dans les tableaux';
$lang['config.display_detected_in_column'] = 'Afficher la colonne <b>Détecté dans</b> dans les tableaux';

//Autorisations
$lang['config.auth.read'] = 'Autorisation d\'afficher la liste des bugs';
$lang['config.auth.create'] = 'Autorisation de signaler un bug';
$lang['config.auth.create_advanced'] = 'Autorisation avancée pour signaler un bug';
$lang['config.auth.create_advanced_explain'] = 'Permet de choisir le niveau et la priorité du bug';
$lang['config.auth.moderate'] = 'Autorisation de modération des bugs';

//Erreurs
$lang['error.e_unexist_bug'] = 'Ce bug n\'existe pas';
$lang['error.e_unexist_parameter'] = 'Ce paramètre n\'existe pas';
$lang['error.e_unexist_type'] = 'Ce type n\'existe pas';
$lang['error.e_unexist_category'] = 'Cette catégorie n\'existe pas';
$lang['error.e_unexist_severity'] = 'Ce niveau n\'existe pas';
$lang['error.e_unexist_priority'] = 'Cette priorité n\'existe pas';
$lang['error.e_unexist_version'] = 'Cette version n\'existe pas';
$lang['error.e_already_rejected_bug'] = 'Ce bug est déjà rejeté';
$lang['error.e_already_reopen_bug'] = 'Ce bug est déjà ré-ouvert';
$lang['error.e_already_fixed_bug'] = 'Ce bug est déjà corrigé';
$lang['error.e_already_pending_bug'] = 'Ce bug est déjà en attente';
$lang['error.e_status_not_changed'] = 'Veuillez changer l\'état du bug';

//Succès
$lang['success.add'] = 'Le bug #:id a été ajouté';
$lang['success.edit'] = 'Le bug #:id a été modifié';
$lang['success.new'] = 'Le bug a été passé à l\'état <b>Nouveau</b>';
$lang['success.fixed'] = 'Le bug a été corrigé';
$lang['success.in_progress'] = 'Le bug est en cours de résolution';
$lang['success.delete'] = 'Le bug #:id a été supprimé';
$lang['success.rejected'] = 'Le bug a été rejeté';
$lang['success.reopen'] = 'Le bug a été ré-ouvert';
$lang['success.assigned'] = 'Le bug a été assigné';
$lang['success.pending'] = 'Le bug a été mis en attente';
$lang['success.add.filter'] = 'Le filtre a été ajouté';
$lang['success.add.details'] = '<p>Votre demande sera traitée dans les plus brefs délais. Un retour vous sera fait si nécessaire en commentaire (vous en recevrez également une copie dans votre messagerie privée).</p><p>Merci d\'avoir participé à la vie du site !</p>';

//Warning
$lang['warning.restrict_display_to_own_elements_enabled'] = 'Seuls les bugs que vous avez déclaré sont affichés dans la liste.';
?>
