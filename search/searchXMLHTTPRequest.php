<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 2.0 - 2008 01 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');
AppContext::get_session()->no_session_location(); //Permet de ne pas mettre jour la page dans la session.

//------------------------------------------------------------------- Language
$lang = LangLoader::get_all_langs('search');

//--------------------------------------------------------------------- Params
$request = AppContext::get_request();

$search_txt = retrieve(POST, 'q', '');
$module_id = TextHelper::strtolower(retrieve(POST, 'moduleName', ''));
$id_search = retrieve(POST, 'idSearch', -1);
$selected_modules = retrieve(POST, 'searched_modules', array());

//------------------------------------------------------------- Other includes
require_once(PATH_TO_ROOT . '/search/search.inc.php');

//----------------------------------------------------------------------- Main
$modules_args = array();

if (($id_search >= 0) && ($module_id != ''))
{
	echo 'var syncErr = false;';

	$search = new Search();
	if (!$search->is_search_id_in_cache($id_search))
	{   // MAJ DES RESULTATS SI ILS NE SONT PLUS DANS LE CACHE
		// Listes des modules de recherches
		$search_modules = array();
		$provider_service = AppContext::get_extension_provider_service();
		$providers = $provider_service->get_providers(SearchableExtensionPoint::EXTENSION_POINT);
		foreach ($provider_service->get_extension_point(SearchableExtensionPoint::EXTENSION_POINT) as $id => $extension_point)
		{
			if (isset($providers[$id]) && $providers[$id]->search() !== false && in_array($id, $selected_modules))
				$search_modules[] = $extension_point;
		}

		// Ajout du paramètre search à tous les modules
		foreach ($search_modules as $id => $extension_point)
			$modules_args[$id] = array('search' => $search_txt);

		// Ajout de la liste des paramètres de recherches spécifiques à chaque module
		foreach ($search_modules as $id => $extension_point)
		{
			if ($form_module->has_search_options())
			{
				// Récupération de la liste des paramètres
				$form_module_args = $extension_point->get_search_args();
				// Ajout des paramètres optionnels sans les sécuriser.
				// Ils sont sécurisés à l'intérieur de chaque module.
				foreach ($form_module_args as $arg)
				{
					if ($request->has_postparameter($arg))
						$modules_args[$id][$arg] = $request->get_postvalue($arg);
				}
			}
		}

		$results = array();
		$ids_search = array();

		get_search_results($search_txt, $search_modules, $modules_args, $results, $ids_search, true);

		if (empty($ids_search[$module_id]))
		{
			$ids_search[$module_id] = 0;
		}

		// Propagation des nouveaux id_search
		foreach ( $ids_search as $module_name => $id_search )
		{
			$search->id_search[$module_name] = $id_search;
			echo 'idSearch[\'' . $module_name . '\'] = ' . $id_search . ';';
		}
	}
	else
	{
		$search->id_search[$module_id] = $id_search;
	}
	echo   'var resultsAJAX = new Array(); var resultWarning = \'\';';
	$nb_results = $search->get_results_by_id($results, $search->id_search[$module_id]);
	if ($nb_results > 0)
	{
		//$module = $modules->get_module($module_id);
		$html_results = '';
		get_html_results($results, $html_results, $module_id);

		echo   'nbResults[\'' . $module_id . '\'] = ' . $nb_results . ';
				resultsAJAX[\'nbResults\'] = \'' . $nb_results . ' ' . addslashes($nb_results > 1 ? $lang['search.results.found'] : $lang['search.result.found']) . '\';
				resultsAJAX[\'results\'] = \''.str_replace(array("\r", "\n", '\''), array('', ' ', '\\\''), $html_results) . '\';
				resultWarning = \'success\';';
	}
	else
	{
		echo   'nbResults[\'' . $module_id . '\'] = 0;
				resultsAJAX[\'nbResults\'] = \''.addslashes($lang['common.no.item.now']) . '\';
				resultsAJAX[\'results\'] = \'\';
				resultWarning = \'notice\';';
	}
}
else
{
	echo 'var syncErr = true;';
}

?>
