<?php
/*##################################################
 *                      abstract_cache_data.class.php
 *                            -------------------
 *   begin                : September 16, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

import('io/cache/property_not_found_exception');
import('io/cache/cache_data');

abstract class AbstractCacheData implements CacheData
{
	private $properties_map = array();
	
	public function get_property($name)
	{
		if (!empty($this->properties_map[$name]))
		{
			return $this->properties_map[$name];
		}
		else
		{
			throw new PropertyNotFoundException($name);
		}
	}
	
	public function set_property($name, $value)
	{
		$this->properties_map[$name] = $value;
	}
}

?>