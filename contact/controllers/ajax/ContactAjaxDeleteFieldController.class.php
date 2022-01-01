<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2013 08 08
*/

class ContactAjaxDeleteFieldController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);

		$code = -1;
		if (!empty($id))
		{
			$config = ContactConfig::load();
			$fields = $config->get_fields();
			if (isset($fields[$id]))
			{
				$field = new ContactField();
				$field->set_properties($fields[$id]);

				if ($field->is_deletable())
				{
					unset($fields[$id]);
					$new_fields_list = array();

					$position = 1;
					foreach ($fields as $key => $f)
					{
						$new_fields_list[$position] = $f;
						$position++;
					}

					$config->set_fields($new_fields_list);

					ContactConfig::save();
					$code = $id;
				}
			}
		}

		return new JSONResponse(array('code' => $code));
	}
}
?>
