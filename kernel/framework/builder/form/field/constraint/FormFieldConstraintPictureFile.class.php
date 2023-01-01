<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 27
 * @since       PHPBoost 4.1 - 2015 06 12
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldConstraintPictureFile extends FormFieldConstraintRegex
{
	private static $regex = '/\.(png|webp|gif|jpg|jpeg|tiff|ico|svg)$/iu';

	public function __construct($error_message = '')
	{
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('warning.regex.picture.file', 'warning-lang');
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
