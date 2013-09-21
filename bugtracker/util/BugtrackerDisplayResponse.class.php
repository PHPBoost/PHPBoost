<?php
/*##################################################
 *                              BugtrackerDisplayResponse.class.php
 *                            -------------------
 *   begin                : February 26, 2013
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class BugtrackerDisplayResponse
{
	private $page_title = '';
	private $breadcrumb_links = array();

	public function add_breadcrumb_link($name, $link)
	{
		if ($link instanceof Url)
		{
			$link = $link->absolute();
		}
		$this->breadcrumb_links[$name] = $link;
	}
	
	public function set_page_title($page_title)
	{
		$this->page_title = $page_title;
	}
	
	public function display($view)
	{
		$response = new SiteDisplayResponse($view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->page_title);
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		foreach ($this->breadcrumb_links as $name => $link)
		{
			$breadcrumb->add($name, $link);
		}
		return $response;
	}
}
?>
