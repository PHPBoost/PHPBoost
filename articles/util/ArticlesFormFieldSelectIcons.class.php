<?php
/*##################################################
 *                        ArticlesFormFieldSelectIcons.class.php
 *                            -------------------
 *   begin                : October 20, 2011
 *   copyright            : (C) 2011 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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
 * @author Patrick DUBEAU <daaxwizeman@gmail.com>
 * @desc
 * @package {@package}
 */
class ArticlesFormFieldSelectIcons extends FormFieldSimpleSelectChoice
{
    /**
     * @desc Constructs a ArticlesFormFieldCategoryIcons.
     * @param string $id Field id
     * @param string $label Field label
     * @param mixed $value Default value (either a FormFieldEnumOption object or a string corresponding to the FormFieldEnumOption's raw value)
     * @param string[] $field_options Map of the field options (this field has no specific option, there are only the inherited ones)
     * @param FormFieldConstraint List of the constraints
     */
	
	const SELECT_OTHER_FILE_PATH = 'other';
	
    public function __construct($id, $label, $value = 0, $field_options = array(), array $constraints = array())
    {
        parent::__construct($id, $label, $value, $this->generate_options(), $field_options, $constraints);
    }

    private function generate_options()
	{
		$options = array();
		
		$options[] = new FormFieldSelectChoiceOption('--', '');
		
		$image_folder_path = new Folder('./');
		
		foreach($image_folder_path->get_files('`\.(png|jpg|bmp|gif|jpeg|tiff)$`i') as $images)
		{
			$image = $images->get_name();
			$options[] = new FormFieldSelectChoiceOption($image, $image);
		}
		$options[] = new FormFieldSelectChoiceOption(LangLoader::get_message('add_category.other_location_icon', 'articles-common', 'articles'), 0);
		return $options;
	}
}
?>