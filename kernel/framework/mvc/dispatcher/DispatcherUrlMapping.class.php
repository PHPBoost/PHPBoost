<?php
/*##################################################
 *                          DispatcherUrlMapping.class.php
 *                            -------------------
 *   begin                : October 06, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 *###################################################
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
 *###################################################
 */

class DispatcherUrlMapping extends UrlMapping
{
	private $high_priority = false;
	private $low_priority = false;
	
	/**
	 * @param UrlMapping[] $mappings
	 */
	public function __construct($dispatcher_name, $match = '([\w/_-]*)$', $from_path = '')
	{
		if (!empty($from_path))
		{
			if ($from_path == 'root')
			{
				$from = '^' . $match;
				$to = $dispatcher_name . '?url=/$1';
				$this->low_priority = true;
			}
			else
			{
				$dispatcher_path = ltrim(TextHelper::substr($from_path, 0, TextHelper::strrpos($from_path, '/') + 1), '/');
				$from = '^' . $dispatcher_path . $match;
				$to = $dispatcher_name . '?url=/' . $dispatcher_path . '$1';
				$this->high_priority = true;
			}
		}
		else
		{
			$dispatcher_path = ltrim(TextHelper::substr($dispatcher_name, 0, TextHelper::strrpos($dispatcher_name, '/') + 1), '/');
			$from = '^' . $dispatcher_path . $match;
			$to = $dispatcher_name . '?url=/$1';
		}
		parent::__construct($from, $to);
	}
	
	/**
	 * @desc Check if the Url must be placed in high priority in the .htaccess file
	 */
	public function is_high_priority()
	{
		return $this->high_priority;
	}
	
	/**
	 * @desc Check if the Url must be placed in low priority in the .htaccess file
	 */
	public function is_low_priority()
	{
		return $this->low_priority;
	}
}
?>