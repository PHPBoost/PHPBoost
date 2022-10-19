<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2018 10 05
 * @since       PHPBoost 4.0 - 2013 11 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

#####################################################
#                    French                         #
#####################################################

$lang['incorrect_sol']        = 'Le mot entré ne correspondait pas à celui qui était affiché. Veuillez réessayer.';
$lang['type_the_answer_here'] = 'Saisissez la valeur ...';
$lang['refresh_captcha']      = 'Changer le code';
$lang['image_captcha']        = 'Obtenir une image';
$lang['audio_captcha']        = 'Obtenir un test audio';
$lang['captcha_help']         = 'Aide';

$lang['config.title']                          = 'Configuration de ReCaptcha';
$lang['config.recaptcha-explain']              = 'Si vous souhaitez utiliser ReCaptcha, rendez-vous sur <a href="' . ReCaptcha::$_signupUrl . '">' . ReCaptcha::$_signupUrl . '</a> pour créer vos identifiants.';
$lang['config.site_key']                       = 'Clé du site';
$lang['config.secret_key']                     = 'Clé secrète';
$lang['config.invisible_mode_enabled']         = 'Activer le mode invisible';
$lang['config.invisible_mode_enabled.explain'] = 'Aucune action n\'est nécessaire de la part de l\'utilisateur pour valider les formulaires. Option à activer lors de la création des identifiants Google ReCaptcha.';
?>
