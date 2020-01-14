<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 14
 * @since       PHPBoost 4.0 - 2013 08 20
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

####################################################
#                    French                        #
####################################################

// Module titles
$lang['calendar.module.title'] = 'Calendrier';

$lang['items'] = 'événements';
$lang['item'] = 'événement';

// Configuration
$lang['calendar.default.contents'] = 'Contenu par défaut d\'un événement';

// Messages
$lang['calendar.notice.no.event'] = 'Aucun événement pour cette date';
$lang['calendar.notice.no.pending.event'] = 'Aucun événement en attente';
$lang['calendar.suscribe.notice.expired.event.date'] = 'L\'événement est en cours ou terminé, vous ne pouvez pas vous inscrire.';
$lang['calendar.unsuscribe.notice.expired.event.date'] = 'L\'événement est en cours ou terminé, vous ne pouvez pas vous désinscrire.';

// Titles
$lang['calendar.event.delete'] = 'Supprimer l\'événement';
$lang['calendar.event.delete.occurrence'] = 'L\'occurrence';
$lang['calendar.event.delete.serie'] = 'Tous les événements de la série';
$lang['calendar.event.add'] = 'Ajouter un événement';
$lang['calendar.event.edit'] = 'Modifier un événement';
$lang['calendar.events.of'] = 'Événements du';
$lang['calendar.event'] = 'Événement';
$lang['calendar.recurrence'] = 'Récurrence';
$lang['calendar.repetition'] = 'Répétition';
$lang['calendar.pending.events'] = 'Événements en attente';
$lang['calendar.events.manager'] = 'Gestion des événements';
$lang['calendar.events.list'] = 'Liste des événements';
$lang['calendar.cancelled.event'] = 'Cet événement a été annulé';

//Labels
$lang['calendar.labels.location'] = 'Adresse';
$lang['calendar.labels.map_displayed'] = 'Afficher l\'adresse sur une carte';
$lang['calendar.labels.created_by'] = 'Créé par';
$lang['calendar.labels.picture'] = 'Ajouter une image';
$lang['calendar.labels.registration_authorized'] = 'Activer l\'inscription des membres à l\'événement';
$lang['calendar.labels.max_registered_members'] = 'Nombre de participants maximum';
$lang['calendar.labels.max_registered_members.explain'] = '0 pour illimité';
$lang['calendar.labels.remaining_place'] = 'Plus qu\'une place disponible !';
$lang['calendar.labels.remaining_places'] = 'Il ne reste que :missing_number places !';
$lang['calendar.labels.max_participants_reached'] = 'Le nombre de participants maximum a été atteint.';
$lang['calendar.labels.last_registration_date_enabled'] = 'Définir une date limite d\'inscription';
$lang['calendar.labels.last_registration_date'] = 'Dernière date d\'inscription';
$lang['calendar.labels.remaining_day'] = 'Dernier jour pour s\'inscrire !';
$lang['calendar.labels.remaining_days'] = 'Il ne reste que :days_left jours pour s\'inscrire !';
$lang['calendar.labels.registration_closed'] = 'Les inscriptions pour cet événement sont terminées.';
$lang['calendar.labels.repeat_type'] = 'Répéter';
$lang['calendar.labels.repeat_number'] = 'Nombre de répétitions';
$lang['calendar.labels.repeat_times'] = 'fois';
$lang['calendar.labels.repeat.never'] = 'Jamais';
$lang['calendar.labels.events_number'] = ':events_number événements';
$lang['calendar.labels.one_event'] = '1 événement';
$lang['calendar.labels.start.date'] = 'Date de début';
$lang['calendar.labels.end.date'] = 'Date de fin';
$lang['calendar.labels.contribution.explain'] = 'Vous n\'êtes pas autorisé à créer un événement, cependant vous pouvez en proposer un.';
$lang['calendar.labels.birthday'] = 'Anniversaire';
$lang['calendar.labels.birthday.of'] = 'Anniversaire de';
$lang['calendar.labels.participants'] = 'Participants';
$lang['calendar.labels.no.one'] = 'Personne';
$lang['calendar.labels.suscribe'] = 'S\'inscrire';
$lang['calendar.labels.unsuscribe'] = 'Se désinscrire';
$lang['calendar.labels.cancel'] = 'Annuler l\'événement';

//Administration
$lang['calendar.config.events.management'] = 'Gestion des événements';
$lang['calendar.config.category.color'] = 'Couleur';
$lang['calendar.config.items_number_per_page'] = 'Nombre d\'événements affichés par page';
$lang['calendar.config.event_color'] = 'Couleur des événements';
$lang['calendar.config.members_birthday_enabled'] = 'Afficher les anniversaires des membres';
$lang['calendar.config.birthday_color'] = 'Couleur des anniversaires';

$lang['calendar.authorizations.display_registered_users'] = 'Autorisation d\'afficher la liste des inscrits';
$lang['calendar.authorizations.register'] = 'Autorisation de s\'inscrire à l\'événement';

//SEO
$lang['calendar.seo.description.root'] = 'Tous les événements du site :site.';
$lang['calendar.seo.description.pending'] = 'Tous les événements en attente.';
$lang['calendar.seo.description.events_list'] = 'Liste des événements du site :site.';

//Feed name
$lang['calendar.feed.name'] = 'Événement';

//Messages
$lang['calendar.message.success.add'] = 'L\'événement <b>:title</b> a été ajouté';
$lang['calendar.message.success.edit'] = 'L\'événement <b>:title</b> a été modifié';
$lang['calendar.message.success.delete'] = 'L\'événement <b>:title</b> a été supprimé';

//Erreurs
$lang['calendar.error.e_invalid_date'] = 'La date entrée est invalide';
$lang['calendar.error.e_invalid_start_date'] = 'La date de début entrée est invalide';
$lang['calendar.error.e_invalid_end_date'] = 'La date de fin entrée est invalide';
$lang['calendar.error.e_user_born_field_disabled'] = 'Le champ <b>Date de naissance</b> n\'est pas affiché dans le profil des membres. Veuillez activer l\'affichage du champ dans la <a href="' . AdminExtendedFieldsUrlBuilder::fields_list()->rel() . '">Gestion des champs du profil</a> pour permettre aux membres de renseigner leur date de naissance et afficher leur date d\'anniversaire dans le calendrier.';
?>
