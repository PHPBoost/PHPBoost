<?php
/*##################################################
 *                          ArticlesPagination.class.php
 *                            -------------------
 *   begin                : May 07, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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
 * @author Patrick DUBEAU <daaxwizeman@gmail.com>
 */
class ArticlesPagination
{
	private $pagination;
	private $current_page;
	private $number_per_page;
	
	public function __construct($current_page, $number_elements, $number_per_page)
	{
		$this->current_page = $current_page;
		$this->number_per_page = $number_per_page;
		$this->pagination = new Pagination($this->get_number_pages($number_elements), $this->current_page);
	}
	
	public function set_url($id_category, $rewrited_name_category, $id_article = '', $rewrited_title = '')
	{
		if ($id_article == '')
		{
			$this->pagination->set_url_sprintf_pattern(ArticlesUrlBuilder::display_category($id_category, $rewrited_name_category)->absolute());
		}
		else
		{
		    $this->pagination->set_url_sprintf_pattern(ArticlesUrlBuilder::display_article($id_category, $rewrited_name_category, $id_article, $rewrited_title)->absolute());
		}
	}
	
	public function display()
	{
		return $this->pagination->export();
	}
	
	public function get_number_per_page()
	{
		return $this->number_per_page;
	}
	
	public function get_display_from()
	{
		$current_page = $this->current_page > 0 ? $this->current_page : 1;
		return ($current_page - 1) * $this->get_number_per_page();
	}
	
	private function get_number_pages($number_elements)
	{
		return ceil($number_elements / $this->get_number_per_page());
	}
}
?>