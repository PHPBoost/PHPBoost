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

class FormFieldConstraintMaxLinks extends AbstractFormFieldConstraint
{
	private int $number_links_authorized = 0;
	private bool $has_html_links = false;

	/**
	 * @param int $number_links_authorized
	 * @param bool $has_html_links true if the content is in HTML
	 * @param string $error_message
	 */
	public function __construct(int $number_links_authorized, bool $has_html_links = false, string $error_message = '')
	{
		$this->number_links_authorized = $number_links_authorized;
		$this->has_html_links = $has_html_links;

		if (empty($error_message))
		{
			$error_message = sprintf(LangLoader::get_message('warning.link.flood', 'warning-lang'), $this->number_links_authorized);
		}
		$this->set_validation_error_message($error_message);
	}

	public function validate(FormField $field): bool
	{
		return $this->exceeding_links($field);
	}

	public function get_js_validation(FormField $field): string
	{
		return '';
	}

	public function exceeding_links(FormField $field): mixed
	{
		return TextHelper::check_nbr_links($field->get_value(), $this->number_links_authorized, $this->has_html_links);
	}
}

?>
