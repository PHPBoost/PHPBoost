<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 1.4 - 2006 01 31
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

require_once('../kernel/begin.php');
require_once('../stats/stats_begin.php');
require_once('../kernel/header.php');

$modulesLoader = AppContext::get_extension_provider_service();
$module = $modulesLoader->get_provider('stats');
if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
{
	echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
}

require_once('../kernel/footer.php');

?>
