<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 19
 * @since       PHPBoost 1.6 - 2006 11 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

####################################################
#                    French                        #
####################################################

//Admin
$LANG['config.ranks.manager'] = 'Gestion des rangs du forum';

//Erreurs
$LANG['e.forum.topic.locked'] = 'Sujet verrouillé, vous ne pouvez pas poster de message';
$LANG['e.category.forum.locked'] = 'Forum verrouillé, création de nouveau sujet/message impossible';
$LANG['e.forum.nonexistent.topic'] = 'Le topic que vous demandez n\'existe pas';
$LANG['e.forum.noncuttable.topic'] = 'Vous ne pouvez pas scinder le sujet à partir de ce message';
$LANG['e.category.right'] = 'Vous n\'êtes pas autorisé à écrire dans cette catégorie';

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

//Forum
$LANG['forum_index'] = 'Index';
$LANG['forum'] = 'Forum';
$LANG['forum_s'] = 'Forums';
$LANG['subforum_s'] = 'Sous-forums';
$LANG['topic'] = 'Sujet';
$LANG['topic_s'] = 'Sujets';
$LANG['author'] = 'Auteur';
$LANG['distributed'] = 'Répartis en';
$LANG['mark_as_read'] = 'Marquer comme lu';
$LANG['show_topic_track'] = 'Sujets suivis';
$LANG['no_msg_not_read'] = 'Aucun message non lu';
$LANG['show_not_reads'] = 'Messages non lus';
$LANG['show_my_msg'] = 'Voir mes messages';
$LANG['show_last_read'] = 'Derniers messages lus';
$LANG['show_no_answer'] = 'Messages sans réponse';
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
$LANG['edit_on'] = 'Edité le';
$LANG['no_message'] = 'Pas de message';
$LANG['group'] = 'Groupe';
$LANG['cut_topic'] = 'Scinder le sujet à partir de ce message';
$LANG['forum_cut_subject'] = 'Scinder le sujet';
$LANG['alert_cut_topic'] = 'Voulez-vous scinder le sujet à partir de ce message ?';
$LANG['track_topic'] = 'Mettre en favori';
$LANG['untrack_topic'] = 'Retirer des favoris';
$LANG['track_topic_pm'] = 'Suivre par message privé';
$LANG['untrack_topic_pm'] = 'Arrêter le suivi par message privé';
$LANG['track_topic_mail'] = 'Suivre par mail';
$LANG['untrack_topic_mail'] = 'Arrêter le suivi par mail';
$LANG['alert_topic'] = 'Alerter les modérateurs';
$LANG['alert_modo_explain'] = 'Vous êtes sur le point d\'alerter les modérateurs. Vous aidez l\'équipe modératrice en lui signalant des topics qui ne respectent pas certaines règles, mais sachez que lorsque vous alertez un modérateur votre pseudo est enregistré, il est donc nécessaire que votre demande soit justifiée sans quoi vous risquez des sanctions de la part de l\'équipe des modérateurs et administrateurs en cas d\'abus. Afin d\'aider l\'équipe, merci d\'expliquer ce qui ne respecte pas les conditions dans ce sujet.

Vous désirez alerter les modérateurs d\'un problème sur le sujet suivant';
$LANG['alert_title'] = 'Brève description';
$LANG['alert_contents'] = 'Merci de détailler davantage le problème afin d\'aider l\'équipe modératrice';
$LANG['alert_success'] = 'Vous avez signalé avec succès la non-conformité du sujet <em>%title</em>, l\'équipe modératrice vous remercie de l\'avoir aidée.';
$LANG['alert_topic_already_done'] = 'Nous vous remercions d\'avoir pris l\'initiative d\'aider l\'équipe modératrice, mais un membre a déjà signalé une non-conformité de ce sujet.';
$LANG['alert_back'] = 'Retour au sujet';
$LANG['explain_track'] = '
    Cocher la case <span class="text-strong">MP</span> (<i class="far fa-envelope"></i>) pour recevoir un message privé,
    <span class="text-strong">Mail</span> (<i class="fa fa-at"></i>) pour un email, lors d\'une réponse au sujet suivi.
    <br />Cocher la case <span class="text-strong">supprimer</span> (<i class="fa fa-trash-alt"></i>) pour ne plus suivre le sujet.';
$LANG['sub_forums'] = 'Sous-forums';
$LANG['moderation_forum'] = 'Modération du forum';
$LANG['no_topics'] = 'Aucun sujet à afficher';
$LANG['for_selection'] = 'Pour la sélection';
$LANG['change_status_to'] = 'Mettre le statut: %s';
$LANG['change_status_to_default'] = 'Mettre le statut par défaut';
$LANG['move_to'] = 'Déplacer vers...';

//Recherche
$LANG['no_result'] = 'Aucun résultat';

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
$LANG['poll_main'] = 'Voilà l\'espace de sondage du site, profitez en pour donner votre avis, ou tout simplement répondre aux sondages.';
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
$LANG['simple_answer'] = 'Réponse simple';
$LANG['multiple_answer'] = 'Réponses multiples';
$LANG['delete_poll'] = 'Supprimer le sondage';
$LANG['require_title_poll'] = 'Veuillez entrer un titre pour le sondage !';

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
$LANG['alert_not_auth'] = 'Cette alerte a été postée dans un forum dans lequel vous n\'êtes pas modérateur.';
$LANG['delete_several_alerts'] = 'Etes vous sur de vouloir supprimer les alertes sélectionnées ?';
$LANG['new_alerts'] = 'nouvelle alerte';
$LANG['new_alerts_s'] = 'nouvelles alertes';
$LANG['action'] = 'Action';

//Ranks
$LANG['upload_rank'] = 'Uploader une image de rang';
$LANG['explain_upload_img'] = 'JPG, GIF, PNG, BMP autorisés';
$LANG['rank'] = 'Rang';
$LANG['special_rank'] = 'Rang spécial';
$LANG['rank_name'] = 'Nom du Rang';
$LANG['nbr_msg'] = 'Nombre de message(s)';
$LANG['img_assoc'] = 'Image associée';
$LANG['require_rank_name'] = 'Veuillez entrer un nom pour le rang !';
$LANG['require_nbr_msg_rank'] = 'Veuillez entrer un nombre de messages pour le rang !';

//SEO
$LANG['member_msg_seo'] = 'Tous les messages du forum d\'un membre';
$LANG['root_description_seo'] = 'Toutes les catégories du forum du site :site.';
$LANG['show_no_answer_seo'] = 'Liste des messages sans réponse';
$LANG['stats_seo'] = 'Toutes les statistiques du forum';
$LANG['topic_title_seo'] = 'Topic :title du forum :forum';
?>
