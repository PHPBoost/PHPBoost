<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 2.0 - 2008 03 22
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../admin/admin_begin.php');

//------------------------------------------------------------------- Language
$lang = LangLoader::get_all_langs('search');

//--------------------------------------------------------------------- Params
$request = AppContext::get_request();

$clearOutCache = $request->get_getvalue('clear', '');
$weighting = $request->get_getvalue('weighting', '');
$valid = $request->get_postvalue('valid', false);

$view = new FileTemplate('search/admin_search.tpl');
$view->add_lang($lang);

$config = SearchConfig::load();

//--------------------------------------------------------------------- Header
define('TITLE', $weighting ? $lang['search.config.weighting'] : $lang['form.configuration']);
require_once('../admin/admin_header.php');

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

		HooksService::execute_hook_action('edit_config', 'search', array('title' => StringVars::replace_vars($lang['form.module.title'], array('module_name' => ModulesManager::get_module('search')->get_configuration()->get_name())), 'url' => Url::to_rel('/search/admin_search.php')));

		$view->put('MESSAGE_HELPER', MessageHelper::display($lang['warning.success.config'], MessageHelper::SUCCESS, 4));
	}
	else
	{
		$search_weightings = new SearchWeightings();
		$provider_service = AppContext::get_extension_provider_service();
		foreach ($provider_service->get_providers(SearchableExtensionPoint::EXTENSION_POINT) as $module_id => $provider)
		{
			if ($provider->search() !== false)
				$search_weightings->add_module_weighting($module_id, retrieve(POST, $module_id, SearchWeightings::DEFAULT_WEIGHTING));
		}
		SearchConfig::load()->set_weightings($search_weightings);
		SearchConfig::save();

		HooksService::execute_hook_action('edit_config', 'search', array('title' => $lang['search.config.weighting'], 'url' => Url::to_rel('/search/admin_search.php?weighting=true')));

		$view->put('MESSAGE_HELPER', MessageHelper::display($lang['warning.success.config'], MessageHelper::SUCCESS, 4));
	}
}
elseif ($clearOutCache) // On vide le contenu du cache de la recherche
{
	$querier = PersistenceContext::get_querier();
	$querier->truncate(PREFIX . 'search_results');
	$querier->truncate(PREFIX . 'search_index');
	$view->put('MESSAGE_HELPER', MessageHelper::display($lang['warning.process.success'], MessageHelper::SUCCESS, 4));
}

$view->assign_vars(array(
	'C_WEIGHTING' => $weighting
));

if (!$weighting)
{
	$provider_service = AppContext::get_extension_provider_service();
	$search_extensions_point_modules = array_keys($provider_service->get_extension_point(SearchableExtensionPoint::EXTENSION_POINT));
	$providers = $provider_service->get_providers(SearchableExtensionPoint::EXTENSION_POINT);

	foreach (ModulesManager::get_activated_modules_map_sorted_by_localized_name() as $id => $module)
	{
		if (isset($providers[$module->get_id()]) && $providers[$module->get_id()]->search() !== false && in_array($module->get_id(), $search_extensions_point_modules))
		{
			$module_configuration = $module->get_configuration();
			if (in_array($module->get_id(), $config->get_unauthorized_providers()))
				$selected = ' selected="selected"';
			else
				$selected = '';

			$view->assign_block_vars('authorized_modules', array(
				'MODULE'      => $module->get_id(),
				'SELECTED'    => $selected,
				'MODULE_NAME' => $module_configuration->get_name()
			));
		}
	}

	$view->put_all(array(
		'CACHE_TIME'         => $config->get_cache_lifetime(),
		'MAX_USE'            => $config->get_cache_max_uses(),
		'ITEMS_PER_PAGE'     => $config->get_nb_results_per_page(),
		'READ_AUTHORIZATION' => Authorizations::generate_select(SearchAuthorizationsService::READ_AUTHORIZATIONS, $config->get_authorizations()),
	));
}
else
{
	foreach ($config->get_weightings_sorted_by_localized_name() as $module_id => $weighting)
	{
		$view->assign_block_vars('weights', array(
			'MODULE'      => $module_id,
			'MODULE_NAME' => ModulesManager::get_module($module_id)->get_configuration()->get_name(),
			'WEIGHT'      => $weighting
		));
	}
}

$view->display();

require_once('../admin/admin_footer.php');
?>
