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
#						ENGLISH						#
#####################################################

$lang['incorrect_sol'] = 'The string you entered for the image verification did not match what was displayed.';
$lang['type_the_answer_here'] = 'Type the answer here ...';
$lang['refresh_captcha'] = 'Change code';
$lang['image_captcha'] = 'Get an image';
$lang['audio_captcha'] = 'Get an audio test';
$lang['captcha_help'] = 'Help';

$lang['config.title'] = 'ReCaptcha configuration';
$lang['config.recaptcha-explain'] = 'If you want to use ReCaptcha v2, go to <a href="' . ReCaptcha::$_signupUrl . '">' . ReCaptcha::$_signupUrl . '</a> to create your ids.';
$lang['config.recaptchav2_enabled'] = 'Enable ReCaptcha v2';
$lang['config.site_key'] = 'Site key';
$lang['config.secret_key'] = 'Secret key';
?>
