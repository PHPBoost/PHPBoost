<?php
/*##################################################
 *                          TemplateParser.class.php
 *                            -------------------
 *   begin                : June 18 2009
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
 * @package {@package}
 * @desc This interfaces represents a class that is able to parse a template source and transform it
 * to a syntax that the PHP engine is able to run.
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 */
interface TemplateParser
{
	/**
	 * @desc Parses the $content string.
	 * @param string $content The content to parse
	 * @return The parsed content
	 */
	function parse($content);
}
?>