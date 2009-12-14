<?php
/*##################################################
 *                           admin_display_response.class.php
 *                            -------------------
 *   begin                : October 18 2009
 *   copyright            : (C) 2009 Loïc Rouchon
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

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc the response
 * @package mvc
 * @subpackage response
 */
class AdminMenusDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view)
	{
        global $LANG;
        
        parent::__construct($view);
        
        $view->add_lang($LANG);
        $this->set_title($LANG['menus_management']);
        $img = '/templates/' . get_utheme() . '/images/admin/menus.png';
        $this->add_link($LANG['menu_configurations'], MenuUrlBuilder::menu_configuration_list()->absolute(), $img);
        $this->add_link($LANG['menus'], MenuUrlBuilder::menu_list()->absolute(), $img);
	}
}
?>