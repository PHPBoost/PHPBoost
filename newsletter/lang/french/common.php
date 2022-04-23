<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 23
 * @since       PHPBoost 3.0 - 2011 03 11
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    French                        #
####################################################

$lang['newsletter.module.title'] = 'Newsletter';

// Configuration
$lang['newsletter.email.sender']      = 'Adresse d\'envoi';
$lang['newsletter.email.sender.clue'] = 'Adresse email valide';
$lang['newsletter.name']              = 'Nom de la newsletter';
$lang['newsletter.name.clue']         = 'Objet de l\'email envoyé';
$lang['newsletter.streams.per.page']  = 'Nombre de flux par page';
$lang['newsletter.default.content']   = 'Contenu par défaut d\'une newsletter de type BBCode ou HTML';

// Authorizations
$lang['newsletter.authorizations.streams.read']           = 'Autorisations d\'accès aux flux';
$lang['newsletter.authorizations.streams.manage']         = 'Autorisations de gérer les flux';
$lang['newsletter.authorizations.streams.subscribe']      = 'Autorisations de s\'enregistrer aux flux';
$lang['newsletter.authorizations.archives.manage']        = 'Autorisations de lecture des archives';
$lang['newsletter.authorizations.archives.moderation']    = 'Autorisations de modération des archives';
$lang['newsletter.authorizations.subscribers.read']       = 'Autorisations de lecture de la liste des inscrits';
$lang['newsletter.authorizations.subscribers.moderation'] = 'Autorisations de modération des inscrits';
$lang['newsletter.authorizations.item.write']             = 'Autorisations de créer une newsletter';

// Streams
$lang['newsletter.streams.management'] = 'Gestion des flux';
$lang['newsletter.stream.add']         = 'Ajouter un flux';
$lang['newsletter.stream.edit']        = 'Modification d\'un flux';
$lang['newsletter.stream.delete']      = 'Suppression d\'un flux';
$lang['newsletter.stream.delete.clue'] = 'Vous êtes sur le point de supprimer le flux. Deux solutions s\'offrent à vous. Vous pouvez soit déplacer l\'ensemble de son contenu (newsletters et flux) dans un autre flux soit supprimer l\'ensemble du flux. <strong>Attention, cette action est irréversible !</strong>';
$lang['newsletter.items.list']         = 'Liste des newsletters';

//Hooks
$lang['newsletter.specific_hook.newsletter_subscribe'] = 'Inscription aux newsletters';
$lang['newsletter.specific_hook.newsletter_subscribe.description.single'] = 'L\'utilisateur <a href=":user_profile_url">:user_display_name</a> s\'est inscrit à la newsletter :streams_list';
$lang['newsletter.specific_hook.newsletter_subscribe.description'] = 'L\'utilisateur <a href=":user_profile_url">:user_display_name</a> s\'est inscrit aux newsletters suivantes : :streams_list';
$lang['newsletter.specific_hook.newsletter_unsubscribe'] = 'Désinscription des newsletters';
$lang['newsletter.specific_hook.newsletter_unsubscribe.description.single'] = 'L\'utilisateur <a href=":user_profile_url">:user_display_name</a> s\'est désinscrit de la newsletter :streams_list';
$lang['newsletter.specific_hook.newsletter_unsubscribe.description'] = 'L\'utilisateur <a href=":user_profile_url">:user_display_name</a> s\'est désinscrit des newsletters suivantes : :streams_list';
$lang['newsletter.specific_hook.newsletter_unsubscribe.all'] = 'L\'utilisateur <a href=":user_profile_url">:user_display_name</a> s\'est désinscrit de toutes les newsletters';

// Subscription
$lang['newsletter.subscribe.streams']     = 'S\'abonner aux newsletters';
$lang['newsletter.subscribe.item']        = 'S\'abonner à une newsletter';
$lang['newsletter.subscribe.item.clue']   = 'Choisissez les flux de newsletters auxquels vous souhaitez être abonné';
$lang['newsletter.subscriber.edit']       = 'Editer un inscrit';
$lang['newsletter.subscriber.email']      = 'Email';
$lang['newsletter.unsubscribe.item']      = 'Se désabonner d\'une newsletter';
$lang['newsletter.unsubscribe.item.clue'] = 'Choisissez les flux de newsletters auxquels vous souhaitez rester abonné';
$lang['newsletter.delete.all.streams']    = 'Se désinscrire de tous les flux';
$lang['newsletter.unsubscribe.items']     = 'Se désinscrire des newsletters';

