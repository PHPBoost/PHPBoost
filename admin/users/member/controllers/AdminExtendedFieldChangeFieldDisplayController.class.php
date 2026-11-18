<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 4.1 - 2015 03 04
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminExtendedFieldChangeFieldDisplayController extends AbstractController
{
	public function execute(HTTPRequestCustom $request): JSONResponse
	{
		$id = $request->get_int('id', 0);
		$display = $request->get_bool('display', true);

		if ($id !== 0)
		{
			PersistenceContext::get_querier()->update(DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST, ['display' => (int)$display], 'WHERE id = :id', [
				'id' => $id
			]);
			ExtendedFieldsCache::invalidate();
		}

		return new JSONResponse(['id' => $id, 'display' => (int)$display]);
	}
}
?>
