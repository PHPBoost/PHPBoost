<?php
/*##################################################
 *                           admin-contents-common.php
 *                            -------------------
 *   begin                : August 10, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
#                     French                       #
 ####################################################
 
$lang = array();

$lang['content'] = 'Contenu';
$lang['content.config'] = 'Configuration du contenu';
$lang['content.config.language'] = 'Langage de formatage';
$lang['content.config.default-formatting-language'] = 'Langage de formatage du contenu par défaut du site';
$lang['content.config.default-formatting-language-explain'] = 'Chaque utilisateur pourra choisir';
$lang['content.config.html-language'] = 'Langage HTML';
$lang['content.config.html-language-use-authorization'] = 'Niveau d\'autorisation pour insérer du langage HTML';
$lang['content.config.html-language-use-authorization-explain'] = 'Attention : le code HTML peut contenir du code Javascript qui peut constituer une source de faille de sécurité si quelqu\'un y insère un code malveillant. Veillez donc à n\'autoriser seulement les personnes de confiance à insérer du HTML.';
$lang['content.config.post-management'] = 'Gestion des posts';
$lang['content.config.max-pm-number'] = 'Nombre maximum de messages privés';
$lang['content.config.max-pm-number-explain'] = 'Illimité pour administrateurs et modérateurs';
$lang['content.config.anti-flood-enabled'] = 'Anti-flood';
$lang['content.config.anti-flood-enabled-explain'] = 'Empêche les messages trop rapprochés, sauf si les visiteurs sont autorisés';
$lang['content.config.delay-flood'] = 'Interval minimum de temps entre les messages';
$lang['content.config.delay-flood-explain'] = '7 secondes par défaut';

$lang['comments'] = 'Commentaires';
$lang['comments.config'] = 'Configuration des commentaires';
$lang['comments.management'] = 'Gestion des commentaires';

$lang['comments.config.display-captcha'] = 'Code de vérification visuel';
$lang['comments.config.captcha-difficulty'] = 'Difficulté du code de vérification';
$lang['comments.config.number-comments-display'] = 'Nombre de commentaires à afficher par défaut';
$lang['comments.config.order-display-comments'] = 'Ordre d\'affichage des commentaires';
$lang['comments.config.order-display-comments.asc'] = 'Du plus ancien au plus récent';
$lang['comments.config.order-display-comments.desc'] = 'Du plus récent au plus ancien';

$lang['comments.config.authorization'] = 'Autorisations';
$lang['comments.config.authorization-read'] = 'Autorisation de voir les commentaires';
$lang['comments.config.authorization-post'] = 'Autorisation de poster un commentaire';
$lang['comments.config.authorization-moderation'] = 'Autorisation de gérer les commentaires';
$lang['comments.config.authorization-note'] = 'Autorisation de noter les commentaires';
$lang['comments.config.max-links-comment'] = 'Nombre de liens autorisés dans un commentaire';
$lang['comments.config.forbidden-tags'] = 'Types de formatage interdits';
$lang['comments.config.approbation'] = 'Approbation des commentaires';
$lang['comments.config.approbation.auto'] = 'Automatiquement';
$lang['comments.config.approbation.moderator'] = 'Modérateur';
$lang['comments.config.approbation.administrator'] = 'Administrateur';

$lang['enabled'] = 'Activé';
$lang['disabled'] = 'Désactivé';
$lang['number-required'] = 'La valeur saisie doit être un nombre';
?>