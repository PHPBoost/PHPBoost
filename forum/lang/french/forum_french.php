<?php
/*##################################################
 *                              forum_french.php
 *                            -------------------
 *   begin                : November 21, 2006
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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
#                                                           French                                                                 #
 ####################################################

global $CONFIG;

//Admin
$LANG['parent_category'] = 'Catégorie parente';
$LANG['subcat'] = 'Sous-catégorie';
$LANG['url_explain'] = 'Transforme le forum en lien internet (http://...)';
$LANG['lock'] = 'Verrouillé';
$LANG['unlock'] = 'Déverrouillé';
$LANG['cat_edit'] = 'Editer une catégorie';
$LANG['del_cat'] = 'Outil de suppression de sous-catégorie';
$LANG['explain_topic'] = 'Le forum que vous désirez supprimer contient <strong>1</strong> sujet, voulez-vous le conserver en le transférant dans un autre forum, ou bien le supprimer?';
$LANG['explain_topics'] = 'Le forum que vous désirez supprimer contient <strong>%d</strong> sujets, voulez-vous les conserver en les transférants dans un autre forum, ou bien tout supprimer?';
$LANG['explain_subcat'] = 'Le forum que vous désirez supprimer contient <strong>1</strong> sous-forum, voulez-vous le conserver en le transférant dans un autre forum, ou bien le supprimer ainsi que son contenu?';
$LANG['explain_subcats'] = 'Le forum que vous désirez supprimer contient <strong>%d</strong> sous-forums, voulez-vous les conserver en les transférants dans un autre forum, ou bien les supprimer ainsi que leur contenu?';
$LANG['keep_topic'] = 'Conserver le(s) sujet(s)';
$LANG['keep_subforum'] = 'Conserver le(s) sous-forum(s)';
$LANG['move_topics_to'] = 'Déplacer le(s) sujet(s) vers';
$LANG['move_sub_forums_to'] = 'Déplacer le(s) sous-forum(s) vers';
$LANG['cat_target'] = 'Catégorie cible';
$LANG['del_all'] = 'Suppression complète';
$LANG['del_forum_contents'] = 'Supprimer le forum "<strong>%s</strong>", ses <strong>sous-forums</strong> et <strong>tout</strong> son contenu <span class="text_small">(Définitif)</span>';
$LANG['forum_config'] = 'Configuration du forum';
$LANG['forum_management'] = 'Gestion du forum';
$LANG['forum_name'] = 'Nom du forum';
$LANG['nbr_topic_p'] = 'Nombre de sujets par page';
$LANG['nbr_topic_p_explain'] = 'Par défaut 20';
$LANG['nbr_msg_p'] = 'Nombre de messages par page';
$LANG['nbr_msg_p_explain'] = 'Par défaut 15';
$LANG['time_new_msg'] = 'Durée pour laquelle les messages lus par les membres sont stockés';
$LANG['time_new_msg_explain'] = 'A régler suivant le nombre de messages par jour, par défaut 30 jours';
$LANG['topic_track_max'] = 'Nombre maximum possible de sujets en favoris';
$LANG['topic_track_max_explain'] = 'Par défaut 40';
$LANG['edit_mark'] = 'Marqueurs d\'édition des messages';
$LANG['forum_display_connexion'] = 'Afficher le formulaire de connexion';
$LANG['no_left_column'] = 'Masquer les blocs de gauche du site sur le forum';
$LANG['no_right_column'] = 'Masquer les blocs de droite du site sur le forum';
$LANG['activ_display_msg'] = 'Activer le message devant le topic';
$LANG['display_msg'] = 'Message devant le titre du topic';
$LANG['explain_display_msg'] = 'Explication du message pour les membres';
$LANG['explain_display_msg_explain'] = 'Si statut non changé';
$LANG['explain_display_msg_bis_explain'] = 'Si statut changé';
$LANG['icon_display_msg'] = 'Icône associée';
$LANG['update_data_cached'] = 'Recompter le nombre de sujets et de messages';
$LANG['explain_forum_groups'] = 'Ces configurations affectent uniquement le forum';
$LANG['forum_groups_config'] = 'Configuration des groupes';
$LANG['flood_auth'] = 'Droit de flooder';
$LANG['edit_mark_auth'] = 'Désactivation du marqueur d\'édition des messages';
$LANG['track_topic_auth'] = 'Désactivation de la limite de sujet suivis';
$LANG['forum_read_feed'] = 'Lire le sujet';

//Requis
$LANG['require_topic_p'] = 'Veuillez entrer le nombre de sujets par page!';
$LANG['require_nbr_msg_p'] = 'Veuillez entrer le nombre de messages par page!';
$LANG['require_time_new_msg'] = 'Veuillez entrer une durée pour la vue des nouveaux messages!';
$LANG['require_topic_track_max'] = 'Veuillez entrer le nombre maximum de sujet suivis!';

//Erreurs
$LANG['e_topic_lock_forum'] = 'Sujet verrouillé, vous ne pouvez pas poster de message';
$LANG['e_cat_lock_forum'] = 'Catégorie verrouillée, création nouveau sujet/message impossible';
$LANG['e_unexist_topic_forum'] = 'Le topic que vous demandez n\'existe pas';
$LANG['e_unexist_cat_forum'] = 'La catégorie que vous demandez n\'existe pas';
$LANG['e_unable_cut_forum'] = 'Vous ne pouvez pas scinder le sujet à partir de ce message';
$LANG['e_cat_write'] = 'Vous n\'êtes pas autorisé à écrire dans cette catégorie';

//Alertes
$LANG['alert_delete_topic'] = 'Supprimer ce sujet ?';
$LANG['alert_lock_topic'] = 'Verrouiller ce sujet ?';
$LANG['alert_unlock_topic'] = 'Déverrouiller ce sujet ?';
$LANG['alert_move_topic'] = 'Déplacer ce sujet ?';
$LANG['alert_warning'] = 'Avertir ce membre ?';
$LANG['alert_history'] = 'Supprimer l\'historique ?';
$LANG['confirm_mark_as_read'] = 'Marquer tous les sujets comme lus ?';
$LANG['contribution_alert_moderators_for_topics'] = 'Sujet non conforme : %s';

//Titres
$LANG['title_forum'] = 'Forum';
$LANG['title_topic'] = 'Sujet';
$LANG['title_post'] = 'Poster';
$LANG['title_search'] = 'Chercher';

//Forum
$LANG['forum_index'] = 'Index';
$LANG['forum'] = 'Forum';
$LANG['forum_s'] = 'Forums';
$LANG['subforum_s'] = 'Sous-forums';
$LANG['topic'] = 'Sujet';
$LANG['topic_s'] = 'Sujets';
$LANG['author'] = 'Auteur';
$LANG['advanced_search'] = 'Recherche avancée';
$LANG['distributed'] = 'Répartis en';
$LANG['mark_as_read'] = 'Marquer comme lu';
$LANG['show_topic_track'] = 'Sujets suivis';
$LANG['no_msg_not_read'] = 'Aucun message non lu';
$LANG['show_not_reads'] = 'Messages non lus';
$LANG['show_last_read'] = 'Derniers messages lus';
$LANG['no_last_read'] = 'message lu';
$LANG['last_message'] = 'Dernier message';
$LANG['last_messages'] = 'Derniers messages';
$LANG['forum_new_subject'] = 'Nouveau sujet';
$LANG['post_new_subject'] = 'Poster un nouveau sujet';
$LANG['forum_edit_subject'] = 'Editer Sujet';
$LANG['forum_announce'] = 'Annonce';
$LANG['forum_postit'] = 'Epinglé';
$LANG['forum_lock'] = 'Verrouiller';
$LANG['forum_unlock'] = 'Déverrouiller';
$LANG['forum_move'] = 'Déplacer';
$LANG['forum_move_subject'] = 'Déplacer le sujet';
$LANG['forum_quote_last_msg'] = 'Reprise du message précédent';
$LANG['edit_message'] = 'Editer Message';
$LANG['edit_by'] = 'Edité par';
$LANG['no_message'] = 'Pas de message';
$LANG['group'] = 'Groupe';
$LANG['cut_topic'] = 'Scinder le sujet à partir de ce message';
$LANG['forum_cut_subject'] = 'Scinder le sujet';
$LANG['alert_cut_topic'] = 'Voulez-vous scinder le sujet à partir de ce message?';
$LANG['track_topic'] = 'Mettre en favori';
$LANG['untrack_topic'] = 'Retirer des favoris';
$LANG['track_topic_pm'] = 'Suivre par message privé';
$LANG['untrack_topic_pm'] = 'Arrêter le suivi message privé';
$LANG['track_topic_mail'] = 'Suivre par mail';
$LANG['untrack_topic_mail'] = 'Arrêter le suivi mail';
$LANG['alert_topic'] = 'Alerter les modérateurs';
$LANG['banned'] = 'Banni';
$LANG['xml_forum_desc'] = 'Derniers sujets du forum';
$LANG['alert_modo_explain'] = 'Vous êtes sur le point d\'alerter les modérateurs. Vous aidez l\'équipe modératrice en lui signalant des topics qui ne respectent pas certaines règles, mais sachez que lorsque vous alertez un modérateur votre pseudo est enregistré, il est donc nécessaire que votre demande soit justifiée sans quoi vous risquez des sanctions de la part de l\'équipe des modérateurs et administrateurs en cas d\'abus. Afin d\'aider l\'équipe, merci d\'expliquer ce qui ne respecte pas les conditions dans ce sujet. 

Vous désirez alerter les modérateurs d\'un problème sur le sujet suivant';
$LANG['alert_title'] = 'Brève description';
$LANG['alert_contents'] = 'Merci de détailler davantage le problème afin d\'aider l\'équipe modératrice';
$LANG['alert_success'] = 'Vous avez signalé avec succès la non-conformité du sujet <em>%title</em>, l\'équipe modératrice vous remercie de l\'avoir aidée.';
$LANG['alert_topic_already_done'] = 'Nous vous remercions d\'avoir pris l\'initiative d\'aider l\'équipe modératrice, mais un membre a déjà signalé une non-conformité de ce sujet.';
$LANG['alert_back'] = 'Retour au sujet';
$LANG['explain_track'] = 'Cochez la case Mp pour recevoir un message privé, Mail pour un email lors d\'une réponse au sujet que vous suivez. Cochez la case supprimer pour ne plus suivre le sujet.';
$LANG['sub_forums'] = 'Sous-forums';
$LANG['moderation_forum'] = 'Modération du forum';
$LANG['no_topics'] = 'Aucun sujet à afficher';
$LANG['for_selection'] = 'Pour la sélection';
$LANG['change_status_to'] = 'Mettre le statut: %s';
$LANG['change_status_to_default'] = 'Mettre le statut par défaut';
$LANG['move_to'] = 'Déplacer vers...';

//Recherche
$LANG['search_forum'] = 'Recherche sur le Forum';
$LANG['relevance'] = 'Pertinence';
$LANG['no_result'] = 'Aucun résultat';
$LANG['invalid_req'] = 'Requête invalide';
$LANG['keywords'] = 'Mots clés (4 caractères minimum)';
$LANG['colorate_result'] = 'Colorer les résultats';

//Stats
$LANG['stats'] = 'Statistiques';
$LANG['nbr_topics_day'] = 'Nombre de sujets par jour';
$LANG['nbr_msg_day'] = 'Nombre de messages par jour';
$LANG['nbr_topics_today'] = 'Nombre de sujets aujourd\'hui';
$LANG['nbr_msg_today'] = 'Nombre de messages aujourd\'hui';
$LANG['forum_last_msg'] = 'Les 10 derniers sujets ayant eu une réponse';
$LANG['forum_popular'] = 'Les 10 sujets les plus populaires';
$LANG['forum_nbr_answers'] = 'Les 10 sujets ayant eu le plus de réponses';

//Historique
$LANG['history'] = 'Historique des actions';
$LANG['history_member_concern'] = 'Membre concerné';
$LANG['no_action'] = 'Aucune action enregistrée';
$LANG['delete_msg'] = 'Suppression d\'un message';
$LANG['delete_topic'] = 'Suppression d\'un sujet';
$LANG['lock_topic'] = 'Verrouillage d\'un sujet';
$LANG['unlock_topic'] = 'Déverrouillage d\'un sujet';
$LANG['move_topic'] = 'Déplacement d\'un sujet';
$LANG['cut_topic'] = 'Scindement d\'un sujet';
$LANG['warning_on_user'] = '+10% à un membre';
$LANG['warning_off_user'] = '-10% à un membre';
$LANG['set_warning_user'] = 'Modification pourcentage avertissement';
$LANG['more_action'] = 'Voir 100 actions en plus';
$LANG['ban_user'] = 'Bannissement d\'un membre';
$LANG['edit_msg'] = 'Edition message d\'un membre';
$LANG['edit_topic'] = 'Edition sujet d\'un membre';
$LANG['solve_alert'] = 'Résolution d\'une alerte';
$LANG['wait_alert'] = 'Mise en attente d\'une alerte';
$LANG['del_alert'] = 'Suppression d\'une alerte';

//Messages du membre
$LANG['show_member_msg'] = 'Voir tous les messages du membre';

//Sondages
$LANG['poll'] = 'Sondage';
$LANG['mini_poll'] = 'Mini Sondage';
$LANG['poll_main'] = 'Voila l\'espace de sondage du site, profitez en pour donner votre avis, ou tout simplement répondre aux sondages.';
$LANG['poll_back'] = 'Retour au(x) sondage(s)';
$LANG['redirect_none'] = 'Aucun sondage disponible';
$LANG['confirm_vote'] = 'Votre vote a bien été pris en compte';
$LANG['already_vote'] = 'Vous avez déjà voté';
$LANG['no_vote'] = 'Votre vote nul a été considéré';
$LANG['poll_vote'] = 'Vote';
$LANG['poll_vote_s'] = 'Votes';
$LANG['poll_result'] = 'Résultats';
$LANG['alert_delete_poll'] = 'Supprimer ce sondage ?';
$LANG['unauthorized_poll'] = 'Vous n\'êtes pas autorisé à voter !';
$LANG['question'] = 'Question';
$LANG['answers'] = 'Réponses';
$LANG['poll_type'] = 'Type de sondage';
$LANG['open_menu_poll'] = 'Ouvrir le menu sondage';
$LANG['simple_answer'] = 'Simple réponse';
$LANG['multiple_answer'] = 'Multiple réponses';
$LANG['delete_poll'] = 'Supprimer le sondage';
$LANG['require_title_poll'] = 'Veuillez entrer un titre pour le sondage!';

//Post
$LANG['forum_mail_title_new_post'] = 'Nouveau message sur le forum';
$LANG['forum_mail_new_post'] = 'Cher %s

Vous suivez le sujet: %s
 
Vous avez demandé à être averti lors d\'une réponse à celui-ci.

%s a répondu sur le sujet: 
%s

[Suite du message : %s]




Si vous ne désirez plus être averti des réponses de ce sujet, cliquez sur le lien ci-dessous. 
%s

' . MailServiceConfig::load()->get_mail_signature();

//Gestion des alertes
$LANG['alert_management'] = 'Gestion des alertes';
$LANG['alert_concerned_topic'] = 'Sujet concerné';
$LANG['alert_concerned_cat'] = 'Catégorie du sujet concerné';
$LANG['alert_login'] = 'Posteur de l\'alerte';
$LANG['alert_msg'] = 'Précisions';
$LANG['alert_not_solved'] = 'En attente de traitement';
$LANG['alert_solved'] = 'Résolue par ';
$LANG['change_status_to_0'] = 'Mettre en attente de traitement';
$LANG['change_status_to_1'] = 'Mettre en résolu';
$LANG['no_alert'] = 'Il n\'y a aucune alerte pour l\'instant';
$LANG['alert_not_auth'] = 'Cette alerte a été postée dans un forum dans lequel vous n\'êtes pas modérateur.';
$LANG['delete_several_alerts'] = 'Etes vous sur de vouloir supprimer les alertes sélectionnées?';
$LANG['new_alerts'] = 'nouvelle alerte';
$LANG['new_alerts_s'] = 'nouvelles alertes';
$LANG['action'] = 'Action';

?>