<?php
/**
 * This class provides easy ways to create several type of charts.
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 08 02
 * @since       PHPBoost 1.2 - 2005 07 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');
load_module_lang('stats'); //Chargement de la langue du module.

$stats_config = StatsConfig::load();

if (AppContext::get_request()->get_postvalue('valid', false))
{
	$stats_config->set_authorizations(Authorizations::build_auth_array_from_form(StatsAuthorizationsService::READ_AUTHORIZATIONS));
	$stats_config->set_elements_number_per_page(retrieve(POST, 'elements_number', 15));
	StatsConfig::save();

	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
}
//Sinon on remplit le formulaire
else
{
	$tpl = new FileTemplate('stats/admin_stats_management.tpl');

	$tpl->put_all(array(
		'L_STATS' => $LANG['stats.module.title'],
		'L_ELEMENTS_NUMBER_PER_PAGE' => $LANG['config.elements.number.per.page'],
		'L_ELEMENTS_NUMBER_EXPLAIN' => $LANG['config.elements.number.per.page.explain'],
		'L_REQUIRE_ELEMENTS_NUMBER' => $LANG['config.require.elements.number'],
		'L_AUTHORIZATIONS' => $LANG['admin.authorizations'],
		'L_READ_AUTHORIZATION' => $LANG['admin.authorizations.read'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],

		'ELEMENTS_NUMBER_PER_PAGE' => $stats_config->get_elements_number_per_page(),
		'READ_AUTHORIZATION' => Authorizations::generate_select(StatsAuthorizationsService::READ_AUTHORIZATIONS, $stats_config->get_authorizations()),
	));
	
	$tpl->display();
}

require_once('../admin/admin_footer.php');

?>
