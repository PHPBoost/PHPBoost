<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 12
 * @since       PHPBoost 5.0 - 2017 04 03
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class GoogleMapsFormFieldSimpleMarker extends AbstractFormField
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
		$this->set_css_form_field_class('form-field-map simple-marker');
	}

	/**
	 * @return string The html code for the input.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();
		$config   = GoogleMapsConfig::load();

		$field_tpl = new FileTemplate('GoogleMaps/GoogleMapsFormFieldSimpleMarker.tpl');
		$field_tpl->add_lang(LangLoader::get_all_langs('GoogleMaps'));

		$this->assign_common_template_variables($template);

		$value = TextHelper::deserialize($this->get_value());

		if (!($value instanceof GoogleMapsMarker))
		{
			$marker = new GoogleMapsMarker();
			$marker->set_properties(array(
				'address' => !is_array($value) ? $value : (isset($value['address']) ? $value['address'] : ''),
				'name' => is_array($value) && isset($value['name']) ? $value['name'] : '',
				'latitude' => is_array($value) && isset($value['latitude']) ? $value['latitude'] : '',
				'longitude' => is_array($value) && isset($value['longitude']) ? $value['longitude'] : '',
				'zoom' => is_array($value) && isset($value['zoom']) ? $value['zoom'] : 0
			));
		}
		else
			$marker = $value;

		$field_tpl->put_all(array_merge($marker->get_array_tpl_vars(), array(
			'C_INCLUDE_API' => $this->include_api,
			'C_CLASS' => !empty($this->get_css_class()),
			'API_KEY' => $config->get_api_key(),
			'DEFAULT_LATITUDE' => $config->get_default_marker_latitude(),
			'DEFAULT_LONGITUDE' => $config->get_default_marker_longitude(),
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_id(),
			'HTML_ID' => $this->get_html_id(),
			'CLASS' => $this->get_css_class(),
			'C_READONLY' => $this->is_readonly(),
			'C_DISABLED' => $this->is_disabled()
		)));

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $field_tpl->render()
		));

		return $template;
	}

	public function retrieve_value()
	{
		$request = AppContext::get_request();
		$marker = new GoogleMapsMarker();
		$field_address_id = $this->get_html_id();
		if ($request->has_postparameter($field_address_id))
		{
			$field_name_id = 'name-' . $this->get_html_id();
			$field_latitude_id = 'latitude-' . $this->get_html_id();
			$field_longitude_id = 'longitude-' . $this->get_html_id();
			$field_zoom_id = 'zoom-' . $this->get_html_id();

			$marker->set_properties(array(
				'name' => $request->get_poststring($field_name_id),
				'address' => $request->get_poststring($field_address_id),
				'latitude' => $request->get_poststring($field_latitude_id),
				'longitude' => $request->get_poststring($field_longitude_id),
				'zoom' => $request->get_poststring($field_zoom_id)
			));
		}

		$this->set_value(TextHelper::serialize($marker->get_properties()));
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
