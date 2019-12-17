<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 23
 * @since       PHPBoost 2.0 - 2008 03 22
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

require_once('../admin/admin_begin.php');

//------------------------------------------------------------------- Language
load_module_lang('search'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);

//--------------------------------------------------------------------- Header
require_once('../admin/admin_header.php');

//--------------------------------------------------------------------- Params
$request = AppContext::get_request();

$clearOutCache = $request->get_getvalue('clear', '');
$weighting = $request->get_getvalue('weighting', '');
$valid = $request->get_postvalue('valid', false);

$tpl = new FileTemplate('search/admin_search.tpl');
$config = SearchConfig::load();

//Si c'est confirmé on execute
if ($valid)
{
	if (!$weighting)
	{
		$authorized_modules = retrieve(POST, 'authorized_modules', array());
		$authorized_modules = !empty($authorized_modules) ? explode(',', $authorized_modules[0]) : $authorized_modules;
		$config = SearchConfig::load();
		$config->set_nb_results_per_page(retrieve(POST, 'nb_results_p', 15));
		$config->set_cache_lifetime(retrieve(POST, 'cache_time', 15));
		$config->set_cache_max_uses(retrieve(POST, 'max_use', 200));
		$config->set_unauthorized_providers($authorized_modules);
		$config->set_authorizations(Authorizations::build_auth_array_from_form(SearchAuthorizationsService::READ_AUTHORIZATIONS));
		SearchConfig::save();

		$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
	}
	else
	{
		$search_weightings = new SearchWeightings();
		$provider_service = AppContext::get_extension_provider_service();
		foreach ($provider_service->get_providers(SearchableExtensionPoint::EXTENSION_POINT) as $module_id => $provider)
		{
			$search_weightings->add_module_weighting($module_id, retrieve(POST, $module_id, SearchWeightings::DEFAULT_WEIGHTING));
		}
		SearchConfig::load()->set_weightings($search_weightings);
		SearchConfig::save();

		$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
	}
}
elseif ($clearOutCache) // On vide le contenu du cache de la recherche
{
	$querier = PersistenceContext::get_querier();
	$querier->truncate(PREFIX . 'search_results');
	$querier->truncate(PREFIX . 'search_index');
	$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
}

$tpl->assign_vars(array(
	'L_SEARCH_MANAGEMENT' => $LANG['search_management'],
	'L_SEARCH_CONFIG' => $LANG['search_config'],
	'L_SEARCH_CONFIG_WEIGHTING' => $LANG['search_config_weighting'],
	'L_UPDATE' => $LANG['update'],
	'L_RESET' => $LANG['reset'],
	'C_WEIGHTING' => $weighting
));

if (!$weighting)
{
	$provider_service = AppContext::get_extension_provider_service();
	$search_extensions_point_modules = array_keys($provider_service->get_extension_point(SearchableExtensionPoint::EXTENSION_POINT));

	foreach (ModulesManager::get_activated_modules_map_sorted_by_localized_name() as $id => $module)
	{
		if (in_array($module->get_id(), $search_extensions_point_modules))
		{
			$module_configuration = $module->get_configuration();
			if (in_array($module->get_id(), $config->get_unauthorized_providers()))
				$selected = ' selected="selected"';
			else
				$selected = '';

			$tpl->assign_block_vars('authorized_modules', array(
				'MODULE' => $module->get_id(),
				'SELECTED' => $selected,
				'L_MODULE_NAME' => $module_configuration->get_name()
			));
		}
	}

	$tpl->put_all(array(
		'L_REQUIRE' => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
		'L_CACHE_TIME' => $LANG['cache_time'],
		'L_CACHE_TIME_EXPLAIN' => $LANG['cache_time_explain'],
		'L_NB_RESULTS_P' => $LANG['nb_results_per_page'],
		'L_MAX_USE' => $LANG['max_use'],
		'L_MAX_USE_EXPLAIN' => $LANG['max_use_explain'],
		'L_CLEAR_OUT_CACHE' => $LANG['clear_out_cache'],
		'L_AUTHORIZED_MODULES' => $LANG['unauthorized_modules'],
		'L_AUTHORIZED_MODULES_EXPLAIN' => $LANG['unauthorized_modules_explain'],
		'L_SEARCH_CACHE' => $LANG['search_cache'],
		'L_AUTHORIZATIONS' => $LANG['admin.authorizations'],
		'L_READ_AUTHORIZATION' => $LANG['admin.authorizations.read'],
		'CACHE_TIME' => $config->get_cache_lifetime(),
		'MAX_USE' => $config->get_cache_max_uses(),
		'NB_RESULTS_P' => $config->get_nb_results_per_page(),
		'READ_AUTHORIZATION' => Authorizations::generate_select(SearchAuthorizationsService::READ_AUTHORIZATIONS, $config->get_authorizations())
	));
}
else
{
	foreach ($config->get_weightings_sorted_by_localized_name() as $module_id => $weighting)
	{
		$tpl->assign_block_vars('weights', array(
			'MODULE' => $module_id,
			'L_MODULE_NAME' => ModulesManager::get_module($module_id)->get_configuration()->get_name(),
			'WEIGHT' => $weighting
		));
	}

	$tpl->assign_vars(array(
		'L_MODULES' => $LANG['modules'],
		'L_WEIGHTS' => $LANG['search_weights'],
		'L_SEARCH_CONFIG_WEIGHTING_EXPLAIN' => $LANG['search_config_weighting_explain']
	));
}

$tpl->display();

require_once('../admin/admin_footer.php');
?>
