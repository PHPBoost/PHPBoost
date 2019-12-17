<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.1 - 2015 07 12
*/

class FormFieldConstraintDisplayNameExists extends AbstractFormFieldConstraint
{
	private $user_id = 0;
	private $error_message;

	public function __construct($user_id = 0, $error_message = '')
	{
		if (!empty($user_id))
		{
			$this->user_id = $user_id;
		}

		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('e_display_name_auth', 'errors');
		}
		$this->set_validation_error_message($error_message);
		$this->error_message = TextHelper::to_js_string($error_message);
	}

	public function validate(FormField $field)
	{
		return !$this->display_name_exists($field);
	}

	public function display_name_exists(FormField $field)
	{
		if (!empty($this->user_id))
		{
			return PersistenceContext::get_querier()->row_exists(DB_TABLE_MEMBER, 'WHERE display_name=:display_name AND user_id != :user_id', array(
				'display_name' => $field->get_value(),
				'user_id' => $this->user_id
			));
		}
		else if ($field->get_value())
		{
			return PersistenceContext::get_querier()->row_exists(DB_TABLE_MEMBER, 'WHERE display_name=:display_name', array(
				'display_name' => $field->get_value()
			));
		}
		return false;
	}

	public function get_js_validation(FormField $field)
	{
		return 'DisplayNameExistValidator(' . TextHelper::to_js_string($field->get_id()) .', '. $this->error_message . ', ' . $this->user_id . ')';
	}
}

?>
