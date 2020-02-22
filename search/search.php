<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 02 22
 * @since       PHPBoost 2.0 - 2008 01 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

//------------------------------------------------------------------- Language
require_once('../kernel/begin.php');
load_module_lang('search');

if (!SearchAuthorizationsService::check_authorizations()->read())
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

$tpl = new FileTemplate('search/search_forms.tpl');

//--------------------------------------------------------------------- Params
$request = AppContext::get_request();

$search = retrieve(REQUEST, 'q', '');
$unsecure_search = stripslashes(retrieve(REQUEST, 'q', ''));
$search_in = retrieve(POST, 'search_in', 'all');
$selected_modules = retrieve(POST, 'searched_modules', '');
$selected_modules = !empty($selected_modules) ? explode(',', $selected_modules) : array();
$query_mode = (bool)retrieve(POST, 'query_mode', true);

if ($search_in !== 'all')
{
	$selected_modules = array($search_in);
}
else if (count($selected_modules) == 1)
{
	$module = $selected_modules['0'];
	$search_in = $module;
}

//--------------------------------------------------------------------- Header

define('TITLE', $LANG['title_search']);

require_once('../kernel/header.php');
$tpl->assign_vars(Array(
	'L_TITLE_SEARCH' => TITLE,
	'L_SEARCHED_TEXT' => $LANG['search.searched.text'],
	'L_SEARCH' => $LANG['search'],
	'TEXT_SEARCHED' => $unsecure_search,
	'L_SEARCH_ALL' => $LANG['search_all'],
	'L_SEARCH_KEYWORDS' => $LANG['search_keywords'],
	'L_SEARCH_MIN_LENGTH' => $LANG['search_min_length'],
	'L_SEARCH_IN_MODULES' => $LANG['search_in_modules'],
	'L_SEARCH_IN_MODULES_EXPLAIN' => $LANG['search_in_modules_explain'],
	'L_SEARCH_SPECIALIZED_FORM' => $LANG['search_specialized_form'],
	'L_SEARCH_SPECIALIZED_FORM_EXPLAIN' => $LANG['search_specialized_form_explain'],
	'L_WARNING_LENGTH_STRING_SEARCH' => TextHelper::to_js_string($LANG['warning_length_string_searched']),
	'L_FORMS' => $LANG['forms'],
	'L_ADVANCED_SEARCH' => $LANG['advanced_search'],
	'L_SIMPLE_SEARCH' => $LANG['simple_search'],
	'U_FORM_VALID' => url('../search/search.php#results'),
	'C_SIMPLE_SEARCH' => $search_in == 'all' ? true : false,
	'SEARCH_MODE_MODULE' => $search_in
));

//------------------------------------------------------------- Other includes

require_once('../search/search.inc.php');

//----------------------------------------------------------------------- Main

$config = SearchConfig::load();
$modules_args = array();
$used_modules = array();

// Génération des formulaires précomplétés et passage aux templates
$provider_service = AppContext::get_extension_provider_service();
$search_extensions_point_modules = array_keys($provider_service->get_extension_point(SearchableExtensionPoint::EXTENSION_POINT));
$search_extensions_point = $provider_service->get_extension_point(SearchableExtensionPoint::EXTENSION_POINT);

