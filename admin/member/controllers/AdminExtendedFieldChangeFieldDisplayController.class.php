<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.1 - 2015 03 04
*/

class AdminExtendedFieldChangeFieldDisplayController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);
		$display = $request->get_bool('display', true);

		if ($id !== 0)
		{
			PersistenceContext::get_querier()->update(DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST, array('display' => (int)$display), 'WHERE id = :id', array(
				'id' => $id
			));
			ExtendedFieldsCache::invalidate();
		}

		return new JSONResponse(array('id' => $id, 'display' => (int)$display));
	}
}
?>
