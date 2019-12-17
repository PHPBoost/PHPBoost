<?php
/**
 * @package     PHPBoost
 * @subpackage  Member\extended-fields\field
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 05 09
 * @since       PHPBoost 3.0 - 2010 12 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

interface MemberExtendedFieldType
{
	/**
	 * This function displayed field for create form
	 * @param instance of MemberExtendedField $member_extended_field.
	 */
	public function display_field_create(MemberExtendedField $member_extended_field);

	/**
	 * This function displayed field for update form
	 * @param instance of MemberExtendedField $member_extended_field.
	 */
	public function display_field_update(MemberExtendedField $member_extended_field);

	/**
	 * This function displayed field for profile
	 * @param instance of MemberExtendedField $member_extended_field.
	 */
	public function display_field_profile(MemberExtendedField $member_extended_field);

	/**
	 * This function delete the field when the member is deleted
	 * @param instance of MemberExtendedField $member_extended_field.
	 */
	public function delete_field(MemberExtendedField $member_extended_field);

	/**
	 * This function returned value form fields
	 * @param instance of HTMLForm $form and instance of MemberExtendedField $member_extended_field.
	 */
	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field);

	/**
	 * Return instanciat constraint depending integer type regex.
	 * @return integer
	 */
	public function constraint($value);

	public function set_disable_fields_configuration(array $names);

	/**
	 * @return Array
	 */
	public function get_disable_fields_configuration();

	public function set_name($name);

	/**
	 * @return String
	 */
	public function get_name();

	/**
	 * Return true if the field used once
	 * @return bool
	 */
	public function get_field_used_once();

	/**
	 * Return true if the field used in the phpboost configuration and can't deleted
	 * @return bool
	 */
	public function get_field_used_phpboost_configuration();
}
?>
