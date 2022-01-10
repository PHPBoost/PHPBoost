<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 01 10
 * @since       PHPBoost 4.0 - 2013 08 20
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

####################################################
#                    French                        #
####################################################

$lang['calendar.module.title'] = 'Calendrier';

$lang['calendar.item']  = 'événement';
$lang['calendar.items'] = 'événements';
$lang['calendar.no.category'] = 'Sans catégorie';

// TreeLinks
$lang['item']  = 'événement';
$lang['items'] = 'événements';

// Titles
$lang['calendar.item.add']         = 'Ajouter un événement';
$lang['calendar.item.edit']        = 'Modifier un événement';
$lang['calendar.item.delete']      = 'Supprimer l\'événement';
$lang['calendar.my.items']         = 'Mes événements';
$lang['calendar.member.items']     = 'Événements publiés par';
$lang['calendar.pending.items']    = 'Événements en attente';
$lang['calendar.filter.items']     = 'Filtrer les événements';
$lang['calendar.items.management'] = 'Gestion des événements';
$lang['calendar.items.list']       = 'Liste des événements';

// Labels
$lang['calendar.items.of.day']             = 'Événements du';
$lang['calendar.items.of.month']           = 'Événements de';
$lang['calendar.items.of.month.alt']       = 'Événements d\'';
$lang['calendar.location']                 = 'Adresse';
$lang['calendar.cancelled.item']           = 'Cet événement a été annulé';
$lang['calendar.remaining.place']          = 'Plus qu\'une place disponible !';
$lang['calendar.remaining.places']         = 'Il ne reste que :missing_number places !';
$lang['calendar.remaining.day']            = 'Dernier jour pour s\'inscrire !';
$lang['calendar.remaining.days']           = 'Il ne reste que :days_left jours pour s\'inscrire !';
$lang['calendar.registration.closed']      = 'Les inscriptions pour cet événement sont terminées.';
$lang['calendar.max.participants.reached'] = 'Le nombre de participants maximum a été atteint.';
$lang['calendar.dates']                    = 'Dates de l\'événement';
$lang['calendar.start.date']               = 'Date de début';
$lang['calendar.end.date']                 = 'Date de fin';
$lang['calendar.birthday']                 = 'Anniversaire';
$lang['calendar.birthday.of']              = 'Anniversaire de';
$lang['calendar.participants']             = 'Participants';
$lang['calendar.no.one']                   = 'Personne';
$lang['calendar.suscribe']                 = 'S\'inscrire';
$lang['calendar.unsuscribe']               = 'Se désinscrire';
$lang['calendar.repetition']               = 'Répétition';
$lang['calendar.repeat.times']             = 'fois';

// Form
$lang['calendar.delete.occurrence']           = 'L\'occurrence';
$lang['calendar.delete.serie']                = 'Tous les événements de la série';
$lang['calendar.form.cancel']                 = 'Annuler l\'événement';
$lang['calendar.form.repeat.type']            = 'Répéter';
$lang['calendar.form.repeat.number']          = 'Nombre de répétitions';
$lang['calendar.form.display.map']            = 'Afficher l\'adresse sur une carte';
$lang['calendar.form.enable.registration']    = 'Activer l\'inscription des membres à l\'événement';
$lang['calendar.form.registration.limit']     = 'Limiter le nombre d\'inscrits';
$lang['calendar.form.max.registered']         = 'Nombre de participants maximum';
$lang['calendar.form.registration.deadline']  = 'Définir une date limite d\'inscription';
$lang['calendar.form.last.date.registration'] = 'Dernière date d\'inscription';

$lang['calendar.authorizations.display.registered.users'] = 'Autorisation d\'afficher la liste des inscrits';
$lang['calendar.authorizations.register']                 = 'Autorisation de s\'inscrire à l\'événement';

// Notice messages
$lang['calendar.suscribe.notice.expired.event.date']   = 'L\'événement est en cours ou terminé, vous ne pouvez pas vous inscrire.';
$lang['calendar.unsuscribe.notice.expired.event.date'] = 'L\'événement est en cours ou terminé, vous ne pouvez pas vous désinscrire.';

// Categories
$lang['calendar.category.color'] = 'Couleur';

// Configuration
$lang['calendar.config.event.color']       = 'Couleur des événements';
$lang['calendar.config.display.birthdays'] = 'Afficher les anniversaires des membres';
$lang['calendar.config.birthday.color']    = 'Couleur des anniversaires';

// SEO
$lang['calendar.seo.description.root']        = 'Tous les événements du site  :site.';
$lang['calendar.seo.description.pending']     = 'Tous les événements en attente.';
$lang['calendar.seo.description.member']      = 'Tous les événements de       :author.';
$lang['calendar.seo.description.events.list'] = 'Liste des événements du site :site.';

// Feed name
$lang['calendar.feed.name'] = 'Événement';

// Messages helper
$lang['calendar.message.success.add']    = 'L\'événement <b>:title</b> a été ajouté';
$lang['calendar.message.success.edit']   = 'L\'événement <b>:title</b> a été modifié';
$lang['calendar.message.success.delete'] = 'L\'événement <b>:title</b> a été supprimé';

// Errors
$lang['calendar.error.invalid.date']             = 'La date entrée est invalide';
$lang['calendar.error.user.born.field.disabled'] = 'Le champ <b>Date de naissance</b> n\'est pas affiché dans le profil des membres. Veuillez activer l\'affichage du champ dans la <a class="offload" href="' . AdminExtendedFieldsUrlBuilder::fields_list()->rel() . '">Gestion des champs du profil</a> pour permettre aux membres de renseigner leur date de naissance et afficher leur date d\'anniversaire dans le calendrier.';
?>
