<?php
/*##################################################
 *                   CacheDataNotFoundException.class.php
 *                            -------------------
 *   begin                : January 22, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @desc This exception is raised when you are asking a cache entry that doesn't exist.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class CacheDataNotFoundException extends Exception
{
	public function __construct($config_name)
	{
		parent::__construct('The cache data identified by "' . $config_name . '" doesn\'t exist');
	}
}
?>