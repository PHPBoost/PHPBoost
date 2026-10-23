<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 22
 * @since       PHPBoost 3.0 - 2011 03 13
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldConstraintAntiFlood extends AbstractFormFieldConstraint
{
	private mixed $content_management_config = null;
	private string $last_posted_timestamp = '';
	private string $anti_flood_duration = '';

	/**
	 * @param string $last_posted_timestamp Timestamp
	 * @param string $anti_flood_duration seconde
	 * @param string $error_message
	 */
	public function __construct(string $last_posted_timestamp, string $anti_flood_duration = '', string $error_message = '')
	{
		$this->content_management_config = ContentManagementConfig::load();

		$this->last_posted_timestamp = $last_posted_timestamp;

		if (empty($anti_flood_duration))
		{
			$anti_flood_duration = $this->content_management_config->get_anti_flood_duration();
		}
		$this->anti_flood_duration = $anti_flood_duration;

		if (empty($error_message))
		{
			$error_message = LangLoader::get_message('warning.flood', 'warning-lang');
		}

		$this->set_validation_error_message($error_message);
	}

	public function validate(FormField $field): bool
	{
		if ($this->content_management_config->is_anti_flood_enabled())
		{
			return !$this->flooding($field);
		}
		return true;
	}

	public function get_js_validation(FormField $field): string
	{
		return '';
	}

	public function flooding(FormField $field): bool
	{
		if ($this->last_posted_timestamp >= (time() - $this->anti_flood_duration))
		{
			return true;
		}
		return false;
	}
}
?>
