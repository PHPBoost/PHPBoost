<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 20
 * @since       PHPBoost 4.0 - 2014 05 09
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

#####################################################
#                    English                        #
#####################################################

$lang['questioncaptcha.config.label']               = 'Questions';
$lang['questioncaptcha.config.label.description']   = 'Separate answers with ; when many answers are possible';
$lang['questioncaptcha.config.label.placeholder']   = 'Question';
$lang['questioncaptcha.config.answers.placeholder'] = 'Answer(s)';
$lang['questioncaptcha.config.delete']              = 'Delete a question';
$lang['questioncaptcha.config.add']                 = 'Add a question';

// Variable sent to admin/advices
$lang['advices.questioncaptcha.items.number'] = 'The captcha QuestionCaptcha is used to secure forms, think to <a href="' . QuestionCaptchaUrlBuilder::configuration()-> rel() . '">Add questions </a>to enhance security.';

?>
