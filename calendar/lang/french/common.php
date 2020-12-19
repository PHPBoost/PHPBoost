<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 19
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
$lang['module.title'] = 'Calendrier';

$lang['items'] = 'événements';
$lang['item'] = 'événement';

$lang['an.item'] = 'un événement';
$lang['the.item'] = 'l\'événement';
$lang['my.items'] = 'Mes événements';

// Configuration
$lang['calendar.default.content'] = 'Contenu par défaut d\'un événement';

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
$lang['calendar.repetition'] = 'Répétition';
$lang['calendar.pending.events'] = 'Événements en attente';
$lang['calendar.events.manager'] = 'Gestion des événements';
$lang['calendar.events.list'] = 'Liste des événements';
$lang['calendar.cancelled.event'] = 'Cet événement a été annulé';

// Labels
$lang['calendar.labels.location'] = 'Adresse';
$lang['calendar.labels.map.displayed'] = 'Afficher l\'adresse sur une carte';
$lang['calendar.labels.thumbnail'] = 'Ajouter une image';
$lang['calendar.labels.registration.authorized'] = 'Activer l\'inscription des membres à l\'événement';
$lang['calendar.labels.remaining.place'] = 'Plus qu\'une place disponible !';
$lang['calendar.labels.remaining.places'] = 'Il ne reste que :missing_number places !';
$lang['calendar.labels.max.registered.members'] = 'Nombre de participants maximum';
$lang['calendar.labels.max.registered.members.explain'] = '0 pour illimité';
$lang['calendar.labels.max.participants.reached'] = 'Le nombre de participants maximum a été atteint.';
$lang['calendar.labels.last.registration.date.enabled'] = 'Définir une date limite d\'inscription';
$lang['calendar.labels.last.registration.date'] = 'Dernière date d\'inscription';
$lang['calendar.labels.remaining.day'] = 'Dernier jour pour s\'inscrire !';
$lang['calendar.labels.remaining.days'] = 'Il ne reste que :days_left jours pour s\'inscrire !';
$lang['calendar.labels.registration.closed'] = 'Les inscriptions pour cet événement sont terminées.';
$lang['calendar.labels.repeat.type'] = 'Répéter';
$lang['calendar.labels.repeat.number'] = 'Nombre de répétitions';
$lang['calendar.labels.repeat.times'] = 'fois';
$lang['calendar.labels.repeat.never'] = 'Jamais';
$lang['calendar.labels.events.number'] = ':items_number événements';
$lang['calendar.labels.one.event'] = '1 événement';
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

// Configuration
$lang['calendar.config.events.management'] = 'Gestion des événements';
$lang['calendar.config.set.to.zero'] = 'Mettre à zéro pour désactiver';
$lang['calendar.config.category.color'] = 'Couleur';
$lang['calendar.config.items.number.per.page'] = 'Nombre d\'événements affichés par page';
$lang['calendar.config.event.color'] = 'Couleur des événements';
$lang['calendar.config.members.birthday.enabled'] = 'Afficher les anniversaires des membres';
$lang['calendar.config.birthday.color'] = 'Couleur des anniversaires';

$lang['calendar.authorizations.display.registered.users'] = 'Autorisation d\'afficher la liste des inscrits';
$lang['calendar.authorizations.register'] = 'Autorisation de s\'inscrire à l\'événement';

// SEO
$lang['calendar.seo.description.root'] = 'Tous les événements du site :site.';
$lang['calendar.seo.description.pending'] = 'Tous les événements en attente.';
$lang['calendar.seo.description.member'] = 'Tous les événements du membre: :autohr.';
$lang['calendar.seo.description.events.list'] = 'Liste des événements du site :site.';

// Feed name
$lang['calendar.feed.name'] = 'Événement';

// Messages
$lang['calendar.message.success.add'] = 'L\'événement <b>:title</b> a été ajouté';
$lang['calendar.message.success.edit'] = 'L\'événement <b>:title</b> a été modifié';
$lang['calendar.message.success.delete'] = 'L\'événement <b>:title</b> a été supprimé';

// Errors
$lang['calendar.error.invalid.date'] = 'La date entrée est invalide';
$lang['calendar.error.user.born.field.disabled'] = 'Le champ <b>Date de naissance</b> n\'est pas affiché dans le profil des membres. Veuillez activer l\'affichage du champ dans la <a href="' . AdminExtendedFieldsUrlBuilder::fields_list()->rel() . '">Gestion des champs du profil</a> pour permettre aux membres de renseigner leur date de naissance et afficher leur date d\'anniversaire dans le calendrier.';
?>