// Newsletters
$lang['newsletter.subscribers.list']     = 'Liste des inscrits';
$lang['newsletter.see.subscribers.list'] = 'Voir les inscrits';
    // Archives
$lang['newsletter.archives']           = 'Archives';
$lang['newsletter.archives.list']      = 'Liste des archives';
$lang['newsletter.see.archives']       = 'Voir les archives';
$lang['newsletter.stream.name']        = 'Nom du flux';
$lang['newsletter.item.name']          = 'Nom de la newsletter';
$lang['newsletter.archives.date']      = 'Date de publication';
$lang['newsletter.subscribers.number'] = 'Nombre d\'inscrits';
    // Types
$lang['newsletter.types.choice']      = 'Veuillez sélectionner un type de message';
$lang['newsletter.types.for.all']     = 'Pour tous';
$lang['newsletter.types.text']        = 'Texte';
$lang['newsletter.types.text.clue']   = 'Vous ne pourrez procéder à aucune mise en forme du message.';
$lang['newsletter.types.bbcode']      = 'BBCode';
$lang['newsletter.types.bbcode.clue'] = 'Vous pouvez formater le texte grâce au BBCode, le langage de mise en forme simplifié adopté sur tout le portail.';
$lang['newsletter.types.html']        = 'HTML';
$lang['newsletter.types.for.experts'] = 'Utilisateurs expérimentés seulement';
$lang['newsletter.types.html.clue']   = 'Vous pouvez mettre en forme le texte à votre guise, mais vous devez connaître le langage html.';
    // Add
$lang['newsletter.title']          = 'Titre de la newsletter';
$lang['newsletter.content']        = 'Contenu';
$lang['newsletter.content.clue']   = 'Utilisez <b>:user_display_name</b> pour afficher le pseudo du membre si besoin (sera remplacé par visiteur pour les inscrits qui ne sont pas membres du site).';
$lang['newsletter.choose.streams'] = 'Choisissez le ou les flux où vous souhaitez envoyer cette newsletter';
$lang['newsletter.send.test']      = 'Envoyer un email de test';
$lang['newsletter.add.item']       = 'Ajouter une newsletter';

// Messages
$lang['newsletter.stream.success.add']         = 'Le flux <b>:name</b> a été ajouté';
$lang['newsletter.stream.success.edit']        = 'Le flux <b>:name</b> a été modifié';
$lang['newsletter.stream.success.delete']      = 'Le flux <b>:name</b> a été supprimé';
$lang['newsletter.stream.delete.confirmation'] = 'Voulez-vous vraiment supprimer le flux :name ?';
$lang['newsletter.success.send.test']          = 'L\'email de test a bien été envoyé';
$lang['newsletter.item.success.add']           = 'La newsletter a été envoyée';
$lang['newsletter.archive.success.delete']     = 'L\'archive a été supprimée';

// Errors
$lang['newsletter.sender.email.not.configured.for.admin'] = 'L\'adresse email d\'envoi de la newsletter n\'a pas été configurée. Veuillez la <a class="offload" href ="' . NewsletterUrlBuilder::configuration()->rel() . '">configurer</a> avant de pouvoir envoyer une newsletter.';
$lang['newsletter.sender.email.not.configured'] = 'L\'adresse email d\'envoi de la newsletter n\'a pas été configurée par l\'administrateur, veuillez réessayer quand ça sera fait.';
$lang['newsletter.stream.not.exists']           = 'Le flux demandé n\'existe pas';
$lang['newsletter.subscriber.not.exists']       = 'L\'inscrit n\'existe pas';
$lang['newsletter.subscriber.already.exists']   = 'Cette adresse email est déjà inscrite aux newsletters du site';
$lang['newsletter.archive.not.exists']          = 'L\'archive n\'existe pas';

// Register extended fields
$lang['newsletter.extended.fields.subscribed.items'] = 'Newsletter(s) souscrite(s)';
$lang['newsletter.extended.fields.select.items']     = 'Sélectionnez la(les) newsletter(s) auxquelles vous souhaitez être inscrit';

// S.E.O.
$lang['newsletter.seo.suscribe']        = 'Renseignez votre adresse email et choisissez la ou les newsletters que vous souhaitez recevoir.';
$lang['newsletter.seo.unsuscribe']      = 'Choisissez la ou les newsletters que vous ne souhaitez plus recevoir.';
$lang['newsletter.seo.suscribers.list'] = 'Liste des inscrits à la newsletter :name.';
$lang['newsletter.seo.home']            = 'Liste des newsletters du site :site.';
$lang['newsletter.seo.archives']        = 'Liste des archives des newsletters :name.';
?>
