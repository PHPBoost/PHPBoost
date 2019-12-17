<?php
/**
 * @package     PHPBoost
 * @subpackage  Member\extended-fields\field
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 12 08
*/

class MemberHiddenExtendedField extends AbstractMemberExtendedField
{
	public function __construct()
	{
		parent::__construct();
		$this->set_disable_fields_configuration(array('name', 'description', 'field_type', 'regex', 'authorizations', 'possible_values', 'default_value'));
		$this->set_name('');
	}

	public function display_field_create(MemberExtendedField $member_extended_field)
	{
		return;
	}

	public function display_field_update(MemberExtendedField $member_extended_field)
	{
		return;
	}

	public function display_field_profile(MemberExtendedField $member_extended_field)
	{
		return;
	}

	public function get_data(HTMLForm $form, MemberExtendedField $member_extended_field)
	{
		return;
	}
}
?>
