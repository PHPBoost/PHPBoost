<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 01
 * @since       PHPBoost 4.1 - 2014 10 11
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

####################################################
#                    French                        #
####################################################

// to be deleted

$lang['forum.config.default.forum.name']   = GeneralConfig::load()->get_site_name() . ' forum';
$lang['forum.config.issue.status']         = '[Réglé]';
$lang['forum.configissue.status.unsolved'] = 'Sujet réglé ?';
$lang['forum.config.issue.status.solved']  = 'Sujet non réglé ?';

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
$lang['forum.config.display.message.before.topic'] = 'Afficher un message devant le titre du topic';
$lang['forum.config.message.before.topic']         = 'Message devant le titre du topic';
$lang['forum.config.status.message.unsolved']      = 'Message devant le titre du topic si statut non changé';
$lang['forum.config.status.message.solved']        = 'Message devant le titre du topic si statut changé';
$lang['forum.config.display.issue.status.icon']    = 'Afficher l\'icône associée';

?>
