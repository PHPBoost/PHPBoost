<?php
/**
 * This class represents an icon fields
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 10 02
 * @since       PHPBoost 6.0 - 2025 02 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class FormFieldIconEditor extends AbstractFormField
{
    public function __construct($id, $label, $value, array $field_options = array(), array $constraints = array())
    {
        parent::__construct($id, $label, $value, $field_options, $constraints);
        $this->set_css_form_field_class('form-field-icon');
    }

    public function display()
    {
        $template = $this->get_template_to_use();

        $view = new FileTemplate('framework/builder/form/fieldelements/FormFieldIconEditor.tpl');
        $view->add_lang(LangLoader::get_all_langs());

        $this->assign_common_template_variables($template);

        $value = !is_null($this->get_value()) ? explode(' ', $this->get_value()) : '';
        $prefix = (isset($value[0]) ? $value[0] : '');
        $icon = (isset($value[1]) ? implode(' ', array_slice($value, 1)) : '');

        $view->put_all([
            'C_PREFIX_FAS'    => $prefix === 'fas',
            'C_PREFIX_FAR'    => $prefix === 'far',
            'C_PREFIX_FAB'    => $prefix === 'fab',
            'C_PREFIX_IBOOST' => $prefix === 'fa iboost',
            'ICON_VALUE'      => $icon,
            'ID'              => $this->get_html_id(),
            'FAS'             => self::get_icon_list(PATH_TO_ROOT . '/templates/__default__/theme/font-awesome/css/solid.css'),
            'FAR'             => self::get_icon_list(PATH_TO_ROOT . '/templates/__default__/theme/font-awesome/css/regular.css'),
            'FAB'             => self::get_icon_list(PATH_TO_ROOT . '/templates/__default__/theme/font-awesome/css/brands.css'),
            'IBOOST'          => self::get_icon_list(PATH_TO_ROOT . '/templates/__default__/theme/icoboost/icoboost.css')
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
		catch(Exception $ex)
		{
			return $this->is_required() ? false : true;
		}
    }

    public function retrieve_value()
    {
        $this->enable();
        $request = AppContext::get_request();

        $value = '';
        $icon_id = $this->get_html_id() . '_icon';
        if ($request->has_postparameter($icon_id))
        {
            $prefix_id = $this->get_html_id() . '_prefix';
            $prefix = $request->get_poststring($prefix_id);
            $icon = $request->get_poststring($icon_id);
            $value = ($prefix && $icon ? $prefix . ' ' . $icon : '');
        }
        $this->set_value($value);
    }

    public function get_icon_list($file)
    {
        $icons = [];
        $css_file = (file_exists($file) ? file_get_contents($file) : '');
        $properties_list = explode('}', $css_file);
        foreach($properties_list as $property)
        {
            $contents = explode('.', $property);
            foreach($contents as $pseudo_class)
            {
                $classes = explode(' ', $pseudo_class);
                if (in_array('--fa:', $classes))
                {
                    foreach($classes as $class)
                    {
                        if (TextHelper::strpos($class, 'fa-') !== false)
                        $icons[] = str_replace('fa-', '', $class);
                    }
                }
            }
        }

        return '"' . implode('", "', $icons) . '"';
    }

    protected function get_default_template()
    {
        return new FileTemplate('framework/builder/form/FormField.tpl');
    }
}
?>
