<?php
/**
 * This class provides easy ways to create several type of charts.
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 15
 * @since       PHPBoost 1.2 - 2005 07 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../admin/admin_begin.php');

$lang = LangLoader::get('common', 'stats');
$form_lang = LangLoader::get('form-lang');

define('TITLE', $lang['stats.config.module.title']);
require_once('../admin/admin_header.php');

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
	$view = new FileTemplate('stats/admin_stats_management.tpl');
	$view->add_lang(array_merge($lang, $form_lang));

	$view->put_all(array(
		'ELEMENTS_NUMBER_PER_PAGE' => $stats_config->get_elements_number_per_page(),
		'READ_AUTHORIZATION'       => Authorizations::generate_select(StatsAuthorizationsService::READ_AUTHORIZATIONS, $stats_config->get_authorizations()),
	));

	$view->display();
}

require_once('../admin/admin_footer.php');

?>
