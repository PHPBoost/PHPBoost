<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.1 - 2015 03 03
*/

class ContactAjaxChangeFieldDisplayController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);

		$display = -1;
		if ($id !== 0)
		{
			$config = ContactConfig::load();
			$fields = $config->get_fields();
			if ($fields[$id]['displayed'])
				$display = $fields[$id]['displayed'] = 0;
			else
				$display = $fields[$id]['displayed'] = 1;
			$config->set_fields($fields);

			ContactConfig::save();
		}

		return new JSONResponse(array('id' => $id, 'display' => $display));
	}
}
?>
