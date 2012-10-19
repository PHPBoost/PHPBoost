<?php
/*##################################################
 *                              bugtracker_french.php
 *                            -------------------
 *   begin                : February 01, 2012
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
 
//Titre du module
$LANG['bugs.module_title'] = 'Bugtracker';

//Messages divers
$LANG['bugs.notice.no_one'] = 'Personne';
$LANG['bugs.notice.none'] = 'Aucun';
$LANG['bugs.notice.none_e'] = 'Aucune';
$LANG['bugs.notice.no_bug'] = 'Aucun bug n\'a été déclaré';
$LANG['bugs.notice.no_bug_solved'] = 'Aucun bug n\'a été corrigé';
$LANG['bugs.notice.no_bug_fixed'] = 'Aucun bug n\'a été corrigé dans cette version';
$LANG['bugs.notice.no_version'] = 'Aucune version existante';
$LANG['bugs.notice.no_type'] = 'Aucun type n\'a été déclaré';
$LANG['bugs.notice.no_category'] = 'Aucune catégorie n\'a été déclarée';
$LANG['bugs.notice.no_priority'] = 'Aucune priorité n\'a été déclarée';
$LANG['bugs.notice.no_severity'] = 'Aucun niveau n\'a été déclaré';
$LANG['bugs.notice.no_history'] = 'Ce bug n\'a aucun historique';
$LANG['bugs.notice.contents_update'] = 'Mise à jour du contenu';
$LANG['bugs.notice.new_comment'] = 'Nouveau commentaire';
$LANG['bugs.notice.reproduction_method_update'] = 'Mise à jour de la méthode de reproduction';
$LANG['bugs.notice.not_defined'] = 'Non défini';
$LANG['bugs.notice.require_login'] = 'Veuillez saisir un pseudo !';
$LANG['bugs.notice.require_type'] = 'Veuillez saisir un nom pour le nouveau type !';
$LANG['bugs.notice.require_category'] = 'Veuillez saisir un nom pour la nouvelle catégorie !';
$LANG['bugs.notice.require_priority'] = 'Veuillez saisir un nom pour la nouvelle priorité !';
$LANG['bugs.notice.require_severity'] = 'Veuillez saisir un nom pour le nouveau niveau !';
$LANG['bugs.notice.require_version'] = 'Veuillez saisir un nom pour la nouvelle version !';
$LANG['bugs.notice.require_choose_type'] = 'Veuillez choisir le type votre bug !';
$LANG['bugs.notice.require_choose_category'] = 'Veuillez choisir la catégorie votre bug !';
$LANG['bugs.notice.require_choose_priority'] = 'Veuillez choisir la priorité de votre bug !';
$LANG['bugs.notice.require_choose_severity'] = 'Veuillez choisir le niveau de votre bug !';
$LANG['bugs.notice.require_choose_detected_in'] = 'Veuillez choisir la version dans laquelle votre bug a été détecté !';
$LANG['bugs.notice.joker'] = 'Utilisez * pour joker';

//Actions
$LANG['bugs.actions'] = 'Actions';
$LANG['bugs.actions.add'] = 'Nouveau bug';
$LANG['bugs.actions.delete'] = 'Supprimer le bug';
$LANG['bugs.actions.edit'] = 'Editer le bug';
$LANG['bugs.actions.history'] = 'Historique du bug';
$LANG['bugs.actions.reject'] = 'Rejeter le bug';
$LANG['bugs.actions.reopen'] = 'Ré-ouvrir le bug';
$LANG['bugs.actions.confirm.del_bug'] = 'Etes-vous sûr de vouloir supprimer ce bug de la liste ? (toute l\'historique associée sera supprimée)';
$LANG['bugs.actions.confirm.del_version'] = 'Etes-vous sûr de vouloir supprimer cette version ?';
$LANG['bugs.actions.confirm.del_type'] = 'Etes-vous sûr de vouloir supprimer ce type ?';
$LANG['bugs.actions.confirm.del_category'] = 'Etes-vous sûr de vouloir supprimer cette catégorie ?';
$LANG['bugs.actions.confirm.del_priority'] = 'Etes-vous sûr de vouloir supprimer cette priorité ?';
$LANG['bugs.actions.confirm.del_severity'] = 'Etes-vous sûr de vouloir supprimer ce niveau ?';

//Titres
$LANG['bugs.titles.add_bug'] = 'Nouveau bug';
$LANG['bugs.titles.add_version'] = 'Ajout d\'une nouvelle version';
$LANG['bugs.titles.add_type'] = 'Ajout d\'un nouveau type';
$LANG['bugs.titles.add_category'] = 'Ajout d\'une nouvelle catégorie';
$LANG['bugs.titles.add_priority'] = 'Ajout d\'une nouvelle priorité';
$LANG['bugs.titles.add_severity'] = 'Ajout d\'un nouveau niveau';
$LANG['bugs.titles.edit_bug'] = 'Edition du bug';
$LANG['bugs.titles.history_bug'] = 'Historique du bug';
$LANG['bugs.titles.view_bug'] = 'Bug';
$LANG['bugs.titles.roadmap'] = 'Feuille de route';
$LANG['bugs.titles.bugs_infos'] = 'Informations sur le bug';
$LANG['bugs.titles.bugs_stats'] = 'Statistiques';
$LANG['bugs.titles.bugs_treatment'] = 'Traitement du bug';
$LANG['bugs.titles.bugs_treatment_state'] = 'Etat du traitement du bug';
$LANG['bugs.titles.disponible_versions'] = 'Versions disponibles';
$LANG['bugs.titles.disponible_types'] = 'Types disponibles';
$LANG['bugs.titles.disponible_categories'] = 'Catégories disponibles';
$LANG['bugs.titles.disponible_priorities'] = 'Priorités disponibles';
$LANG['bugs.titles.disponible_severities'] = 'Niveaux disponibles';
$LANG['bugs.titles.admin.management'] = 'Gestion bugtracker';
$LANG['bugs.titles.admin.config'] = 'Configuration';
$LANG['bugs.titles.choose_version'] = 'Version à afficher';
$LANG['bugs.titles.solved_bugs'] = 'Bugs résolus';
$LANG['bugs.titles.unsolved_bugs'] = 'Bugs non-résolus';
$LANG['bugs.titles.contents_value_title'] = 'Description par défaut d\'un bug';
$LANG['bugs.titles.contents_value'] = 'Description par défaut';

//Libellés
$LANG['bugs.labels.fields.id'] = 'ID';
$LANG['bugs.labels.fields.title'] = 'Titre';
$LANG['bugs.labels.fields.contents'] = 'Description';
$LANG['bugs.labels.fields.author_id'] = 'Détecté par';
$LANG['bugs.labels.fields.submit_date'] = 'Détecté le';
$LANG['bugs.labels.fields.status'] = 'Etat';
$LANG['bugs.labels.fields.type'] = 'Type';
$LANG['bugs.labels.fields.category'] = 'Catégorie';
$LANG['bugs.labels.fields.reproductible'] = 'Reproductible';
$LANG['bugs.labels.fields.reproduction_method'] = 'Méthode de reproduction';
$LANG['bugs.labels.fields.severity'] = 'Niveau';
$LANG['bugs.labels.fields.priority'] = 'Priorité';
$LANG['bugs.labels.fields.detected_in'] = 'Détecté dans la version';
$LANG['bugs.labels.fields.fixed_in'] = 'Corrigé dans la version';
$LANG['bugs.labels.fields.assigned_to_id'] = 'Assigné à';
$LANG['bugs.labels.fields.updater_id'] = 'Modifié par';
$LANG['bugs.labels.fields.update_date'] = 'Modifié le';
$LANG['bugs.labels.fields.updated_field'] = 'Champ modifié';
$LANG['bugs.labels.fields.old_value'] = 'Ancienne valeur';
$LANG['bugs.labels.fields.new_value'] = 'Nouvelle valeur';
$LANG['bugs.labels.fields.change_comment'] = 'Commentaire';
$LANG['bugs.labels.fields.version'] = 'Version';
$LANG['bugs.labels.fields.version_detected_in'] = 'Afficher dans la liste "Détecté dans la version"';
$LANG['bugs.labels.fields.version_fixed_in'] = 'Afficher dans la liste "Corrigé dans la version"';
$LANG['bugs.labels.fields.version_detected'] = 'Version détectée';
$LANG['bugs.labels.fields.version_fixed'] = 'Version corrigée';
$LANG['bugs.labels.color'] = 'Couleur';
$LANG['bugs.labels.number'] = 'Nombre de bugs';
$LANG['bugs.labels.number_corrected'] = 'Nombre de bugs corrigés';
$LANG['bugs.labels.top_10_posters'] = 'Top 10 : posteurs';
$LANG['bugs.labels.default'] = 'Par défaut';
$LANG['bugs.labels.del_default_value'] = 'Supprimer la valeur par défaut';
$LANG['bugs.labels.type_mandatory'] = 'Section "Type" obligatoire ?';
$LANG['bugs.labels.category_mandatory'] = 'Section "Catégorie" obligatoire ?';
$LANG['bugs.labels.severity_mandatory'] = 'Section "Niveau" obligatoire ?';
$LANG['bugs.labels.priority_mandatory'] = 'Section "Priorité" obligatoire ?';
$LANG['bugs.labels.detected_in_mandatory'] = 'Section "Détecté dans la version" obligatoire ?';
$LANG['bugs.labels.date_format'] = 'Format d\'affichage de la date';
$LANG['bugs.labels.date_time'] = 'Date et heure';

//Etats
$LANG['bugs.status.new'] = 'Nouveau';
$LANG['bugs.status.assigned'] = 'Assigné';
$LANG['bugs.status.fixed'] = 'Corrigé';
$LANG['bugs.status.reopen'] = 'Ré-ouvert';
$LANG['bugs.status.rejected'] = 'Rejeté';

//Explications
$LANG['bugs.explain.roadmap'] = 'Permet d\'afficher la liste des bugs corrigés pour chaque version';
$LANG['bugs.explain.pm'] = 'Permet d\'envoyer un MP dans les cas suivants :<br />
- Commentaire sur un bug<br />
- Edition d\'un bug<br />
- Suppression d\'un bug<br />
- Assignation d\'un bug<br />
- Rejet d\'un bug<br />
- Réouverture d\'un bug<br />';
$LANG['bugs.explain.type'] = 'Types des demandes. Exemples : Anomalie, Demande d\'évolution...';
$LANG['bugs.explain.category'] = 'Catégorie des demandes. Exemples : Noyau, Module...';
$LANG['bugs.explain.severity'] = 'Niveau des demandes. Exemples : Mineur, Majeur, Critique...';
$LANG['bugs.explain.priority'] = 'Priorité des demandes. Exemples : Basse, Normale, Elevée...';
$LANG['bugs.explain.version'] = 'Liste des versions du produit.';
$LANG['bugs.explain.remarks'] = 'Remarques : <br />
- Si la liste est vide, cette option ne sera pas visible lors de la signalisation d\'un bug<br />
- Si la liste ne contient qu\'une seule valeur, cette option ne sera pas non plus visible et sera attribuée par défaut au bug<br /><br />';
$LANG['bugs.explain.contents_value'] = 'Entrez ci-dessous la description par défaut à afficher lors de l\'ouverture d\'un nouveau bug. Laissez vide pour que la description ne soit pas pré-remplie.';

//MP
$LANG['bugs.pm.assigned.title'] = '[%s] Le bug #%d vous a été assigné par %s';
$LANG['bugs.pm.assigned.contents'] = 'Cliquez ici pour afficher le détail du bug :
%s';
$LANG['bugs.pm.comment.title'] = '[%s] Le bug #%d a été commenté par %s';
$LANG['bugs.pm.comment.contents'] = '%s a ajouté le commentaire suivant au bug #%d :

%s

Lien vers le bug :
%s';
$LANG['bugs.pm.edit.title'] = '[%s] Le bug #%d a été modifié par %s';
$LANG['bugs.pm.edit.contents'] = '%s a modifié les champs suivants dans le bug #%d :

%s

Lien vers le bug :
%s';
$LANG['bugs.pm.reopen.title'] = '[%s] Le bug #%d a été ré-ouvert par %s';
$LANG['bugs.pm.reopen.contents'] = '%s a ré-ouvert le bug #%d.
Lien vers le bug :
%s';
$LANG['bugs.pm.reject.title'] = '[%s] Le bug #%d a été rejeté par %s';
$LANG['bugs.pm.reject.contents'] = '%s a rejeté le bug #%d.
Lien vers le bug :
%s';
$LANG['bugs.pm.delete.title'] = '[%s] Le bug #%d a été supprimé par %s';
$LANG['bugs.pm.delete.contents'] = '%s a supprimé le bug #%d.
Lien vers le bug :
%s';

//Recherche
$LANG['bugs.search.where'] = 'Où ?';
$LANG['bugs.search.where.title'] = 'Titre';
$LANG['bugs.search.where.contents'] = 'Contenu';

//Configuration
$LANG['bugs.config.items_per_page'] = 'Nombre de bugs par page'; 
$LANG['bugs.config.rejected_bug_color_label'] = 'Couleur de la ligne d\'un bug "Rejeté"';
$LANG['bugs.config.fixed_bug_color_label'] = 'Couleur de la ligne d\'un bug "Fermé"';
$LANG['bugs.config.activ_com'] = 'Activer les commentaires';
$LANG['bugs.config.activ_roadmap'] = 'Activer la feuille de route';
$LANG['bugs.config.activ_cat_in_title'] = 'Afficher la catégorie dans le titre du bug';
$LANG['bugs.config.activ_pm'] = 'Activer l\'envoi de MP';

//Autorisations
$LANG['bugs.config.auth'] = 'Autorisations';
$LANG['bugs.config.auth.read'] = 'Autorisation d\'afficher la liste des bugs';
$LANG['bugs.config.auth.create'] = 'Autorisation de signaler un bug';
$LANG['bugs.config.auth.create_advanced'] = 'Autorisation avancées pour signaler un bug';
$LANG['bugs.config.auth.create_advanced_explain'] = 'Permet de choisir le niveau et la priorité du bug';
$LANG['bugs.config.auth.moderate'] = 'Autorisation de modération des bugs';

//Erreurs
$LANG['bugs.error.require_items_per_page'] = 'Veuillez remplir le champ \"Nombre de bugs par page\"';
$LANG['bugs.error.e_no_user_assigned'] = 'Ce bug n\'a été assigné à aucun utilisateur, l\'état ne pas passer à "' . $LANG['bugs.status.assigned'] . '"';
$LANG['bugs.error.e_no_fixed_version'] = 'Veuillez sélectionner la version de correction avant de passer à l\'état "' . $LANG['bugs.status.fixed'] . '"';
$LANG['bugs.error.e_config_success'] = 'La configuration a été modifiée avec succès';
$LANG['bugs.error.e_edit_success'] = 'Le bug a été modifié avec succès';
$LANG['bugs.error.e_delete_success'] = 'Le bug a été supprimé avec succès';
$LANG['bugs.error.e_reject_success'] = 'Le bug a été rejeté';
$LANG['bugs.error.e_reopen_success'] = 'Le bug a été ré-ouvert';
$LANG['bugs.error.e_unexist_bug'] = 'Ce bug n\'existe pas';

?>