<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 02
 * @since       PHPBoost 4.1 - 2015 02 25
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    French                        #
####################################################

$lang['forum.module.title'] = 'Forum';

$lang['forum.my.items']     = 'Mes messages';
$lang['forum.member.items'] = 'Messages publiés par';

// Authorizations
$lang['forum.authorizations.read.topics.content']       = 'Autorisation d\'afficher le contenu des topics';
$lang['forum.authorizations.flood']                     = 'Autorisation de flooder';
$lang['forum.authorizations.hide.edition.mark']         = 'Désactivation du marqueur d\'édition des messages';
$lang['forum.authorizations.unlimited.topics.tracking'] = 'Désactivation de la limite de sujets suivis';
$lang['forum.authorizations.multiple.posts']            = 'Utilisateurs autorisés à poster plusieurs messages consécutifs';

// Categories
$lang['forum.category.status.locked']    = 'Verrouillé';
$lang['forum.category.icon']             = 'Icône Font-Awesome';
$lang['forum.category.icon.clue']        = 'Ne s\'affiche dans le forum que si aucune vignette n\'est sélectionnée.';
$lang['forum.category.icon.placeholder'] = 'fa{b,r,s,t} fa-{icon-name} fa-{options} ...';

// Configuration
$lang['forum.config.forum.name']                   = 'Nom du forum';
$lang['forum.config.topics.per.page']              = 'Nombre de sujets par page';
$lang['forum.config.messages.per.page']            = 'Nombre de messages par page';
$lang['forum.config.read.messages.storage']        = 'Durée de stockage des messages lus';
$lang['forum.config.read.messages.storage.clue']   = 'En jours. A régler suivant le nombre de messages quotidiens.';
$lang['forum.config.favorite.topics.number']       = 'Nombre maximum de sujets en favoris pour chaque membre';
$lang['forum.config.enable.edit.marker']           = 'Marqueurs d\'édition des messages';
$lang['forum.config.enable.multiple.posts']        = 'Autoriser les utilisateurs à poster plusieurs messages consécutifs';
$lang['forum.config.enable.multiple.posts.clue']   = 'Si l\'option est désactivée, le dernier message de l\'utilisateur sera automatiquement complété avec le nouveau contenu lors de l\'ajout d\'un message';
$lang['forum.config.display.connexion.form']       = 'Afficher le formulaire de connexion';
$lang['forum.config.display.thumbnails']           = 'Afficher les vignettes de catégorie';
$lang['forum.config.display.message.before.topic'] = 'Afficher un message devant le titre du topic';
$lang['forum.config.message.before.topic']         = 'Message devant le titre du topic';
$lang['forum.config.status.message.unsolved']      = 'Message devant le titre du topic si statut non changé';
$lang['forum.config.status.message.solved']        = 'Message devant le titre du topic si statut changé';
$lang['forum.config.display.issue.status.icon']    = 'Afficher l\'icône associée';
    // Default
$lang['forum.config.default.forum.name']    = GeneralConfig::load()->get_site_name() . ' forum';
$lang['forum.config.issue.status']          = '[Réglé]';
$lang['forum.config.issue.status.unsolved'] = 'Sujet réglé ?';
$lang['forum.config.issue.status.solved']   = 'Sujet non réglé ?';

// Hooks
$lang['forum.specific_hook.forum_add_topic']              = 'Ajout d\'un sujet';
$lang['forum.specific_hook.forum_edit_topic']             = 'Modification d\'un sujet';
$lang['forum.specific_hook.forum_delete_topic']           = 'Suppression d\'un sujet';
$lang['forum.specific_hook.forum_lock_topic']             = 'Verrouillage d\'un sujet';
$lang['forum.specific_hook.forum_unlock_topic']           = 'Déverrouillage d\'un sujet';
$lang['forum.specific_hook.forum_move_topic']             = 'Déplacement d\'un sujet';
$lang['forum.specific_hook.forum_move_topic.description'] = 'Sujet déplacé de <span class="text-strong">:old_category</span> à <span class="text-strong">:new_category</span>.';
$lang['forum.specific_hook.forum_add_poll']               = 'Ajout d\'un sondage';
$lang['forum.specific_hook.forum_edit_poll']              = 'Modification d\'un sondage';
$lang['forum.specific_hook.forum_answer_poll']            = 'Réponse à un sondage';

