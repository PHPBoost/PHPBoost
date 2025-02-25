<?php
/**
 * This class represents an icon fields
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2025 02 25
 * @since       PHPBoost 6.0 - 2025 02 21
*/

class FormFieldIconEditor extends AbstractFormField
{
	public function __construct($id, $label, $value = '', array $field_options = [], array $constraints = [])
    {
        parent::__construct($id, $label, $value, $field_options, $constraints);
    }

	public function display()
    {
        $template = $this->get_template_to_use();

		$view = new FileTemplate('framework/builder/form/fieldelements/FormFieldIconEditor.tpl');
		$view->add_lang(LangLoader::get_all_langs());

        $this->assign_common_template_variables($template);

        $value = TextHelper::deserialize($this->get_value());
        if($value)
        {
            foreach($value as $prefix => $icon)
            {
                $view->put_all([
                    'C_PREFIX_FAS' => $prefix === 'fas',
                    'C_PREFIX_FAR' => $prefix === 'far',
                    'C_PREFIX_FAB' => $prefix === 'fab',
                    'C_PREFIX_IBOOST' => $prefix === 'fa iboost',
                    'ICON_VALUE' => $icon,
                ]);
            }
        }

		$view->put_all([
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_html_id(),
            'ICON_PREFIX' => $this->get_html_id() . '_prefix',
            'ICON' => $this->get_html_id() . '_icon',
            'ICON_LIST' => $this->get_html_id() . '_icon_list',
            'SELECTED' => $this->get_html_id() . '_selected',
            'FAS' => self::get_icon_list(PATH_TO_ROOT . '/templates/__default__/theme/font-awesome/css/solid.css'),
            'FAB' => self::get_icon_list(PATH_TO_ROOT . '/templates/__default__/theme/font-awesome/css/brand.css'),
            'IBOOST' => self::get_icon_list(PATH_TO_ROOT . '/templates/__default__/theme/icoboost/icoboost.css'),
        ]);

		$template->assign_block_vars('fieldelements', [
			'ELEMENT' => $view->render()
        ]);

        return $template;
    }

    public function validate()
	{
		try
		{
			$this->retrieve_value();
			return true;
		}
		catch(Exception $ex){}
	}

	public function retrieve_value()
    {
        $request = AppContext::get_request();

		$values = [];
        $icon_id = $this->get_html_id() . '_icon';
        if ($request->has_postparameter($icon_id))
        {
            $prefix_id = $this->get_html_id() . '_prefix';
            $prefix = $request->get_poststring($prefix_id);
            $icon = $request->get_poststring($icon_id);
            if(!empty($prefix) && !empty($icon))
                $values[$prefix] = $icon;
        }
		$this->set_value(TextHelper::serialize($values));
    }

	public function get_icon_list($file)
	{
        $icons = [];
        $css_file = '';
        if (file_exists($file))
            $css_file = file_get_contents($file);
		$properties_list = explode('}', $css_file);
        foreach($properties_list as $property)
        {
			$contents = explode('.', $property);
			foreach($contents as $pseudo_class)
			{
				$classes = explode(' ', $pseudo_class);
                if (in_array('--fa:', $classes))
                    foreach($classes as $class)
                    {
                        if (TextHelper::strpos($class, 'fa-') !== false) {
                            $icons[] = str_replace('fa-', '', $class);
                        }
                    }
			}
        }
        $iconList = '"' . implode('", "', $icons) . '"';
		return $iconList;
	}

    protected function compute_options(array &$field_options)
	{
        parent::compute_options($field_options);
    }

    protected function get_default_template()
    {
        return new FileTemplate('framework/builder/form/FormField.tpl');
    }
}
?>
