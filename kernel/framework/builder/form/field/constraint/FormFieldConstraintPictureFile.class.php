<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 15
 * @since       PHPBoost 4.1 - 2015 06 12
 * @contributor mipel <mipel@phpboost.com>
*/

class FormFieldConstraintPictureFile extends FormFieldConstraintRegex
{
	private static $regex = '/\.(png|gif|jpg|jpeg|tiff|ico|svg)$/iu';

	public function __construct($error_message = '')
	{
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('form.doesnt_match_picture_file_regex', 'status-messages-common');
		}
		$this->set_validation_error_message($error_message);

		parent::__construct(
			self::$regex,
			self::$regex,
			$error_message
		);
	}
}

?>
