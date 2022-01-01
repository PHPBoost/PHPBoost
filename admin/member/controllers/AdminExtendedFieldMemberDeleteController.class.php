<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2015 04 30
 * @since       PHPBoost 3.0 - 2010 12 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminExtendedFieldMemberDeleteController extends AdminController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', null);

		$code = -1;
		if ($id !== null)
		{
			$extended_field = new ExtendedField();
			$extended_field->set_id($id);
			$exist_field = ExtendedFieldsDatabaseService::check_field_exist_by_id($extended_field);
			if ($exist_field)
			{
				ExtendedFieldsService::delete_by_id($id);
				$code = $id;
			}
		}
		return new JSONResponse(array('code' => $code));
	}
}

?>