// Email
$lang['forum.email.title.new.post'] = 'Nouveau message sur le forum';
$lang['forum.email.new.post'] = 'Cher.e %s

Vous suivez le sujet: %s

Vous avez demandé à être averti lors d\'une réponse à celui-ci.

%s a répondu sur le sujet:
%s

[Suite du message : %s]




Si vous ne désirez plus être averti des réponses de ce sujet, cliquez sur le lien ci-dessous.
%s

' . MailServiceConfig::load()->get_mail_signature();

// Extended fields
$lang['forum.extended.field.skype']      = 'Skype';
$lang['forum.extended.field.skype.clue'] = '';

$lang['forum.extended.field.signing']      = 'Signature';
$lang['forum.extended.field.signing.clue'] = 'Apparaît sous chacun de vos messages';

// Forum table (index | categories | forums | specials)
$lang['forum.post.new.topic']  = 'Poster un nouveau sujet';
$lang['forum.forum']           = 'Forum';
$lang['forum.forums']          = 'Forums';
$lang['forum.topic.status']    = 'État du sujet';
$lang['forum.popular.topic']   = 'Sujet populaire';
$lang['forum.new.topic']       = 'Nouveau sujet';
$lang['forum.topic.options']   = 'Options du message';
$lang['forum.topic.author']    = 'Auteur du sujet';
$lang['forum.topics.number']   = 'Nombre de sujets';
$lang['forum.messages.number'] = 'Nombre de messages';
$lang['forum.answers.number']  = 'Nombre de réponses';
$lang['forum.views.number']    = 'Nombre de vues';
$lang['forum.sub.forums']      = 'Sous-forums';
$lang['forum.topic']           = 'Sujet';
$lang['forum.topics']          = 'Sujets';
$lang['forum.last.message']    = 'Dernier message';
$lang['forum.last.messages']   = 'Derniers messages';
$lang['forum.see.message']     = 'Voir ce message';
    // bottom
$lang['forum.distributed']    = 'répartis en';
$lang['forum.online.users']   = 'Utilisateurs en ligne';
$lang['forum.no.online.user'] = 'Aucun membre connecté';
$lang['forum.statistics']     = 'Statistiques du forum';
    // no item
$lang['forum.no.topic']          = 'Aucun sujet à afficher';
$lang['forum.no.message.now']    = 'Il n\'y a aucun message pour l\'instant';
$lang['forum.no.unread.message'] = 'Aucun message non lu';

// History
$lang['forum.history']        = 'Historique des actions';
$lang['forum.concerned.user'] = 'Membre concerné';
    // actions
$lang['no_action']        = 'Aucune action enregistrée';
$lang['delete_msg']       = 'Suppression d\'un message';
$lang['delete_topic']     = 'Suppression d\'un sujet';
$lang['lock_topic']       = 'Verrouillage d\'un sujet';
$lang['unlock_topic']     = 'Déverrouillage d\'un sujet';
$lang['move_topic']       = 'Déplacement d\'un sujet';
$lang['cut_topic']        = 'Scindement d\'un sujet';
$lang['warning_on_user']  = '+10% à un membre';
$lang['warning_off_user'] = '-10% à un membre';
$lang['set_warning_user'] = 'Modification du % d\'avertissement';
$lang['more_action']      = 'Voir 100 actions en plus';
$lang['ban_user']         = 'Bannissement d\'un membre';
$lang['readonly_user']    = 'Membre en lecture seule';
$lang['edit_msg']         = 'Edition message d\'un membre';
$lang['edit_topic']       = 'Edition sujet d\'un membre';
$lang['solve_alert']      = 'Résolution d\'une alerte';
$lang['wait_alert']       = 'Mise en attente d\'une alerte';
$lang['del_alert']        = 'Suppression d\'une alerte';

