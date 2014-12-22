<?php
/*##################################################
 *                          ModulePagination.class.php
 *                            -------------------
 *   begin                : June 19, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class ModulePagination
{
	private $pagination;
	private $current_page;
	private $number_elements;
	private $number_items_per_page;
	
	public function __construct($current_page, $number_elements, $number_items_per_page, $type = Pagination::FULL_PAGINATION)
	{
		$this->current_page = $current_page;
		$this->number_elements = $number_elements;
		$this->number_items_per_page = (int)$number_items_per_page;
		$this->pagination = new Pagination($this->get_number_pages(), $this->current_page, $type);
	}
	
	public function set_url(Url $url)
	{
		$this->pagination->set_url_sprintf_pattern($url->rel());
	}
	
	public function display()
	{
		return $this->pagination->export();
	}
	
	public function get_number_items_per_page()
	{
		return $this->number_items_per_page;
	}
	
	public function current_page_is_empty()
	{
		return $this->current_page > $this->pagination->get_number_pages();
	}
	
	public function get_display_from()
	{
		$current_page = $this->current_page > 0 ? $this->current_page : 1;
		return ($current_page - 1) * $this->get_number_items_per_page();
	}
	
	public function has_several_pages()
	{
		return $this->get_number_pages() > 1;
	}
	
	public function get_number_pages()
	{
		return ceil($this->number_elements / $this->get_number_items_per_page());
	}
}
?>