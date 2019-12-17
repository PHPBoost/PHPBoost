<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 04
 * @since       PHPBoost 3.0 - 2009 12 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
		$request = AppContext::get_request();
		if (!empty($_SERVER['REQUEST_URI']) && ($request->is_search_engine_robot() || AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)))
		{
			$requested_url = TextHelper::substr($_SERVER['REQUEST_URI'], 0, 255);
			$from_url = (string)TextHelper::substr($request->get_url_referrer(), 0, 255);
			$error_404 = null;
			$result = AdminError404DAO::instance()->find_by_criteria('WHERE requested_url=:requested_url AND from_url=:from_url', array('requested_url' => $requested_url, 'from_url' => $from_url));
			if ($result->get_rows_count() > 0 && $result->has_next())
			{
				$error_404 = $result->fetch();
				$error_404->increment();
			}
			else
			{
				$error_404 = new AdminError404($requested_url, $from_url);
			}
			AdminError404DAO::instance()->save($error_404, 'times');
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
