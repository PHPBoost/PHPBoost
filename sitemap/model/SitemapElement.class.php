<?php
/*##################################################
 *                        SitemapElement.class.php
 *                            -------------------
 *   begin                : February 3rd 2009
 *   copyright            : (C) 2009 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
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
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc This abstract is the root of every object which can be contained by a Sitemap object.
 * Some SitemapElements objects can contain one or many SitemapElement objects therefore the elements
 * can be represented by a tree an each element has a depth in the tree.
 */
abstract class SitemapElement
{
	/**
	 * @var int Depth of the element in the elements tree
	 */
	var $depth = 1;

	/**
	 * @desc Builds a SitemapElement object
	 * @param string $name Name of the object
	 */
	public function __construct()
	{
	}

	/**
	 * @desc Returns the depth of the element in the tree
	 * @return int depth
	 */
	public function get_depth()
	{
		return $this->depth;
	}

	/**
	 * @desc Sets the depth of the element
	 * @param int $depth the depth of the element
	 */
	public function set_depth($depth)
	{
		$this->depth = $depth;
	}

	/**
	 * @desc Returns the name of the menu
	 * @return string name
	 */
	public abstract function get_name();

	/**
	 * @desc Exports the element
	 * @param SitemapExportConfig $export_config Export configuration
	 * @param int $depth Depth of the element
	 * @return string The exported code
	 */
	public abstract function export(SitemapExportConfig  $export_config);
}
?>