// Links (top | bottom)
$lang['forum.links']                  = 'Liens';
$lang['forum.index']                  = 'Index';
$lang['forum.unanswered.topics']      = 'Sujets sans réponse';
$lang['forum.tracked.topics']         = 'Sujets suivis';
$lang['forum.last.read.messages']     = 'Derniers messages lus';
$lang['forum.unread.messages']        = 'Messages non lus';
$lang['forum.reload.unread.messages'] = 'Réactualiser les messages non lus';
$lang['forum.mark.topics.as.read']    = 'Marquer tous les sujets comme lu';

// Moderation
$lang['forum.moderation.forum']       = 'Modération du forum';
$lang['forum.for.selection']          = 'Pour la sélection';
$lang['forum.change.issue.status.to'] = 'Mettre le statut: %s';
$lang['forum.default.issue.status']   = 'Mettre le statut par défaut';
$lang['forum.no.moderation']          = 'Aucune action';
$lang['forum.set.as.selected']        = 'Sélectionner comme meilleure réponse';
$lang['forum.set.as.unselected']      = 'Désélectionner comme meilleure réponse';
    // Reports
        // User reports
$lang['forum.reports.management']        = 'Gestion des signalements';
$lang['forum.report.topic']              = 'Signaler ce sujet';
$lang['forum.report.topic.title']        = 'Signaler un sujet';
$lang['forum.report.concerned.topic']    = 'Sujet concerné';
$lang['forum.report.concerned.category'] = 'Catégorie du sujet concerné';
$lang['forum.report.author']             = 'Signalé par';
$lang['forum.report.message']            = 'Précisions';
$lang['forum.report.unsolved']           = 'En attente de traitement';
$lang['forum.report.solved']             = 'Résolue par ';
$lang['forum.report.change.to.unsolved'] = 'Mettre en attente de traitement';
$lang['forum.report.change.to.solved']   = 'Mettre en résolu';
$lang['forum.report.not.auth']           = 'Ce signalement a été posté dans un forum dans lequel vous n\'êtes pas modérateur.';
$lang['forum.delete.several.reports']    = 'Etes vous sur de vouloir supprimer les signalement sélectionnés ?';
$lang['forum.new.report']                = 'nouveau signalement';
$lang['forum.new.reports']               = 'nouveaux signalements';
$lang['forum.report.clue'] = '
    Vous êtes sur le point de signaler un sujet aux modérateurs.
    <br />Vous aidez l\'équipe modératrice en lui signalant des sujets qui ne respectent pas certaines règles,
    mais sachez que lorsque vous alertez un modérateur votre pseudo est enregistré.
    <br />Il est donc nécessaire que votre demande soit justifiée sans quoi vous risquez des sanctions de la part de l\'équipe des modérateurs et administrateurs en cas d\'abus. Afin d\'aider l\'équipe, merci d\'expliquer ce qui ne respecte pas les conditions dans ce sujet.
    <br /><br />
    Vous désirez alerter les modérateurs d\'un problème sur le sujet suivant:
';
$lang['forum.report.title']              = 'Brève description';
$lang['forum.report.content']            = 'Merci de détailler davantage le problème afin d\'aider l\'équipe modératrice';
$lang['forum.report.success']            = 'Vous avez signalé avec succès la non-conformité du sujet <em>%title</em>, l\'équipe modératrice vous remercie de l\'avoir aidée.';
$lang['forum.report.topic.already.done'] = 'Nous vous remercions d\'avoir pris l\'initiative d\'aider l\'équipe modératrice, mais un membre a déjà signalé une non-conformité de ce sujet.';
$lang['forum.report.back']               = 'Retour au sujet';
$lang['forum.report.alternative.pm'] = '
    Message privé envoyé au membre
    <p class="smaller">(Laisser vide pour aucun message privé)</p>
    Le membre averti ne pourra pas répondre à ce message, et ne connaîtra pas l\'expéditeur.
';

