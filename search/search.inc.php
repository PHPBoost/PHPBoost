<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 22
 * @since       PHPBoost 2.0 - 2008 02 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

if (defined('PHPBOOST') !== true) exit;

$config = SearchConfig::load();

define ('RESULTS_PER_PAGE', $config->get_nb_results_per_page());

/**
 *  Exécute la recherche
 */
function execute_search($search, &$search_modules, &$modules_args, &$results)
{
	$requests = array();

	$config = SearchConfig::load();
	foreach ($search_modules as $module_id => $module)
	{
		if (!$search->is_in_cache($module_id))
		{
			$modules_args[$module_id]['weight'] = $config->get_weightings()->get_module_weighting($module_id);
			// On rajoute l'identifiant de recherche comme parametre pour faciliter la requete
			$modules_args[$module_id]['id_search'] = !empty($search->id_search[$module_id]) ? $search->id_search[$module_id] : 0;
			$requests[$module_id] = $module->get_search_request($modules_args[$module_id]);
		}
	}

	$search->insert_results($requests);
}

/**
 *  Exécute la recherche si les résultats ne sont pas dans le cache et
 *  renvoie les résultats.
 */
function get_search_results($search_string, &$search_modules, &$modules_args, &$results, &$ids_search, $just_insert = false)
{
	$modules_options = array();

	$search = new Search($search_string, $modules_args);
	execute_search($search, $search_modules, $modules_args, $results);

	$ids_search = $search->get_ids();

	if (!$just_insert)
	{
		$modules_ids = array();
		foreach ($search_modules as $module_id => $module)
		{
			$modules_ids[] = $module_id;
		}
		return $search->get_results($results, $modules_ids);
	}
	else
		return -1;
}

/**
 *  Renvoie une chaine contenant les resultats
 */
function get_html_results(&$results, &$html_results, &$results_name)
{
	$provider_service = AppContext::get_extension_provider_service();
	$display_all_results = ($results_name == 'all');

	$tpl_results = new FileTemplate('search/search_generic_pagination_results.tpl');
	$tpl_results->assign_vars(Array(
		'RESULTS_NAME'  => $results_name,
		'C_ALL_RESULTS' => $display_all_results
	));

	$nb_pages = round(count($results) / RESULTS_PER_PAGE) + 1;
	$nb_results = count($results);

	if (!$display_all_results)
	{
		$provider = $provider_service->get_provider(TextHelper::strtolower($results_name));
		$extension_point = $provider->get_extension_point(SearchableExtensionPoint::EXTENSION_POINT);
		$results_data = array();
		$personnal_parse_results = $extension_point->has_customized_results();
		if ($personnal_parse_results && $results_name != 'all')
		{
			$results_data = $extension_point->compute_search_results(array('results' => $results));
			$nb_results = min($nb_results, count($results_data));
		}
	}

	for ($num_page = 0; $num_page < $nb_pages; $num_page++)
	{
		$tpl_results->assign_block_vars('page', array(
			'NUM_PAGE'      => $num_page,
			'BLOCK_DISPLAY' => ($num_page == 0 ? 'block' : 'none')
		));

		for ($i = 0 ; $i < RESULTS_PER_PAGE; $i++)
		{
			$num_item = $num_page * RESULTS_PER_PAGE + $i;
			if (($num_item) >= $nb_results)
				break;

			if ($display_all_results || !$personnal_parse_results)
			{
				$tpl_result = new FileTemplate('search/search_generic_results.tpl');
				$module = ModulesManager::get_module($results[$num_item]['module']);
				if ($display_all_results)
				{
					$tpl_result->assign_vars(array(
						'C_ALL_RESULTS' => true,
						'L_MODULE_NAME' => $module->get_configuration()->get_name()
					));
				}
				else
				{
					$tpl_result->assign_vars(array(
						'C_ALL_RESULTS' => false,
						'L_MODULE_NAME' => $module->get_configuration()->get_name(),
					));
				}
				$tpl_result->assign_vars(array(
					'TITLE'  => stripslashes($results[$num_item]['title']),
					'U_LINK' => url($results[$num_item]['link'])
				));

				$tpl_results->assign_block_vars('page.results', array(
					'result' => $tpl_result->render()
				));
			}
			else
			{
				$tpl_results->assign_block_vars('page.results', array(
					'result' => $extension_point->parse_search_result($results_data[$num_item])
				));
			}
		}
	}
	$html_results = $tpl_results->render();
}

?>
