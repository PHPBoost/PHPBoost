<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 11
 * @since       PHPBoost 4.0 - 2014 02 21
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BugtrackerConstraintStatusChanged extends AbstractFormFieldConstraint
{
	private $bug_id = 0;
	private $bug_status = BugtrackerItem::NEW_BUG;
	private $error_message;

	public function __construct($bug_id = 0, $bug_status = '', $error_message = '')
	{
		if (!empty($bug_id))
		{
			$this->bug_id = $bug_id;
		}

		if (!empty($bug_status))
		{
			$this->bug_status = $bug_status;
		}

		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('error.e_status_not_changed', 'common', 'bugtracker');
		}
		$this->set_validation_error_message($error_message);
		$this->error_message = TextHelper::to_js_string($error_message);
	}

	public function validate(FormField $field)
	{
		return (!empty($this->bug_id) && $this->bug_status != $field->get_value()->get_raw_value());
	}

	public function get_js_validation(FormField $field)
	{
		return 'BugtrackerStatusChangedValidator(' . $this->error_message . ', ' . $this->bug_id . ', \'' . $this->bug_status . '\')';
	}
}
?>
