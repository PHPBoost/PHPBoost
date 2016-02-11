<?php
/*##################################################
 *                           AdminSmileysDisplayResponse.class.php
 *                            -------------------
 *   begin                : May 22, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class AdminSmileysDisplayResponse extends AdminMenuDisplayResponse
{
	public function __construct($view, $title_page)
	{
		parent::__construct($view);
		
		$lang = LangLoader::get('admin');
		$this->set_title($lang['smiley_management']);
		
		$this->add_link($lang['smiley_management'], AdminSmileysUrlBuilder::management());
		$this->add_link($lang['add_smiley'], AdminSmileysUrlBuilder::add());
		
		$env = $this->get_graphical_environment();
		$env->set_page_title($title_page);
	}
}
?>
