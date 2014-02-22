<?php
/*##################################################
 *                               admin_forum_config.php
 *                            -------------------
 *   begin                : March 22, 2008
 *   copyright            : (C) 2008 LoÃ¯c Rouchon
 *   email                : horn@phpboost.com
 *
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../admin/admin_begin.php');

//------------------------------------------------------------------- Language
load_module_lang('search'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);

//--------------------------------------------------------------------- Header
require_once('../admin/admin_header.php');

//--------------------------------------------------------------------- Params
$clearOutCache = !empty($_GET['clear']) ? true : false;
$weighting = retrieve(GET, 'weighting', false);

//Si c'est confirmé on execute
if (!empty($_POST['valid']))
{
    if (!$weighting)
    {
    	$config = SearchConfig::load();
		$config->set_nb_results_per_page(retrieve(POST, 'nb_results_p', 15));
		$config->set_cache_lifetime(retrieve(POST, 'cache_time', 15));
		$config->set_cache_max_uses(retrieve(POST, 'max_use', 200));
		$config->set_unauthorized_providers(retrieve(POST, 'authorized_modules', array()));
		SearchConfig::save();

        AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
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
        
        AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
    }
}
elseif ($clearOutCache) // On vide le contenu du cache de la recherche
{
    $querier = PersistenceContext::get_querier();
	$querier->truncate(PREFIX . 'search_results');
	$querier->truncate(PREFIX . 'search_index');
    AppContext::get_response()->redirect(HOST.SCRIPT);
}
else
{
    $tpl = new FileTemplate('search/admin_search.tpl');
	$config = SearchConfig::load();
	
    $tpl->assign_vars(array(
        'THEME' => get_utheme(),
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
        foreach ($provider_service->get_providers(SearchableExtensionPoint::EXTENSION_POINT) as $module_id => $provider)
        {
        	$module_configuration = ModulesManager::get_module($module_id)->get_configuration();
        	
            if (in_array($module_id, $config->get_unauthorized_providers()))
            $selected = ' selected="selected"';
            else
            $selected = '';

            $tpl->assign_block_vars('authorized_modules', array(
                'MODULE' => $module_id,
                'SELECTED' => $selected,
                'L_MODULE_NAME' => ucfirst($module_configuration->get_name())
            ));
        }

        $tpl->put_all(array(
            'L_CACHE_TIME' => $LANG['cache_time'],
            'L_CACHE_TIME_EXPLAIN' => $LANG['cache_time_explain'],
            'L_NB_RESULTS_P' => $LANG['nb_results_per_page'],
            'L_MAX_USE' => $LANG['max_use'],
            'L_MAX_USE_EXPLAIN' => $LANG['max_use_explain'],
            'L_CLEAR_OUT_CACHE' => $LANG['clear_out_cache'],
            'L_AUTHORIZED_MODULES' => $LANG['unauthorized_modules'],
            'L_AUTHORIZED_MODULES_EXPLAIN' => $LANG['unauthorized_modules_explain'],
            'L_SEARCH_CACHE' => $LANG['search_cache'],
            'CACHE_TIME' => $config->get_cache_lifetime(),
            'MAX_USE' => $config->get_cache_max_uses(),
            'NB_RESULTS_P' => $config->get_nb_results_per_page()
        ));
    }
    else
    {
        foreach ($config->get_weightings()->get_modules_weighting() as $module_id => $weighting)
        {
			$tpl->assign_block_vars('weights', array(
				'MODULE' => $module_id,
				'L_MODULE_NAME' => ucfirst(ModulesManager::get_module($module_id)->get_configuration()->get_name()),
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
}
require_once('../admin/admin_footer.php');
?>