<?php
/*##################################################
 *		      GoogleMapsFormFieldSimpleAddress.class.php
 *                            -------------------
 *   begin                : April 3, 2017
 *   copyright            : (C) 2017 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class GoogleMapsFormFieldSimpleAddress extends AbstractFormField
{
	/**
	 * @var Usefull to know if we have to include all the necessary JS includes
	 */
	private $include_api = true;

	/**
	 * @desc Constructs a GoogleMapsFormFieldSimpleAddress.
	 * @param string $id Field identifier
	 * @param string $label Field label
	 * @param string $value Default value
	 * @param string[] $field_options Map containing the options
	 * @param FormFieldConstraint[] $constraints The constraints checked during the validation
	 */
	public function __construct($id, $label, $value, array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
		$this->set_css_form_field_class('form-field-map simple-address');
	}

	/**
	 * @return string The html code for the input.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();
		$config   = GoogleMapsConfig::load();

		$field_tpl = new FileTemplate('GoogleMaps/GoogleMapsFormFieldSimpleAddress.tpl');
		$field_tpl->add_lang(LangLoader::get('common', 'GoogleMaps'));

		$this->assign_common_template_variables($template);

		$field_tpl->put_all(array(
			'C_INCLUDE_API' => $this->include_api,
			'C_CLASS' => !empty($this->get_css_class()),
			'C_ADDRESS' => !empty($this->get_value()),
			'API_KEY' => $config->get_api_key(),
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'ADDRESS' => $this->get_value(),
			'CLASS' => $this->get_css_class(),
			'C_READONLY' => $this->is_readonly(),
			'C_DISABLED' => $this->is_disabled()
		));

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $field_tpl->render()
		));

		return $template;
	}

	protected function compute_options(array &$field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = TextHelper::strtolower($attribute);
			switch ($attribute)
			{
				case 'include_api':
					$this->include_api = (bool)$value;
					unset($field_options['include_api']);
					break;
			}
		}
		parent::compute_options($field_options);
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}
}
?>
