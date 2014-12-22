<?php
/*##################################################
 *                           CacheData.class.php
 *                            -------------------
 *   begin                : September 16, 2009
 *   copyright            : (C) 2009 Benoit Sautel
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
 * @desc This interface represents data which are stored automatically by the cache manager.
 * The storage mode is very powerful, it uses a two-level cache and the database.
 * <p>The cache manager is able to manager very well configuration values. They are stored
 * in a map associating a value to a property</p>
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
interface CacheData
{
	/**
	 * This method is called when the data needs to be sychronized.
	 * For instance,
	 */
	function synchronize();
}
?>