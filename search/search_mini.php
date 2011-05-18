<?php
/*##################################################
*                               search.php
*                            -------------------
*   begin                : January 27, 2008
*   copyright            : (C) 2008 Rouchon Lïc
*   email                : loic.rouchon@phpboost.com
*
*
 ###################################################
*
*  This program is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*
 ###################################################*/

function search_mini($position, $block)
{
    $lang = LangLoader::get('main', 'search');
    $search = AppContext::get_request()->get_string('q', '');

    $tpl = new FileTemplate('search/search_mini.tpl');

    MenuService::assign_positions_conditions($tpl, $block);
    $tpl->put_all(Array(
        'SEARCH' => $lang['title'],
        'TEXT_SEARCHED' => !empty($search) ? stripslashes(AppContext::get_request()->get_string('q', '')) : $lang['title'] . '...',
        'WARNING_LENGTH_STRING_SEARCH' => addslashes($lang['warning_length_string_searched']),
    	'L_SEARCH' => $lang['do_search'],
        'U_FORM_VALID' => url(TPL_PATH_TO_ROOT . '/search/search.php#results'),
        'L_ADVANCED_SEARCH' => $lang['advanced_search'],
        'U_ADVANCED_SEARCH' => url(TPL_PATH_TO_ROOT . '/search/search.php'),
    ));

    return $tpl->render();
}

?>