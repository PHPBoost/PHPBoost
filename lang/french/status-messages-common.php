<?php
/*##################################################
 *                                status-messages-common.php
 *                            -------------------
 *   begin                : April 12, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

$lang['success'] = 'Succès';
$lang['error'] = 'Erreur';

$lang['error.fatal'] = 'Fatale';
$lang['error.notice'] = 'Suggestion';
$lang['error.warning'] = 'Avertissement';
$lang['error.unknow'] = 'Inconnue';

$lang['csrf_invalid_token'] = 'Jeton de session invalide. Veuillez réessayer car l\'opération n\'a pas pu être effectuée.';

//Element
$lang['element.already_exists'] = 'L\'élément que vous demandez existe déjà.';
$lang['element.unexist'] = 'L\'élément que vous demandez n\'existe pas.';
$lang['element.not_visible'] = 'Cet élément n\'est pas encore ou n\'est plus approuvé, il n\'est pas affiché pour les autres utilisateurs du site.';

$lang['misfit.php'] = 'Version PHP inadaptée';
$lang['misfit.phpboost'] = 'Version de PHPBoost inadaptée';

//Process
$lang['process.success'] = 'L\'opération s\'est déroulée avec succès';
$lang['process.error'] = 'Une erreur s\'est produite lors de l\'opération';

$lang['confirm.delete'] = 'Voulez-vous vraiment supprimer cet élément ?';

$lang['message.success.config'] = 'La configuration a été modifiée';

//Captcha
$lang['captcha.validation_error'] = 'Le champ de vérification visuel n\'a pas été saisi correctement !';
$lang['captcha.is_default'] = 'Le captcha que vous souhaitez désinstaller ou désactiver est défini sur le site, veuillez d\'abord sélectionner un autre captcha dans la configuration du contenu.';
$lang['captcha.last_installed'] = 'Dernier captcha, vous ne pouvez pas le supprimer ou le désactiver. Veuillez d\'abord en installer un autre.';

//Form
$lang['form.doesnt_match_regex'] = 'La valeur saisie n\'est pas au bon format';
$lang['form.doesnt_match_url_regex'] = 'La valeur saisie doit être une url valide';
$lang['form.doesnt_match_mail_regex'] = 'La valeur saisie doit être un mail valide';
$lang['form.doesnt_match_length_intervall'] = 'La valeur saisie ne respecte par la longueur définie';
$lang['form.doesnt_match_integer_intervall'] = 'La valeur saisie ne respecte pas l\'intervalle définie (:lower_bound <= valeur <= :upper_bound)';
$lang['form.has_to_be_filled'] = 'Le champ ":name" doit être renseigné';
$lang['form.validation_error'] = 'Veuillez corriger les erreurs du formulaire';
$lang['form.fields_must_be_equal'] = 'Les champs ":field1" et ":field2" doivent être égaux';
$lang['form.fields_must_not_be_equal'] = 'Les champs ":field1" et ":field2" doivent avoir des valeurs différentes';

//User
$lang['user.not_exists'] = 'L\'utilisateur n\'existe pas !';
$lang['user.auth.passwd_flood'] = 'Il vous reste :remaining_tries essai(s) après cela il vous faudra attendre 5 minutes pour obtenir 2 nouveaux essais (10min pour 5)!';
$lang['user.auth.passwd_flood_max'] = 'Vous avez épuisé tous vos essais de connexion, votre compte est verrouillé pendant 5 minutes.';
?>