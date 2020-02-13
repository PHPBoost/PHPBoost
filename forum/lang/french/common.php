<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2020 02 10
 * @since   	PHPBoost 4.1 - 2015 02 25
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    French                        #
####################################################

$lang['module_title'] = 'Forum';
$lang['module_config_title'] = 'Configuration du module forum';

$lang['forum.actions.add_rank'] = 'Ajouter un rang';
$lang['forum.manage_ranks'] = 'Gérer les rangs';
$lang['forum.ranks_management'] = 'Gestion des rangs';
$lang['forum.no_answer'] = 'Sujets sans réponse';

$lang['xml_forum_desc'] = 'Derniers sujets du forum';
$lang['go_top'] = 'Remonter';
$lang['go_bottom'] = 'Descendre';
$lang['forum.connected.mbr.yes'] = 'Membre connecté';
$lang['forum.connected.mbr.no'] = 'Membre non connecté';

$lang['forum.links'] = 'Liens';
$lang['forum.message_options'] = 'Options du message';
$lang['forum.messages'] = 'Messages sur le forum';

//config
$lang['config.forum_name'] = 'Nom du forum';
$lang['config.number_topics_per_page'] = 'Nombre de sujets par page';
$lang['config.number_messages_per_page'] = 'Nombre de messages par page';
$lang['config.read_messages_storage_duration'] = 'Durée pour laquelle les messages lus par les membres sont stockés';
$lang['config.read_messages_storage_duration.explain'] = 'En jours. A régler suivant le nombre de messages par jour.';
$lang['config.max_topic_number_in_favorite'] = 'Nombre de sujets maximum en favoris pour chaque membre';
$lang['config.edit_mark_enabled'] = 'Marqueurs d\'édition des messages';
$lang['config.multiple_posts_allowed'] = 'Autoriser les utilisateurs à poster plusieurs messages consécutifs';
$lang['config.multiple_posts_allowed.explain'] = 'Si l\'option est désactivée, le dernier message de l\'utilisateur sera automatiquement complété avec le nouveau contenu lors de l\'ajout d\'un message';
$lang['config.connexion_form_displayed'] = 'Afficher le formulaire de connexion';
$lang['config.message_before_topic_title_displayed'] = 'Afficher le message devant le titre du topic';
$lang['config.message_before_topic_title'] = 'Message devant le titre du topic';
$lang['config.message_when_topic_is_unsolved'] = 'Message devant le titre du topic si statut non changé';
$lang['config.message_when_topic_is_solved'] = 'Message devant le titre du topic si statut changé';
$lang['config.message_before_topic_title_icon_displayed'] = 'Afficher l\'icône associée';
$lang['config.message_before_topic_title_icon_displayed.explain'] = '<i class="fa fa-msg-display"></i> / <i class="fa fa-msg-not-display"></i>';

//Categories
$lang['category.status.locked'] = 'Verrouillé';

//Extended Field
$lang['extended-field.field.website'] = 'Site web';
$lang['extended-field.field.website-explain'] = 'Veuillez renseigner un site web valide (ex : https://www.phpboost.com)';

$lang['extended-field.field.skype'] = 'Skype';
$lang['extended-field.field.skype-explain'] = '';

$lang['extended-field.field.signing'] = 'Signature';
$lang['extended-field.field.signing-explain'] = 'Apparaît sous chacun de vos messages';

//authorizations
$lang['authorizations.read_topics_content'] = 'Autorisation d\'afficher le contenu des topics';
$lang['authorizations.flood'] = 'Autorisation de flooder';
$lang['authorizations.hide_edition_mark'] = 'Désactivation du marqueur d\'édition des messages';
$lang['authorizations.unlimited_topics_tracking'] = 'Désactivation de la limite de sujet suivis';
$lang['authorizations.multiple_posts'] = 'Utilisateurs autorisés à poster plusieurs messages consécutifs';
?>
