<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2013 08 04
*/

class ContactAjaxCheckFieldNameController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_value('id', 0);
		$name = $request->get_value('name', '');
		$field_name = ContactField::rewrite_field_name($name);

		$result = 0;
		if (!empty($id))
		{
			foreach (ContactConfig::load()->get_fields() as $key => $f)
			{
				if ($key != $id && $f['field_name'] == $field_name)
					$result = 1;
			}
		}
		else
		{
			foreach (ContactConfig::load()->get_fields() as $key => $f)
			{
				if ($f['field_name'] == $field_name)
					$result = 1;
			}
		}

		return new JSONResponse(array('result' => $result));
	}
}
?>
