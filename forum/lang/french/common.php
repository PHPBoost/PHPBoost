<?php
/*##################################################
 *                               common.php
 *                            -------------------
 *   begin                : February 25, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
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

$lang['module_title'] = 'Forum';
$lang['module_config_title'] = 'Configuration du module forum';

$lang['forum.actions.add_rank'] = 'Ajouter un rang';
$lang['forum.manage_ranks'] = 'Gérer les rangs';
$lang['forum.ranks_management'] = 'Gestion des rangs';

$lang['xml_forum_desc'] = 'Derniers sujets du forum';
$lang['go_top'] = 'Remonter';
$lang['go_bottom'] = 'Descendre';

$lang['forum.links'] = 'Liens';
$lang['forum.message_options'] = 'Options du message';

//config
$lang['config.forum_name'] = 'Nom du forum';
$lang['config.number_topics_per_page'] = 'Nombre de sujets par page';
$lang['config.number_messages_per_page'] = 'Nombre de messages par page';
$lang['config.read_messages_storage_duration'] = 'Durée pour laquelle les messages lus par les membres sont stockés';
$lang['config.read_messages_storage_duration.explain'] = 'En jours. A régler suivant le nombre de messages par jour.';
$lang['config.max_topic_number_in_favorite'] = 'Nombre de sujets en favoris maximum pour chaque membre';
$lang['config.edit_mark_enabled'] = 'Marqueurs d\'édition des messages';
$lang['config.multiple_posts_allowed'] = 'Autoriser les utilisateurs à poster plusieurs messages consécutifs';
$lang['config.multiple_posts_allowed.explain'] = 'Si l\'option est désactivée, le dernier message de l\'utilisateur sera automatiquement complété avec le nouveau contenu lors de l\'ajout d\'un message';
$lang['config.connexion_form_displayed'] = 'Afficher le formulaire de connexion';
$lang['config.left_column_disabled'] = 'Masquer les blocs de gauche du site sur le forum';
$lang['config.right_column_disabled'] = 'Masquer les blocs de droite du site sur le forum';
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
$lang['extended-field.field.website-explain'] = 'Veuillez renseigner un site web valide (ex : http://www.phpboost.com)';

$lang['extended-field.field.skype'] = 'Skype';
$lang['extended-field.field.skype-explain'] = '';

$lang['extended-field.field.signing'] = 'Signature';
$lang['extended-field.field.signing-explain'] = 'Apparaît sous chacun de vos messages';

//authorizations
$lang['authorizations.read_topics_content'] = 'Autorisation d\'afficher le contenu des topics';
$lang['authorizations.flood'] = 'Autorisation de flooder';
$lang['authorizations.hide_edition_mark'] = 'Désactivation du marqueur d\'édition des messages';
$lang['authorizations.unlimited_topics_tracking'] = 'Désactivation de la limite de sujet suivis';
?>