foreach (ModulesManager::get_installed_modules_map_sorted_by_localized_name() as $id => $module)
{
	if (in_array($module->get_id(), $search_extensions_point_modules))
	{
		$module_configuration = $module->get_configuration();
		if (!in_array($module->get_id(), $config->get_all_unauthorized_providers()))
		{
			// Ajout du paramètre search à tous les modules
			$modules_args[$module->get_id()]['search'] = $search;
			if ($search_extensions_point[$module->get_id()] && $search_extensions_point[$module->get_id()]->has_search_options())
			{
				// Récupération de la liste des paramètres
				$form_module_args = $search_extensions_point[$module->get_id()]->get_search_args();
				// Ajout des paramètres optionnels sans les sécuriser.
				// Ils sont sécurisés à l'intérieur de chaque module.
				if ($search_in != 'all')
				{
					foreach ($form_module_args as $arg)
					{
						if ($arg == 'search')
						{   // 'search' non sécurisé
							$modules_args[$module->get_id()]['search'] = $search;
						}
						elseif ($request->has_postparameter($arg))
						{   // Argument non sécurisé (sécurisé par le module en question)
							$modules_args[$module->get_id()][$arg] = $request->get_postvalue($arg);
						}
					}
				}

				$tpl->assign_block_vars('forms', array(
					'MODULE_NAME' => $module->get_id(),
					'L_MODULE_NAME' => TextHelper::ucfirst($module_configuration->get_name()),
					'C_SEARCH_FORM' => true,
					'C_SELECTED' => count($selected_modules) == 1 ? in_array($module->get_id(), $selected_modules) : false,
					'SEARCH_FORM' => $search_extensions_point[$module->get_id()]->get_search_form($modules_args[$module->get_id()])
				));
			}
			else
			{
				$tpl->assign_block_vars('forms', array(
					'MODULE_NAME' => $module->get_id(),
					'L_MODULE_NAME' => TextHelper::ucfirst($module_configuration->get_name()),
					'C_SEARCH_FORM' => false,
					'C_SELECTED' => count($selected_modules) == 1 ? in_array($module->get_id(), $selected_modules) : false,
					'SEARCH_FORM' => $LANG['search_no_options']
				));
			}

			// Récupération de la liste des modules à traiter
			if ( ($selected_modules === array()) || ($search_in === $module->get_id()) ||
				(($search_in === 'all') && (in_array($module->get_id(), $selected_modules))) )
			{
				$selected = ' selected="selected"';
				$used_modules[$module->get_id()] = $search_extensions_point[$module->get_id()]; // Ajout du module à traiter
			}
			else
			{
				$selected = '';
			}

			$tpl->assign_block_vars('searched_modules', array(
				'MODULE' => $module->get_id(),
				'L_MODULE_NAME' => TextHelper::ucfirst($module_configuration->get_name()),
				'SELECTED' => $selected
			));
		}
	}
}

$tpl->display();

if (!empty($search))
{
	$tpl = new FileTemplate('search/search_results.tpl');

	$results = array();
	$idsSearch = array();

	if ( $search_in != 'all' ) // If we are searching in only one module
	{
		if (isset($used_modules[$search_in]) && isset($modules_args[$search_in]))
		{
			$used_modules = array($search_in => $used_modules[$search_in]);
			$modules_args = array($search_in => $modules_args[$search_in]);
		}
		else
		{
			$used_modules = array();
			$modules_args = array();
		}
	}
	else
	{   // We remove modules that we're not searching in
		foreach ($modules_args as $module_id => $module_args)
		{
			if (!$query_mode && (!in_array($module_id, $selected_modules) || !isset($modules_args[$module_id])))
			{
				unset($modules_args[$module_id]);
				unset($used_modules[$module_id]);
			}
		}
	}

	// Génération des résultats et passage aux templates
	$nbResults = get_search_results($search, $used_modules, $modules_args, $results, $idsSearch);

	foreach ($used_modules as $module_id => $extension_point)
	{
		$tpl->assign_block_vars('results', array(
			'MODULE_NAME' => $module_id,
			'L_MODULE_NAME' => TextHelper::ucfirst(ModulesManager::get_module($module_id)->get_configuration()->get_name()),
			'ID_SEARCH' => $idsSearch[$module_id]
		));
	}

	$all_html_result = '';
	if ( $nbResults > 0 )
		get_html_results($results, $all_html_result, $search_in);

	$tpl->assign_vars(Array(
		'NB_RESULTS_PER_PAGE' => NB_RESULTS_PER_PAGE,
		'L_TITLE_ALL_RESULTS' => $LANG['title_all_results'],
		'L_RESULTS' => $LANG['results'],
		'L_RESULTS_CHOICE' => $LANG['results_choice'],
		'L_PRINT' => $LANG['print'],
		'L_NB_RESULTS_FOUND' => $nbResults > 1 ? $LANG['nb_results_found'] : ($nbResults == 0 ? $LANG['no_results_found'] : $LANG['one_result_found']),
		'L_SEARCH_RESULTS' => $LANG['search_results'],
		'NB_RESULTS' => $nbResults,
		'ALL_RESULTS' => $all_html_result,
		'SEARCH_IN' => $search_in,
		'C_SIMPLE_SEARCH' => ($search_in == 'all')
	));

	$tpl->display();
}

//--------------------------------------------------------------------- Footer
require_once('../kernel/footer.php');

?>
