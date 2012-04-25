<?php
/*##################################################
 *                          SearchResult.class.php
 *                            -------------------
 *   begin                : October 17, 2010
 *   copyright            : (C) 2010 Loic Rouchon
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

class SearchResult
{
	private $id;
	private $title;
	private $link;
	private $relevance;

	/**
	 * @desc Builds a <code>SearchResult</code> object
	 * @param int $id the element identifier in its own module
	 * @param string $title the element title
	 * @param string $link a link to a page that will display the element
	 * @param float $relevance the relevance of the element within the current search
	 */
	public function __construct($id, $title, $link, $relevance)
	{
		$this->id = $id;
		$this->title = $title;
		$this->link = $link;
		$this->relevance = $relevance;
	}

	public function get_id()
	{
		return $this->module_id;
	}

	public function get_title()
	{
		return $this->title;
	}

	public function get_link()
	{
		return Url::to_rel($this->link);
	}

	public function get_relevance()
	{
		return $this->relevance;
	}
}
?>