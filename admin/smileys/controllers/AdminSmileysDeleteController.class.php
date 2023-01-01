<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.1 - 2015 05 22
*/

class AdminSmileysDeleteController extends AdminController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);

		PersistenceContext::get_querier()->delete(DB_TABLE_SMILEYS, 'WHERE idsmiley = :id', array('id' => $id));

		###### Régénération du cache des smileys #######
		SmileysCache::invalidate();

		AppContext::get_response()->redirect(AdminSmileysUrlBuilder::management());
	}
}
?>
