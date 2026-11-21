<?php
/**
 * @package     PHPBoost
 * @subpackage  Member\extended-fields\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 20
 * @since       PHPBoost 3.0 - 2010 12 08
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface MemberExtendedFieldType
{
	/**
	 * This function displayed field for create form
	 */
	public function display_field_create(MemberExtendedField $member_extended_field): void;

	/**
	 * This function displayed field for update form
	 */
	public function display_field_update(MemberExtendedField $member_extended_field): void;

	/**
	 * This function displayed field for profile
	 */
	public function display_field_profile(MemberExtendedField $member_extended_field): mixed;

	/**
	 * This function delete the field when the member is 
	 */
	public function delete_field(MemberExtendedField $member_extended_field): void;

	/**
	 * This function returned value form fields
	 * @param HTMLForm $form and MemberExtendedField $member_extended_field.
	 */
	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field): mixed;

	/**
	 * Return instanciat constraint depending integer type regex.
	 */
	public function constraint(mixed $value): mixed;

	public function set_disable_fields_configuration(array $names): void;

	public function get_disable_fields_configuration(): array;

	public function set_name(string $name): void;

	public function get_name(): string;

	/**
	 * Return true if the field used once
	 */
	public function get_field_used_once(): bool;

	/**
	 * Return true if the field used in the phpboost configuration and can't deleted
	 */
	public function get_field_used_phpboost_configuration(): bool;
}
?>
