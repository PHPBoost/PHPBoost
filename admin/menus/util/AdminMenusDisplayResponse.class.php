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
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

import('/admin/menus/util/MenuUrlBuilder');
import('mvc/response/AdminDisplayResponse');

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc the response
 * @package mvc
 * @subpackage response
 */
class AdminMenusDisplayResponse extends AbstractResponse
{
	public function __construct($view)
	{
		import('core/environment/AdminDisplayGraphicalEnvironment');
		$env = new AdminDisplayGraphicalEnvironment();
		
		global $LANG;
		
		$full_view = new View('admin/menus/template.tpl');
		
		$full_view->add_lang($LANG);
		$view->add_lang($LANG);
		
		$full_view->add_subtemplate('content', $view);
		
		$full_view->assign_vars(array(
			'U_MENU_CONFIGURATIONS' => MenuUrlBuilder::menu_configuration_list()->absolute(),
			'U_MENUS' => MenuUrlBuilder::menu_list()->absolute()
		));
		
		parent::__construct($env , $full_view);
	}
}
?>