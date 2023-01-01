<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2014 07 30
 * @since       PHPBoost 4.0 - 2013 11 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

#####################################################
#                    English                        #
#####################################################

$lang['incorrect_sol']        = 'The string you entered for the image verification did not match what was displayed.';
$lang['type_the_answer_here'] = 'Type the answer here ...';
$lang['refresh_captcha']      = 'Change code';
$lang['image_captcha']        = 'Get an image';
$lang['audio_captcha']        = 'Get an audio test';
$lang['captcha_help']         = 'Help';

$lang['config.title']                          = 'ReCaptcha configuration';
$lang['config.recaptcha-explain']              = 'If you want to use ReCaptcha, go to <a href="' . ReCaptcha::$_signupUrl . '">' . ReCaptcha::$_signupUrl . '</a> to create your ids.';
$lang['config.site_key']                       = 'Site key';
$lang['config.secret_key']                     = 'Secret key';
$lang['config.invisible_mode_enabled']         = 'Enable invisible mode';
$lang['config.invisible_mode_enabled.explain'] = 'No action is required from the user to validate the form. Enable the option when creating Google ReCaptcha id.';
?>
