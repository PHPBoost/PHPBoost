<?php
/*##################################################
 *                             newsletter_common.php
 *                            -------------------
 *   begin                :  March 11, 2011
 *   copyright            : (C) 2011 MASSY Kévin
 *   email                : soldier.weasel@gmail.com
 *
 *  
 ###################################################
 *
 *   This program is a free software. You can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/


 ####################################################
 #						French						#
 ####################################################

$lang = array();

//Title
$lang['newsletter.home'] = 'Accueil';
$lang['newsletter'] = 'Newsletter';
$lang['newsletter.config'] = 'Configuration';
$lang['newsletter.archives'] = 'Archives';
$lang['newsletter.subscribers'] = 'Liste des inscrits';
$lang['newsletter.streams'] = 'Gestion des flux';

//Other title
$lang['subscribe.newsletter'] = 'S\'abonner aux newsletters';
$lang['subscriber.edit'] = 'Editer un inscrit';
$lang['archives.list'] = 'Liste des archives';
$lang['newsletter-add'] = 'Ajouter une newsletter';
$lang['newsletter.subscribe_newsletters'] = 'S\'abonner à une newsletter';
$lang['newsletter.unsubscribe_newsletters'] = 'Se désabonner à une newsletter';
$lang['streams.add'] = 'Ajouter un flux';
$lang['streams.edit'] = 'Modifier un flux';
$lang['newsletter.list_newsletters'] = 'Liste des newsletters';

//Admin
$lang['admin.mail-sender'] = 'Adresse d\'envoi';
$lang['admin.mail-sender-explain'] = 'Adresse mail valide';
$lang['admin.newsletter-name'] = 'Nom de la newsletter';
$lang['admin.newsletter-name-explain'] = 'Objet du mail envoyé';

//Authorizations
$lang['admin.newsletter-authorizations'] = 'Autorisations';
$lang['auth.read'] = 'Autorisations d\'accès aux flux';
$lang['auth.archives-read'] = 'Autorisations de lecture des archives';
$lang['auth.subscribers-read'] = 'Autorisations de lecture de la liste des inscrits';
$lang['auth.subscribers-moderation'] = 'Autorisations de modérer les inscrits';
$lang['auth.subscribe'] = 'Autorisations de s\'enregistrer aux newsletters';
$lang['auth.create-newsletter'] = 'Autorisations de créer une newsletter';

//Categories
$lang['streams.name'] = 'Nom';
$lang['streams.description'] = 'Description';
$lang['streams.picture'] = 'Image de représentation';
$lang['streams.visible'] = 'Afficher';
$lang['streams.picture-preview'] = 'Prévisualiser l\'image du flux';
$lang['streams.auth.read'] = 'Autorisations d\'accès au flux';
$lang['streams.auth.subscribers-read'] = 'Autorisations de lecture des inscrits';
$lang['streams.auth.subscribers-moderation'] = 'Autorisations de modération des inscrits';
$lang['streams.auth.create-newsletter'] = 'Autorisations de créer une newsletter';
$lang['streams.auth.subscribe'] = 'Autorisations de s\'enregistrer à la newsletter';
$lang['streams.auth.archives-read'] = 'Autorisations de lecture des archives';
$lang['streams.active-advanced-authorizations'] = 'Activer les autorisations avancées du flux';
$lang['streams.visible-no'] = 'Non';
$lang['streams.visible-yes'] = 'Oui';
$lang['streams.no_cats'] = 'Aucun flux';

//Subscribe
$lang['subscribe.mail'] = 'Mail';
$lang['subscribe.newsletter_choice'] = 'Choisissez les newsletters auxquelles vous souhaitez vous abonner';

//Subscribers
$lang['subscribers.list'] = 'Liste des inscrits';
$lang['subscribers.pseudo'] = 'Pseudo';
$lang['subscribers.mail'] = 'Mail';
$lang['subscribers.delete'] = 'Voulez-vous vraiment supprimer cette personne des inscrits ?';
$lang['subscribers.no_users'] = 'Aucun inscrit';

// Unsubcribe
$lang['newsletter.delete_all_streams'] = 'Se désinscrire de tout les flux';
$lang['unsubscribe.newsletter'] = 'Se désinscrire des newsletters';
$lang['unsubscribe.newsletter_choice'] = 'Choisissez les newsletters ou vous souhaitez rester abonner';

//Archives
$lang['archives.stream_name'] = 'Nom du flux';
$lang['archives.name'] = 'Nom de la newsletter';
$lang['archives.date'] = 'Date de publication';
$lang['archives.nbr_subscribers'] = 'Nombre d\'inscrit';
$lang['archives.not_archives'] = 'Aucune archive n\'est disponible';

//Add newsletter
$lang['add.choice_streams'] = 'Choisissez le ou les flux ou vous souhaitez envoyer cette newsletter';
$lang['add.send_test'] = 'Envoyer un mail de test';
$lang['add.add_newsletter'] = 'Ajouter une newsletter';

//Types newsletters
$lang['newsletter.types.choice'] = 'Veuillez séléctionner un type de message';
$lang['newsletter.types.null'] = '--';
$lang['newsletter.types.text'] = 'Texte';
$lang['newsletter.types.text_explain'] = '<span style="color:green;"><strong>Pour tous</strong></span><br />Vous ne pourrez procéder à aucune mise en forme du message.';
$lang['newsletter.types.bbcode'] = 'BBCode';
$lang['newsletter.types.bbcode_explain'] = '<span style="color:green;"><strong>Pour tous</strong></span><br />Vous pouvez formater le texte grâce au BBCode, le langage de mise en forme simplifiée adopté sur tout le portail.';
$lang['newsletter.types.html'] = 'HTML';
$lang['newsletter.types.html_explain'] = '<span style="color:red;"><strong>Utilisateurs expérimentés seulement</strong></span><br />Vous pouvez mettre en forme le texte à votre guise, mais vous devez connaître le langage html.';
$lang['newsletter.types.next'] = 'Suivant';

//Other
$lang['newsletter.page'] = 'Page';
$lang['newsletter.no_newsletters'] = 'Aucune newsletter de disponible';
$lang['unsubscribe_newsletter'] = 'Se désabonner de cette newsletter';
$lang['newsletter.view_archives'] = 'Voir les archives';
$lang['newsletter.view_subscribers'] = 'Voir les inscrits';
$lang['newsletter.title'] = 'Titre de la newsletter';
$lang['newsletter.contents'] = 'Contenu';
$lang['newsletter.visitor'] = 'Visiteur';
$lang['newsletter.submit'] = 'OK';

//Errors
$lang['admin.success-saving-config'] = 'Vous avez modifié avec succès la configuration';
$lang['admin.success-add-stream'] = 'Catégorie ajoutée avec succès';
$lang['admin.stream-not-existed'] = 'La catégorie demandée n\'existe pas';
$lang['admin.success-add-stream'] = 'La catégorie a bien été ajouté';
$lang['admin.success-edit-stream'] = 'La catégorie a bien été modifié';
$lang['admin.success-delete-stream'] = 'La catégorie a bien été supprimé';
$lang['success-subscribe'] = 'Vous vous êtes inscrit au(x) newsletter(s) avec succès';
$lang['success-unsubscribe'] = 'Vous vous êtes désinscrit des newsletters avec succès';
$lang['success-delete-subscriber'] = 'Vous avez supprimé la personne inscrite avec succès';
$lang['success-edit-subscriber'] = 'Vous avez édité la personne inscrite avec succès';
$lang['error-subscriber-not-existed'] = 'L\'incrit n\'existe pas';
$lang['error-archive-not-existed'] = 'L\'archive n\'existe pas';
$lang['newsletter.success-add'] = 'La newsletter a bien été ajouté et envoyé';
$lang['newsletter.success-send-test'] = 'Le mail de test a bien été envoyé';

//Authorizations
$lang['newsletter.not_level'] = 'Vous n\'avez pas les autorisations';
$lang['errors.not_authorized_read'] = 'Vous n\'avez pas les autorisations nécessaires pour voir cette page';
$lang['errors.not_authorized_subscribe'] = 'Vous n\'avez pas les autorisations nécessaires pour vous enregistrer';
$lang['errors.not_authorized_read_subscribers'] = 'Vous n\'avez pas les autorisations nécessaires pour voir les inscrits';
$lang['errors.not_authorized_moderation_subscribers'] = 'Vous n\'avez pas les autorisations nécessaires pour modérer et gérer les inscrits';
$lang['errors.not_authorized_create_newsletters'] = 'Vous n\'avez pas les autorisations nécessaires pour créer une newsletter';
$lang['errors.not_authorized_read_archives'] = 'Vous n\'avez pas les autorisations nécessaires pour voir les archives';

//Register extended field
$lang['extended_fields.newsletter.name'] = 'S\'abonner aux newsletters';
$lang['extended_fields.newsletter.description'] = 'Séléctionner le(s) newsletter(s) dont vous souhaitez être inscrit';
?>