// Poll
$lang['forum.poll']               = 'Sondage';
$lang['forum.mini.poll']          = 'Mini Sondage';
$lang['forum.poll.main']          = 'Voilà l\'espace de sondage du site, profitez en pour donner votre avis, ou tout simplement répondre aux sondages.';
$lang['forum.poll.back']          = 'Retour au(x) sondage(s)';
$lang['forum.redirect.none']      = 'Aucun sondage disponible';
$lang['forum.confirm.vote']       = 'Votre vote a bien été pris en compte';
$lang['forum.already.vote']       = 'Vous avez déjà voté';
$lang['forum.no.vote']            = 'Votre vote nul a été considéré';
$lang['forum.poll.cast.vote']     = 'Voter';
$lang['forum.poll.vote']          = 'Vote';
$lang['forum.poll.votes']         = 'Votes';
$lang['forum.poll.result']        = 'Résultats';
$lang['forum.alert.delete.poll']  = 'Supprimer ce sondage ?';
$lang['forum.unauthorized.poll']  = 'Vous n\'êtes pas autorisé à voter !';
$lang['forum.question']           = 'Question';
$lang['forum.answers']            = 'Réponses';
$lang['forum.poll.type']          = 'Type de sondage';
$lang['forum.open.poll.menu']     = 'Ouvrir le menu sondage';
$lang['forum.close.poll.menu']    = 'Fermer le menu sondage s\'il est vide';
$lang['forum.simple.answer']      = 'Réponse simple';
$lang['forum.multiple.answer']    = 'Réponses multiples';
$lang['forum.delete.poll']        = 'Supprimer le sondage';
$lang['forum.require.poll.title'] = 'Veuillez entrer un titre pour le sondage !';
$lang['forum.poll.results']       = 'Résultats du sondage';

// Ranks
$lang['forum.ranks.management']             = 'Gestion des rangs du forum';
$lang['forum.rank.add']                     = 'Ajouter un rang';
$lang['forum.upload.rank.thumbnail']        = 'Uploader une image pour le rang';
$lang['forum.upload.rank.thumbnail.clue']   = 'JPG, GIF, PNG, BMP autorisés';
$lang['forum.rank']                         = 'Rang';
$lang['forum.special.rank']                 = 'Rang spécial';
$lang['forum.rank.name']                    = 'Nom du Rang';
$lang['forum.rank.messages.number']         = 'Nombre de messages nécessaires pour atteindre ce rang';
$lang['forum.rank.thumbnail']               = 'Image associée';
$lang['forum.require.rank.name']            = 'Veuillez entrer un nom pour le rang !';
$lang['forum.require.rank.messages.number'] = 'Veuillez entrer un nombre de messages pour le rang !';

// S.E.O.
$lang['forum.member.messages.seo']  = 'Tous les messages de :author.';
$lang['forum.root.description.seo'] = 'Toutes les catégories du forum du site :site.';
$lang['forum.show.no.answer.seo']   = 'Liste des messages sans réponse';
$lang['forum.stats.seo']            = 'Toutes les statistiques du forum';
$lang['forum.topic.title.seo']      = 'Sujet :title du forum :forum';

// Search
$lang['forum.no.result'] = 'Aucun résultat';

// Stats
$lang['forum.stats']                   = 'Statistiques du forum';
$lang['forum.topics.number.per.day']   = 'Nombre de sujets par jour';
$lang['forum.messages.number.per.day'] = 'Nombre de messages par jour';
$lang['forum.topics.number.today']     = 'Nombre de sujets aujourd\'hui';
$lang['forum.messages.number.today']   = 'Nombre de messages aujourd\'hui';
$lang['forum.last.10.active.topics']   = 'Les 10 derniers sujets ayant eu une réponse';
$lang['forum.10.most.popular.topics']  = 'Les 10 sujets les plus populaires';
$lang['forum.10.most.active.topics']   = 'Les 10 sujets ayant eu le plus de réponses';

// Topics
$lang['forum.last.forum.topics']    = 'Derniers sujets du forum';
$lang['forum.connected.member']     = 'Membre connecté';
$lang['forum.not.connected.member'] = 'Membre non connecté';
$lang['forum.link.to.topic']        = 'Lien vers le sujet';

