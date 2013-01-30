<?php
/*##################################################
 *                             FormFieldRanksSelect.class.php
 *                            -------------------
 *   begin                : September 26, 2011
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
class FormFieldRanksSelect extends FormFieldSimpleSelectChoice
{
	const MEMBER = '0';
	const MODERATOR = '1';
	const ADMINISTRATOR = '2';
	
    /**
     * @desc Constructs a FormFieldRanksSelect.
     * @param string $id Field id
     * @param string $label Field label
     * @param mixed $value Default value (either a FormFieldEnumOption object or a string corresponding to the FormFieldEnumOption's raw value)
     * @param string[] $field_options Map of the field options (this field has no specific option, there are only the inherited ones)
     * @param FormFieldConstraint List of the constraints
     */
    public function __construct($id, $label, $value = 0, $field_options = array(), array $constraints = array())
    {
        parent::__construct($id, $label, $value, $this->generate_options(), $field_options, $constraints);
    }

    private function generate_options()
	{
		$lang = LangLoader::get('user-common');
		$options = array();
		$options[] = new FormFieldSelectChoiceOption($lang['member'], self::MEMBER);
		$options[] = new FormFieldSelectChoiceOption($lang['moderator'], self::MODERATOR);
		$options[] = new FormFieldSelectChoiceOption($lang['administrator'], self::ADMINISTRATOR);
		return $options;
	}
}
?>