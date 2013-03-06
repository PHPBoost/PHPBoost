<?php
/*##################################################
 *                              calendar_common.php
 *                            -------------------
 *   begin                : November 20, 2012
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
$lang['calendar.module_title'] = 'Calendrier';

//Messages divers
$lang['calendar.notice.no_current_action'] = 'Aucun événement pour cette date';

//Actions
$lang['calendar.actions.confirm.del_event'] = 'Supprimer l\'événement ?';

//Titres
$lang['calendar.titles.admin.config'] = 'Configuration';
$lang['calendar.titles.admin.authorizations'] = 'Autorisations';
$lang['calendar.titles.add_event'] = 'Ajouter un événement';
$lang['calendar.titles.edit_event'] = 'Editer l\'événement';
$lang['calendar.titles.events'] = 'Evénements';
$lang['calendar.titles.event'] = 'Evénement';

//Labels
$lang['calendar.labels.title'] = 'Titre';
$lang['calendar.labels.contents'] = 'Description';
$lang['calendar.labels.location'] = 'Adresse';
$lang['calendar.labels.created_by'] = 'Créé par';
$lang['calendar.labels.category'] = 'Catégorie';
$lang['calendar.labels.registration_authorized'] = 'Activer l\'inscription des membres à l\'événement';
$lang['calendar.labels.max_registred_members'] = 'Nombre de participants maximum';
$lang['calendar.labels.max_registred_members.explain'] = 'Mettre 0 pour illimité';
$lang['calendar.labels.repeat_type'] = 'Répéter';
$lang['calendar.labels.repeat_number'] = 'Nombre de répétitions';
$lang['calendar.labels.repeat.never'] = 'Jamais';
$lang['calendar.labels.repeat.daily'] = 'Tous les jours de la semaine';
$lang['calendar.labels.repeat.daily_not_weekend'] = 'Tous les jours de la semaine (du lundi au vendredi)';
$lang['calendar.labels.repeat.weekly'] = 'Toutes les semaines';
$lang['calendar.labels.repeat.monthly'] = 'Tous les mois';
$lang['calendar.labels.repeat.yearly'] = 'Tous les ans';
$lang['calendar.labels.start_date'] = 'Date de début';
$lang['calendar.labels.end_date'] = 'Date de fin';
$lang['calendar.labels.contribution'] = 'Contribution';
$lang['calendar.labels.contribution.explain'] = 'Vous n\'êtes pas autorisé à créer un événement, cependant vous pouvez en proposer un. Votre contribution suivra le parcours classique et sera traitée dans le panneau de contribution de PHPBoost. Vous pouvez, dans le champ suivant, justifier votre contribution de façon à expliquer votre démarche à un approbateur.';
$lang['calendar.labels.contribution.description'] = 'Complément de contribution';
$lang['calendar.labels.contribution.description.explain'] = 'Expliquez les raisons de votre contribution (pourquoi vous souhaitez proposer cet événement). Ce champ est facultatif.';

//Explications
$lang['calendar.explain.date'] = '<span class="text_small">(jj/mm/aa)</span>';

//Administration
$lang['calendar.config.category.color'] = 'Couleur';
$lang['calendar.config.category.manage'] = 'Gérer les catégories';
$lang['calendar.config.category.add'] = 'Ajouter une catégorie';
$lang['calendar.config.category.edit'] = 'Modifier une catégorie';
$lang['calendar.config.category.delete'] = 'Supprimer une catégorie';
$lang['calendar.config.authorizations.read'] = 'Autorisations de lecture';
$lang['calendar.config.authorizations.write'] = 'Autorisations d\'écriture';
$lang['calendar.config.authorizations.contribution'] = 'Autorisations de contribution';
$lang['calendar.config.authorizations.moderation'] = 'Autorisation de modération';
//Feed name
$lang['calendar.feed.name'] = 'Evénements';

//Succès
$lang['calendar.success.config'] = 'La configuration a été modifiée';

//Erreurs
$lang['calendar.error.e_unexist_event'] = 'L\'évènement sélectionné n\'existe pas';
$lang['calendar.error.e_invalid_date'] = 'La date entrée est invalide';
$lang['calendar.error.e_invalid_start_date'] = 'La date de début entrée est invalide';
$lang['calendar.error.e_invalid_end_date'] = 'La date de fin entrée est invalide';
?>
