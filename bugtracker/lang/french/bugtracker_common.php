<?php
/*##################################################
 *                              bugtracker_common.php
 *                            -------------------
 *   begin                : November 09, 2012
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

//Titre du module
$lang['bugs.module_title'] = 'Rapport de bugs';

//Messages divers
$lang['bugs.notice.no_one'] = 'Personne';
$lang['bugs.notice.none'] = 'Aucun';
$lang['bugs.notice.none_e'] = 'Aucune';
$lang['bugs.notice.no_bug'] = 'Aucun bug n\'a été déclaré';
$lang['bugs.notice.no_bug_solved'] = 'Aucun bug n\'a été corrigé';
$lang['bugs.notice.no_bug_fixed'] = 'Aucun bug n\'a été corrigé dans cette version';
$lang['bugs.notice.no_bug_in_progress'] = 'Aucun bug n\'est en cours de correction dans cette version';
$lang['bugs.notice.no_bug_matching_filter'] = 'Aucun bug ne correspond au filtre sélectionné';
$lang['bugs.notice.no_bug_matching_filters'] = 'Aucun bug ne correspond aux filtres sélectionnés';
$lang['bugs.notice.no_version'] = 'Aucune version existante';
$lang['bugs.notice.no_type'] = 'Aucun type n\'a été déclaré';
$lang['bugs.notice.no_category'] = 'Aucune catégorie n\'a été déclarée';
$lang['bugs.notice.no_priority'] = 'Aucune priorité n\'a été déclarée';
$lang['bugs.notice.no_severity'] = 'Aucun niveau n\'a été déclaré';
$lang['bugs.notice.no_history'] = 'Ce bug n\'a aucun historique';
$lang['bugs.notice.contents_update'] = 'Mise à jour du contenu';
$lang['bugs.notice.new_comment'] = 'Nouveau commentaire';
$lang['bugs.notice.reproduction_method_update'] = 'Mise à jour de la méthode de reproduction';
$lang['bugs.notice.not_defined'] = 'Non défini';
$lang['bugs.notice.not_defined_e_date'] = 'Date non définie';
$lang['bugs.notice.joker'] = 'Utilisez * pour joker';

//Actions
$lang['bugs.actions'] = 'Actions';
$lang['bugs.actions.add'] = 'Nouveau bug';
$lang['bugs.actions.delete'] = 'Supprimer le bug';
$lang['bugs.actions.edit'] = 'Editer le bug';
$lang['bugs.actions.history'] = 'Historique du bug';
$lang['bugs.actions.reject'] = 'Rejeter le bug';
$lang['bugs.actions.reopen'] = 'Ré-ouvrir le bug';
$lang['bugs.actions.confirm.del_bug'] = 'Etes-vous sûr de vouloir supprimer ce rapport de la liste ? (tout l\'historique associé sera supprimé)';
$lang['bugs.actions.confirm.del_version'] = 'Etes-vous sûr de vouloir supprimer cette version ?';
$lang['bugs.actions.confirm.del_type'] = 'Etes-vous sûr de vouloir supprimer ce type ?';
$lang['bugs.actions.confirm.del_category'] = 'Etes-vous sûr de vouloir supprimer cette catégorie ?';
$lang['bugs.actions.confirm.del_priority'] = 'Etes-vous sûr de vouloir supprimer cette priorité ?';
$lang['bugs.actions.confirm.del_severity'] = 'Etes-vous sûr de vouloir supprimer ce niveau ?';
$lang['bugs.actions.confirm.del_default_value'] = 'Etes-vous sûr de vouloir la valeur par défaut ?';
$lang['bugs.actions.confirm.del_filter'] = 'Etes-vous sûr de vouloir supprimer ce filtre ?';

//Titres
$lang['bugs.titles.add_bug'] = 'Nouveau bug';
$lang['bugs.titles.add_version'] = 'Ajout d\'une nouvelle version';
$lang['bugs.titles.add_type'] = 'Ajout d\'un nouveau type';
$lang['bugs.titles.add_category'] = 'Ajout d\'une nouvelle catégorie';
$lang['bugs.titles.add_priority'] = 'Ajout d\'une nouvelle priorité';
$lang['bugs.titles.add_severity'] = 'Ajout d\'un nouveau niveau';
$lang['bugs.titles.edit_bug'] = 'Edition du bug';
$lang['bugs.titles.history_bug'] = 'Historique du bug';
$lang['bugs.titles.history'] = 'Historique';
$lang['bugs.titles.view_bug'] = 'Bug';
$lang['bugs.titles.roadmap'] = 'Feuille de route';
$lang['bugs.titles.bugs_infos'] = 'Informations sur le bug';
$lang['bugs.titles.bugs_stats'] = 'Statistiques';
$lang['bugs.titles.bugs_treatment'] = 'Traitement du bug';
$lang['bugs.titles.bugs_treatment_state'] = 'Etat du traitement du bug';
$lang['bugs.titles.versions'] = 'Versions';
$lang['bugs.titles.types'] = 'Types';
$lang['bugs.titles.categories'] = 'Catégories';
$lang['bugs.titles.priorities'] = 'Priorités';
$lang['bugs.titles.severities'] = 'Niveaux';
$lang['bugs.titles.admin.config'] = 'Configuration';
$lang['bugs.titles.admin.authorizations'] = 'Autorisations';
$lang['bugs.titles.admin.module_config'] = 'Configuration du module bugtracker';
$lang['bugs.titles.admin.module_authorizations'] = 'Configuration des autorisations du module bugtracker';
$lang['bugs.titles.choose_version'] = 'Version à afficher';
$lang['bugs.titles.solved_bugs'] = 'Bugs résolus';
$lang['bugs.titles.unsolved_bugs'] = 'Bugs non-résolus';
$lang['bugs.titles.contents_value_title'] = 'Description par défaut d\'un bug';
$lang['bugs.titles.contents_value'] = 'Description par défaut';
$lang['bugs.titles.filter'] = 'Filtre';
$lang['bugs.titles.filters'] = 'Filtres';
$lang['bugs.titles.legend'] = 'Légende';
$lang['bugs.titles.informations'] = 'Informations';
$lang['bugs.titles.version_informations'] = 'Informations sur la version';

//Libellés
$lang['bugs.labels.fields.id'] = 'ID';
$lang['bugs.labels.fields.title'] = 'Titre';
$lang['bugs.labels.fields.contents'] = 'Description';
$lang['bugs.labels.fields.author_id'] = 'Détecté par';
$lang['bugs.labels.fields.submit_date'] = 'Détecté le';
$lang['bugs.labels.fields.fix_date'] = 'Corrigé le';
$lang['bugs.labels.fields.status'] = 'Etat';
$lang['bugs.labels.fields.type'] = 'Type';
$lang['bugs.labels.fields.category'] = 'Catégorie';
$lang['bugs.labels.fields.reproductible'] = 'Reproductible';
$lang['bugs.labels.fields.reproduction_method'] = 'Méthode de reproduction';
$lang['bugs.labels.fields.severity'] = 'Niveau';
$lang['bugs.labels.fields.priority'] = 'Priorité';
$lang['bugs.labels.fields.detected_in'] = 'Détecté dans la version';
$lang['bugs.labels.fields.fixed_in'] = 'Corrigé dans la version';
$lang['bugs.labels.fields.assigned_to_id'] = 'Assigné à';
$lang['bugs.labels.fields.updater_id'] = 'Modifié par';
$lang['bugs.labels.fields.update_date'] = 'Modifié le';
$lang['bugs.labels.fields.updated_field'] = 'Champ modifié';
$lang['bugs.labels.fields.old_value'] = 'Ancienne valeur';
$lang['bugs.labels.fields.new_value'] = 'Nouvelle valeur';
$lang['bugs.labels.fields.change_comment'] = 'Commentaire';
$lang['bugs.labels.fields.version'] = 'Version';
$lang['bugs.labels.fields.version_detected_in'] = 'Afficher dans la liste "Détecté dans la version"';
$lang['bugs.labels.fields.version_fixed_in'] = 'Afficher dans la liste "Corrigé dans la version"';
$lang['bugs.labels.fields.version_detected'] = 'Version détectée';
$lang['bugs.labels.fields.version_fixed'] = 'Version corrigée';
$lang['bugs.labels.fields.version_release_date'] = 'Date de sortie';
$lang['bugs.labels.fields.version_release_date.explain'] = 'Format : jj/mm/aaaa';
$lang['bugs.labels.page'] = 'Page';
$lang['bugs.labels.color'] = 'Couleur';
$lang['bugs.labels.number'] = 'Nombre de bugs';
$lang['bugs.labels.number_fixed'] = 'Nombre de bugs corrigés';
$lang['bugs.labels.number_in_progress'] = 'Nombre de bugs en cours de correction';
$lang['bugs.labels.top_posters'] = 'Top posteurs';
$lang['bugs.labels.login'] = 'Pseudo';
$lang['bugs.labels.default'] = 'Par défaut';
$lang['bugs.labels.default_value'] = 'Valeur par défaut';
$lang['bugs.labels.del_default_value'] = 'Supprimer la valeur par défaut';
$lang['bugs.labels.type_mandatory'] = 'Section "Type" obligatoire ?';
$lang['bugs.labels.category_mandatory'] = 'Section "Catégorie" obligatoire ?';
$lang['bugs.labels.severity_mandatory'] = 'Section "Niveau" obligatoire ?';
$lang['bugs.labels.priority_mandatory'] = 'Section "Priorité" obligatoire ?';
$lang['bugs.labels.detected_in_mandatory'] = 'Section "Détecté dans la version" obligatoire ?';
$lang['bugs.labels.date_format'] = 'Format d\'affichage de la date';
$lang['bugs.labels.date_time'] = 'Date et heure';
$lang['bugs.labels.detected'] = 'Détecté';
$lang['bugs.labels.fixed'] = 'Corrigé';
$lang['bugs.labels.release_date'] = 'Date de parution';
$lang['bugs.labels.not_yet_fixed'] = 'Pas encore corrigé';
$lang['bugs.labels.alert_fix'] = 'Passer l\'alerte en réglé';
$lang['bugs.labels.alert_delete'] = 'Supprimer l\'alerte';
$lang['bugs.labels.matching_selected_filter'] = 'correspondants au filtre sélectionné';
$lang['bugs.labels.matching_selected_filters'] = 'correspondants aux filtres sélectionnés';
$lang['bugs.labels.save_filters'] = 'Sauvegarder les filtres';
$lang['bugs.labels.version_name'] = 'Nom de la version';

//Etats
$lang['bugs.status.new'] = 'Nouveau';
$lang['bugs.status.assigned'] = 'Assigné';
$lang['bugs.status.in_progress'] = 'En cours';
$lang['bugs.status.fixed'] = 'Corrigé';
$lang['bugs.status.reopen'] = 'Réouvert';
$lang['bugs.status.rejected'] = 'Rejeté';

//Explications
$lang['bugs.explain.contents'] = 'Détails qui seront utiles pour la résolution du bug';
$lang['bugs.explain.roadmap'] = 'Permet d\'afficher la liste des bugs corrigés pour chaque version.<br />La feuille de route n\'est affichée que s\'il y a au moins une version dans la liste.';
$lang['bugs.explain.type'] = 'Types des demandes. Exemples : Anomalie, Demande d\'évolution...';
$lang['bugs.explain.category'] = 'Catégorie des demandes. Exemples : Noyau, Module...';
$lang['bugs.explain.severity'] = 'Niveau des demandes. Exemples : Mineur, Majeur, Critique...';
$lang['bugs.explain.priority'] = 'Priorité des demandes. Exemples : Basse, Normale, Elevée...';
$lang['bugs.explain.version'] = 'Liste des versions du produit.';
$lang['bugs.explain.remarks'] = 'Remarques : <br />
- Si la liste est vide, cette option ne sera pas visible lors de la signalisation d\'un bug<br />
- Si la liste ne contient qu\'une seule valeur, cette option ne sera pas non plus visible et sera attribuée par défaut au bug<br /><br />';
$lang['bugs.explain.contents_value'] = 'Entrez ci-dessous la description par défaut à afficher lors de l\'ouverture d\'un nouveau bug. Laissez vide pour que la description ne soit pas pré-remplie.';

//MP
$lang['bugs.pm.assigned.title'] = '[Rapport de bugs] Le bug #:id vous a été assigné par :author';
$lang['bugs.pm.assigned.contents'] = 'Cliquez ici pour afficher le détail du bug :
:link';
$lang['bugs.pm.comment.title'] = '[Rapport de bugs] Le bug #:id a été commenté par :author';
$lang['bugs.pm.comment.contents'] = ':author a ajouté le commentaire suivant au bug #:id :

:comment

Lien vers le bug :
:link';
$lang['bugs.pm.edit.title'] = '[Rapport de bugs] Le bug #:id a été modifié par :author';
$lang['bugs.pm.edit.contents'] = ':author a modifié les champs suivants dans le bug #:id :

:fields

Lien vers le bug :
:link';
$lang['bugs.pm.reopen.title'] = '[Rapport de bugs] Le bug #:id a été ré-ouvert par :author';
$lang['bugs.pm.reopen.contents'] = ':author a ré-ouvert le bug #:id.
Lien vers le bug :
:link';
$lang['bugs.pm.reject.title'] = '[Rapport de bugs] Le bug #:id a été rejeté par :author';
$lang['bugs.pm.reject.contents'] = ':author a rejeté le bug #:id.
Lien vers le bug :
:link';
$lang['bugs.pm.delete.title'] = '[Rapport de bugs] Le bug #:id a été supprimé par :author';
$lang['bugs.pm.delete.contents'] = ':author a supprimé le bug #:id.';

//Recherche
$lang['bugs.search.where'] = 'Où ?';
$lang['bugs.search.where.title'] = 'Titre';
$lang['bugs.search.where.contents'] = 'Description';

//Configuration
$lang['bugs.config.items_per_page'] = 'Nombre de bugs par page'; 
$lang['bugs.config.rejected_bug_color_label'] = 'Couleur de la ligne d\'un bug "Rejeté"';
$lang['bugs.config.fixed_bug_color_label'] = 'Couleur de la ligne d\'un bug "Fermé"';
$lang['bugs.config.activ_com'] = 'Activer les commentaires';
$lang['bugs.config.activ_roadmap'] = 'Activer la feuille de route';
$lang['bugs.config.activ_stats'] = 'Activer les statistiques';
$lang['bugs.config.activ_stats_top_posters'] = 'Afficher les top posteurs';
$lang['bugs.config.stats_top_posters_number'] = 'Nombre d\'utilisateurs affichés';
$lang['bugs.config.activ_progress_bar'] = 'Afficher la barre de progression des bugs';
$lang['bugs.config.activ_admin_alerts'] = 'Activer les alertes administrateur';
$lang['bugs.config.admin_alerts_fix_action'] = 'Action à la fermeture d\'un bug';
$lang['bugs.config.activ_cat_in_title'] = 'Afficher la catégorie dans le titre du bug';
$lang['bugs.config.pm'] = 'MP';
$lang['bugs.config.activ_pm'] = 'Activer l\'envoi de MP';
$lang['bugs.config.activ_pm.comment'] = 'Envoyer un MP lors de l\'ajout d\'un nouveau commentaire';
$lang['bugs.config.activ_pm.assign'] = 'Envoyer un MP lors de l\'assignation d\'un bug';
$lang['bugs.config.activ_pm.edit'] = 'Envoyer un MP lors de l\'édition d\'un bug';
$lang['bugs.config.activ_pm.reject'] = 'Envoyer un MP lors du rejet d\'un bug';
$lang['bugs.config.activ_pm.reopen'] = 'Envoyer un MP lors de la réouverture d\'un bug';
$lang['bugs.config.activ_pm.delete'] = 'Envoyer un MP lors de la suppression d\'un bug';

//Autorisations
$lang['bugs.config.auth.read'] = 'Autorisation d\'afficher la liste des bugs';
$lang['bugs.config.auth.create'] = 'Autorisation de signaler un bug';
$lang['bugs.config.auth.create_advanced'] = 'Autorisation avancée pour signaler un bug';
$lang['bugs.config.auth.create_advanced_explain'] = 'Permet de choisir le niveau et la priorité du bug';
$lang['bugs.config.auth.moderate'] = 'Autorisation de modération des bugs';

//Erreurs
$lang['bugs.error.require_items_per_page'] = 'Veuillez remplir le champ \"Nombre de bugs par page\"';
$lang['bugs.error.e_no_fixed_version'] = 'Veuillez sélectionner la version de correction avant de passer à l\'état "' . $lang['bugs.status.fixed'] . '"';
$lang['bugs.error.e_unexist_bug'] = 'Ce bug n\'existe pas';
$lang['bugs.error.e_unexist_parameter'] = 'Ce paramètre n\'existe pas';
$lang['bugs.error.e_unexist_type'] = 'Ce type n\'existe pas';
$lang['bugs.error.e_unexist_category'] = 'Cette catégorie n\'existe pas';
$lang['bugs.error.e_unexist_severity'] = 'Ce niveau n\'existe pas';
$lang['bugs.error.e_unexist_priority'] = 'Cette priorité n\'existe pas';
$lang['bugs.error.e_unexist_version'] = 'Cette version n\'existe pas';
$lang['bugs.error.e_already_rejected_bug'] = 'Ce bug est déjà rejeté';
$lang['bugs.error.e_already_reopen_bug'] = 'Ce bug est déjà ré-ouvert';
$lang['bugs.error.e_unexist_pm_type'] = 'Ce type de MP n\'existe pas';

//Succès
$lang['bugs.success.config'] = 'La configuration a été modifiée';
$lang['bugs.success.add'] = 'Le bug #:id a été ajouté';
$lang['bugs.success.edit'] = 'Le bug #:id a été modifié';
$lang['bugs.success.fixed'] = 'Le bug #:id a été corrigé';
$lang['bugs.success.delete'] = 'Le bug #:id a été supprimé';
$lang['bugs.success.reject'] = 'Le bug #:id a été rejeté';
$lang['bugs.success.reopen'] = 'Le bug #:id a été ré-ouvert';
?>
