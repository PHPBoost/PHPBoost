<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.0 - 2014 05 09
 */

define('PATH_TO_ROOT', is_dir(__DIR__ . '/../../modules') ? '../..' : '..');

require_once PATH_TO_ROOT . '/kernel/init.php';

$url_controller_mappers = [new UrlControllerMapper('AdminQaptchaConfig', '`^(?:/admin)?(?:/config)?/?$`')];
DispatchManager::dispatch($url_controller_mappers);
