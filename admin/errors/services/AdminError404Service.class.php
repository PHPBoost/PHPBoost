<?php
/*##################################################
 *                           AdminError404Service.class.php
 *                            -------------------
 *   begin                : December 13, 2009
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
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc
 */
class AdminError404Service
{
	public static function list_404_errors()
	{
		return AdminError404DAO::instance()->find_all(DAO::FIND_ALL, 0, array(array('column' => 'times', 'way' => SQLQuerier::ORDER_BY_DESC)));
	}

	public static function clear_404_errors_list()
	{
		return AdminError404DAO::instance()->delete_all();
	}

	public static function register_404()
	{
		if (!empty($_SERVER['REQUEST_URI']))
		{
			$requested_url = substr($_SERVER['REQUEST_URI'], 0, 255);
			$from_url = (string)substr(AppContext::get_request()->get_url_referrer(), 0, 255);
			$error_404 = null;
			$result = AdminError404DAO::instance()->find_by_criteria(
				    'WHERE requested_url=:requested_url AND from_url=:from_url',
			array('requested_url' => $requested_url, 'from_url' => $from_url));
			if ($result->get_rows_count() > 0 && $result->has_next())
			{
				$error_404 = $result->fetch();
				$error_404->increment();
			}
			else
			{
				$error_404 = new AdminError404($requested_url, $from_url);
			}
			AdminError404DAO::instance()->save($error_404);
		}
	}

	public static function delete_404_error($id)
	{
		try
		{
			$error = AdminError404DAO::instance()->find_by_id($id);
			AdminError404DAO::instance()->delete($error);
		}
		catch (ObjectNotFoundException $exception)
		{
			
		}
	}
}
?>