$lang['forum.message']        = 'Message';
$lang['forum.messages']       = 'Messages';
$lang['forum.forum.message']  = 'Message sur le forum';
$lang['forum.forum.messages'] = 'Messages sur le forum';

$lang['forum.quote.last.message']   = 'Reprise du message précédent';
$lang['forum.edit.message']         = 'Éditer le message';
$lang['forum.edited.by']            = 'Édité par';
$lang['forum.edited.on']            = 'Édité le';
$lang['forum.edit.in.topic']        = 'Éditer un message dans le sujet';
$lang['forum.reply']                = 'Répondre';

    // Profile
$lang['forum.see.member.datas']     = 'Voir les infos du membre';
$lang['forum.registred.on']         = 'Inscrit le';
$lang['forum.show.member.messages'] = 'Voir tous les messages du membre';
    // Controls
$lang['forum.message.controls']     = 'Gestion du message';
$lang['forum.quote.message']        = 'Citer ce message';
$lang['forum.edit.topic']           = 'Éditer le sujet';
$lang['forum.move.topic']           = 'Déplacer le sujet';
$lang['forum.cut.topic']            = 'Scinder le sujet à partir de ce message';
$lang['forum.alert.cut.topic']      = 'Voulez-vous scinder le sujet à partir de ce message ?';

// Track
$lang['forum.track.topic']         = 'Mettre en favori';
$lang['forum.untrack.topic']       = 'Retirer des favoris';
$lang['forum.no.tracked.topic']    = 'Il n\y a pas de sujet suivi pour l\'instant';
$lang['forum.track.topic.pm']      = 'Suivre par message privé';
$lang['forum.untrack.topic.pm']    = 'Arrêter le suivi par message privé';
$lang['forum.track.topic.email']   = 'Suivre par email';
$lang['forum.untrack.topic.email'] = 'Arrêter le suivi par email';
$lang['forum.untrack.topic.email'] = 'Arrêter le suivi par email';
$lang['forum.track.clue'] = '
    Cocher la case MP (<i class="fa fa-people-arrows"></i>) pour un message privé,
    Email (<i class="fa iboost fa-iboost-email"></i>) pour un email, lorsque le sujet suivi reçoit une réponse.
    <br />Cocher la case Supprimer (<i class="far fa-trash-alt"></i>) pour ne plus suivre le sujet.
';

// Types
$lang['forum.announce'] = 'Annonce';
$lang['forum.pinned']   = 'Épinglé';
$lang['forum.lock']     = 'Verrouiller';
$lang['forum.locked']   = 'Verrouillé';
$lang['forum.unlock']   = 'Déverrouiller';

// Warnings
    // errors
$lang['forum.error.locked.topic']       = 'Sujet verrouillé, vous ne pouvez pas poster de message';
$lang['forum.error.non.existent.topic'] = 'Le sujet que vous demandez n\'existe pas';
$lang['forum.error.non.cuttable.topic'] = 'Vous ne pouvez pas scinder le sujet à partir de ce message';
$lang['forum.error.locked.category']    = 'Forum verrouillé, création de nouveau sujet/message impossible';
$lang['forum.error.category.right']     = 'Vous n\'êtes pas autorisé à écrire dans cette catégorie';
    // alerts
$lang['forum.alert.delete.message'] = 'Supprimer ce message ?';
$lang['forum.alert.delete.topic']   = 'Supprimer ce sujet ?';
$lang['forum.alert.lock.topic']     = 'Verrouiller ce sujet ?';
$lang['forum.alert.unlock.topic']   = 'Déverrouiller ce sujet ?';
$lang['forum.alert.move.topic']     = 'Déplacer ce sujet ?';
$lang['forum.alert.warning']        = 'Avertir ce membre ?';
$lang['forum.alert.history']        = 'Supprimer l\'historique ?';
$lang['forum.confirm.mark.as.read'] = 'Marquer tous les sujets comme lus ?';
$lang['forum.non.compliant.topic']  = 'Sujet non conforme aux règles du forum : %s';

?>
