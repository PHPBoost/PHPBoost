<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 09
 * @since       PHPBoost 5.1 - 2017 09 28
*/

class SandboxConstraintUserIsAdmin extends AbstractFormFieldConstraint
{
	private $error_message;

	public function __construct($error_message = '')
	{
		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('sandbox.is.not.admin', 'common', 'sandbox');
		}
		$this->set_validation_error_message($error_message);
		$this->error_message = TextHelper::to_js_string($error_message);
	}

	public function validate(FormField $field)
	{
		return $field->get_value() ? $this->user_exists($field) : true;
	}

	public function user_exists(FormField $field)
	{
		if ($field->get_value())
		{
			return PersistenceContext::get_querier()->row_exists(DB_TABLE_MEMBER, 'WHERE level>=2 AND display_name=:display_name', array(
				'display_name' => $field->get_value()
			));
		}
		return false;
	}

	public function get_js_validation(FormField $field)
	{
		return 'UserExistValidator(' . TextHelper::to_js_string($field->get_id()) .', '. $this->error_message . ')';
	}
}

?>
