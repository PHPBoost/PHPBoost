<?php
/*##################################################
 *                             MenuStringFilter.class.php
 *                            -------------------
 *   begin                : March 06, 2011
 *   copyright            : (C) 2011 Régis Viarre
 *   email                : crowkait@phpboost.com
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
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class represents a filter based on string comparison
 * @package {@package}
 */
class MenuStringFilter extends Filter
{
	public function __construct($pattern)
	{
	   parent::__construct($pattern);
	}
	
	public function match()
	{
		if (substr($this->pattern, -10) == '/index.php')
		{
			return Url::is_current_url('/' . substr($this->pattern, 0, -9), true) || Url::is_current_url($this->pattern);
		}
		else
			return Url::is_current_url($this->pattern);
	}
}
?>