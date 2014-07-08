<?php
/*##################################################
 *                         FormFieldConstraintMaxLinks.class.php
 *                            -------------------
 *   begin                : March 13, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/
 
/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @desc
 * @package {@package}
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
			$error_message = sprintf(LangLoader::get_message('e_l_flood', 'errors'), $this->number_links_authorized);
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