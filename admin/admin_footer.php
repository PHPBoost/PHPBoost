<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 1.2 - 2005 06 20
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

if (defined('PHPBOOST') !== true)
{
    exit;
}

AppContext::get_response()->clean_output();
$content = AppContext::get_response()->get_previous_ob_content();
Environment::display($content);
Environment::destroy();
?>
