<?php
/**
 * @package     Ajax
 * @subpackage  Controllers
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 09 02
 * @since       PHPBoost 4.1 - 2012 11 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AjaxUserAutoCompleteController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$suggestions = array();

		try {
			$result = PersistenceContext::get_querier()->select("SELECT display_name, level, user_groups FROM " . DB_TABLE_MEMBER . " WHERE display_name LIKE '" . str_replace('*', '%', $request->get_value('value', '')) . "%'");

			while($row = $result->fetch())
			{
				$user_group_color = User::get_group_color($row['user_groups'], $row['level']);

				$profile_link = new LinkHTMLElement('', $row['display_name'], array('onclick' => 'return false;', 'style' => (!empty($user_group_color) ? 'color:' . $user_group_color : '')), UserService::get_level_class($row['level']));

				$suggestions[] = $profile_link->display();
			}
			$result->dispose();
		} catch (Exception $e) {
		}

		return new JSONResponse(array('suggestions' => $suggestions));
	}
}
?>
