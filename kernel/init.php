<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 06 30
 * @since       PHPBoost 3.0 - 2011 10 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '..');
require_once PATH_TO_ROOT . '/kernel/framework/core/environment/Environment.class.php';
Environment::load_imports();

Environment::init();

/* DEPRECATED VARS */
$Bread_crumb = new BreadCrumb();
/* END DEPRECATED */
?>
