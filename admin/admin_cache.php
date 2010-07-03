<?php
/*##################################################
 *                               admin_config.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$cache_mode = retrieve(GET, 'cache', '');

if (empty($cache_mode))    // Génération du cache de la configuration
{
    //Si c'est confirmé on execute
    if (!empty($_POST['cache']))
    {
        AppContext::get_cache_service()->clear_cache();
        AppContext::get_response()->redirect('/admin/admin_cache.php?s=1');
    }
    else //Sinon on rempli le formulaire
    {
        $Template->set_filenames(array(
            'admin_cache'=> 'admin/admin_cache.tpl'
        ));
        
        //Gestion erreur.
        $get_error = retrieve(GET, 's', 0);
        if ($get_error == 1)
        {
        	$Errorh->handler($LANG['cache_success'], E_USER_SUCCESS);
        }
        
        $Template->assign_vars(array(
            'L_CACHE' => $LANG['cache'],
            'L_SYNDICATION' => $LANG['syndication'],
            'L_EXPLAIN_SITE_CACHE' => $LANG['explain_site_cache'],
            'L_GENERATE' => $LANG['generate']
        ));
        
        $Template->pparse('admin_cache');
    }
}
else    // Génération du cache des rss
{
    //Si c'est confirmé on execute
    if (!empty($_POST['cache']))
    {
        AppContext::get_cache_service()->clear_syndication_cache();
        
        AppContext::get_response()->redirect('/admin/admin_cache.php?cache=syndication&s=1');
    }
    else //Sinon on rempli le formulaire
    {
        $Template->set_filenames(array(
            'admin_cache_syndication'=> 'admin/admin_cache_syndication.tpl'
        ));
        
        //Gestion erreur.
        $get_error = retrieve(GET, 's', 0);
        if ($get_error == 1)
        {
            $Errorh->handler($LANG['cache_success'], E_USER_SUCCESS);
        }
        
        $Template->assign_vars(array(
            'L_CACHE' => $LANG['cache'],
            'L_SYNDICATION' => $LANG['syndication'],
            'L_EXPLAIN_SITE_CACHE' => $LANG['explain_site_cache_syndication'],
            'L_GENERATE' => $LANG['generate']
        ));
        
        $Template->pparse('admin_cache_syndication');
    }
}

require_once('../admin/admin_footer.php');

?>