<?php
/**
 * @package     Builder
 * @subpackage  Form\field\constraint
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 04
 * @since       PHPBoost 3.0 - 2011 03 13
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldConstraintMaxLinks extends AbstractFormFieldConstraint
{
	private $number_links_authorized;
	private $has_html_content;

	/**
	 * @param int $number_links_authorized
	 * @param bool $has_html_links true if the content is in HTML
	 * @param string $error_message
	 */
	public function __construct($number_links_authorized, $has_html_links = false, $error_message = '')
	{
		$this->number_links_authorized = $number_links_authorized;
		$this->has_html_links = $has_html_links;

		if (empty($error_message))
		{
			$error_message = sprintf(LangLoader::get_message('warning.link.flood', 'warning-lang'), $this->number_links_authorized);
		}
		$this->set_validation_error_message($error_message);
	}

	public function validate(FormField $field)
	{
		return $this->exceeding_links($field);
	}

	public function get_js_validation(FormField $field)
	{
		return '';
	}

	public function exceeding_links($field)
	{
		return TextHelper::check_nbr_links($field->get_value(), $this->number_links_authorized, $this->has_html_links);
	}
}

?>
