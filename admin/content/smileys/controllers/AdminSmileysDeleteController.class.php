<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 4.1 - 2015 05 22
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminSmileysDeleteController extends AdminController
{
	public function execute(HTTPRequestCustom $request): Response
	{
		$id = $request->get_getint('id', 0);

		PersistenceContext::get_querier()->delete(DB_TABLE_SMILEYS, 'WHERE idsmiley = :id', ['id' => $id]);

		###### Regenerating the smiley cache #######
		SmileysCache::invalidate();

		AppContext::get_response()->redirect(AdminSmileysUrlBuilder::management());
	}
}
?>
