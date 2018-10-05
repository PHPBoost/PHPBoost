<?php
/*##################################################
 *                        common.php
 *                        -------------------
 *   begin                : November 1, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

#####################################################
#						French						#
#####################################################

$lang['incorrect_sol'] = 'Le mot entré ne correspondait pas à celui qui était affiché. Veuillez réessayer.';
$lang['type_the_answer_here'] = 'Saisissez la valeur ...';
$lang['refresh_captcha'] = 'Changer le code';
$lang['image_captcha'] = 'Obtenir une image';
$lang['audio_captcha'] = 'Obtenir un test audio';
$lang['captcha_help'] = 'Aide';

$lang['config.title'] = 'Configuration de ReCaptcha';
$lang['config.recaptcha-explain'] = 'Si vous souhaitez utiliser ReCaptcha, rendez-vous sur <a href="' . ReCaptcha::$_signupUrl . '">' . ReCaptcha::$_signupUrl . '</a> pour créer vos identifiants.';
$lang['config.site_key'] = 'Clé du site';
$lang['config.secret_key'] = 'Clé secrète';
$lang['config.invisible_mode_enabled'] = 'Activer le mode invisible';
$lang['config.invisible_mode_enabled.explain'] = 'Aucune action n\'est nécessaire de la part de l\'utilisateur pour valider les formulaires. Option à activer lors de la création des identifiants Google ReCaptcha.';
?>
