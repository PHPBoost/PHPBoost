<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 1.2 - 2005 08 14
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

if (defined('PHPBOOST') !== true)
{
	exit;
}
$env = new SiteNodisplayGraphicalEnvironment();
Environment::set_graphical_environment($env);

ob_start();
?>
