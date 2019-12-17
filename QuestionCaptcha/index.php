<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 06 19
 * @since       PHPBoost 4.0 - 2014 05 09
*/

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = array(new UrlControllerMapper('AdminQuestionCaptchaConfig', '`^(?:/admin)?(?:/config)?/?$`'));
DispatchManager::dispatch($url_controller_mappers);
?>
