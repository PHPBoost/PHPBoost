<?php
/*##################################################
 *                          AdminSearchDisplayResponse.class.php
 *                            -------------------
 *   begin                : April 10 2010
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc the response
 */
class AdminSearchDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct(Template $view, array $lang)
	{
        parent::__construct($view);
        $view->add_lang($lang);
        $this->set_title($lang['search_management']);
        $img = '/search/search.png';
        $this->add_link($lang['search_config'], AdminSearchUrlBuilder::config()->absolute(), $img);
        $this->add_link($lang['search_config_weighting'], AdminSearchUrlBuilder::weight()->absolute(), $img);
	}
}
?>