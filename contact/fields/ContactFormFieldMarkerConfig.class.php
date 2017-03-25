<?php
/*##################################################
 *		      ContacusFormFieldMarkerConfig.class.php
 *                            -------------------
 *   begin                : April 15, 2016
 *   copyright            : (C) 2016 Sebastien Lartigue
 *   email                : babso@web33.fr
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
 * @author Sebastien Lartigue <babso@web33.fr>
 */

class ContactFormFieldMarkerConfig extends AbstractFormField
{
	private $max_input = 200;

	public function __construct($id, $label, array $value = array(), array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	function display()
	{
		$template = $this->get_template_to_use();
		$config   = ContactConfig::load();

		$tpl = new FileTemplate('contact/ContactFormFieldMarkerConfig.tpl');
		$tpl->add_lang(LangLoader::get('common', 'contact'));

		$tpl->put_all(array(
			'NAME'         => $this->get_html_id(),
			'ID'           => $this->get_html_id(),
			'C_DISABLED'   => $this->is_disabled(),
			'GMAP_API_KEY' => $config->get_gmap_api_key()
		));

		$this->assign_common_template_variables($template);

		$i = 0;
		foreach ($this->get_value() as $id => $options)
		{
			$tpl->assign_block_vars('fieldelements', array(
				'ID'                => $i,
				'POPUP_TITLE'       => $options['popup_title'],
				'MAP_LATITUDE'      => $options['latitude'],
				'MAP_LONGITUDE'     => $options['longitude'],
				'MAP_STREET_NUMBER' => $options['street_number'],
				'MAP_STREET_NAME'   => $options['street_name'],
				'MAP_POSTAL_CODE'   => $options['postal_code'],
				'MAP_LOCALITY'      => $options['locality']
			));
			$i++;
		}

		if ($i == 0)
		{
			$tpl->assign_block_vars('fieldelements', array(
				'ID'            => $i,
				'POPUP_TITLE'   => '',
				'MAP_LATITUDE'      => '',
				'MAP_LONGITUDE'     => '',
				'MAP_STREET_NUMBER' => '',
				'MAP_STREET_NAME'   => '',
				'MAP_POSTAL_CODE'   => '',
				'MAP_LOCALITY'      => '',
			));
		}

		$tpl->put_all(array(
			'MAX_INPUT'  => $this->max_input,
			'NBR_FIELDS' => $i == 0 ? 1 : $i
		));

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $tpl->render()
		));

		return $template;
	}

	public function retrieve_value()
	{
		$request = AppContext::get_request();
		$values = array();
		for ($i = 0; $i < $this->max_input; $i++)
		{
			$field_popup_title_id   = 'field_popup_title_' . $this->get_html_id() . '_' . $i;
			$field_latitude_id      = 'field_latitude_' . $this->get_html_id() . '_' . $i;
			$field_longitude_id     = 'field_longitude_' . $this->get_html_id() . '_' . $i;
			$field_street_number_id = 'field_street_number_' . $this->get_html_id() . '_' . $i;
			$field_street_name_id   = 'field_street_name_' . $this->get_html_id() . '_' . $i;
			$field_postal_code_id   = 'field_postal_code_' . $this->get_html_id() . '_' . $i;
			$field_locality_id      = 'field_locality_' . $this->get_html_id() . '_' . $i;

			if ($request->has_postparameter($field_popup_title_id) && $request->has_postparameter($field_latitude_id) && $request->has_postparameter($field_longitude_id))
			{
				$field_popup_title   = $request->get_poststring($field_popup_title_id);
				$field_latitude      = $request->get_poststring($field_latitude_id);
				$field_longitude     = $request->get_poststring($field_longitude_id);
				$field_street_number = $request->get_poststring($field_street_number_id);
				$field_street_name   = $request->get_poststring($field_street_name_id);
				$field_postal_code   = $request->get_poststring($field_postal_code_id);
				$field_locality      = $request->get_poststring($field_locality_id);

				if (!empty($field_popup_title) && !empty($field_latitude) && !empty($field_longitude))
					$values[] = array(
						'popup_title'   => $field_popup_title,
						'latitude'      => $field_latitude,
						'longitude'     => $field_longitude,
						'street_number' => $field_street_number,
						'street_name'   => $field_street_name,
						'postal_code'   => $field_postal_code,
						'locality'      => $field_locality
					);
			}
		}
		$this->set_value($values);
	}

	protected function compute_options(array &$field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = TextHelper::strtolower($attribute);
			switch ($attribute)
			{
				case 'max_input':
					$this->max_input = $value;
					unset($field_options['max_input']);
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
